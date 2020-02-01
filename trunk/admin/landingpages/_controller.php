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
			auth_check('litm',2,1);
			include_once($BF.'components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'components/list/sortlistjs.php');
			}

			$q = "SELECT L.ID,L.chrKEY,
					IF(L.idType=2,CONCAT(F.chrLanguage,' ',L.chrLandingPage),L.chrLandingPage) AS chrLandingPage 
					FROM LandingPages AS L
					JOIN Languages AS F ON L.idLanguage=F.ID
					WHERE !F.bDeleted 
					ORDER BY L.ID";
			$results = db_query($q,"getting Landing Pages");
			
			# The template to use (should be the last thing before the break)
			$title = "Administrative Control Center";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Administrate Landing Pages"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Administrate Landing Pages";
			$page_instructions = "Select a Landing Page to Edit from the List.";
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
			auth_check('litm',2,3);
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Landing Page'); } // Check Required Field for Query
			
			$info = db_query("SELECT L.ID, L.chrKEY, L.txtPage,
								IF(L.idType=2,CONCAT(F.chrLanguage,' ',L.chrLandingPage),L.chrLandingPage) AS chrLandingPage 
								FROM LandingPages AS L
								JOIN Languages AS F ON L.idLanguage=F.ID
								WHERE !F.bDeleted AND L.chrKEY='".$_REQUEST['key']."'","getting landingpage",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Landing Page'); } // Did we get a result?
				
			
			if(isset($_POST['txtPage'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type="text/javascript" src="<?=$BF?>components/tiny_mce/tiny_mce_gzip.js"></script>
	<script type="text/javascript">
	tinyMCE_GZ.init({
		plugins : 'style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',
		themes : 'simple,advanced',
		languages : 'en',
		disk_cache : true,
		debug : false
	});
	</script>
	<!-- Needs to be seperate script tags! -->
	<script language="javascript" type="text/javascript">
		tinyMCE.init({
			mode : "textareas",
			plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,filemanager",
			theme_advanced_buttons1_add : "fontselect,fontsizeselect",
			theme_advanced_buttons2_add : "separator,forecolor,backcolor",
			theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator",
			theme_advanced_buttons3_add : "emotions,flash,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
			theme_advanced_toolbar_location : "top",
			theme_advanced_path_location : "bottom",
			content_css : "/example_data/example_full.css",
		    plugin_insertdate_dateFormat : "%Y-%m-%d",
		    plugin_insertdate_timeFormat : "%H:%M:%S",
			extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
			external_link_list_url : "example_data/example_link_list.js",
			external_image_list_url : "example_data/example_image_list.js",
			flash_external_list_url : "example_data/example_flash_list.js",
			file_browser_callback : "mcFileManager.filebrowserCallBack",
			theme_advanced_resize_horizontal : false,
			theme_advanced_resizing : true,
			apply_source_formatting : true,
			
			filemanager_rootpath : "<?=realpath($BF . 'uploads')?>",
			filemanager_path : "<?=realpath($BF . 'uploads')?>",
			relative_urls : false,
			document_base_url : "<?=$PROJECT_ADDRESS?>"
		});
	</script>
	<!-- /tinyMCE -->
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Administrative Control Center";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Edit Landing Page"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Edit ".$info['chrLandingPage'];
			$page_instructions = 'Please update the information below and press the "Update Information" when you are done making changes. NOTE! Please use Firefox to Edit the Landing Page.';
			include($BF ."includes/adminlinks.php");
			include($BF ."models/template.php");		
			
			break;
						
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
//			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>