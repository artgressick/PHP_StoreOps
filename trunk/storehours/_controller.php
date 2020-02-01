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

			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'components/list/sortlistjs.php');
			}

			$tmp = db_query("SELECT ID, bClosed, idDayOfWeek, DATE_FORMAT(tOpening,'".$_SESSION['chrLanguage']['time_format2']."') as tOpening, DATE_FORMAT(tClosing,'".$_SESSION['chrLanguage']['time_format2']."') as tClosing FROM StoreHours WHERE !bDeleted AND idStore='".$storeinfo['ID']."'","Getting Regular Hours");
			if(mysqli_num_rows($tmp) < 7) { header("Location: updatehours.php"); die(); }
			$hours = array();
			while($row = mysqli_fetch_assoc($tmp)) {
				$hours[$row['idDayOfWeek']] = $row;
			}
			
			
			if(!isset($_REQUEST['d']) || !is_numeric($_REQUEST['d']) || strlen($_REQUEST['d']) != 6) {
				$_REQUEST['d'] = date('mY');
			}
			
			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrLanguage']['store_hours'];
			$section = "storehours";
			$leftlink = "";
			$page_background = "#4d6492";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['store_hours']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			include($BF ."includes/storehourslinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Update Hours Page
		#################################################
		case 'updatehours.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			$storeinfo = check_idStore();
			include_once($BF.'components/formfields.php');

			function day_of_week($val) {
				# Sunday = 0, Saturday = 6
				$dow = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
				return $dow[$val];
			}
			
			$tmp = db_query("SELECT ID, bClosed, idDayOfWeek, DATE_FORMAT(tOpening,'".$_SESSION['chrLanguage']['time_format2']."') as tOpening, DATE_FORMAT(tClosing,'".$_SESSION['chrLanguage']['time_format2']."') as tClosing FROM StoreHours WHERE !bDeleted AND idStore='".$storeinfo['ID']."'","Getting Regular Hours");
			$hours = array();
			while($row = mysqli_fetch_assoc($tmp)) {
				$hours[$row['idDayOfWeek']] = $row;
			}
			
			if(count($_POST)) { include($post_file); }
			
			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'components/list/sortlistjs.php');
?>
				<script type="text/javascript" src='<?=$BF?>includes/forms.js'></script>
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
							days[0]="<?=$_SESSION['chrLanguage']['Sunday']?>";
							days[1]="<?=$_SESSION['chrLanguage']['Monday']?>";
							days[2]="<?=$_SESSION['chrLanguage']['Tuesday']?>";
							days[3]="<?=$_SESSION['chrLanguage']['Wednesday']?>";
							days[4]="<?=$_SESSION['chrLanguage']['Thursday']?>";
							days[5]="<?=$_SESSION['chrLanguage']['Friday']?>";
							days[6]="<?=$_SESSION['chrLanguage']['Saturday']?>";
							for(i=0;i<days.length;i++) {
								if(document.getElementById('bClosed'+i).checked==false) {
									if(errEmpty('tBegin'+i,"<?=$_SESSION['chrLanguage']['must_enter_begin_time_for']?> "+days[i])) { totalErrors++; }
									if(errEmpty('tClose'+i,"<?=$_SESSION['chrLanguage']['must_enter_close_time_for']?> "+days[i])) { totalErrors++; }
								} else {
									setColorDefault('tBegin'+i);
									setColorDefault('tClose'+i);
								}
							}
							if(totalErrors == 0) {
								document.getElementById('idForm').submit();
							} else {
								window.scrollTo(0,0);
								return false;
							}
						} else { return false; }
					}
				</script>
