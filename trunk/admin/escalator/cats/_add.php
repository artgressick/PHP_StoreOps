<?
	include_once($BF.'components/add_functions.php');
	$table = 'EscalatorCats'; # added so not to forget to change the insert AND audit

	//Get the next order
	$order = db_query("SELECT max(dOrder) as dMax FROM ".$table." WHERE idLanguage='".$_POST['idLanguage']."' AND !bDeleted","Get Order",1);
	if($order['dMax'] == '') { $order['dMax'] = 0; }
	$order['dMax']++;
	
	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		dOrder = '". $order['dMax'] ."',
		idLanguage = '". $_POST['idLanguage'] ."',
		chrCategory = '". encode($_POST['chrCategory']) ."'
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
			txtNewValue='". encode($_POST['chrCategory']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		$_SESSION['idEscLang'] = $_POST['idLanguage'];
		
		$_SESSION['infoMessages'][] = "Category: ".$_POST['chrCategory']." has been added successfully.";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this Category.');
	}
?>