<?
	include_once($BF.'components/add_functions.php');
	$table = 'Pages'; # added so not to forget to change the insert AND audit

	//Get the next order
	if($_POST['idParent']=='') { $_POST['idParent'] = 0; }
	$dOrder = db_query("SELECT dOrder FROM ".$table." WHERE !bDeleted AND idManual='".$info['ID']."' AND idParent='". $_POST['idParent'] ."' ORDER BY dOrder DESC","Getting dOrder",1);
	if(!is_numeric($dOrder['dOrder'])) { $dOrder['dOrder'] = 1; } else { $dOrder['dOrder']++; }
	
	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		bShow = '". $_POST['bShow'] ."',
		dOrder = '". $dOrder['dOrder'] ."',
		chrTitle = '". encode($_POST['chrTitle']) ."',
		idManual = '". $info['ID'] ."',
		idParent = '". $_POST['idParent'] ."',
		txtPage = '". encode($_POST['txtPage']) ."',
		dtCreated=now(),
		idCreator='".$_SESSION['idUser']."'
		";
	
	# if there database insertion is successful	
	if(db_query($q,"Insert into ". $table)) {
		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($_POST['chrTitle']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		$_SESSION['infoMessages'][] = "Page: ".$_POST['chrTitle']." has been added successfully.";
		
		if($_POST['moveTo'] == 'index.php') {
			$_POST['moveTo'] .= '?key='.$_SESSION['ManualKey'];
		} else if($_POST['pagekey'] != '') {
			$_POST['moveTo'] .= '?key='.$_POST['pagekey'];
		}
		
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this page.');
	}
?>