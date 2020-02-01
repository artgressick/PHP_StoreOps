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
			auth_check('litm',9,1);
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
				$tableName = "EscalatorCats";
				include($BF ."includes/overlay.php");
			}
			
			if(isset($_REQUEST['idEscLang']) && is_numeric($_REQUEST['idEscLang'])) {
				$_SESSION['idEscLang'] = $_REQUEST['idEscLang'];
			}
			
			if(!isset($_SESSION['idEscLang']) || !is_numeric($_SESSION['idEscLang'])) {
				$_SESSION['idEscLang'] = 1;
			}
			
			$q = "SELECT ID, chrKEY, chrCategory, dOrder
				FROM EscalatorCats
				WHERE !bDeleted AND idLanguage='".$_SESSION['idEscLang']."'
				ORDER BY dOrder, chrCategory";
			$results = db_query($q,"getting Categories");

			if(count($_POST)) { include($post_file); }
			
			# The template to use (should be the last thing before the break)
			$title = "Administrate Escalator Categories";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Administrate Escalator Categories"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = (access_check(9,2) ? linkto(array('address'=>'add.php','img'=>'/images/plus_add.png')):'').' Escalator Categories <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$page_instructions = "Select a Escalator Category to Edit from the list.";
			$filter = db_query("SELECT ID,chrLanguage AS chrRecord FROM Languages WHERE !bDeleted ORDER BY dOrder, chrLanguage", "Getting Filter Options");
			$filter = form_select($filter,array('caption'=>'- Select Language -','name'=>'idEscLang','nocaption'=>'true','value'=>$_SESSION['idEscLang'],'extra'=>'onchange="location.href=\'index.php?idEscLang=\'+this.value"'));
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
			auth_check('litm',9,2);
			include($BF.'components/formfields.php');
			
			if(isset($_POST['chrCategory'])) { include($post_file); }

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
			$title = "Add Escalator Category";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Add Escalator Category"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Add Escalator Category";
			$page_instructions = 'Enter information and click "Add"';
			$bodyParams = "document.getElementById('chrCategory').focus();";
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
			auth_check('litm',9,3);
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Category'); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM EscalatorCats
								WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Category Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Category'); } // Did we get a result?
				
			
			if(isset($_POST['chrCategory'])) { include($post_file); }
			
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
			$title = "Edit Escalator Category";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Edit Escalator Category"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Edit ".$info['chrCategory'];
			$page_instructions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$bodyParams = "document.getElementById('chrCategory').focus();";
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