<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../';
	
	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];
	switch($file_name[0]) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			$storeinfo = check_idStore();
			include_once($BF.'components/formfields.php');

			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage($_SESSION['chrLanguage']['invalid_manual']); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM Manuals
								WHERE !bDeleted AND bShow AND chrKEY='".$_REQUEST['key']."'","getting Manual Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage($_SESSION['chrLanguage']['invalid_manual']); } // Did we get a result?

			# Stuff In The Header
			function sith() { 
				global $BF,$info;
				?><script type='text/javascript' src='<?=$BF?>includes/toggle.js'></script>
				<link rel="stylesheet" type="text/css" href="<?=$BF?>includes/apple_search/default.css" id="default"  />
				<script type="text/javascript" src="<?=$BF?>includes/apple_search/applesearch.js"></script><?
			}
			
			if(isset($_POST['chrSearch'.$info['ID']])) { $_SESSION['chrSearch'.$info['ID']] = $_POST['chrSearch'.$info['ID']]; $_SESSION['intSearch'.$info['ID']] = 0; } 

			# The template to use (should be the last thing before the break)
			$title = $info['chrManual'];
			$section = 'MAN'.$info['chrKEY'];
			$leftlink = "";
			$bodyParams = 'applesearch.init();';
			$page_background = $info['chrBGColor'];
			$headerlink_color = $info['chrLinkColor'];
			// Banner Information
			$banner_title = $info['chrManual']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			include($BF ."includes/manuallinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	View Page
		#################################################
		case 'page.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			$storeinfo = check_idStore();
			include_once($BF.'components/formfields.php');

			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage($_SESSION['chrLanguage']['invalid_page']); } // Check Required Field for Query
			
			$page = db_query("SELECT Pages.*,
								CONCAT(ID,'.',(SELECT COUNT(Audit.ID) FROM Audit WHERE Audit.idType=2 AND Audit.idRecord=Pages.ID AND chrTableName='Pages' AND chrColumnName='txtPage')) as chrVersion
								FROM Pages
								WHERE !bDeleted AND bShow AND chrKEY='".$_REQUEST['key']."'","getting Page Info",1); // Get Info
			
			if($page['ID'] == "") { errorPage($_SESSION['chrLanguage']['invalid_page']); } // Did we get a result?

			$info = db_query("SELECT *
								FROM Manuals
								WHERE !bDeleted AND bShow AND ID='".$page['idManual']."'","getting Manual Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage($_SESSION['chrLanguage']['invalid_manual']); } // Did we get a result?
						
			
			# Stuff In The Header
			function sith() { 
				global $BF,$info;
				?><script type='text/javascript' src='<?=$BF?>includes/toggle.js'></script>
				<link rel="stylesheet" type="text/css" href="<?=$BF?>includes/apple_search/default.css" id="default"  />
				<script type="text/javascript" src="<?=$BF?>includes/apple_search/applesearch.js"></script><?
			}
			
			if(isset($_POST['chrSearch'.$info['ID']])) { $_SESSION['chrSearch'.$info['ID']] = $_POST['chrSearch'.$info['ID']]; $_SESSION['intSearch'.$info['ID']] = 0; } 
			
			# The template to use (should be the last thing before the break)
			$title = $page['chrTitle'];
			$section = 'MAN'.$info['chrKEY'];
			$current_man_page = $page['chrKEY'];
			$bodyParams = 'applesearch.init();';
			$leftlink = "";
			$page_background = $info['chrBGColor'];
			$headerlink_color = $info['chrLinkColor'];
			// Banner Information
			$banner_title = manual_breadcrumbs($page['ID'],true,true,true); // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			include($BF ."includes/manuallinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Email Page
		#################################################
		case 'email_page.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			$storeinfo = check_idStore();
			include_once($BF.'components/formfields.php');

			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage($_SESSION['chrLanguage']['invalid_page']); } // Check Required Field for Query
			
			$page = db_query("SELECT *
								FROM Pages
								WHERE !bDeleted AND bShow AND chrKEY='".$_REQUEST['key']."'","getting Page Info",1); // Get Info
			
			if($page['ID'] == "") { errorPage($_SESSION['chrLanguage']['invalid_page']); } // Did we get a result?

			$info = db_query("SELECT *
								FROM Manuals
								WHERE !bDeleted AND bShow AND ID='".$page['idManual']."'","getting Manual Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage($_SESSION['chrLanguage']['invalid_manual']); } // Did we get a result?

			if(count($_POST)) { include($post_file); }
			
			# Stuff In The Header
			function sith() { 
				global $BF,$info;
				?><script type='text/javascript' src='<?=$BF?>includes/toggle.js'></script>
				<script type="text/javascript" src='<?=$BF?>includes/forms.js'></script>
				<script type="text/javascript">
					var totalErrors = 0;
					function error_check() {
						if(document.getElementById('btnpress').value == 1) { 
							if(totalErrors != 0) { reset_errors(); }  
							
							totalErrors = 0;
							if(errEmpty('chrTo',"<?=$_SESSION['chrLanguage']['must_enter_email_address']?>")) { totalErrors++; }
							else if(errCC('chrTo', "<?=$_SESSION['chrLanguage']['only_valid_apple_emails']?>")) { totalErrors++; }
							if(errEmpty('chrSubject',"<?=$_SESSION['chrLanguage']['must_enter_subject']?>")) { totalErrors++; }
							if(errEmpty('txtBody',"<?=$_SESSION['chrLanguage']['must_enter_message']?>")) { totalErrors++; }
							if(totalErrors == 0) {
								document.getElementById('idForm').submit();
							} else {	
								window.scrollTo(0,0);
								return false;
							}
						} else { return false; }
					}
				</script>
				<link rel="stylesheet" type="text/css" href="<?=$BF?>includes/apple_search/default.css" id="default"  />
				<script type="text/javascript" src="<?=$BF?>includes/apple_search/applesearch.js"></script><?
			}
			
			if(isset($_POST['chrSearch'.$info['ID']])) { $_SESSION['chrSearch'.$info['ID']] = $_POST['chrSearch'.$info['ID']]; $_SESSION['intSearch'.$info['ID']] = 0; } 

			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrLanguage']['email_page'].': '.$page['chrTitle'];
			$section = 'MAN'.$info['chrKEY'];
			$current_man_page = $page['chrKEY'];
			$bodyParams = 'applesearch.init();';
			$leftlink = "";
			$page_background = $info['chrBGColor'];
			$headerlink_color = $info['chrLinkColor'];
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['email_page'].': '.$page['chrTitle']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			$page_title = $_SESSION['chrLanguage']['email_page'].': '.$page['chrTitle'];
			$page_instructions = $_SESSION['chrLanguage']['email_instructions'];
			include($BF ."includes/manuallinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	pdf-book Page
		#################################################
		case 'export_book.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			$storeinfo = check_idStore();
			include_once($BF.'components/formfields.php');

			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage($_SESSION['chrLanguage']['invalid_manual']); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM Manuals
								WHERE !bDeleted AND bShow AND chrKEY='".$_REQUEST['key']."'","getting Manual Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage($_SESSION['chrLanguage']['invalid_manual']); } // Did we get a result?

			# Stuff In The Header
			function sith() { 
				global $BF,$info;
				?><script type='text/javascript' src='<?=$BF?>includes/toggle.js'></script>
				<link rel="stylesheet" type="text/css" href="<?=$BF?>includes/apple_search/default.css" id="default"  />
				<script type="text/javascript" src="<?=$BF?>includes/apple_search/applesearch.js"></script>
				<script type="text/javascript">
					var checkflag = false;
					function init(){
						document.onkeydown = register;
						document.onkeyup = register;
						document.onclick = register;
						if (document.body.scrollTop == 0)
						document.searchform.search.focus();
					}
			
					function register(e){
						if (!e) e = window.event;
						var skey = 'shiftKey';
						var ckey = 'crtlKey';
						shiftpressed = e[skey];
						controlpressed = e[ckey];
					}
					function multiselect(e,v) {
						if(!e)e=window.event;
						var skey='shiftKey';
						var ckey='ctrlKey';
						shiftpressed = e[skey];
						controlpressed = e[ckey];
						if(shiftpressed == false) {
							firstselected = v;
							if(controlpressed == false) {
							} else {
								chk = document.getElementsByTagName('input');
								for(i=0;i<chk.length;i++) {
									if(chk[i].name.indexOf('listids')>-1) {
										if(chk[i].id != v) {
											chk[i].checked = false;
										}
									}
								}
							}
						} else {
							lastselected = v;
							start = false;
							chk = document.getElementsByTagName('input');
							for(i=0;i<chk.length;i++) {
								if(chk[i].name.indexOf('listids')>-1) {
									if(start == false && chk[i].id == firstselected) {
										start = true;
									}
									if(start == true) {
										chk[i].checked = true;
									}
									if(chk[i].id == lastselected){
										break;
									}
								}
							}
						}
					}
					function togglecheckboxes() {
						if(checkflag == false){
							val=true;
							checkflag=true;
							value="<?=$_SESSION['chrLanguage']['uncheck_all']?>";
						} else {
							val=false;
							checkflag=false;
							value="<?=$_SESSION['chrLanguage']['check_all']?>";
						}
						chk = document.getElementsByTagName('input');
							for(i=0;i<chk.length;i++){
								if(chk[i].name.indexOf('listids')>-1) {
									chk[i].checked = val;
								}
							}
						document.getElementById('chkbutton').value = value;
					}</script><?
			}
			
			if(isset($_POST['chrSearch'.$info['ID']])) { $_SESSION['chrSearch'.$info['ID']] = $_POST['chrSearch'.$info['ID']]; $_SESSION['intSearch'.$info['ID']] = 0; } 

			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrLanguage']['print_articles_book'].': '.$info['chrManual'];
			$section = 'PDF-MAN'.$info['chrKEY'];
			$leftlink = "";
			$bodyParams = 'applesearch.init();';
			$page_background = $info['chrBGColor'];
			$headerlink_color = $info['chrLinkColor'];
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['print_articles_book'].': '.$info['chrManual']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			$page_title = $_SESSION['chrLanguage']['print_articles_book'].': '.$info['chrManual']; // Title of this page. (REQUIRED)
			$page_instructions = $_SESSION['chrLanguage']['print_articles_inst'];
			include($BF ."includes/manuallinks.php");
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