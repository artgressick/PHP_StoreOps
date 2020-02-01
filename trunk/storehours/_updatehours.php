<?php
	include_once($BF.'components/add_functions.php');
	include_once($BF.'components/edit_functions.php');
	$emailinfo = db_query("
		SELECT L.email_mb_language, L.email_mb_convert_encoding, C.chrEmail
		FROM Stores AS S
		JOIN Languages AS L ON S.idLanguage=L.ID
		JOIN Countries AS C ON S.idCountry=C.ID
		WHERE S.ID='".$storeinfo['ID']."'
		","Get email settings",1);
	if(count($hours) == 7) { // edit
		$dow = 0;
		$q2 = "";
		$cnt = 0;
		while($dow < 7) {
			if(!isset($_POST['bClosed'.$dow])) { $_POST['bClosed'.$dow] = 0; }
			if($_POST['tBegin'.$dow] == '') { $_POST['tBegin'.$dow] = '00:00:00'; } else { $_POST['tBegin'.$dow] = date('H:i',strtotime($_POST['tBegin'.$dow])).':00'; }
			if($_POST['tClose'.$dow] == '') { $_POST['tClose'.$dow] = '00:00:00'; } else { $_POST['tClose'.$dow] = date('H:i',strtotime($_POST['tClose'.$dow])).':00'; }
			if($_POST['bClosed'.$dow] != $hours[$dow]['bClosed'] || $_POST['tBegin'.$dow] != date('H:i',strtotime($hours[$dow]['tOpening'])).':00' || $_POST['tClose'.$dow] != date('H:i',strtotime($hours[$dow]['tClosing'])).':00') {
				$q = "UPDATE StoreHours SET bClosed='".$_POST['bClosed'.$dow]."', tOpening='".$_POST['tBegin'.$dow]."', tClosing='".$_POST['tClose'.$dow]."' WHERE ID='".$hours[$dow]['ID']."'";
				if(db_query($q,"Update Hours")) {
					$cnt++;
				}
			}
			$dow++;
		}
		if($cnt > 0) {
			$_SESSION['infoMessages'][] = $_SESSION['chrLanguage']['store_hours_update_success'];
			include_once($BF.'includes/_emailer.php');

			$message = "
						<p>".$storeinfo['chrStore']." ".$_SESSION['chrLanguage']['store_hours_email1']."</p>
						<table cellpadding='5' cellspacing='0' border='0'>
							<tr>";
			$dow = 0;
			$q2 = "";
			while($dow < 7) {
				$message .= "
								<td style='vertical-align:top;'>
									<div style='text-align:center;font-weight:bold;'>".$_SESSION['chrLanguage'][day_of_week($dow)]."</div>
									".(isset($_POST['bClosed'.$dow]) && $_POST['bClosed'.$dow]==1?"<p style='text-align:center; font-style: italic;'>".$_SESSION['chrLanguage']['closed']."</p>":"<table cellpadding='0' cellspacing='0' border='0'>
										<tr>
											<td style='padding-right:5px;'>".$_SESSION['chrLanguage']['open'].":</td>
											<td>".date($_SESSION['chrLanguage']['php_hours'],strtotime($_POST['tBegin'.$dow]))."</td>
										</tr>
										<tr>
											<td style='padding-right:5px;'>".$_SESSION['chrLanguage']['close'].":</td>
											<td>".date($_SESSION['chrLanguage']['php_hours'],strtotime($_POST['tClose'.$dow]))."</td>
										</tr>
									</table>")."
								</td>";
				$dow++;
			}
			$message .= "
							</tr>
						</table>";
			
			$subject = $_SESSION['chrLanguage']['store_hours_email2']." ".$storeinfo['chrStore'];
			
			//function emailer($to,$subject,$message,$from='',$cc='',$bcc='',$mb_language='en',$mb_convert_encoding='UTF-8',$attachments=array()) {
			
			emailer($storeinfo['chrEmail'],$subject,$message,$emailinfo['chrEmail'],'','',$emailinfo['email_mb_language'],$emailinfo['email_mb_convert_encoding']);
			//emailer('jsummers@techitsolutions.com',$subject,$message,$storeinfo['chrEmail']);
			
			header("Location: index.php");
			die();
		} else {
			$_SESSION['infoMessages'][] = $_SESSION['chrLanguage']['store_hours_no_change'];
			header("Location: index.php");
			die();
		}
		
		
		
	} else {  // add
		if(count($hours) < 7) {
			db_query("DELETE FROM StoreHours WHERE idStore='".$storeinfo['ID']."'","Remove exsisting hours");
		}
		
		$dow = 0;
		$q2 = "";
		while($dow < 7) {
			$q2 .= "('".(isset($_POST['bClosed'.$dow]) && $_POST['bClosed'.$dow]==1?"1":"0")."','".$storeinfo['ID']."','".$dow."',now(),'".($_POST['tBegin'.$dow] != ''?date('H:i',strtotime($_POST['tBegin'.$dow])):'00:00').":00','".($_POST['tClose'.$dow] != ''?date('H:i',strtotime($_POST['tClose'.$dow])):'00:00').":00'),";
			$dow++;
		}
		if($q2 != '') {
			$q = "INSERT INTO StoreHours (bClosed, idStore, idDayOfWeek, dtCreated, tOpening, tClosing) VALUES ".substr($q2,0,-1);
			if(db_query($q,"Inserting Hours")) {
				$_SESSION['infoMessages'][] = $_SESSION['chrLanguage']['store_hours_update_success'];
				include_once($BF.'includes/_emailer.php');

				$message = "
							<p>".$storeinfo['chrStore']." ".$_SESSION['chrLanguage']['store_hours_email3']."</p>
							<table cellpadding='5' cellspacing='0' border='0'>
								<tr>";
				$dow = 0;
				while($dow < 7) {
					$message .= "
									<td style='vertical-align:top;'>
										<div style='text-align:center;font-weight:bold;'>".$_SESSION['chrLanguage'][day_of_week($dow)]."</div>
										".(isset($_POST['bClosed'.$dow]) && $_POST['bClosed'.$dow]==1?"<p style='text-align:center; font-style: italic;'>".$_SESSION['chrLanguage']['closed']."</p>":"<table cellpadding='0' cellspacing='0' border='0'>
											<tr>
												<td style='padding-right:5px;'>".$_SESSION['chrLanguage']['open'].":</td>
												<td>".date($_SESSION['chrLanguage']['php_hours'],strtotime($_POST['tBegin'.$dow]))."</td>
											</tr>
											<tr>
												<td style='padding-right:5px;'>".$_SESSION['chrLanguage']['close'].":</td>
												<td>".date($_SESSION['chrLanguage']['php_hours'],strtotime($_POST['tClose'.$dow]))."</td>
											</tr>
										</table>")."
									</td>";
					$dow++;
				}
				$message .= "
								</tr>
							</table>";
				
				$subject = $_SESSION['chrLanguage']['store_hours_email4']." ".$storeinfo['chrStore'];
				
				//function emailer($to,$subject,$message,$from='',$cc='',$bcc='',$mb_language='en',$mb_convert_encoding='UTF-8',$attachments=array()) {
				
				emailer($storeinfo['chrEmail'],$subject,$message,$emailinfo['chrEmail'],'','',$emailinfo['email_mb_language'],$emailinfo['email_mb_convert_encoding']);
				//emailer('jsummers@techitsolutions.com',$subject,$message,$storeinfo['chrEmail']);
				
				header("Location: index.php");
				die();
			} else {
				errorPage($_SESSION['chrLanguage']['store_hours_error']);
			}
		} else {
			errorPage($_SESSION['chrLanguage']['store_hours_error']);
		}
	}
	
?>