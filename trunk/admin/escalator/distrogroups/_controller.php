<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../../';
	
	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];
	
	switch($file_name[0]) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			auth_check('litm',11,1);
			include_once($BF.'components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script><?
				include($BF .'components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "DistroGroups";
				include($BF ."includes/overlay.php");
			}
			
			$q = "SELECT ID, chrKEY, chrName, chrEmail
				FROM DistroGroups
				WHERE !bDeleted
				ORDER BY chrName, chrEmail";
			$results = db_query($q,"getting Distro Groups");
			
			# The template to use (should be the last thing before the break)
			$title = "Administrate Distro Groups";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Administrate Distro Groups"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = (access_check(11,2) ? linkto(array('address'=>'add.php','img'=>'/images/plus_add.png')):'').' Distro Groups <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$page_instructions = "Select a Distro Group to Edit from the list.";
			include($BF ."includes/adminlinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	add Page
		#################################################
		case 'add.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',11,2);
			include($BF.'components/formfields.php');
			
			if(isset($_POST['chrName'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
			
?>	<script type='text/javascript'>
		var page = 'add';
	</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Add Distro Group";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Add Distro Group"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Add Distro Group";
			$page_instructions = 'Enter information and click "Add"';
			$bodyParams = "document.getElementById('chrName').focus();";
			include($BF ."includes/adminlinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',11,3);
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Distro Group'); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM DistroGroups
								WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Distro Group Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Distro Group'); } // Did we get a result?
				
			
			if(isset($_POST['chrName'])) { include($post_file); }
			
			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
			
?>	<script type='text/javascript'>
		var page = 'edit';
	</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit Distro Group";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Edit Distro Group"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Edit ".$info['chrName'];
			$page_instructions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$bodyParams = "document.getElementById('chrName').focus();";
			include($BF ."includes/adminlinks.php");
			include($BF ."models/template.php");		
			
			break;
						
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>