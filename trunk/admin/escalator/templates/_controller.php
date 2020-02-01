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
			auth_check('litm',10,1);
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
				$tableName = "EscalatorTemplates";
				include($BF ."includes/overlay.php");
			}
			
			if(isset($_REQUEST['idEscLang']) && is_numeric($_REQUEST['idEscLang'])) {
				$_SESSION['idEscLang'] = $_REQUEST['idEscLang'];
			}
			
			if(!isset($_SESSION['idEscLang']) || !is_numeric($_SESSION['idEscLang'])) {
				$_SESSION['idEscLang'] = 1;
			}
			
			$q = "SELECT T.ID, T.chrKEY, T.chrTitle, C.chrCategory, T.dOrder, T.bShow
				FROM EscalatorTemplates AS T
				JOIN EscalatorCats AS C ON T.idCategory=C.ID
				WHERE !T.bDeleted AND !C.bDeleted AND T.idLanguage='".$_SESSION['idEscLang']."'
				ORDER BY C.dOrder, T.dOrder, T.chrTitle";
			$results = db_query($q,"getting Categories");

			if(count($_POST)) { include($post_file); }
			
			# The template to use (should be the last thing before the break)
			$title = "Administrate Escalator Templates";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Administrate Escalator Templates"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = (access_check(10,2) ? linkto(array('address'=>'add.php','img'=>'/images/plus_add.png')):'').' Escalator Templates <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			$page_instructions = "Select a Escalator Templates to Edit from the list.";
			$filter = db_query("SELECT ID,chrLanguage AS chrRecord FROM Languages WHERE !bDeleted ORDER BY dOrder, chrLanguage", "Getting Filter Options");
			$filter = form_select($filter,array('caption'=>'- Select Language -','nocaption'=>'true','value'=>$_SESSION['idEscLang'],'extra'=>'onchange="location.href=\'index.php?idEscLang=\'+this.value"'));
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
			auth_check('litm',10,2);
			include($BF.'components/formfields.php');
			
			if(isset($_POST['chrTitle'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
				include($BF .'components/list/sortlistjs.php');
		
		$type_results = db_query("SELECT ID,chrFieldType FROM FieldTypes WHERE !bDeleted ORDER BY dOrder","getting field types");	
		
		$types = '<option value="">-Select Option Type-</option>';
		while($row = mysqli_fetch_row($type_results)) {
			$types .= '<option value="'.$row[0] .'">'.$row[1].'</option>';
		}
		$types .= "</select>";
		
		$messages[1] = "<em>A text box will appear for this question with a space limit of 255 characters.</em>";
		$messages[2] = "<em>A text area will appear for this question.  This will be big enough to hold multiple paragraphs of information.</em>";
		$messages[3] = "<em>A select box appear for this question.  Please fill in the names of the options you would like to use.</em>";
		$messages[4] = "<em>A set of checkboxes will appear for this question.  Please fill in the names of the options you would like to appear for the checkboxes.</em>";
		$messages[5] = "<em>A set of radio boxes will appear for this question.  Please fill in the names of the options you would like to appear for the radio boxes.</em>";		
		$messages[6] = "<em>Question will be used as a Section Header. This can be used to seperate Sections.</em>";
		$messages[7] = "<em>A text box will appear for this question with a space limit of 255 characters, however the data will be masked in the e-mail.</em>";
		
?>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript' src='autogen.js'></script>
	<link href="<?=$BF?>includes/dynamic.css" rel="stylesheet" type="text/css" />	

	<script type='text/javascript'>
		var page = 'add';
		var types = '<?=$types?>';
		var messages = new Array('','<?=implode("','",$messages)?>');
		
		var Cat=new Array();
		var NoCat = "<option value=''>- N/A -</option>";
		function changelanguage(idLanguage) {
<?
		$allcategories = db_query("SELECT idLanguage,ID,chrCategory FROM EscalatorCats WHERE !bDeleted ORDER BY idLanguage, dOrder, chrCategory","Getting All Categories");
		$count = 0;
		$prelang = 0;
		$cnt=1;
		while($row = mysqli_fetch_assoc($allcategories)) {
			if($prelang != $row['idLanguage']) {
				if($count > 0) {
?>
				return;
			}
			
<?				
				}
?>
			if(idLanguage == <?=$row['idLanguage']?>) {
				document.getElementById('idCategory').options.length = 0;
				document.getElementById('idCategory').options[0] = new Option("- Select Category -",'');<?			
				$prelang = $row['idLanguage'];
				$cnt=1;
			}
?>			
				document.getElementById('idCategory').options[<?=$cnt?>] = new Option("<?=$row['chrCategory']?>",'<?=$row['ID']?>');<?			
			$cnt++;
			$count++;
		}
		if($count > 0) {
?>

				return;
			}
<?
		}
?>
			document.getElementById('idCategory').options.length = 0;
			document.getElementById('idCategory').options[0] = new Option("- No Categories For Language -",'');
		}

		function remove_contact(idRecord, tbl) {
			document.getElementById('bDelete'+ idRecord).value = '1';
			document.getElementById(tbl+'ID'+ idRecord).style.display = 'none';
			repaint(tbl);
		}
	</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Add Escalator Template";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Add Escalator Template"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Add Escalator Template";
			$page_instructions = 'Enter information and click "Add"';
			$bodyParams = "document.getElementById('chrTitle').focus();changelanguage(".$_SESSION['idEscLang'].");";
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
			auth_check('litm',10,3);
			include($BF.'components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Template'); } // Check Required Field for Query
			
			$info = db_query("SELECT *
								FROM EscalatorTemplates
								WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Template Info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid Template'); } // Did we get a result?
				
			
			if(isset($_POST['chrTitle'])) { include($post_file); }
			
			# Stuff In The Header
			function sith() { 
				global $BF, $PROJECT_ADDRESS;
				include($BF .'components/list/sortlistjs.php');
		
		$type_results = db_query("SELECT ID,chrFieldType FROM FieldTypes WHERE !bDeleted ORDER BY dOrder","getting field types");	
		
		$types = '<option value="">-Select Option Type-</option>';
		while($row = mysqli_fetch_row($type_results)) {
			$types .= '<option value="'.$row[0] .'">'.$row[1].'</option>';
		}
		$types .= "</select>";
		
		$messages[1] = "<em>A text box will appear for this question with a space limit of 255 characters.</em>";
		$messages[2] = "<em>A text area will appear for this question.  This will be big enough to hold multiple paragraphs of information.</em>";
		$messages[3] = "<em>A select box appear for this question.  Please fill in the names of the options you would like to use.</em>";
		$messages[4] = "<em>A set of checkboxes will appear for this question.  Please fill in the names of the options you would like to appear for the checkboxes.</em>";
		$messages[5] = "<em>A set of radio boxes will appear for this question.  Please fill in the names of the options you would like to appear for the radio boxes.</em>";
		$messages[6] = "<em>Question will be used as a Section Header. This can be used to seperate Sections.</em>";
		$messages[7] = "<em>A text box will appear for this question with a space limit of 255 characters, however the data will be masked in the e-mail.</em>";			
		
?>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript' src='autogen.js'></script>
	<link href="<?=$BF?>includes/dynamic.css" rel="stylesheet" type="text/css" />	

	<script type='text/javascript'>
		var page = 'edit';
		var types = '<?=$types?>';
		var messages = new Array('','<?=implode("','",$messages)?>');
		
		var Cat=new Array();
		var NoCat = "<option value=''>- N/A -</option>";
		function changelanguage(idLanguage) {
<?
		$allcategories = db_query("SELECT idLanguage,ID,chrCategory FROM EscalatorCats WHERE !bDeleted ORDER BY idLanguage, dOrder, chrCategory","Getting All Categories");
		$count = 0;
		$prelang = 0;
		$cnt=1;
		while($row = mysqli_fetch_assoc($allcategories)) {
			if($prelang != $row['idLanguage']) {
				if($count > 0) {
?>
				return;
			}
			
<?				
				}
?>
			if(idLanguage == <?=$row['idLanguage']?>) {
				document.getElementById('idCategory').options.length = 0;
				document.getElementById('idCategory').options[0] = new Option("- Select Category -",'');<?			
				$prelang = $row['idLanguage'];
				$cnt=1;
			}
?>			
				document.getElementById('idCategory').options[<?=$cnt?>] = new Option("<?=$row['chrCategory']?>",'<?=$row['ID']?>');<?			
			$cnt++;
			$count++;
		}
		if($count > 0) {
?>

				return;
			}
<?
		}
?>
			document.getElementById('idCategory').options.length = 0;
			document.getElementById('idCategory').options[0] = new Option("- No Categories For Language -",'');
		}

		function remove_contact(idRecord, tbl) {
			document.getElementById('bDelete'+ idRecord).value = '1';
			document.getElementById(tbl+'ID'+ idRecord).style.display = 'none';
			repaint(tbl);
		}
	</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}
			
			# The template to use (should be the last thing before the break)
			$title = "Edit Escalator Template";
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = "Edit Escalator Template"; // Title of this page. (REQUIRED)
			$banner_instructions = ""; // Instructions or description. (NOT REQUIRED)
			$page_title = "Edit ".$info['chrTitle'];
			$page_instructions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$bodyParams = "document.getElementById('chrTitle').focus();";
			include($BF ."includes/adminlinks.php");
			include($BF ."models/template.php");		
			
			break;

		#################################################
		##	Popup Add Distro
		#################################################
		case 'adddistro.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			include($BF.'components/formfields.php');
			
			# Stuff In The Header
			function sith() { 
				global $BF,$info;
?>				  <script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script>
				  <script type='text/javascript'>

					function add_contact(idRecord,chrName,chrEmail) {
						var tbl = '<?=$_REQUEST['tbl']?>';
						
						if(window.opener.document.getElementById(tbl + 'ID' + idRecord)) {
							if(window.opener.document.getElementById(tbl + 'ID' + idRecord).style.display == 'none' && window.opener.document.getElementById('bDelete'+idRecord).value=='1') {
								window.opener.document.getElementById(tbl + 'ID' + idRecord).style.display = '';
								window.opener.document.getElementById('bDelete'+idRecord).value='0';
							}
							window.opener.repaint(tbl);
						} else {
						
							tfoot = window.opener.document.getElementById(tbl+'tfoot');
							tbody = window.opener.document.getElementById(tbl+'tbody');
							tfoot.style.display = 'none';
							
							var tr = window.opener.document.createElement('tr');
							var td1 = window.opener.document.createElement('td');
							var td2 = window.opener.document.createElement('td');
							var td4 = window.opener.document.createElement('td');
							tr.id = tbl+"ID"+ idRecord;
							
							td1.innerHTML = chrName;
							td2.innerHTML = chrEmail;
							td4.className = "options";				
							td4.innerHTML = "<span class='deleteImage'><a href='javascript:remove_contact(\""+idRecord+"\", \""+tbl+"\");' title='Delete: "+chrName+"'><img id='deleteButton"+tbl+idRecord+"' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src=\"<?=$BF?>images/button_delete_on.png\";' onmouseout='this.src=\"<?=$BF?>images/button_delete.png\";' /></a></span><input type='hidden' id='bDelete"+idRecord+"' name='bDelete"+idRecord+"' value='0' /><input type='hidden' name='idDistro[]' value='"+idRecord+"' />";
							 
							tr.appendChild(td1);
							tr.appendChild(td2);
							tr.appendChild(td4);
							
							tbody.appendChild(tr);
			
							window.opener.repaint(tbl);
						}
					}
				</script>
<?
				include($BF .'components/list/sortlistjs.php');
			}
				
			# The template to use (should be the last thing before the break)
			$title = "Distro Groups";
			$page_background = "darkred";
			$page_title = "Distro Groups";
			$page_instructions = 'Select a Contact from the list to add to the Template Distro List';
			$bodyParams = "document.getElementById('chrSearch').focus();";
			include($BF ."includes/adminlinks.php");
			include($BF ."models/popup.php");
			
			break;

		#################################################
		##	Preview Template Page
		#################################################
		case 'preview.php':
			# Adding in the lib file
			include($BF .'_lib.php');
			include_once($BF.'components/formfields.php');

			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage($_SESSION['chrLanguage']['error_invalid_escalator_template']); } // Check Required Field for Query
			
			$info = db_query("SELECT ID, chrKEY, bUploads, chrTitle, txtDirections, txtDistro, idLanguage
								FROM EscalatorTemplates
								WHERE bShow AND !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Template",1); // Get Info
			
			if($info['ID'] == "") { errorPage($_SESSION['chrLanguage']['error_invalid_escalator_template']); } // Did we get a result?

			$storeinfo = db_query("SELECT * FROM Stores WHERE !bDeleted AND bShow AND idLanguage='".$info['idLanguage']."'","Getting Store Info",1);
			
			$results = db_query("SELECT ID, bRequired, idFieldType, dOrder, chrQuestion, txtOptions, idTemplate
				FROM EscalatorQuestions
				WHERE idTemplate = ".$info['ID']." AND !bDeleted
				ORDER BY dOrder, chrQuestion
			","Getting questions");

			
			if(count($_POST)) { $_SESSION['infoMessages'][] = $_SESSION['chrLanguage']['escalation_sent']; }
			
			# Stuff In The Header
			function sith() { 
				global $BF, $results;
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
			$title = 'PREVIEW: '.$_SESSION['chrLanguage']['escalator'].' - '.$info['chrTitle'];
			$section = "admin";
			$leftlink = "";
			$page_background = "darkred";
			$headerlink_color = "white";
			// Banner Information
			$banner_title = 'PREVIEW: '.$_SESSION['chrLanguage']['escalator'].' - '.$info['chrTitle']; // Title of this page. (REQUIRED)
			$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
			$page_title = 'PREVIEW: '.$info['chrTitle'];
			$page_instructions = $info['txtDirections']." <br /><span style='color:red;'>This will not actually send the escalation</span>";
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