<?
			}
	

			$defaulttimes = db_query("SELECT DATE_FORMAT('2008-12-01 08:00:00','".$_SESSION['chrLanguage']['time_format2']."') as tOpening, DATE_FORMAT('2008-12-01 21:00:00','".$_SESSION['chrLanguage']['time_format2']."') as tClosing","Set Default Times",1);
			
			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrLanguage']['update_hours'];
			$section = "storehours";
			$leftlink = "update_hours";
			$page_background = "#4d6492";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['update_hours']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			$page_title = $_SESSION['chrLanguage']['update_hours'].': '.$storeinfo['chrStore'];
			$page_instructions = $_SESSION['chrLanguage']['update_hours_instructions'];
			include($BF ."includes/storehourslinks.php");
			include($BF ."models/template.php");		
			
			break;

		#################################################
		##	Holidays Page
		#################################################
		case 'holidays.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			$storeinfo = check_idStore();
			include_once($BF.'components/formfields.php');
			
			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'components/list/sortlistjs.php');
			}

			$tmp = db_query("SELECT ID, bClosed, idDayOfWeek, DATE_FORMAT(tOpening,'".$_SESSION['chrLanguage']['time_format2']."') as tOpening, DATE_FORMAT(tClosing,'".$_SESSION['chrLanguage']['time_format2']."') as tClosing FROM StoreHours WHERE !bDeleted AND idStore='".$storeinfo['ID']."'","Getting Regular Hours");
			if(mysqli_num_rows($tmp) < 7) { header("Location: updatehours.php"); die(); }
			
			$q = "SELECT ID, chrKEY, chrHoliday, CONCAT('<span style=\"display:none;\">',dBegin,'</span>',DATE_FORMAT(dBegin,'".$_SESSION['chrLanguage']['date_format2']."')) as dBegin, 
				  CONCAT('<span style=\"display:none;\">',dEnd,'</span>',DATE_FORMAT(dEnd,'".$_SESSION['chrLanguage']['date_format2']."')) as dEnd, 
				  if((SELECT COUNT(ID) FROM HolidayStoreHours AS H WHERE H.idHoliday=Holidays.ID AND !H.bDeleted AND idStore='".$storeinfo['ID']."') > 0,'".$_SESSION['chrLanguage']['completed']."','".$_SESSION['chrLanguage']['not_completed']."') AS chrStatus
				FROM Holidays
				WHERE !bDeleted AND bShow AND idCountry='".$storeinfo['idCountry']."'
				ORDER BY dBegin";
			$results = db_query($q,"getting Holidays");
			
			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrLanguage']['holidays'];
			$section = "storehours";
			$leftlink = "holidays";
			$page_background = "#4d6492";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['holidays']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			$page_title = $_SESSION['chrLanguage']['holidays'];
			$page_instructions = $_SESSION['chrLanguage']['holiday_instructions'];
			include($BF ."includes/storehourslinks.php");
			include($BF ."models/template.php");		
			
			break;	
		#################################################
		##	Update Holiday Hours Page
		#################################################
		case 'holidayhours.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			$storeinfo = check_idStore();
			include_once($BF.'components/formfields.php');

			function day_of_week($val) {
				# Sunday = 0, Saturday = 6
				$dow = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
				return $dow[$val];
			}
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage($_SESSION['chrLanguage']['invalid_holiday']); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM Holidays
								WHERE !bDeleted AND bShow AND chrKEY='".$_REQUEST['key']."'","getting Holiday Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage($_SESSION['chrLanguage']['invalid_holiday']); } // Did we get a result?
			
			
			$tmp = db_query("SELECT ID, bClosed, idDayOfWeek, DATE_FORMAT(tOpening,'".$_SESSION['chrLanguage']['time_format2']."') as tOpening, DATE_FORMAT(tClosing,'".$_SESSION['chrLanguage']['time_format2']."') as tClosing FROM StoreHours WHERE !bDeleted AND idStore='".$storeinfo['ID']."'","Getting Regular Hours");
			if(mysqli_num_rows($tmp) < 7) { header("Location: updatehours.php"); die(); }
			$hours = array();
			while($row = mysqli_fetch_assoc($tmp)) {
				$hours[$row['idDayOfWeek']] = $row;
			}
			
			if(count($_POST)) { include($post_file); }

			$newhours = db_query("SELECT * FROM HolidayStoreHours WHERE idHoliday='".$info['ID']."' AND idStore='".$storeinfo['ID']."'","Getting Holiday Hours");
			while($row = mysqli_fetch_assoc($newhours)) {
				$holidayhours[$row['dDate']] = $row;	
			}
			
			
			# Stuff In The Header
			function sith() { 
				global $BF, $info;
				include($BF .'components/list/sortlistjs.php');
?>
				<script type="text/javascript" src='<?=$BF?>includes/forms.js'></script>
				<script type="text/javascript">
					function closed(dDate) {
						if(document.getElementById('bClosed'+dDate).checked == true) {
							document.getElementById('tBegin'+dDate).value='';
							document.getElementById('tBegin'+dDate).disabled=true;
							document.getElementById('tClose'+dDate).value='';
							document.getElementById('tClose'+dDate).disabled=true;
						} else {
							document.getElementById('tBegin'+dDate).value=document.getElementById('d_tBegin'+dDate).value;
							document.getElementById('tBegin'+dDate).disabled=false;
							document.getElementById('tClose'+dDate).value=document.getElementById('d_tClose'+dDate).value;
							document.getElementById('tClose'+dDate).disabled=false;
						}
					} 
					var totalErrors = 0;
					function error_check() {
						
						if(document.getElementById('btnpress').value == 1) {
							if(totalErrors != 0) { reset_errors(); }  
<?
	$totalDays = (strtotime($info['dEnd']) - strtotime($info['dBegin']))/60/60/24;
	$i=0;
	$dCurrent = $info['dBegin'];
	while($i <= $totalDays) {
?>
								if(document.getElementById('bClosed<?=$dCurrent?>').checked==false) {
									if(errEmpty('tBegin<?=$dCurrent?>',"<?=$_SESSION['chrLanguage']['must_enter_begin_time_for']." "?>"+document.getElementById('tag<?=$dCurrent?>').innerHTML)) { totalErrors++; }
									if(errEmpty('tClose<?=$dCurrent?>',"<?=$_SESSION['chrLanguage']['must_enter_close_time_for']." "?>"+document.getElementById('tag<?=$dCurrent?>').innerHTML)) { totalErrors++; }
								} else {
									setColorDefault('tBegin<?=$dCurrent?>');
									setColorDefault('tClose<?=$dCurrent?>');
								}
<?
		$dCurrent = date('Y-m-d',strtotime($info['dBegin']." + ".($i++ + 1)." days"));
	}
?>
							if(totalErrors == 0) {
								document.getElementById('idForm').submit();
							} else {	
								window.scrollTo(0,0);
								return false;
							}
						} else { return false; }
					}
				</script>
<?
			}

			$defaulttimes = db_query("SELECT DATE_FORMAT('2008-12-01 08:00:00','".$_SESSION['chrLanguage']['time_format2']."') as tOpening, DATE_FORMAT('2008-12-01 21:00:00','".$_SESSION['chrLanguage']['time_format2']."') as tClosing","Set Default Times",1);

			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrLanguage']['update_holiday_hours'];
			$section = "storehours";
			$leftlink = "update_holiday_hours";
			$page_background = "#4d6492";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['update_holiday_hours']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			$page_title = $_SESSION['chrLanguage']['update_holiday_hours'].': '.$info['chrHoliday'];
			$page_instructions = $_SESSION['chrLanguage']['update_holiday_hours_instructions'];
			include($BF ."includes/storehourslinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

	function get_dates($var) {
		
		$intMonth = substr($var,0,2);
		$intYear = substr($var,2,6);
		
		return(array(
				$intMonth,
				$intYear,
				1-(idate('w', mktime(0, 0, 0, $intMonth, 1, $intYear))),
				idate('t', mktime(0, 0, 0, $intMonth, 1, $intYear)),
				idate('t', mktime(0, 0, 0, ($intMonth-1), 1, $intYear))
		));
	}
	
?>