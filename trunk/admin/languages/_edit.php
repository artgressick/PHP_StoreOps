<?
	include_once($BF.'components/edit_functions.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'Languages';
	$mysqlStr = '';
	$audit = '';

	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	list($mysqlStr,$audit) = set_strs($mysqlStr,'bShow',$info['bShow'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrIcon',$info['chrIcon'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrLanguage',$info['chrLanguage'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtLandingPage',$info['txtLandingPage'],$audit,$table,$info['ID']);

	db_query("DELETE FROM Langs WHERE idLanguage=".$info['ID'],"Remove Replacements");
	
	$master = db_query("SELECT chrVar FROM MasterLang ORDER BY dOrder","Getting Master Language");
	$values = '';
	$updated = false;
	while($row = mysqli_fetch_assoc($master)) {
		$values .= "('".$info['ID']."','".$row['chrVar']."','".encode($_POST[$row['chrVar']])."'),";
	}
	
	if($values!='') { db_query("INSERT INTO Langs (idLanguage,chrVar,chrLabel) VALUES ".substr($values,0,-1),"Insert Values"); $updated = true; }
	
	
	// if nothing has changed, don't do anything.  Otherwise update / audit.
	if($mysqlStr != '' || $updated) { 
		$_SESSION['infoMessages'][] = $_POST['chrLanguage']." has been successfully updated in the Database.";
		if($mysqlStr != '') { list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']); }
	 } else {
	 	$_SESSION['infoMessages'][] = "No Changes have been made to ".$_POST['chrLanguage'];
	 }
	
	header("Location: index.php");
	die();	
?>