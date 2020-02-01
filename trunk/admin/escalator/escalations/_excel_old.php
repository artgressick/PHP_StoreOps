<?
	$BF = '../../../';
//	$NON_HTML_PAGE=1;
	require($BF.'_lib.php');
	
	$time = date('m-d-Y');
		
	$info = db_query("SELECT ID, chrKEY, chrTitle, idLanguage
						FROM EscalatorTemplates
						WHERE bShow AND !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Template",1); // Get Info
	
	$filename = str_replace(" ", "_", decode($info['chrTitle'].'_('.$time.')'));
	
	$tmp_questions = db_query("SELECT ID, idFieldType, chrQuestion FROM EscalatorQuestions WHERE !bDeleted AND idFieldType != 6 AND idTemplate='".$info['ID']."' ORDER BY dOrder, chrQuestion","Getting Questions");

	while($row = mysqli_fetch_assoc($tmp_questions)) {
		$questions[$row['ID']] = $row['chrQuestion'];
	}

	//header("Content-type: application/vnd.ms-excel; charset=UTF-16LE");
	header("Content-Type: text/html; charset=UTF-8");  
	//header( "Content-type: application/vnd.ms-excel; charset=UTF-16LE" );
//	header("Content-Disposition: attachment; filename=".$filename.".txt");
//	header("Pragma: no-cache");
//	header("Expires: 0");

?>
<style>
	.Heading { font-weight:bold; font-size:11px; border-right: 1px solid #000000; margin:2px; vertical-align:middle; height:20px; }

	.FirstRow1 { font-size:11px; border-top: 1px solid #000000; border-right: 1px solid #000000; margin:2px; vertical-align:middle; background-color:#DDDDDD; }
	.FirstRow2 { font-size:11px; border-top: 1px solid #000000; border-right: 1px solid #000000; margin:2px; vertical-align:middle; background-color:#FFFFFF; }

</style>
<table border="0">
	<tr>
		<td class="Heading">Store Name (Number)</td>
		<td class="Heading">Date Submitted</td>
		<td class="Heading">Status</td>
<?
	foreach($questions AS $id => $chrQuestion) {
?>
		<td class="Heading"><?=decode($chrQuestion,'export')?></td>
<?
	}
?>
	</tr>
	<tr>
<?
	$q = "SELECT E.ID, CONCAT(S.chrStore,' (',S.chrStoreNum,')') AS chrStore, IF(idStatus=1,'Open','Closed') AS chrStatus, DATE_FORMAT(E.dtCreated,'%c/%e/%Y - %l:%i %p (PST)') AS dtCreated,
			(SELECT GROUP_CONCAT(CONCAT(A.idQuestion,':::',A.txtAnswer) ORDER BY A.idQuestion SEPARATOR '|||') FROM EscAnswers AS A WHERE A.idEscalation=E.ID GROUP BY A.idEscalation) AS txtAnswers
			FROM Escalations AS E
			JOIN Stores AS S ON E.idStore=S.ID
			WHERE !E.bDeleted AND E.idTemplate='".$info['ID']."'";
	
	if($_REQUEST['chrSearch'] != '') {
		$q .= " AND E.ID IN(SELECT EA.idEscalation 
							FROM EscAnswers AS EA 
							JOIN Escalations AS ES ON EA.idEscalation=ES.ID
							WHERE lcase(EA.txtAnswer) LIKE lcase('%".encode($_REQUEST['chrSearch'])."%')
							GROUP BY EA.idEscalation)";
	}
	
/*	if($_REQUEST['idStatus'] != '0') {
		$q .= " AND E.idStatus='".$_REQUEST['idStatus']."'";
	}
*/
	if(is_numeric($_REQUEST['idStoreFilter'])) {
		$q .= " AND E.idStore='".$_REQUEST['idStoreFilter']."'";
	}
	
	$q .= " ORDER BY dtCreated, chrStore";
	
	$answers = db_query($q,"Getting Answers");
	$count=0;
	while($row = mysqli_fetch_assoc($answers)) {
		$count++;
		$css = ($count%2 ? "1" : "2" );
?>
		<td class="FirstRow<?=$css?>"><?=decode($row['chrStore'],'export')?></td>
		<td class="FirstRow<?=$css?>"><?=decode($row['dtCreated'],'export')?></td>
		<td class="FirstRow<?=$css?>"><?=decode($row['chrStatus'],'export')?></td>
<?	
		$tmp_ans = explode('|||',$row['txtAnswers']);
		foreach($tmp_ans AS $k => $v) {
			$tmp = explode(':::',$v);
			$this_answers[$tmp[0]] = $tmp[1]; 
		}
		
		foreach($questions AS $id => $chrQuestion) {
?>
		<td class="FirstRow<?=$css?>"><?=decode($this_answers[$id],'export')?></td>
<?
		}
	}
?>
	</tr>
</table>