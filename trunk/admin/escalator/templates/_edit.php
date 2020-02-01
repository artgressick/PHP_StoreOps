<?
	include_once($BF.'components/edit_functions.php');
	include_once($BF.'components/add_functions.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'EscalatorTemplates';
	$mysqlStr = '';
	$audit = '';

	$_POST['txtDistro'] = '';
	if(isset($_POST['idDistro']) && count($_POST['idDistro'])) {
		foreach($_POST['idDistro'] AS $k => $id) {
			if($_POST['bDelete'.$id]==0) {
				if($_POST['txtDistro']!='') { $_POST['txtDistro'] .= ','; }
				$_POST['txtDistro'] .= $id;
			}
		}
	}
	
	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	list($mysqlStr,$audit) = set_strs($mysqlStr,'bShow',$info['bShow'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'bPlainEmail',$info['bPlainEmail'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'bManager',$info['bManager'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'bUploads',$info['bUploads'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idLanguage',$info['idLanguage'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idCategory',$info['idCategory'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrTitle',$info['chrTitle'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtDirections',$info['txtDirections'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtDistro',$info['txtDistro'],$audit,$table,$info['ID']);
	// if nothing has changed, don't do anything.  Otherwise update / audit.
	

	if($mysqlStr != '') { 
		list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
	}

	
	$q = "";
	$i = 0;
	while($i++ <= $_POST['intCount']) {
		if(isset($_POST['QID-'.$i]) && is_numeric($_POST['QID-'.$i])) {
			if(isset($_POST['chrQuestion'.$i]) && $_POST['chrQuestion'.$i] != '') {
				$optionVals = '';
				if($_POST['idFieldType'.$i] != 1 && $_POST['idFieldType'.$i] != 2 && $_POST['idFieldType'.$i] != 6 && $_POST['idFieldType'.$i] != 7) {
					$j = 0;
					# Create a ||| seperated list of options.
					while($j++ <= $_POST['optionval'.$i]) {
						$optionVals .= (isset($_POST['optionval'.$i.'-'.$j]) && $_POST['optionval'.$i.'-'.$j] != "" ? encode($_POST['optionval'.$i.'-'.$j]).'|||' : '');
					}
				}
				$query = "UPDATE EscalatorQuestions SET 
						bDeleted='".$_POST['bDeleted'.$i]."',
						bRequired='".(isset($_POST['bRequired'.$i]) && $_POST['bRequired'.$i] == "on" ? 1 : 0)."',
						idFieldType='".$_POST['idFieldType'.$i]."',
						dOrder='".$_POST['dOrder'.$i]."',
						chrQuestion='".encode($_POST['chrQuestion'.$i])."',
						txtOptions='".substr($optionVals,0,-3)."'
						WHERE ID=".$_POST['QID-'.$i]."
				";
			} else if($_POST['idFieldType'.$i] != 6) {
				$optionVals = '';
				if($_POST['idFieldType'.$i] != 1 && $_POST['idFieldType'.$i] != 2 && $_POST['idFieldType'.$i] != 6 && $_POST['idFieldType'.$i] != 7) {
					$j = 0;
					# Create a ||| seperated list of options.
					while($j++ <= $_POST['optionval'.$i]) {
						$optionVals .= (isset($_POST['optionval'.$i.'-'.$j]) && $_POST['optionval'.$i.'-'.$j] != "" ? encode($_POST['optionval'.$i.'-'.$j]).'|||' : '');
					}
				}
				$query = "UPDATE EscalatorQuestions SET 
						bDeleted='1',
						bRequired='".(isset($_POST['bRequired'.$i]) && $_POST['bRequired'.$i] == "on" ? 1 : 0)."',
						idFieldType='".$_POST['idFieldType'.$i]."',
						dOrder='".$_POST['dOrder'.$i]."',
						chrQuestion='".encode($_POST['chrQuestion'.$i])."',
						txtOptions='".substr($optionVals,0,-3)."'
						WHERE ID=".$_POST['QID-'.$i]."
				";
			} else {
				$query = "DELETE FROM EscalatorQuestions WHERE ID=".$_POST['QID-'.$i]."";
			}
			if($query != '') {
				db_query($query,"Update Question");
			}
		} else {
			# First, make sure that the question is set AND that it wasn't set to be removed.
			if(isset($_POST['chrQuestion'.$i]) && $_POST['chrQuestion'.$i] != '' && $_POST['bDeleted'.$i] != 1) {
				
				# If they chose a text (1) or textarea (2) field, continue, else run a few more checks.
				if($_POST['idFieldType'.$i] == 1 || $_POST['idFieldType'.$i] == 2 || $_POST['idFieldType'.$i] == 6 || $_POST['idFieldType'.$i] == 7) {
					$q .= "('". makekey() ."','". $info['ID'] ."','". (isset($_POST['bRequired'.$i]) && $_POST['bRequired'.$i] == "on" ? 1 : 0) ."','". $_POST['idFieldType'.$i] ."','". $_POST['dOrder'.$i] ."','". encode($_POST['chrQuestion'.$i]) ."',''),";	
				} else {
					$optionVals = "";
					$j = 0;
					# Create a ||| seperated list of options.
					while($j++ <= $_POST['optionval'.$i]) {
						$optionVals .= ($_POST['optionval'.$i.'-'.$j] != "" ? encode($_POST['optionval'.$i.'-'.$j]).'|||' : '');
					}
					# Check to make sure at least ONE option was in fact added
					if($optionVals != "") {
						$q .= "('". makekey() ."','". $info['ID'] ."','". (isset($_POST['bRequired'.$i]) && $_POST['bRequired'.$i] == "on" ? 1 : 0) ."','". $_POST['idFieldType'.$i] ."','". $_POST['dOrder'.$i] ."','". encode($_POST['chrQuestion'.$i]) ."','". substr($optionVals,0,-3) ."'),";	
					}
				}
			}
		}
	}
	
	if($q != "") {
		db_query("INSERT INTO EscalatorQuestions (chrKEY,idTemplate,bRequired,idFieldType,dOrder,chrQuestion,txtOptions) VALUES ".substr($q,0,-1),"Adding the questions");
	}
	
	$_SESSION['infoMessages'][] = $_POST['chrTitle']." has been successfully updated in the Database.";
	$_SESSION['idEscLang'] = $_POST['idLanguage'];
	header("Location: index.php");
	die();	
?>