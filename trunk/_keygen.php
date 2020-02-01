<?php
	$BF = '';
	include_once($BF .'_lib.php');
	include_once($BF.'components/add_functions.php');
	
	// Add to the array any table that needs keys inserted
	$tables = array('Users','Shows','EquipmentTypes','NetworkingTypes','Buildings','Rooms','Diagrams','ReservationEquipment','ReservationNetworking',
					'RequestEquipment','RequestNetworking','3rdPartyEquipment','ScheduledNetworking','SessionTypes','Sessions','EquipmentRequest',
					'NetworkingRequest','Providers');

	//Run through the tables
	foreach ($tables as $table) {
		$results = db_query("SELECT ID FROM ".$table." WHERE chrKEY=''","Getting all ID's");
		$total = mysqli_num_rows($results);
		$count=0;
		while ($row = mysqli_fetch_assoc($results)) {
			if(db_query("UPDATE ".$table." SET chrKEY='".makekey()."' WHERE ID=".$row['ID'],"Updating KEY")) { $count++; }
		}
		echo $table." Done. ".$count." of ".$total." Records Updated with Keys.<br />";
	}
	
	echo "<br /><br />All Tables Updated."
?>