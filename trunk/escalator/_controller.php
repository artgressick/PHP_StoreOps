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
				?><script type='text/javascript' src='<?=$BF?>includes/toggle.js'></script><?
			}

			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrLanguage']['escalator'];
			$section = "escalator";
			$leftlink = "";
			$page_background = "grey";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['escalator']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			include($BF ."includes/escalatorlinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Template Page
		#################################################
		case 'template.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			$storeinfo = check_idStore();
			include_once($BF.'components/formfields.php');

			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage($_SESSION['chrLanguage']['error_invalid_escalator_template']); } // Check Required Field for Query
			
			$info = db_query("SELECT ID, chrKEY, bUploads, chrTitle, txtDirections, txtDistro, bPlainEmail, bManager
								FROM EscalatorTemplates
								WHERE bShow AND !bDeleted AND idLanguage IN (".$storeinfo['txtLanguage'].") AND chrKEY='".$_REQUEST['key']."'","getting Template",1); // Get Info
			
			if($info['ID'] == "") { errorPage($_SESSION['chrLanguage']['error_invalid_escalator_template']); } // Did we get a result?
			
			$results = db_query("SELECT ID, bRequired, idFieldType, dOrder, chrQuestion, txtOptions, idTemplate
				FROM EscalatorQuestions
				WHERE idTemplate = ".$info['ID']." AND !bDeleted
				ORDER BY dOrder, chrQuestion
			","Getting questions");

			
			if(count($_POST)) { include($post_file); }
			
			# Stuff In The Header
			function sith() { 
				global $BF, $results, $info;
				include($BF .'components/list/sortlistjs.php');
?>
	<script type='text/javascript' src='<?=$BF?>includes/toggle.js'></script>
	<script type="text/javascript" src='<?=$BF?>includes/forms.js'></script>
	<script type="text/javascript">
		var totalErrors = 0;
		function error_check() {
			if(document.getElementById('btnpress').value == 1) { 
				if(totalErrors != 0) { reset_errors(); }  
				
				totalErrors = 0;
				if(errCC('chrCC', "<?=$_SESSION['chrLanguage']['only_valid_apple_emails']?>")) { totalErrors++; }
<?
			if($info['bManager']) {
?>
				if(errEmpty('chrEmployeeName', "<?=$_SESSION['chrLanguage']['you_must_enter_an_answer_for']?> \"<?=$_SESSION['chrLanguage']['your_name']?>\"")) { totalErrors++; }
				if(errEmpty('chrEmployeeEmail', "<?=$_SESSION['chrLanguage']['you_must_enter_an_answer_for']?> \"<?=$_SESSION['chrLanguage']['your_email']?>\"")) { totalErrors++; } 
				else if(errEmail('chrEmployeeEmail','Apple',"<?=$_SESSION['chrLanguage']['only_valid_apple_emails']?>")) { totalErrors++; }
<?					
			}
				$i = 1;
				while($row = mysqli_fetch_assoc($results)) {
					if($row['bRequired'] && $row['idFieldType'] != 6) {
						if($row['idFieldType'] == 1 || $row['idFieldType'] == 2 || $row['idFieldType'] == 3 || $row['idFieldType'] == 7) {
?>
			 	if(errEmpty('<?=$row['ID']?>', "<?=$_SESSION['chrLanguage']['you_must_enter_an_answer_for']?> \"<?=$row['chrQuestion']?>\"")) { totalErrors++; }<?
						} else {
?> 
				if(errEmpty('<?=$row['ID']?>[]', "<?=$_SESSION['chrLanguage']['you_must_select_an_answer_for']?> \"<?=$row['chrQuestion']?>\"","array")) { totalErrors++; }<?
						}
					}
					$i++;
				}
				mysqli_data_seek($results,0);
?>
				if(totalErrors == 0) {
					document.getElementById('idForm').submit();
				} else {	
					window.scrollTo(0,0);
					return false;
				}
			} else { return false; }
		}
		function newOption(num,table) {
			var currentnum = parseInt(document.getElementById('int'+table).value) + 1;
			document.getElementById('int'+table).value = currentnum;
			
			var tr = document.createElement('tr');
			var td1 = document.createElement('td');
			var td2 = document.createElement('td');
			
			td1.innerHTML = "<?=$_SESSION['chrLanguage']['file']?> "+ currentnum +":";
			td2.id = table+"file"+ currentnum;
			td2.innerHTML = "<input type='file' name='chr"+table+"File"+ currentnum +"' id='chr"+table+"File"+ currentnum +"' />";
			
			tr.appendChild(td1);
			tr.appendChild(td2);
			document.getElementById(table+"tbody").appendChild(tr);
		}

	</script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrLanguage']['escalator'].' - '.$info['chrTitle'];
			$section = "escalator";
			$leftlink = "";
			$page_background = "grey";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['escalator'].' - '.$info['chrTitle']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			$page_title = $info['chrTitle'];
			$page_instructions = $info['txtDirections'];
			$current_esc_page = $_REQUEST['key'];
			include($BF ."includes/escalatorlinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	Escalations Page
		#################################################
		case 'escalations.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			$storeinfo = check_idStore();
			include($BF.'components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'components/list/sortlistjs.php');
				?><script type='text/javascript' src='<?=$BF?>includes/toggle.js'></script><?
			}
			
			if(!isset($_REQUEST['chrSearch'])) { $_REQUEST['chrSearch'] = ''; }
			if(!isset($_REQUEST['idStatus']) || !is_numeric($_REQUEST['idStatus'])) { $_REQUEST['idStatus'] = '0'; }
			
			$q = "SELECT E.ID, E.chrKEY, CONCAT('<span style=\'display:none;\'>',E.dtCreated,'</span>',DATE_FORMAT(E.dtCreated,'".$_SESSION['chrLanguage']['date_time_format']."')) as dtCreated, E.idStatus AS idEscStatus, T.chrTitle, C.chrCategory
					FROM Escalations AS E
					JOIN EscalatorTemplates AS T ON E.idTemplate=T.ID
					JOIN EscalatorCats AS C ON T.idCategory=C.ID
					WHERE !E.bDeleted AND !T.bManager AND !T.bDeleted AND idStore='".$storeinfo['ID']."'";
			
			if($_REQUEST['chrSearch'] != '') {
				$q .= " AND E.ID IN(SELECT EA.idEscalation 
									FROM EscAnswers AS EA 
									JOIN Escalations AS ES ON EA.idEscalation=ES.ID
									WHERE lcase(EA.txtAnswer) LIKE lcase('%".encode($_REQUEST['chrSearch'])."%') AND ES.idStore='".$storeinfo['ID']."'
									GROUP BY EA.idEscalation)";
			}

			
			
			if($_REQUEST['idStatus'] != '0') {
				$q .= " AND E.idStatus='".$_REQUEST['idStatus']."'";
			}
			
			
			$q .= "	ORDER BY dtCreated";
			$results = db_query($q,"getting Escalations for this store");
	
			# The template to use (should be the last thing before the break)
			$title = $_SESSION['chrLanguage']['view_escalation'];
			$section = "escalator";
			$leftlink = "view_escalation";
			$page_background = "grey";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = $_SESSION['chrLanguage']['view_escalation']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			$page_title = $_SESSION['chrLanguage']['view_escalation'];
			$page_instructions = $_SESSION['chrLanguage']['view_escalations_instructions'];
			
			$filter = "<b>".$_SESSION['chrLanguage']['status']."</b>:
					<select name='idStatus' id='idStatus' onchange='document.getElementById(\"FormFilter\").submit();'>
						<option value='1'".($_REQUEST['idStatus']==1?" selected='selected'":"").">".$_SESSION['chrLanguage']['esc_status_1']."</option>
						<option value='2'".($_REQUEST['idStatus']==2?" selected='selected'":"").">".$_SESSION['chrLanguage']['esc_status_2']."</option>
					</select>&nbsp;&nbsp;&nbsp;&nbsp;
					<b>".$_SESSION['chrLanguage']['search']."</b>: <input type='text' name='chrSearch' value='".encode($_REQUEST['chrSearch'])."' size='20' />";
			
			include($BF ."includes/escalatorlinks.php");
			include($BF ."models/template.php");	

			break;
		#################################################
		##	View Page
		#################################################
		case 'view.php':
			# Adding in the lib file
			include($BF .'_lib.php');
 			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Escalation'); } // Check Required Field for Query

 			$info = db_query("SELECT E.ID, E.chrKEY, E.idStatus, DATE_FORMAT(E.dtCreated,'".$_SESSION['chrLanguage']['date_time_format']."') AS dtCreated, E.chrCC, T.chrTitle, C.chrCategory, E.idTemplate, T.txtDistro,E.idStore, T.bPlainEmail, T.bManager, E.chrEmployeeName, E.chrEmployeeEmail
 							  FROM Escalations AS E
 							  JOIN EscalatorTemplates AS T ON E.idTemplate=T.ID
 							  JOIN EscalatorCats AS C ON T.idCategory=C.ID
 							  WHERE !E.bDeleted AND E.chrKEY='". $_REQUEST['key'] ."'","getting info",1); // Get Info
 			
 			if($info['ID'] == '') { errorPage('Invalid Escalation'); }
			
 			$storeinfo2 = check_idStore($info['idStore']);
 			
 			$master = db_query("SELECT chrVar, chrLabel FROM MasterLang","Getting Master Language");
			while($row = mysqli_fetch_assoc($master)) {
				$storelang[$row['chrVar']] = $row['chrLabel'];
			}
			$langreplace = db_query("SELECT chrVar, chrLabel 
									 FROM Languages
									 JOIN Langs ON Langs.idLanguage=Languages.ID
									 WHERE !Languages.bDeleted AND Languages.ID=".$storeinfo2['idLanguage'],"Getting Replacements");
				
			while ($row = mysqli_fetch_assoc($langreplace)) {
				$storelang[$row['chrVar']] = $row['chrLabel'];
			}
 			
			include($BF.'components/formfields.php');
			
			# Stuff In The Header
			function sith() { 
				global $BF, $storelang;
?>			
			<style type="text/css">
				.FormName { font-size: 12px; line-height: 14px; font-weight: bold; margin-left:2px; }
			</style>
			<script type='text/javascript' src='<?=$BF?>includes/toggle.js'></script>
			<script type="text/javascript" src='<?=$BF?>includes/forms.js'></script>
			<script type="text/javascript">
				var totalErrors = 0;
				function error_check() {
					if(document.getElementById('btnpress').value == 1) { 
						if(totalErrors != 0) { reset_errors(); }  
						
						totalErrors = 0;
						if(errEmpty('txtComments', "<?=$storelang['error_comments']?>")) { totalErrors++; }
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

 			$results = db_query("SELECT ID, idFieldType, dOrder, chrQuestion 
 				FROM EscalatorQuestions
				WHERE idTemplate = ".$info['idTemplate']." AND !bDeleted
				ORDER BY dOrder, chrQuestion
			","Getting questions");

 			if(count($_POST)) { include($post_file); }

			# The template to use (should be the last thing before the break)
			$title = $storelang['view_escalation'].': '.$info['chrTitle'].' - '.$storeinfo2['chrStore'];
			$section = "escalator";
			$leftlink = "view_escalation";
			$page_background = "grey";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = $storelang['view_escalation'].': '.$info['chrTitle'].' - '.$storeinfo2['chrStore']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			$page_title = $storelang['view_escalation'].': '.$info['chrTitle'].' - '.$storeinfo2['chrStore'];
			$page_instructions = '';
			include($BF ."includes/escalatorlinks.php");
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