<?
	include_once($BF.'components/add_functions.php');
	$table = 'Languages'; # added so not to forget to change the insert AND audit

	//Get the next order
	$order = db_query("SELECT max(dOrder) as dMax FROM ".$table." WHERE !bDeleted","Get Order",1);
	if($order['dMax'] == '') { $order['dMax'] = 0; }
	$order['dMax']++;

	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		bShow = '". $_POST['bShow'] ."',
		dOrder = '". $order['dMax'] ."',
		chrIcon = '". $_POST['chrIcon'] ."',
		chrLanguage = '". encode($_POST['chrLanguage']) ."',
		txtLandingPage = '". encode($_POST['txtLandingPage']) ."'
	";
	
	# if there database insertion is successful	
	if(db_query($q,"Insert into ". $table)) {
		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

		$master = db_query("SELECT chrVar FROM MasterLang ORDER BY dOrder","Getting Master Language");
		$values = '';
		while($row = mysqli_fetch_assoc($master)) {
			$values .= "('".$newID."','".$row['chrVar']."','".encode($_POST[$row['chrVar']])."'),";
		}
		
		if($values!='') { db_query("INSERT INTO Langs (idLanguage,chrVar,chrLabel) VALUES ".substr($values,0,-1),"Insert Values"); }
		db_query("INSERT INTO LandingPages SET idType=2, idLanguage='".$newID."', chrKEY='".makekey()."', chrLandingPage='Escalator Landing Page', txtPage='Welcome to Escalator'","Insert Escalator Landing Page");
		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($_POST['chrLanguage']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		$_SESSION['infoMessages'][] = "Language: ".$_POST['chrLanguage']." has been added successfully.";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this language.');
	}
?>