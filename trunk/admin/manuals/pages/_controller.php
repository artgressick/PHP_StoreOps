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
			auth_check('litm',13,1);
			include_once($BF.'components/formfields.php');

			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Manual'); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM Manuals
								WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Manual Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Manual'); } // Did we get a result?

			$_SESSION['ManualKey'] = $info['chrKEY'];
			$_SESSION['PageKey'] = '';
					
			$langdata = db_query("SELECT chrLanguage FROM Languages WHERE ID='".$info['idLanguage']."'","Getting Lang Information",1);
			
			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script><?
				include($BF .'components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "Pages";
				include($BF ."includes/overlay.php");
			}
			
			$q = "SELECT ID, chrKEY, chrTitle, dOrder, bShow, (SELECT COUNT(P.ID) FROM Pages AS P WHERE !bDeleted AND P.idParent=Pages.ID) as intChildren, 
				CONCAT(ID,'.',(SELECT COUNT(Audit.ID) FROM Audit WHERE Audit.idType=2 AND Audit.idRecord=Pages.ID AND chrTableName='Pages' AND chrColumnName='txtPage')) as chrVersion
				FROM Pages
				WHERE !bDeleted AND idParent=0 AND idManual='".$info['ID']."'
				ORDER BY dOrder, chrTitle";
			$results = db_query($q,"getting Pages");
			
			if(count($_POST)) { include($post_file); }
			
			# The template to use (should be the last thing before the break)
			$title = "Administrate Manual: ".$info['chrManual']." - ".$langdata['chrLanguage'];
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Administrate Manual: ".$info['chrManual']." - ".$langdata['chrLanguage']; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = (access_check(13,2) ? linkto(array('address'=>'add.php','img'=>'/images/plus_add.png')):'').' Manual: '.$info['chrManual'].' - '.$langdata['chrLanguage'].' <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$page_instructions = "Select a Page to Edit from the list.";
			
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
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_SESSION['ManualKey']) || $_SESSION['ManualKey'] == "") { errorPage('Invalid Manual'); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM Manuals
								WHERE !bDeleted AND chrKEY='".$_SESSION['ManualKey']."'","getting Manual Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Manual'); } // Did we get a result?
			
			if(isset($_POST['chrTitle'])) { include($post_file); }

			$info2['ID'] = '';
			if(isset($_REQUEST['key'])) { 
				$info2 = db_query("SELECT ID FROM Pages WHERE chrKEY='".$_REQUEST['key']."'","Get Page ID",1);	
			}
			
			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
			
?>	<script type='text/javascript'>
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
	<script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script>
<?
				include($BF .'components/list/sortlistjs.php');
			}

			# The template to use (should be the last thing before the break)
			$title = "Add Page: ".($info2['ID'] == '' ? $info['chrManual'] : manual_breadcrumbs($info2['ID'],false));
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Add Page: ".($info2['ID'] == '' ? $info['chrManual'] : manual_breadcrumbs($info2['ID'],false)); // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Add Page: ".($info2['ID'] == '' ? $info['chrManual'] : manual_breadcrumbs($info2['ID'],false));
			$page_instructions = 'Enter information and click "Add". <div style="font-weight:bold; color:red;">Please Note!! that Children Pages will not show unless all the Parent pages are enabled. Please use FireFox to edit pages.</div>';
			$bodyParams = "document.getElementById('chrTitle').focus();";
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
			if(!isset($_SESSION['ManualKey']) || $_SESSION['ManualKey'] == "") { errorPage('Invalid Manual'); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM Manuals
								WHERE !bDeleted AND chrKEY='".$_SESSION['ManualKey']."'","getting Manual Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Manual'); } // Did we get a result?
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Page'); } // Check Required Field for Query
			
			$info2 = db_query("SELECT *
								FROM Pages
								WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Page Info",1); // Get Info
			
			if($info2['ID'] == "") { errorPage('Invalid Page'); } // Did we get a result?
				
			if(count($_POST)) { include($post_file); }
			
			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
			
?>	<script type='text/javascript'>
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
	<script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script>
<?
				include($BF .'components/list/sortlistjs.php');
			}
			
			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "Pages";
				include($BF ."includes/overlay.php");
			}

			# The template to use (should be the last thing before the break)
			$breadcrumbs = manual_breadcrumbs($info2['ID'],false);
			$title = "Edit Page: ".$breadcrumbs;
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Edit Page: ".$breadcrumbs; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = (access_check(13,2) ? linkto(array('address'=>'add.php?key='.$info2['chrKEY'],'img'=>'/images/plus_add.png')):'')." Edit Page: ".$breadcrumbs;
			$page_instructions = 'Add, Edit, re-order Child Pages, or Edit this page contents below.  <div style="font-weight:bold; color:red;">Please Note!! that Children Pages will not show unless all the Parent pages are enabled. Saving the Order will not save Page Edits, and Updating the page will not save any Order Changes. Please use FireFox to edit pages.</div>';
			$bodyParams = "document.getElementById('chrTitle').focus();";
			include($BF ."includes/adminlinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	History Page
		#################################################
		case 'history.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',13,1);
			include($BF.'components/formfields.php');
			include_once($BF.'components/inline_function.php');
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_SESSION['ManualKey']) || $_SESSION['ManualKey'] == "") { errorPage('Invalid Manual'); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM Manuals
								WHERE !bDeleted AND chrKEY='".$_SESSION['ManualKey']."'","getting Manual Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Manual'); } // Did we get a result?
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Page'); } // Check Required Field for Query
			
			$info2 = db_query("SELECT *
								FROM Pages
								WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Page Info",1); // Get Info
			
			if($info2['ID'] == "") { errorPage('Invalid Page'); } // Did we get a result?
				
			$results = db_query("SELECT A.ID, A.dtDateTime, A.txtOldValue, A.txtNewValue, U.chrFirst, U.chrLast
								FROM Audit AS A
								JOIN Users AS U ON A.idUser=U.ID
								WHERE A.idType=2 AND A.idRecord='".$info2['ID']."' AND A.chrTableName='Pages' AND A.chrColumnName='txtPage' ORDER BY dtDateTime DESC","Getting History");
			
			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
			}
			
			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$breadcrumbs = manual_breadcrumbs($info2['ID'],false);
			$title = "Page History: ".$breadcrumbs;
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Page History: ".$breadcrumbs; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Page History: ".$breadcrumbs;
			$page_instructions = 'This shows all changes made to the page in order from newest to oldest';
			include($BF ."includes/adminlinks.php");
			include($BF ."models/template.php");		
			
			break;

		#################################################
		##	View History Page
		#################################################
		case 'viewhistory.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',13,1);
			include($BF.'components/formfields.php');
			
			if(!isset($_REQUEST['idPage']) || $_REQUEST['idPage'] == "") { errorPage('Invalid Page'); } 
			
			$info = db_query("SELECT ID, chrTitle
								FROM Pages
								WHERE !bDeleted AND ID='".$_REQUEST['idPage']."'","getting Page Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Page'); } // Did we get a result?
			
			$history = db_query("SELECT A.ID, A.dtDateTime, A.txtOldValue, A.txtNewValue
								FROM Audit AS A
								WHERE A.idType=2 AND A.ID='".$_REQUEST['id']."' AND A.chrTableName='Pages' AND A.chrColumnName='txtPage'","Getting History",1);
			
			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
			}
			
			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$title = "Page: ".$info['chrTitle'].' Version: '.$info['ID'].'.'.$_REQUEST['ver'];
			// Banner Information
			$banner_title = "Page: ".$info['chrTitle'].' Version: '.$info['ID'].'.'.$_REQUEST['ver']; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_background = "darkred";
			$headerlink_color = "white";
			$page_title = "Page: ".$info['chrTitle'].' Version: '.$info['ID'].'.'.$_REQUEST['ver'];
			include($BF ."models/popup.php");		
			
			break;
		#################################################
		##  Revert History Page
		#################################################
		case 'reverthistory.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',13,3);
			include($BF.'components/formfields.php');
			
			if(!isset($_REQUEST['idPage']) || $_REQUEST['idPage'] == "") { errorPage('Invalid Page'); } 
			
			$info = db_query("SELECT *, (SELECT P.chrKEY FROM Pages AS P WHERE P.ID = Pages.idParent) AS ParentKey
								FROM Pages
								WHERE !bDeleted AND ID='".$_REQUEST['idPage']."'","getting Page Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Page'); } // Did we get a result?
			
			$history = db_query("SELECT A.ID, A.dtDateTime, A.txtOldValue, A.txtNewValue
								FROM Audit AS A
								WHERE A.idType=2 AND A.ID='".$_REQUEST['id']."' AND A.chrTableName='Pages' AND A.chrColumnName='txtPage'","Getting History",1);
			
			$q = "UPDATE Pages SET txtPage='".($_REQUEST['type']=='new'?$history['txtNewValue']:$history['txtOldValue'])."' WHERE ID='".$info['ID']."'";
			if(db_query($q,"Revert Page")) {
				$q = "INSERT INTO Audit SET 
					idType=2, 
					idRecord='". $info['ID'] ."',
					chrColumnName = 'txtPage',
					txtNewValue='". ($_REQUEST['type']=='new'?$history['txtNewValue']:$history['txtOldValue']) ."',
					txtOldValue='". $info['txtPage'] ."',
					dtDateTime=now(),
					chrTableName='Pages',
					idUser='". $_SESSION['idUser'] ."'
				";
				db_query($q,"Insert audit");
				$_SESSION['infoMessages'][] = $info['chrTitle']." has been successfully reverted in the Database.";
			} else {
				errorPage('An error has occurred while trying to revert this page.');	
			}
			
			if($info['ParentKey'] == '') {
				$refer = "index.php?key=".$_SESSION['ManualKey'];
			} else {
				$refer = "edit.php?key=".$info['ParentKey'];
			}
			header("Location: ".$refer);
			die();	
			
			break;
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>