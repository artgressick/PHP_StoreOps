<?
	include_once($BF.'components/add_functions.php');
	$table = 'Users'; # added so not to forget to change the insert AND audit

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
	
	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		chrFirst = '". encode($_POST['chrFirst']) ."',
		chrLast = '". encode($_POST['chrLast']) ."',
		chrEmail = '". $_POST['chrEmail'] ."',
		chrPassword = '". sha1($_POST['chrPassword']) ."',
		bGlobalAdmin = '". $_POST['bGlobalAdmin'] ."',
		txtSecurity = '". $_POST['txtSecurity']."',
		idUserCreated = '". $_SESSION['idUser'] ."',
		dtCreated = now()
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
			txtNewValue='". encode($_POST['chrFirst'].' '.$_POST['chrLast']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		$_SESSION['infoMessages'][] = "User: ".$_POST['chrFirst'].' '.$_POST['chrLast']." has been added successfully.";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this user.');
	}
?>