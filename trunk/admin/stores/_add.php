<?
	include_once($BF.'components/add_functions.php');
	$table = 'Stores'; # added so not to forget to change the insert AND audit

	//Get the next order

	$_POST['txtLanguage2'] = implode(',',$_POST['txtLanguage']);
	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		bShow = '". $_POST['bShow'] ."',
		chrStore = '". encode($_POST['chrStore']) ."',
		chrStoreNum = '". encode($_POST['chrStoreNum']) ."',
		chrEmail = '". encode($_POST['chrEmail']) ."',
		idRegion = '". $_POST['idRegion'] ."',
		idDivision = '". $_POST['idDivision'] ."',
		chrPhone = '". encode($_POST['chrPhone']) ."',
		chrFax = '". encode($_POST['chrFax']) ."',
		chrAddress = '". encode($_POST['chrAddress']) ."',
		chrAddress2 = '". encode($_POST['chrAddress2']) ."',
		chrAddress3 = '". encode($_POST['chrAddress3']) ."',
		chrCity = '". encode($_POST['chrCity']) ."',
		chrLocal = '". encode($_POST['chrLocal']) ."',
		chrPostalCode = '". encode($_POST['chrPostalCode']) ."',
		idCountry = '". $_POST['idCountry'] ."',
		txtLanguage = '". $_POST['txtLanguage2'] ."',
		chrManager = '". encode($_POST['chrManager']) ."',
		chrManagerEmail = '". encode($_POST['chrManagerEmail']) ."'
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
			txtNewValue='". encode($_POST['chrStore']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		$_SESSION['infoMessages'][] = "Store: ".$_POST['chrStore']." has been added successfully.";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this store.');
	}
?>