<?
	include_once($BF.'components/edit_functions.php');
	include_once($BF.'components/add_functions.php');
	include_once($BF.'includes/_emailer.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'Escalations';
	$mysqlStr = '';
	$audit = '';

	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idStatus',$info['idStatus'],$audit,$table,$info['ID']);

	if($mysqlStr != '') { 
		if($mysqlStr != '') { list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']); }
	}
	
	db_query("INSERT INTO EscComments SET idEscalation='".$info['ID']."', dtAdded=now(), txtComment='".encode($_POST['txtComments'])."'","Insert Comment");
	
	$temp = db_query("SELECT idQuestion, txtAnswer FROM EscAnswers WHERE idEscalation=".$info['ID'],"Getting Answers");
	$answers = array();
	while($row = mysqli_fetch_assoc($temp)) {
		$answers[$row['idQuestion']] = $row['txtAnswer'];
	}
	
	
	$files = db_query("SELECT ID, chrFileName FROM EscFiles WHERE idEscalation='".$info['ID']."'","Getting Files");
	$attachments = array();
	while($row = mysqli_fetch_assoc($files)) {
		$attachments[] = $BF.'escalator/files/'.$row['chrFileName'];
	}
	
	$emailinfo = db_query("
			SELECT L.email_mb_language, L.email_mb_convert_encoding, C.chrEmail
			FROM Stores AS S
			JOIN Languages AS L ON S.idLanguage=L.ID
			JOIN Countries AS C ON S.idCountry=C.ID
			WHERE S.ID='".$storeinfo2['ID']."'
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
		$message = encode($storelang['store_name_number'].': '.$storeinfo2['chrStore'].' ('.$storeinfo2['chrStoreNum'].')'."\r\n");
		$message .= encode($storelang['status'].': '.$storelang['esc_status_'.$_POST['idStatus']]."\r\n");
	} else {
		$message = encode('<b>'.$storelang['store_name_number'].'</b>: '.$storeinfo2['chrStore'].' ('.$storeinfo2['chrStoreNum'].')');
		$message .= encode('<p><b>'.$storelang['status'].'</b>: '.$storelang['esc_status_'.$_POST['idStatus']].'</p>');
	}
	
	if($info['bManager']) {
		if($info['bPlainEmail']) {
			$message .= encode($storelang['employee_name'].': '.$info['chrEmployeeName']."\r\n");
			$message .= encode($storelang['employee_email'].': '.$info['chrEmployeeEmail']."\r\n");
		} else {
			$message .= encode('<p><b>'.$storelang['employee_name'].'</b>: '.$info['chrEmployeeName'].'</p>');
			$message .= encode('<p><b>'.$storelang['employee_email'].'</b>: '.$info['chrEmployeeEmail'].'</p>');
		}
	}
	
	mysqli_data_seek($results,0);
	while($row = mysqli_fetch_assoc($results)) {
		if($row['idFieldType'] == 6) {
			if($info['bPlainEmail']) {
				$message .= encode($row['chrQuestion']."\r\n");
			} else {
				$message .= encode('<div style="padding:10px 0 5px 0; border-bottom:1px solid #999; font-size:20px; font-weight:bold;">'.$row['chrQuestion'].'</div>');
			}
		} else if($row['idFieldType'] == 7 && $answers[$row['ID']] != '') {
			if($info['bPlainEmail']) {
				$message .= encode($row['chrQuestion'].': '.$storelang['information_masked']."\r\n");
			} else {
				$message .= encode('<p><b>'.$row['chrQuestion'].'</b>: '.$storelang['information_masked'].'</p>');
			}
		} else {
			if($info['bPlainEmail']) {
				$message .= encode($row['chrQuestion'].': '.($answers[$row['ID']] != '' ? $answers[$row['ID']] : $storelang['n/a'])."\r\n");
			} else {
				$message .= encode('<p><b>'.$row['chrQuestion'].'</b>: '.($answers[$row['ID']] != '' ? nl2br($answers[$row['ID']]) : $storelang['n/a']).'</p>');
			}
		}		
	}
	
	$comments = db_query("SELECT ID, txtComment, DATE_FORMAT(dtAdded,'".$storelang['date_time_format']."') AS dtDate FROM EscComments WHERE idEscalation='".$info['ID']."' ORDER BY dtAdded","Getting Comments");
	if($info['bPlainEmail']) {
		$message .= encode($storelang['comments']."\r\n");
	} else {
		$message .= encode('<div style="padding:10px 0 5px 0; border-bottom:1px solid #999; font-size:20px; font-weight:bold;">'.$storelang['comments'].'</div>');
	}
	while($row = mysqli_fetch_assoc($comments)) {
		if($info['bPlainEmail']) {
			$message .= encode($row['dtDate'].': '.$row['txtComment']."\r\n");
		} else {
			$message .= encode('<p><b>'.$row['dtDate'].'</b>: '.nl2br($row['txtComment']).'</p>');
		}
	}
	if($info['bPlainEmail']) {
		$message .= encode($storelang['view_escalation_link'].': '.$PROJECT_ADDRESS.'escalator/view.php?key='.$info['chrKEY']."\r\n");
	} else {	
		$message .= encode('<p>'.$storelang['view_escalation_link'].'<br /><a href="'.$PROJECT_ADDRESS.'escalator/view.php?key='.$info['chrKEY'].'">'.$PROJECT_ADDRESS.'escalator/view.php?key='.$info['chrKEY'].'</a></p>');
	}
	
	//emailer($to,$subject,$message,$from='',$cc='',$bcc='',$mb_language='en',$mb_convert_encoding='UTF-8',$attachments=array())
	if(emailer(($info['bManager'] ? $storeinfo2['chrManagerEmail'].', '.$info['chrEmployeeEmail'] : $storeinfo2['chrEmail']),
			encode('Re: '.$storelang['escalator'].': '.$info['chrTitle'].' - '.$storeinfo2['chrStore']),
			$message,
			($info['bManager'] ? $info['chrEmployeeEmail'] : $emailinfo['chrEmail']),
			$info['chrCC'],
			'',
			$emailinfo['email_mb_language'],
			$emailinfo['email_mb_convert_encoding'],
			$attachments)) {
				if($bcc != '') {
					emailer($bcc,
								encode('Re: '.$storelang['escalator'].': '.$info['chrTitle'].' - '.$storeinfo2['chrStore']),
								$message,
								($info['bManager'] ? $info['chrEmployeeEmail'] : $emailinfo['chrEmail']),
								'',
								'',
								$emailinfo['email_mb_language'],
								$emailinfo['email_mb_convert_encoding'],
								$attachments);
				}
				$_SESSION['infoMessages'][] = $storelang['comments_sent'];
				header("Location: view.php?key=".$info['chrKEY']);
				die();	
			} else {
				errorPage($storelang['comments_error']);
			}
		
?>
	
?>