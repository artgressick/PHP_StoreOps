<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$info;
		
		$page_results = db_query("SELECT ID, chrKEY, chrTitle, idParent, dOrder, (SELECT GROUP_CONCAT(P.ID ORDER BY dOrder SEPARATOR ',') FROM Pages AS P WHERE P.idParent=Pages.ID AND P.bShow AND !P.bDeleted GROUP BY idParent) as chrChildren FROM Pages WHERE !bDeleted AND bShow AND idManual='".$info['ID']."' ORDER BY idParent,dOrder, chrTitle","Getting Pages");
		$pages = array();
		$temp_pages = array();
		$nav = array();
		$temp = array();
		while($row = mysqli_fetch_assoc($page_results)) {
			if(isset($temp_pages[$row['idParent']]) && $row['idParent'] != 0) { 
				$dOrder = $temp_pages[$row['idParent']]['order'].'.'.$row['dOrder'];
			} else { $dOrder = $row['dOrder']; }
			$temp_pages[$row['ID']] = array('order'=>$dOrder,'ID'=>$row['ID'],'chrKEY'=>$row['chrKEY'],'chrTitle'=>$row['chrTitle'],'idParent'=>$row['idParent'],'chrChildren'=>$row['chrChildren']);
		}
		foreach($temp_pages AS $k => $data) {
			$temp[$data['order']] = $data;
		}
		unset($temp_pages); ksort($temp);
		foreach($temp AS $k => $data) {
			$pages[$data['ID']]	= $data; 
		}	
		
		$cnt=0;
		foreach($pages AS $id => $row) {
			$leftpadding = (substr_count($row['order'],'.')*13);
			$cnt++;
			$nav[$row['ID']] = "<div style='padding:5px;".(substr_count($row['order'],'.') == 0?'font-weight:bold;':'')."'><input type='checkbox' name='listids[]' id='article".$row['ID']."' value='".$row['ID']."' onClick=\"multiselect(event,'article".$row['ID']."');\" /><label for='article".$row['ID']."'><span style='padding-left:".($leftpadding==0 ? '10px' : ($leftpadding+10)."px").";'>".$row['chrTitle']."</span></label></div>
			";
		}			
?>
	<form method='post' action='_export_book.php' id='idForm'>
<?
	if($_SESSION['bGlobalAdmin']==1) {
?>
		<div style='padding:5px 0;'><input type='button' name="chkbutton" id="chkbutton" value="<?=$_SESSION['chrLanguage']['check_all']?>" onClick="togglecheckboxes()"></div>
<?
	}
	foreach($pages AS $id => $row) {
?>
		<?=$nav[$row['ID']]?>
<?
	}
	unset($pages[$id]);
?>
	 	<div style='margin-top: 10px;'><input type='submit' value='<?=$_SESSION['chrLanguage']['submit']?>' /></div>
	 	<input type='hidden' name='chrKEY' value='<?=$info['chrKEY']?>' />
	 </form>
<?	} ?>