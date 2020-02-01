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
			auth_check('litm',13,1);
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
				$tableName = "Manuals";
				include($BF ."includes/overlay.php");
			}
			
			if(isset($_REQUEST['idManLang']) && is_numeric($_REQUEST['idManLang'])) {
				$_SESSION['idManLang'] = $_REQUEST['idManLang'];
			}
			
			if(!isset($_SESSION['idManLang']) || !is_numeric($_SESSION['idManLang'])) {
				$_SESSION['idManLang'] = 1;
			}
			
			$q = "SELECT ID, chrKEY, chrManual, dOrder, bShow
				FROM Manuals
				WHERE !bDeleted AND idLanguage='".$_SESSION['idManLang']."'
				ORDER BY dOrder, chrManual";
			$results = db_query($q,"getting Manuals");
			
			if(count($_POST)) { include($post_file); }
			
			# The template to use (should be the last thing before the break)
			$title = "Administrate Manuals";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Administrate Manuals"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = (access_check(13,2) ? linkto(array('address'=>'add.php','img'=>'/images/plus_add.png')):'').' Manuals <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$page_instructions = "Select a Manual to Edit from the list.";
			
			$filter = db_query("SELECT ID,chrLanguage AS chrRecord FROM Languages WHERE !bDeleted ORDER BY dOrder, chrLanguage", "Getting Filter Options");
			$filter = form_select($filter,array('caption'=>'- Select Language -','name'=>'idManLang','nocaption'=>'true','value'=>$_SESSION['idManLang'],'extra'=>'onchange="location.href=\'index.php?idManLang=\'+this.value"'));
			
			
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
			auth_check('litm',13,2);
			include($BF.'components/formfields.php');
			
			if(isset($_POST['chrManual'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
			
?>	<script type="text/javascript" src="colorfind.js"></script>
	<script type='text/javascript'>
		var page = 'add';
	</script>
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
			$title = "Add Manual";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Add Manual"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Add Manual";
			$page_instructions = 'Enter information and click "Add"';
			$bodyParams = "document.getElementById('chrManual').focus();";
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
			auth_check('litm',13,3);
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Manual'); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM Manuals
								WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Manual Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Manual'); } // Did we get a result?
				
			
			if(isset($_POST['chrManual'])) { include($post_file); }
			
			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
			
?>	<script type="text/javascript" src="colorfind.js"></script>
	<script type='text/javascript'>
		var page = 'edit';
	</script>
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
			$title = "Edit Manual";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Edit Manual"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Edit ".$info['chrManual'];
			$page_instructions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$bodyParams = "document.getElementById('chrManual').focus();";
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