<?php
	include_once($BF.'components/add_functions.php');
	include_once($BF.'includes/_emailer.php');
	$table = 'Escalations'; # added so not to forget to change the insert AND audit
	
	$key = makekey();
	$q = "INSERT INTO ".$table." SET
		  chrKEY = '".$key."',
		  idStore = '".$_COOKIE['idStore']."',
		  dtCreated = now(),
		  idStatus = 1,
		  idTemplate = '".$info['ID']."',
		  chrCC = '".encode($_POST['chrCC'])."'
		 ";
	if($info['bManager']) {
		$q .= ",chrEmployeeName = '".encode($_POST['chrEmployeeName'])."',
		  chrEmployeeEmail = '".encode($_POST['chrEmployeeEmail'])."'
		";
	}

	if(db_query($q,"Insert into ".$table)) {
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

		$q = "";
		while($row = mysqli_fetch_assoc($results)) {
			# if it's just a basic sentance or paragraph
			if($row['idFieldType'] != 6) { 
				if($row['idFieldType'] == 1 || $row['idFieldType'] == 2 || $row['idFieldType'] == 7) {
					$q .= "('". $newID ."','". $row['ID'] ."','". encode($_POST[$row['ID']]) ."'),";
				} else if($row['idFieldType'] == 3) {
					$tmp_options = explode('|||',$row['txtOptions']);
					$i = 0;
					$len = count($tmp_options);
					$ans = "";
					while($i < $len) {
						if($_POST[$row['ID']] != '' && $i == $_POST[$row['ID']]) { $ans = $tmp_options[$i]; break; }
						$i++;
					}
					$q .= "('". $newID ."','". $row['ID'] ."','". $ans ."'),";
				} else {
					$tmp_options = explode('|||',$row['txtOptions']);
					$i = 0;
					$len = count($tmp_options);
					$ans = "";
					while($i < $len) {
						if(in_array($i,$_POST[$row['ID']])) { $ans .= $tmp_options[$i].", "; }
						$i++;
					}
					$q .= "('". $newID ."','". $row['ID'] ."','". substr($ans,0,-2) ."'),";
				}
			} 
		}
	
		if(db_query("INSERT INTO EscAnswers (idEscalation,idQuestion,txtAnswer) VALUES ".substr($q,0,-1),"insert answers")) {		
			# Files section.
			$table2 = "EscFiles";
			$i = 0;
			$attachments = array();
			while ($i++ <  $_POST['intFiles']) {
				if($_FILES['chrFilesFile'.$i]['name'] != '') {
					$q = "INSERT INTO ". $table2 ." SET  
						chrKEY = '". makekey() ."',
						idEscalation = '". $newID ."',
						dtAdded=now()
					";

				# if there database insertion is successful	
					if(db_query($q,"Insert into ". $table2)) {
						global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
						$newID2 = mysqli_insert_id($mysqli_connection);

						$attName = strtolower(str_replace(" ","_",basename($_FILES['chrFilesFile'.$i]['name'])));  //dtn: Replace any spaces with underscores.

						$uploaddir = $BF . 'escalator/files/'; 	//dtn: Setting up the directory name for where things go
					
						//dtn: Update the EmailMessages DB with the file attachment info.
						db_query("UPDATE ". $table2 ." SET 
							dbFileSize = '". $_FILES['chrFilesFile'.$i]['size'] ."',
							chrFileName = '". $newID2 ."-". $attName ."',
							chrFileType = '". $_FILES['chrFilesFile'.$i]['type'] ."'
							WHERE ID=". $newID2 ."	
						","insert attachment");
			
						$uploadfile = $uploaddir . $newID2 .'-'. $attName;
					
						move_uploaded_file($_FILES['chrFilesFile'.$i]['tmp_name'], $uploadfile);  //dtn: move the file to where it needs to go.
						$attachments[] = $uploadfile;
					}
				}
			}
		} else {
			db_query("DELETE FROM ".$table." WHERE ID=".$newID,"Remove Record");
			errorPage($_SESSION['chrLanguage']['escalation_error']);
		}
		
	} else {
		errorPage($_SESSION['chrLanguage']['escalation_error']);
	}
	
	//Since everything has gone ok, lets create the e-mail.
	
	$emailinfo = db_query("
			SELECT L.email_mb_language, L.email_mb_convert_encoding, C.chrEmail
			FROM Stores AS S
			JOIN Languages AS L ON S.idLanguage=L.ID
			JOIN Countries AS C ON S.idCountry=C.ID
			WHERE S.ID='".$storeinfo['ID']."'
			","Get email settings",1);
		
	$bcc = '';
	if($info['txtDistro'] != '') {
		$distro = db_query("SELECT chrEmail FROM DistroGroups WHERE !bDeleted AND ID IN (".$info['txtDistro'].")","Getting Distro List");
		while($row = mysqli_fetch_assoc($distro)) {
			$bcc .= $row['chrEmail'].',';
		}
		if($bcc != '') { $bcc = substr($bcc,0,-1); }
	}
	
	if($info['bPlainEmail']) {
		$message = encode($_SESSION['chrLanguage']['store_name_number'].': '.$storeinfo['chrStore'].' ('.$storeinfo['chrStoreNum'].')
		\r\n');
		$message .= encode($_SESSION['chrLanguage']['status'].': '.$_SESSION['chrLanguage']['esc_status_1']."
		\r\n");
	} else {
		$message = encode('<b>'.$_SESSION['chrLanguage']['store_name_number'].'</b>: '.$storeinfo['chrStore'].' ('.$storeinfo['chrStoreNum'].')');
		$message .= encode('<p><b>'.$_SESSION['chrLanguage']['status'].'</b>: '.$_SESSION['chrLanguage']['esc_status_1'].'</p>');
	}
	
	if($info['bManager']) {
		if($info['bPlainEmail']) {
			$message .= encode($_SESSION['chrLanguage']['employee_name'].': '.$_POST['chrEmployeeName']."
			\r\n");
			$message .= encode($_SESSION['chrLanguage']['employee_email'].': '.$_POST['chrEmployeeEmail']."
			\r\n");
		} else {
			$message .= encode('<p><b>'.$_SESSION['chrLanguage']['employee_name'].'</b>: '.$_POST['chrEmployeeName'].'</p>');
			$message .= encode('<p><b>'.$_SESSION['chrLanguage']['employee_email'].'</b>: '.$_POST['chrEmployeeEmail'].'</p>');
		}
	}
	
	mysqli_data_seek($results,0);
	while($row = mysqli_fetch_assoc($results)) {
		# if it's just a basic sentance or paragraph
		if($row['idFieldType'] == 1 && $_POST[$row['ID']] != '') {
			if($info['bPlainEmail']) {
				$message .= encode($row['chrQuestion'].': '.$_POST[$row['ID']]."
				\r\n");
			} else {
				$message .= encode('<p><b>'.$row['chrQuestion'].'</b>: '.$_POST[$row['ID']].'</p>');
			}
		} else if($row['idFieldType'] == 2 && $_POST[$row['ID']] != '') {
			if($info['bPlainEmail']) {
				$message .= encode($row['chrQuestion'].': '.$_POST[$row['ID']]."
				\r\n");
			} else {
				$message .= encode('<p><b>'.$row['chrQuestion'].'</b>: '.nl2br($_POST[$row['ID']]).'</p>');
			}
		} else if($row['idFieldType'] == 7 && $_POST[$row['ID']] != '') {
			if($info['bPlainEmail']) {
				$message .= encode($row['chrQuestion'].': '.$_SESSION['chrLanguage']['information_masked']."
				\r\n");
			} else {
				$message .= encode('<p><b>'.$row['chrQuestion'].'</b>: '.$_SESSION['chrLanguage']['information_masked'].'</p>');
			}
		} else if($row['idFieldType'] == 3 && $_POST[$row['ID']] != '') {
			$tmp_options = explode('|||',$row['txtOptions']);
			$i = 0;
			$len = count($tmp_options);
			$ans = "";
			while($i < $len) {
				if($_POST[$row['ID']] != '' && $i == $_POST[$row['ID']]) { $ans = $tmp_options[$i]; break; }
				$i++;
			}
			if($info['bPlainEmail']) {
				$message .= encode($row['chrQuestion'].': '.$ans."
				\r\n");
			} else {
				$message .= encode('<p><b>'.$row['chrQuestion'].'</b>: '.$ans.'</p>');
			}
		} else if(($row['idFieldType'] == 4 || $row['idFieldType'] == 5) && $_POST[$row['ID']] != '') {
			$tmp_options = explode('|||',$row['txtOptions']);
			$i = 0;
			$len = count($tmp_options);
			$ans = "";
			while($i < $len) {
				if(in_array($i,$_POST[$row['ID']])) { $ans .= $tmp_options[$i].", "; }
				$i++;
			}
			if($info['bPlainEmail']) {
				$message .= encode($row['chrQuestion'].': '.substr($ans,0,-1)."
				\r\n");
			} else {
				$message .= encode('<p><b>'.$row['chrQuestion'].'</b>: '.substr($ans,0,-1).'</p>');
			}
		} else if($row['idFieldType'] == 6) {
			if($info['bPlainEmail']) {
				$message .= encode($row['chrQuestion']."
				\r\n");
			} else {
				$message .= encode('<div style="padding:10px 0 5px 0; border-bottom:1px solid #999; font-size:20px; font-weight:bold;">'.$row['chrQuestion'].'</div>');
			}
		}
	}
	if($info['bPlainEmail']) {
		$message .= encode($_SESSION['chrLanguage']['view_escalation_link'].': '.$PROJECT_ADDRESS.'escalator/view.php?key='.$key."
		\r\n");
	} else {
		$message .= encode('<p>'.$_SESSION['chrLanguage']['view_escalation_link'].'<br /><a href="'.$PROJECT_ADDRESS.'escalator/view.php?key='.$key.'">'.$PROJECT_ADDRESS.'escalator/view.php?key='.$key.'</a></p>');
	}
	//emailer($to,$subject,$message,$from='',$cc='',$bcc='',$mb_language='en',$mb_convert_encoding='UTF-8',$attachments=array())
	if(emailer(($info['bManager'] ? $storeinfo['chrManagerEmail'].', '.$_POST['chrEmployeeEmail'] : $storeinfo['chrEmail']),
			encode($_SESSION['chrLanguage']['escalator'].': '.$info['chrTitle'].' - '.$storeinfo['chrStore']),
			$message,
			($info['bManager'] ? $_POST['chrEmployeeEmail'] : $emailinfo['chrEmail']),
			$_POST['chrCC'],
			'',
			$emailinfo['email_mb_language'],
			$emailinfo['email_mb_convert_encoding'],
			$attachments)) {
				if($bcc != '') {
					emailer($bcc,
					encode($_SESSION['chrLanguage']['escalator'].': '.$info['chrTitle'].' - '.$storeinfo['chrStore']),
					$message,
					($info['bManager'] ? $_POST['chrEmployeeEmail'] : $emailinfo['chrEmail']),
					'',
					'',
					$emailinfo['email_mb_language'],
					$emailinfo['email_mb_convert_encoding'],
					$attachments);
				}
				
				$_SESSION['infoMessages'][] = $_SESSION['chrLanguage']['escalation_sent'];
				header("Location: template.php?key=".$info['chrKEY']);
				die();	
			} else {
				errorPage($_SESSION['chrLanguage']['escalation_error']);
			}
		
?>