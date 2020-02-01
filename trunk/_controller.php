<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '';
	
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

			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'components/list/sortlistjs.php');
			}

			# The template to use (should be the last thing before the break)
			$page_title = $_SESSION['chrLanguage']['landing_index_title'];
			$section = "home";
			$leftlink = "";
			$page_background = "lightblue";
			$headerlink_color = "black";
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['welcome_to_storeops']; // Title of this page. (REQUIRED)
			$banner_instructions = $_SESSION['chrLanguage']['landing_index_directions']; // Instructions or description. (NOT REQUIRED)
			include($BF ."includes/homelinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Log Out Page
		#################################################
		case 'logout.php':
			# Adding in the lib file
			include($BF .'_lib.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}
						# The template to use (should be the last thing before the break)
			$title = "Logged Out";
			$section = "home";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Logged Out"; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			include($BF ."includes/homelinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Error Page
		#################################################
		case 'error.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			$storeinfo = check_idStore();
			include_once($BF.'components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrLanguage']['error_page'];
			$section = "";
			$leftlink = "";
			$page_background = "red";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['error_page']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			include($BF ."includes/homelinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Error Page
		#################################################
		case 'locked.php':
			$title = "Account Locked";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			include_once($BF.'components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrLanguage']['account_locked'];
			$section = "";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['account_locked']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			include($BF ."includes/homelinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Stores Page
		#################################################
		case 'stores.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			include_once($BF.'components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'components/list/sortlistjs.php');
			}

			if(isset($_POST['idStore']) && is_numeric($_POST['idStore'])) { 
				setcookie("idStore", $_POST['idStore'], time()+60*60*24*180,'/');  /* expire in 180 days */

				$temp = db_query("SELECT S.ID, S.chrStore, S.txtLanguage
				FROM Stores AS S
				JOIN Regions AS R ON S.idRegion=R.ID
				JOIN Divisions AS D ON S.idDivision=D.ID
				JOIN Countries AS C ON S.idCountry=C.ID
				WHERE !S.bDeleted AND !R.bDeleted AND !D.bDeleted AND !C.bDeleted AND S.ID=".$_POST['idStore'],"Get Store Info",1);
			if($temp['ID'] != '') {
				$_COOKIE['idStore'] = $temp['ID'];
				$_SESSION['chrStore'] = $temp['chrStore'];
				$test = db_query("SELECT ID FROM Languages WHERE !bDeleted AND bShow AND ID IN (".$temp['txtLanguage'].") ","Checking Languages",1);

//				$langids = explode(',',$temp['txtLanguage']);
				setcookie("StoreOpsLanguage", $test['ID'], time()+60*60*24*180, '/');  /* expire in 180 days */ 
				$_COOKIE['StoreOpsLanguage'] = $test['ID'];
				$master = db_query("SELECT chrVar, chrLabel FROM MasterLang","Getting Master Language");
				while($row = mysqli_fetch_assoc($master)) {
					$_SESSION['chrLanguage'][$row['chrVar']] = $row['chrLabel'];
				}
				$langreplace = db_query("SELECT chrVar, chrLabel 
										 FROM Languages
										 JOIN Langs ON Langs.idLanguage=Languages.ID
										 WHERE !Languages.bDeleted AND Languages.ID=".$_COOKIE['StoreOpsLanguage'],"Getting Replacements");
					
				while ($row = mysqli_fetch_assoc($langreplace)) {
					$_SESSION['chrLanguage'][$row['chrVar']] = $row['chrLabel'];
				}
				$langs = db_query("SELECT ID, chrIcon, chrLanguage FROM Languages WHERE !bDeleted AND bShow AND ID IN (".$temp['txtLanguage'].") ORDER BY dOrder","Get Languages");
				$_SESSION['LangIcons'] = array();
				while($row = mysqli_fetch_assoc($langs)) {
					$_SESSION['LangIcons'][$row['ID']] = array('icon'=>$row['chrIcon'],'chrLang'=>$row['chrLanguage']);
				} 
			} else {
				unset($_SESSION['chrStore']);
				unset($_COOKIE['idStore']);
			}
				
				header("Location: index.php");
				die();
			}
			
			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrLanguage']['choose_store'];
			$section = "home";
			$leftlink = "";
			$page_background = "lightblue";
			$headerlink_color = "black";
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['choose_store']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			$page_title = $_SESSION['chrLanguage']['choose_store'];
			$page_instructions = "";
			include($BF ."includes/homelinks.php");
			include($BF ."models/template.php");		
			
			break;

		#################################################
		##	Import Page
		#################################################
		case 'import.php':
			$title = "Import Data";	# Page Title
			# Adding in the lib file
			include($BF .'_lib.php');
			include_once($BF.'components/formfields.php');
			include_once($BF.'components/add_functions.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$title = "Import Data";
			$section = "";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Import Data"; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			include($BF ."includes/homelinks.php");
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