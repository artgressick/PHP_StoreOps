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
			auth_check('litm',14,1);
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
				$tableName = "Holidays";
				include($BF ."includes/overlay.php");
			}
			if(!isset($_REQUEST['idCountry']) || !is_numeric($_REQUEST['idCountry'])) { $_REQUEST['idCountry'] = 1; }
			
			$q = "SELECT ID, chrKEY, bShow, chrHoliday, CONCAT('<span style=\"display:none;\">',dBegin,'</span>',DATE_FORMAT(dBegin,'%c-%e-%Y')) as dBegin, 
				  CONCAT('<span style=\"display:none;\">',dEnd,'</span>',DATE_FORMAT(dEnd,'%c-%e-%Y')) as dEnd, 
				  (SELECT COUNT(DISTINCT H.idStore) FROM HolidayStoreHours AS H WHERE H.idHoliday=Holidays.ID AND !H.bDeleted) as intStores
				FROM Holidays
				WHERE !bDeleted AND idCountry='".$_REQUEST['idCountry']."'
				ORDER BY dBegin";
			$results = db_query($q,"getting Holidays");
			
			# The template to use (should be the last thing before the break)
			$title = "Administrate Holidays";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Administrate Holidays"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$filter = db_query("SELECT ID,chrCountry AS chrRecord FROM Countries WHERE !bDeleted ORDER BY dOrder, chrCountry", "Getting Filter Options");
			$filter = form_select($filter,array('caption'=>'- Select Country -','name'=>'idCountry','nocaption'=>'true','value'=>$_REQUEST['idCountry'],'extra'=>'onchange="location.href=\'index.php?idCountry=\'+this.value"'));
			$page_title = (access_check(14,2) ? linkto(array('address'=>'add.php','img'=>'/images/plus_add.png')):'').' Holidays <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$page_instructions = "Select a Holiday to Edit from the list.";
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
			auth_check('litm',14,2);
			include($BF.'components/formfields.php');
			
			if(isset($_POST['chrHoliday'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
			
?>	<script type='text/javascript'>
		var page = 'add';
	</script>
	<script type="text/javascript" src="colorfind.js"></script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Add Holiday";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Add Holiday"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Add Holiday";
			$page_instructions = 'Enter information and click "Add"';
			$bodyParams = "document.getElementById('chrHoliday').focus();";
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
			auth_check('litm',14,3);
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Holiday'); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM Holidays
								WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Holiday Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Holiday'); } // Did we get a result?
				
			
			if(isset($_POST['chrHoliday'])) { include($post_file); }
			
			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
			
?>	<script type='text/javascript'>
		var page = 'edit';
	</script>
	<script type="text/javascript" src="colorfind.js"></script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit Holiday";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Edit Holiday"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Edit ".$info['chrHoliday'];
			$page_instructions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$bodyParams = "document.getElementById('chrHoliday').focus();";
			include($BF ."includes/adminlinks.php");
			include($BF ."models/template.php");		
			
			break;

		#################################################
		##	Download Excel
		#################################################
		case '_excel.php':
			# Adding in the lib file
			$NON_HTML_PAGE=1;
			include($BF .'_lib.php');
			auth_check('litm',14,1);
			require_once "Spreadsheet/Excel/Writer.php";

			# Stuff In The Header
			function sith() { 
				global $BF;
			}
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Holiday'); } // Check Required Field for Query
			
			$q = "SELECT ID,chrHoliday,dBegin,dEnd FROM Holidays WHERE !bDeleted AND chrKEY = '".$_REQUEST['key']."'
				";
			$info = db_query($q,"getting Holiday",1);
			
			if($info['ID'] == "") { errorPage('Invalid Holiday'); } // Did we get a result?
			
			
			break;
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>