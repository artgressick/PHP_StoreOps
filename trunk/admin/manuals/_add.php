<?
	include_once($BF.'components/add_functions.php');
	$table = 'Manuals'; # added so not to forget to change the insert AND audit

	//Get the next order
	if(db_query("INSERT INTO Security SET chrOptions='1,2,3,4', dOrder='999', chrDescription='Manual: ".encode($_POST['chrManual'])."', bDeleted=1","Insert Security Setting")) {
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$idSecurity = mysqli_insert_id($mysqli_connection);
	
		$dOrder = db_query("SELECT dOrder FROM ".$table." WHERE !bDeleted AND idLanguage=".$_POST['idLanguage']." ORDER BY dOrder DESC","Getting dOrder",1);
		if(!is_numeric($dOrder['dOrder'])) { $dOrder['dOrder'] = 1; } else { $dOrder['dOrder']++; }
		
		$q = "INSERT INTO ". $table ." SET 
			chrKEY = '". makekey() ."',
			bShow = '". $_POST['bShow'] ."',
			bResource = '". $_POST['bResource'] ."',
			dOrder = '". $dOrder['dOrder'] ."',
			chrManual = '". encode($_POST['chrManual']) ."',
			idSecurity = '". $idSecurity ."',
			idLanguage = '". $_POST['idLanguage'] ."',
			chrBGColor = '". encode($_POST['chrBGColor']) ."',
			chrLinkColor = '". encode($_POST['chrLinkColor']) ."',
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
				txtNewValue='". encode($_POST['chrManual']) ."',
				dtDateTime=now(),
				chrTableName='". $table ."',
				idUser='". $_SESSION['idUser'] ."'
			";
			db_query($q,"Insert audit");
			//End the code for History Insert 
		
			$_SESSION['idManLang'] = $_POST['idLanguage'];
			
			$_SESSION['infoMessages'][] = "Manual: ".$_POST['chrManual']." has been added successfully.";
			header("Location: ". $_POST['moveTo']);
			die();
		} else {
			# if the database insertion failed, send them to the error page with a useful message
			db_query("DELETE FROM Security WHERE ID=".$idSecurity,"Remove Security");
			errorPage('An error has occurred while trying to add this manual.');
		}
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this manual.');
	}
		
?>