<?php
	$BF = '../';
	$NON_HTML_PAGE=1;
	include($BF .'_lib.php');

	# Check for KEY, if not Error, Get $info, Error if no results
	if(!isset($_POST['chrKEY']) || $_POST['chrKEY'] == "") { errorPage($_SESSION['chrLanguage']['invalid_manual']); } // Check Required Field for Query
	
	$info = db_query("SELECT *
						FROM Manuals
						WHERE !bDeleted AND bShow AND chrKEY='".$_POST['chrKEY']."'","getting Manual Info",1); // Get Info
	
	if($info['ID'] == "") { errorPage($_SESSION['chrLanguage']['invalid_manual']); } // Did we get a result?

	$page_results = db_query("SELECT ID, chrKEY, chrTitle, idParent, dOrder, txtPage, CONCAT(ID,'.',(SELECT COUNT(Audit.ID) FROM Audit WHERE Audit.idType=2 AND Audit.idRecord=Pages.ID AND chrTableName='Pages' AND chrColumnName='txtPage')) as chrVersion FROM Pages WHERE !bDeleted AND bShow AND idManual='".$info['ID']."' ORDER BY idParent,dOrder, chrTitle","Getting Pages");
	$pages = array();
	$temp_pages = array();
	$nav = array();
	$temp = array();
	while($row = mysqli_fetch_assoc($page_results)) {
		if(isset($temp_pages[$row['idParent']]) && $row['idParent'] != 0) { 
			$dOrder = $temp_pages[$row['idParent']]['order'].'.'.$row['dOrder'];
		} else { $dOrder = $row['dOrder']; }
		$temp_pages[$row['ID']] = array('order'=>$dOrder,'ID'=>$row['ID'],'chrKEY'=>$row['chrKEY'],'chrTitle'=>$row['chrTitle'],'idParent'=>$row['idParent'],'txtPage'=>$row['txtPage'],'chrVersion'=>$row['chrVersion']);
	}
	foreach($temp_pages AS $k => $data) {
		$temp[$data['order']] = $data;
	}
	unset($temp_pages); ksort($temp);
	foreach($temp AS $k => $data) {
		$pages[$data['ID']]	= $data; 
	}	
	
	require_once($BF.'components/html2fpdf/html2fpdf.php'); 
	header('Content-type: application/pdf');
	header('Content-Disposition: attachment; filename="'.str_replace(' ','_',decode($info['chrManual'])).'.pdf"');
	
	// activate Output-Buffer:
	ob_start();
	$cnt=0;
?>
<html> 
	<head> 
		<title><?=decode($info['chrManual'])?></title> 
 	</head> 
	<body>
<?
	foreach($_POST['listids'] AS $k => $v) {
		if($cnt++ > 0) {
?>
	<hr />
<?
		}
?>	
	<div style='font-weight:bold; font-size:15px;'><?=decode($pages[$v]['chrTitle']).' - '.$_SESSION['chrLanguage']['article_num'].': '.date('Y',strtotime($pages[$v]['dtCreated'])).'-'.$pages[$v]['chrVersion']?></div>
	<hr /> 
	<?=decode($pages[$v]['txtPage'])?>
<?
	}
?>
	</body>
</html>
<?
	$pdf = new HTML2FPDF(); 
	// Output-Buffer in variable: 
	$html=ob_get_contents(); 
	// delete Output-Buffer 
	ob_end_clean(); 
	$pdf->AddPage(); 
	$pdf->WriteHTML($html);
	$pdf->Output(str_replace(' ','_',decode($info['chrManual'])).'.pdf','I');
?>