<?php
	if(count($_POST['listids']) > 0) {
		if(db_query("UPDATE Escalations SET idStatus='".$_POST['idNewStatus']."' WHERE ID IN (".implode(',',$_POST['listids']).")","Update Status")) {
			$_SESSION['infoMessages'][] = "Status has been updated successfully.";
			header("Location: list.php?key=".$info['chrKEY'].($_POST['idStatus']!=''?"&idStatus=".$_POST['idStatus']:"").($_POST['chrSearch']!=''?"&chrSearch=".$_POST['chrSearch']:""));
			die();
		} else {
			errorPage('An error has occurred while trying to update the status.');
		}
	}
?>