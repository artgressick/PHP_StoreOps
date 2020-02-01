<?php
	include_once($BF.'components/add_functions.php');
	include_once($BF.'components/edit_functions.php');
	
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
			$_SESSION['infoMessages'][] = "Store Hours for ".$info['chrStore']." has been updated successfully.";
			header("Location: index.php");
			die();
		} else {
			$_SESSION['infoMessages'][] = "No changes have been made to ".$info['chrStore']." store hours";
			header("Location: index.php");
			die();
		}
		
		
		
	} else {  // add
		if(count($hours) < 7) {
			db_query("DELETE FROM StoreHours WHERE idStore='".$info['ID']."'","Remove exsisting hours");
		}
		
		$dow = 0;
		$q2 = "";
		while($dow < 7) {
			$q2 .= "('".(isset($_POST['bClosed'.$dow]) && $_POST['bClosed'.$dow]==1?"1":"0")."','".$info['ID']."','".$dow."',now(),'".($_POST['tBegin'.$dow] != ''?date('H:i',strtotime($_POST['tBegin'.$dow])):'00:00').":00','".($_POST['tClose'.$dow] != ''?date('H:i',strtotime($_POST['tClose'.$dow])):'00:00').":00'),";
			$dow++;
		}
		if($q2 != '') {
			$q = "INSERT INTO StoreHours (bClosed, idStore, idDayOfWeek, dtCreated, tOpening, tClosing) VALUES ".substr($q2,0,-1);
			if(db_query($q,"Inserting Hours")) {
				$_SESSION['infoMessages'][] = "Store Hours for ".$info['chrStore']." has been added successfully.";
				header("Location: index.php");
				die();
			} else {
				errorPage('An error has occurred while trying to add these store hours.');
			}
		} else {
			errorPage('An error has occurred while trying to add these store hours.');
		}
	}
	
?>