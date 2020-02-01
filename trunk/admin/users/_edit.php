<?
	include_once($BF.'components/edit_functions.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'Users';
	$mysqlStr = '';
	$audit = '';

	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrFirst',$info['chrFirst'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrLast',$info['chrLast'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrEmail',$info['chrEmail'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'bGlobalAdmin',$info['bGlobalAdmin'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'bLocked',$info['bLocked'],$audit,$table,$info['ID']);
	if($_POST['chrPassword'] != "" && $_POST['chrPassword2'] != "" && $_POST['chrPassword'] == $_POST['chrPassword2']) {
		list($mysqlStr,$audit) = set_strs_password($mysqlStr,'chrPassword',$info['chrPassword'],$audit,$table,$info['ID']);
	}

	$_POST['txtSecurity'] = '';
	$q = "SELECT ID
			FROM Security
			WHERE !bDeleted
			ORDER BY ID
			";

	$securityoptions = db_query($q, "Getting Files");
	while($row = mysqli_fetch_assoc($securityoptions)) {
		if(isset($_POST['secure'.$row['ID']])) {
			if($_POST['txtSecurity'] != '') { $_POST['txtSecurity'] .= '|'; }
			$_POST['txtSecurity'] .= $row['ID'].':'.implode(',',$_POST['secure'.$row['ID']]);
		}
	}
	
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtSecurity',$info['txtSecurity'],$audit,$table,$info['ID']);
	
	// if nothing has changed, don't do anything.  Otherwise update / audit.
	if($mysqlStr != '') { 
		$_SESSION['infoMessages'][] = $_POST['chrFirst']." ".$_POST['chrLast']. " has been successfully updated in the Database.";
		list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
	 } else {
	 	$_SESSION['infoMessages'][] = "No Changes have been made to ".$_POST['chrFirst']." ".$_POST['chrLast'];
	 }
	
	header("Location: index.php");
	die();	
?>