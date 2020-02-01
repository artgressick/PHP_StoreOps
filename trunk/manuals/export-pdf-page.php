<?php
	$BF = '../';
	$NON_HTML_PAGE=1;
	include($BF .'_lib.php');

	# Check for KEY, if not Error, Get $info, Error if no results
	if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage($_SESSION['chrLanguage']['invalid_page']); } // Check Required Field for Query
	
	$page = db_query("SELECT *, CONCAT(ID,'.',(SELECT COUNT(Audit.ID) FROM Audit WHERE Audit.idType=2 AND Audit.idRecord=Pages.ID AND chrTableName='Pages' AND chrColumnName='txtPage')) as chrVersion
						FROM Pages
						WHERE !bDeleted AND bShow AND chrKEY='".$_REQUEST['key']."'","getting Page Info",1); // Get Info
	
	if($page['ID'] == "") { errorPage($_SESSION['chrLanguage']['invalid_page']); } // Did we get a result?

	$info = db_query("SELECT *
						FROM Manuals
						WHERE !bDeleted AND bShow AND ID='".$page['idManual']."'","getting Manual Info",1); // Get Info
	
	if($info['ID'] == "") { errorPage($_SESSION['chrLanguage']['invalid_manual']); } // Did we get a result?

	require_once($BF.'components/html2fpdf/html2fpdf.php'); 
	header('Content-type: application/pdf');
	header('Content-Disposition: attachment; filename="'.str_replace(' ','_',decode($page['chrTitle'])).'.pdf"');
	
	// activate Output-Buffer:
	ob_start();
?>
<html> 
	<head> 
		<title><?=decode($page['chrTitle'])?></title> 
 	</head> 
	<body>
	<div style='font-weight:bold; font-size:15px;'><?=decode($page['chrTitle']).' - '.$_SESSION['chrLanguage']['article_num'].': '.date('Y',strtotime($page['dtCreated'])).'-'.$page['chrVersion']?></div>
	<hr /> 
	<?=decode($page['txtPage'])?>
	</body>
</html>
<?
	$pdf = new HTML2FPDF(); 
	$title = decode($page['chrTitle']).' - '.$_SESSION['chrLanguage']['article_num'].': '.date('Y',strtotime($page['dtCreated'])).'-'.$page['chrVersion'];
	
	// Output-Buffer in variable: 
	$html=ob_get_contents(); 
	// delete Output-Buffer 
	ob_end_clean(); 
	$pdf->AddPage(); 
	$pdf->WriteHTML($html);
	$pdf->Output(str_replace(' ','_',decode($page['chrTitle'])).'.pdf','I');
?>