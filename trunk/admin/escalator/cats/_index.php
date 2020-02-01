<?
	include_once($BF.'components/edit_functions.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'EscalatorCats';
	$mysqlStr = '';
	$audit = '';
	$no_change = true;
	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	while ($row = mysqli_fetch_assoc($results)) {
		
		$_POST['dOrder'] = $_POST['dOrder'.$row['ID']];
		$mysqlStr = "";
		list($mysqlStr,$audit) = set_strs($mysqlStr,'dOrder',$row['dOrder'],$audit,$table,$row['ID']);
		// if nothing has changed, don't do anything.  Otherwise update / audit.
		if($mysqlStr != '') { 
			$no_change = false;
			list($str,$aud) = update_record($mysqlStr, $audit, $table, $row['ID']);
		}
	}

	if(!$no_change) { 
		$_SESSION['infoMessages'][] = "Escalator Category Order has been successfully updated in the Database.";
	}
	header("Location: index.php");
	die();
?>