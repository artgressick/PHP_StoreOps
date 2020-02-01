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
			auth_check('litm',12,1);
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
			}
			
			if(isset($_REQUEST['idEscLang']) && is_numeric($_REQUEST['idEscLang'])) {
				$_SESSION['idEscLang'] = $_REQUEST['idEscLang'];
			}
			
			if(!isset($_SESSION['idEscLang']) || !is_numeric($_SESSION['idEscLang'])) {
				$_SESSION['idEscLang'] = 1;
			}
			
			$q = "SELECT T.ID, T.chrKEY, T.chrTitle, C.chrCategory, (SELECT COUNT(E.ID) FROM Escalations AS E WHERE !E.bDeleted AND E.idTemplate=T.ID AND E.idStatus=1) as intOpen, (SELECT COUNT(E.ID) FROM Escalations AS E WHERE !E.bDeleted AND E.idTemplate=T.ID AND E.idStatus=2) as intClosed
				FROM EscalatorTemplates AS T
				JOIN EscalatorCats AS C ON T.idCategory=C.ID
				WHERE !T.bDeleted AND !C.bDeleted AND T.idLanguage='".$_SESSION['idEscLang']."'
				ORDER BY C.dOrder,T.dOrder,T.chrTitle";
			$results = db_query($q,"getting Categories");

			if(count($_POST)) { include($post_file); }
			
			# The template to use (should be the last thing before the break)
			$title = "Store Escalations";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Store Escalations"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = 'Store Escalations';
			$page_instructions = "Select a Escalator Template to view the Escalations.";
			$filter = db_query("SELECT ID,chrLanguage AS chrRecord FROM Languages WHERE !bDeleted ORDER BY dOrder, chrLanguage", "Getting Filter Options");
			$filter = form_select($filter,array('caption'=>'- Select Language -','name'=>'idEscLang','nocaption'=>'true','value'=>$_SESSION['idEscLang'],'extra'=>'onchange="location.href=\'index.php?idEscLang=\'+this.value"'));
			include($BF ."includes/adminlinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	List Page
		#################################################
		case 'list.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			auth_check('litm',12,1);
			include_once($BF.'components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script><?
				include($BF .'components/list/sortlistnew.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "Escalations";
				include($BF ."includes/overlay.php");
			}

			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Escalation Template'); } // Check Required Field for Query
			
			$info = db_query("SELECT ID, chrKEY, chrTitle, idLanguage
								FROM EscalatorTemplates
								WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Template",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Escalation Template'); } // Did we get a result?
			
			if(!isset($_REQUEST['chrSearch'])) { $_REQUEST['chrSearch'] = ''; }
			if(!isset($_REQUEST['idStoreFilter'])) { $_REQUEST['idStoreFilter'] = ''; }
			if(!isset($_REQUEST['idStatus']) || !is_numeric($_REQUEST['idStatus'])) { $_REQUEST['idStatus'] = '1'; }
			
			$q = "SELECT E.ID, E.chrKEY, E.idStatus, CONCAT('<span style=\'display:none;\'>',E.dtCreated,'</span>',DATE_FORMAT(E.dtCreated,'%c/%e/%Y - %l:%i %p')) as dtCreated, DATE_FORMAT(E.dtCreated,'%c/%e/%Y - %l:%i %p') AS dtCreated2, S.chrStore, S.chrStoreNum, IF(E.idStatus=1,'Open','Closed') AS chrStatus,
					(SELECT COUNT(F.ID) FROM EscFiles AS F WHERE F.idEscalation=E.ID) AS intFiles,
					(SELECT COUNT(C.ID) FROM EscComments AS C WHERE C.idEscalation=E.ID) AS intComments
					FROM Escalations AS E
					JOIN Stores AS S ON E.idStore=S.ID
					WHERE !E.bDeleted AND E.idTemplate='".$info['ID']."'";

			if($_REQUEST['chrSearch'] != '') {
				$q .= " AND E.ID IN(SELECT EA.idEscalation 
									FROM EscAnswers AS EA 
									JOIN Escalations AS ES ON EA.idEscalation=ES.ID
									WHERE ES.idTemplate='".$info['ID']."' AND 
									lcase(EA.txtAnswer) LIKE '%".encode(strtolower($_REQUEST['chrSearch']))."%'
									AND lcase(EA.txtAnswer) RLIKE '[[:<:]]".encode(strtolower($_REQUEST['chrSearch']))."[[:>:]]')";
									
									
									
/*
				$q .= " AND E.ID IN(SELECT EA.idEscalation 
									FROM EscAnswers AS EA 
									JOIN Escalations AS ES ON EA.idEscalation=ES.ID
									WHERE lcase(EA.txtAnswer) RLIKE lcase('%".encode($_REQUEST['chrSearch'])."%')
									GROUP BY EA.idEscalation)";
*/
			}
			
			if($_REQUEST['idStatus'] != '0') {
				$q .= " AND E.idStatus='".$_REQUEST['idStatus']."'";
			}

			if(is_numeric($_REQUEST['idStoreFilter'])) {
				$q .= " AND E.idStore='".$_REQUEST['idStoreFilter']."'";
			}
			if(!isset($_REQUEST['ordCol'])) { $_REQUEST['ordCol'] = ""; }
			if(!isset($_REQUEST['sortCol']) || $_REQUEST['sortCol'] == "") { $_REQUEST['sortCol'] = "dtCreated,chrStore"; }
			
			$q .= " ORDER BY ".$_REQUEST['sortCol']." ".$_REQUEST['ordCol'];

			$results = db_query($q,"getting Escalations");

			if(count($_POST)) { include($post_file); }
			
			# The template to use (should be the last thing before the break)
			$title = "Store Escalation - ".$info['chrTitle'];
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Store Escalations - ".$info['chrTitle']; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = 'Store Escalations - '.$info['chrTitle'];
			$page_instructions = "Select a Escalation to view from the list.";
			$page_instructions2 = "<input type='button' value='Export to Excel' onclick='location.href=\"_excel.php?key=".$_REQUEST['key']."&chrSearch=".$_REQUEST['chrSearch']."&idStatus=".$_REQUEST['idStatus']."&idStoreFilter=".$_REQUEST['idStoreFilter']."\"' />";
			
			$stores = db_query("SELECT ID, CONCAT(chrStore,' (',chrStoreNum,')') AS chrRecord FROM Stores WHERE !bDeleted AND idLanguage='".$info['idLanguage']."' ORDER BY chrRecord", "Getting Stores");
			$filter = "<b>Stores</b>:".form_select($stores,array('caption'=>'- ALL -','name'=>'idStoreFilter','nocaption'=>'true','value'=>$_REQUEST['idStoreFilter'],'extra'=>'onchange="document.getElementById(\'FormFilter\').submit();"','style'=>'width:75px;'));
			
			$filter .= "&nbsp;&nbsp;<input type='hidden' name='key' value='".$_REQUEST['key']."' />
					<b>Status</b>:<select name='idStatus' id='idStatus' onchange='document.getElementById(\"FormFilter\").submit();'>
						<option value='1'".($_REQUEST['idStatus']==1?" selected='selected'":"").">Open</option>
						<option value='2'".($_REQUEST['idStatus']==2?" selected='selected'":"").">Closed</option>
						<option value='0'".($_REQUEST['idStatus']==0?" selected='selected'":"").">ALL</option>
					</select>&nbsp;&nbsp;
					<b>Search</b>:<input type='text' name='chrSearch' value='".encode($_REQUEST['chrSearch'])."' size='10' title='Search' />";
			include($BF ."includes/adminlinks.php");
			include($BF ."models/template.php");		
			
			break;
		#################################################
		##	View Page
		#################################################
		case 'view.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			auth_check('litm',12,1);
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
				global $BF;
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
			$title = 'View Escalation: '.$info['chrTitle'].' - '.$storeinfo2['chrStore'];
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = 'View Escalation: '.$info['chrTitle'].' - '.$storeinfo2['chrStore']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			$page_title = 'View Escalation: '.$info['chrTitle'].' - '.$storeinfo2['chrStore'];
			$page_instructions = '';
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