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
			auth_check('litm',15,1);
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
				$tableName = "Stores";
				include($BF ."includes/overlay.php");
			}
			
			$q = "SELECT S.ID, S.chrKEY, CONCAT(S.chrStore,' / ', S.chrStoreNum) AS chrStore,
					GROUP_CONCAT(CONCAT(idDayOfWeek,':::',bClosed,':::',tOpening,':::',tClosing) ORDER BY idDayOfWeek SEPARATOR '|||') AS txtHours
				FROM Stores AS S
				LEFT JOIN StoreHours AS SH ON S.ID=SH.idStore
				WHERE !S.bDeleted
				GROUP BY S.ID
				ORDER BY chrStore";
			$results = db_query($q,"getting Store hours");
			
			# The template to use (should be the last thing before the break)
			$title = "Administrate Store Hours";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Administrate Store Hours"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = 'Stores Hours <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>&nbsp;&nbsp;'.linkto(array('address'=>'_excel.php','display'=>'Export to Excel','style'=>'color:blue; font-size:11px;'));
			$page_instructions = "Select a Store to Edit their store hours from the list.";
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
			auth_check('litm',15,3);
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Store'); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM Stores
								WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Store Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Store'); } // Did we get a result?
			
			function day_of_week($val) {
				# Sunday = 0, Saturday = 6
				$dow = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
				return $dow[$val];
			}
			
			$tmp = db_query("SELECT ID, bClosed, idDayOfWeek, DATE_FORMAT(tOpening,'%l:%i %p') as tOpening, DATE_FORMAT(tClosing,'%l:%i %p') as tClosing FROM StoreHours WHERE !bDeleted AND idStore='".$info['ID']."'","Getting Regular Hours");
			$hours = array();
			while($row = mysqli_fetch_assoc($tmp)) {
				$hours[$row['idDayOfWeek']] = $row;
			}
			
			if(count($_POST)) { include($post_file); }
			
			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
			
?>				<script type="text/javascript" src='<?=$BF?>includes/forms.js'></script>
				<script type="text/javascript">
					function closed(dow) {
						if(document.getElementById('bClosed'+dow).checked == true) {
							document.getElementById('tBegin'+dow).value='';
							document.getElementById('tBegin'+dow).disabled=true;
							document.getElementById('tClose'+dow).value='';
							document.getElementById('tClose'+dow).disabled=true;
						} else {
							document.getElementById('tBegin'+dow).value=document.getElementById('d_tBegin'+dow).value;
							document.getElementById('tBegin'+dow).disabled=false;
							document.getElementById('tClose'+dow).value=document.getElementById('d_tClose'+dow).value;
							document.getElementById('tClose'+dow).disabled=false;
						}
					} 
					var totalErrors = 0;
					function error_check() {
						
						if(document.getElementById('btnpress').value == 1) {
							if(totalErrors != 0) { reset_errors(); }  
							var days=new Array();
							days[0]="Sunday";
							days[1]="Monday";
							days[2]="Tuesday";
							days[3]="Wednesday";
							days[4]="Thursday";
							days[5]="Friday";
							days[6]="Saturday";
							for(i=0;i<days.length;i++) {
								if(document.getElementById('bClosed'+i).checked==false) {
									if(errEmpty('tBegin'+i,"You must enter a Begin time for "+days[i])) { totalErrors++; }
									if(errEmpty('tClose'+i,"You must enter a Close time for "+days[i])) { totalErrors++; }
								} else {
									setColorDefault('tBegin'+i);
									setColorDefault('tClose'+i);
								}
							}
							if(totalErrors == 0) {
								document.getElementById('idForm').submit();
							} else {	
								return false;
							}
						} else { return false; }
					}
				</script>
<?
			}

			$defaulttimes = db_query("SELECT DATE_FORMAT('2008-12-01 08:00:00','%l:%i %p') as tOpening, DATE_FORMAT('2008-12-01 21:00:00','%l:%i %p') as tClosing","Set Default Times",1);
			
			# The template to use (should be the last thing before the break)
			$title = "Edit Store Hours";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Edit Store Hours"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Edit Store Hours for Store: ".$info['chrStore'];
			$page_instructions = 'Please update the information below and press the "Update Information" when you are done making changes. Use time enteries of 13:00 or 1:00 pm.';
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
			auth_check('litm',15,1);
			require_once "Spreadsheet/Excel/Writer.php";

			# Stuff In The Header
			function sith() { 
				global $BF;
			}
			
			break;
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>