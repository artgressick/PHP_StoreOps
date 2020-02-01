<?php
	$totalDays = (strtotime($info['dEnd']) - strtotime($info['dBegin']))/60/60/24;
	
	$emailinfo = db_query("
		SELECT L.email_mb_language, L.email_mb_convert_encoding, C.chrEmail
		FROM Stores AS S
		JOIN Languages AS L ON S.idLanguage=L.ID
		JOIN Countries AS C ON S.idCountry=C.ID
		WHERE S.ID='".$storeinfo['ID']."'
		","Get email settings",1);
	
	$i=0;
	$dCurrent = $info['dBegin'];
	$q2 = '';
	while($i <= $totalDays) {
		$dow = date('w',strtotime($dCurrent));
		$q2 .= "('".(isset($_POST['bClosed'.$dCurrent]) && $_POST['bClosed'.$dCurrent]==1?"1":"0")."','".$storeinfo['ID']."','".$info['ID']."','".$dow."','".$dCurrent."','".($_POST['tBegin'.$dCurrent] != ''?date('H:i',strtotime($_POST['tBegin'.$dCurrent])):'00:00').":00','".($_POST['tClose'.$dCurrent] != ''?date('H:i',strtotime($_POST['tClose'.$dCurrent])):'00:00').":00',now()),";
		
		
		$dCurrent = date('Y-m-d',strtotime($info['dBegin']." + ".($i++ + 1)." days"));
	}
	if($q2 != '') {
		db_query("DELETE FROM HolidayStoreHours WHERE idStore='".$storeinfo['ID']."' AND idHoliday='".$info['ID']."'","Removing Hours");
		$q = "INSERT INTO HolidayStoreHours (bClosed, idStore, idHoliday, idDayOfWeek, dDate, tOpening, tClosing, dtCreated) VALUES ".substr($q2,0,-1);
		if(db_query($q,"Inserting Hours")) {
			$_SESSION['infoMessages'][] = $_SESSION['chrLanguage']['holiday_store_hours_update_success'];
			include_once($BF.'includes/_emailer.php');

			$message = "
						<p>".$storeinfo['chrStore']." ".$_SESSION['chrLanguage']['holiday_email1']." ".$info['chrHoliday'].".</p>
						<table cellpadding='5' cellspacing='0' border='0'>
							<tr>";
			$totalDays = (strtotime($info['dEnd']) - strtotime($info['dBegin']))/60/60/24;
			
			$i=0;
			$dCurrent = $info['dBegin'];
			$q2 = '';
			while($i <= $totalDays) {
				$dow = date('w',strtotime($dCurrent));
				$message .= "
								<td style='vertical-align:top;'>
									<div style='text-align:center;font-weight:bold;'>".$_SESSION['chrLanguage'][day_of_week($dow)].', '.date($_SESSION['chrLanguage']['php_date'],strtotime($dCurrent))."</div>
									".(isset($_POST['bClosed'.$dCurrent]) && $_POST['bClosed'.$dCurrent]==1?"<p style='text-align:center; font-style: italic;'>".$_SESSION['chrLanguage']['closed']."</p>":"<table cellpadding='0' cellspacing='0' border='0'>
										<tr>
											<td style='padding-right:5px;'>".$_SESSION['chrLanguage']['open'].":</td>
											<td>".date($_SESSION['chrLanguage']['php_hours'],strtotime($_POST['tBegin'.$dCurrent]))."</td>
										</tr>
										<tr>
											<td style='padding-right:5px;'>".$_SESSION['chrLanguage']['close'].":</td>
											<td>".date($_SESSION['chrLanguage']['php_hours'],strtotime($_POST['tClose'.$dCurrent]))."</td>
										</tr>
									</table>")."
								</td>";
				$dCurrent = date('Y-m-d',strtotime($info['dBegin']." + ".($i++ + 1)." days"));
			}
			$message .= "
							</tr>
						</table>";
			
			$subject = $_SESSION['chrLanguage']['holiday_email2']." ".$storeinfo['chrStore']."";
			
			//function emailer($to,$subject,$message,$from='',$cc='',$bcc='',$mb_language='en',$mb_convert_encoding='UTF-8',$attachments=array()) {
			
			emailer($storeinfo['chrEmail'],$subject,$message,$emailinfo['chrEmail'],'','',$emailinfo['email_mb_language'],$emailinfo['email_mb_convert_encoding']);
			//emailer('jsummers@techitsolutions.com',$subject,$message,$storeinfo['chrEmail']);
			
			header("Location: index.php");
			die();
		} else {
			errorPage($_SESSION['chrLanguage']['holiday_store_hours_error']);
		}
	} else {
		errorPage($_SESSION['chrLanguage']['holiday_store_hours_error']);
	}
?>