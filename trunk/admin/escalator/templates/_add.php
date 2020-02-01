<?
	include_once($BF.'components/add_functions.php');
	$table = 'EscalatorTemplates'; # added so not to forget to change the insert AND audit

	//Get the next order
	$order = db_query("SELECT max(dOrder) as dMax FROM ".$table." WHERE idLanguage='".$_POST['idLanguage']."' AND !bDeleted","Get Order",1);
	if($order['dMax'] == '') { $order['dMax'] = 0; }
	$order['dMax']++;
	
	$idDistros = '';
	if(isset($_POST['idDistro']) && count($_POST['idDistro'])) {
		foreach($_POST['idDistro'] AS $k => $id) {
			if($_POST['bDelete'.$id]==0) {
				if($idDistros!='') { $idDistros .= ','; }
				$idDistros .= $id;
			}
		}
	}
	
	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		dOrder = '". $order['dMax'] ."',
		bShow = '". $_POST['bShow'] ."',
		bPlainEmail = '". $_POST['bPlainEmail'] ."',
		bManager = '".$_POST['bManager']."',
		bUploads = '". $_POST['bUploads'] ."',
		idLanguage = '". $_POST['idLanguage'] ."',
		idCategory = '". $_POST['idCategory'] ."',
		chrTitle = '". encode($_POST['chrTitle']) ."',
		txtDirections = '". encode($_POST['txtDirections']) ."',
		txtDistro = '".$idDistros."',
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
			txtNewValue='". encode($_POST['chrCategory']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
		
		
		$q = "";
		$i = 0;
		while($i++ <= $_POST['intCount']) {
			# First, make sure that the question is set AND that it wasn't set to be removed.
			if(isset($_POST['chrQuestion'.$i]) && $_POST['chrQuestion'.$i] != '' && $_POST['bDeleted'.$i] != 1) {
				
				# If they chose a text (1) or textarea (2) field, continue, else run a few more checks.
				if($_POST['idFieldType'.$i] == 1 || $_POST['idFieldType'.$i] == 2 || $_POST['idFieldType'.$i] == 6 || $_POST['idFieldType'.$i] == 7) {
					$q .= "('". makekey() ."','". $newID ."','". (isset($_POST['bRequired'.$i]) && $_POST['bRequired'.$i] == "on" ? 1 : 0) ."','". $_POST['idFieldType'.$i] ."','". $_POST['dOrder'.$i] ."','". encode($_POST['chrQuestion'.$i]) ."',''),";	
				} else {
					$optionVals = "";
					$j = 0;
					# Create a ||| seperated list of options.
					while($j++ <= $_POST['optionval'.$i]) {
						$optionVals .= ($_POST['optionval'.$i.'-'.$j] != "" ? encode($_POST['optionval'.$i.'-'.$j]).'|||' : '');
					}
					# Check to make sure at least ONE option was in fact added
					if($optionVals != "") {
						$q .= "('". makekey() ."','". $newID ."','". (isset($_POST['bRequired'.$i]) && $_POST['bRequired'.$i] == "on" ? 1 : 0) ."','". $_POST['idFieldType'.$i] ."','". $_POST['dOrder'.$i] ."','". encode($_POST['chrQuestion'.$i]) ."','". substr($optionVals,0,-3) ."'),";	
					}
				}
			}
		}
		
		if($q != "") {
			db_query("INSERT INTO EscalatorQuestions (chrKEY,idTemplate,bRequired,idFieldType,dOrder,chrQuestion,txtOptions) VALUES ".substr($q,0,-1),"Adding the questions");
		}
	
		$_SESSION['idEscLang'] = $_POST['idLanguage'];
		
		$_SESSION['infoMessages'][] = "Category: ".$_POST['chrCategory']." has been added successfully.";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this template.');
	}

?>