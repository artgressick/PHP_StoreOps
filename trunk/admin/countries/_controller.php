<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';
	
	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];
	
	switch($file_name[0]) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			auth_check('litm',8,1);
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
				$tableName = "Countries";
				include($BF ."includes/overlay.php");
			}
			
			$q = "SELECT * FROM Countries WHERE !bDeleted ORDER BY dOrder, chrCountry";
			$results = db_query($q,"getting Countries");
			
			if(count($_POST)) { include($post_file); }
			
			# The template to use (should be the last thing before the break)
			$title = "Administrate Countries";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Administrate Countries"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = (access_check(8,2) ? linkto(array('address'=>'add.php','img'=>'/images/plus_add.png')):'').' Countries <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$page_instructions = "Select a Country to Edit from the List.";
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
			auth_check('litm',8,2);
			include($BF.'components/formfields.php');
			
			if(isset($_POST['chrCountry'])) { include($post_file); }

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
			$title = "Add Country";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Add Country"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Add Country";
			$page_instructions = 'Enter information and click "Add"';
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
			auth_check('litm',8,3);
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Country'); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM Countries
								WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Country Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Country'); } // Did we get a result?
				
			
			if(isset($_POST['chrCountry'])) { include($post_file); }
			
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
			$title = "Edit Country";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Edit Country"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Edit ".$info['chrCountry'];
			$page_instructions = 'Please update the information below and press the "Update Information" when you are done making changes.';
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