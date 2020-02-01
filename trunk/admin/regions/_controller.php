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
			auth_check('litm',6,1);
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
				$tableName = "Regions";
				include($BF ."includes/overlay.php");
			}
			
			$q = "SELECT ID, chrKEY, chrRegion, (SELECT COUNT(S.ID) FROM Stores AS S WHERE S.idRegion=Regions.ID AND !S.bDeleted) as intCount
				FROM Regions
				WHERE !bDeleted
				ORDER BY chrRegion";
			$results = db_query($q,"getting Regions");
			
			# The template to use (should be the last thing before the break)
			$title = "Administrate Regions";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Administrate Regions"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = (access_check(6,2) ? linkto(array('address'=>'add.php','img'=>'/images/plus_add.png')):'').' Regions <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$page_instructions = "Select a Region to Edit from the list.";
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
			auth_check('litm',6,2);
			include($BF.'components/formfields.php');
			
			if(isset($_POST['chrRegion'])) { include($post_file); }

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
			$title = "Add Region";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Add Region"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Add Region";
			$page_instructions = 'Enter information and click "Add"';
			$bodyParams = "document.getElementById('chrRegion').focus();";
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
			auth_check('litm',6,3);
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Region'); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM Regions
								WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Region Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Region'); } // Did we get a result?
				
			
			if(isset($_POST['chrRegion'])) { include($post_file); }
			
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
			$title = "Edit Region";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Edit Region"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Edit ".$info['chrRegion'];
			$page_instructions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$bodyParams = "document.getElementById('chrRegion').focus();";
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