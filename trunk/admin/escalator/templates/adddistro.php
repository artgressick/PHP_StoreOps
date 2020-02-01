<?php 
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;

		$q = "SELECT ID, chrName, chrEmail
			  FROM DistroGroups 
			  WHERE !bDeleted";

		if(isset($_REQUEST['chrSearch']) && $_REQUEST['chrSearch'] != '') {  // if there is a search term 
			$q .= " AND (chrName LIKE '%" . encode($_REQUEST['chrSearch']) . "%' || chrEmail LIKE '%" . encode($_REQUEST['chrSearch']) . "%') ";
		}
		
		$q .= " ORDER BY chrName,chrEmail";
		
		$results = db_query($q,'Getting Distro Groups');
?>
		<form action="" method="get" id="idForm" style='margin:0; padding:0;'>
		<div style='padding:5px 0px 5px 5px; background:#CCC;'>
			<?=form_text(array('caption'=>'Search','nocaption'=>'true','name'=>'chrSearch','size'=>'30','maxlength'=>'230','value'=>(isset($_REQUEST['chrSearch'])?$_REQUEST['chrSearch']:'')))?>
			<?=form_button(array('type'=>'submit','value'=>'Search'))?>
			<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'tbl','value'=>$_REQUEST['tbl']))?>
		</div>
		</form>
		<table id='equipment' class='List sortable' style='width:100%;' cellpadding="0" cellspacing="0">
			<thead>
				<tr>		
					<th class='ListHeadSortOn sorttable_sorted sorttable_alpha'>Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
					<th class='sorttable_alpha'>E-mail&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
				</tr>
			</thead>
			<tbody>
<?	$count = 0;
	if(mysqli_num_rows($results)) { 
		while($row = mysqli_fetch_assoc($results)) {
?>
			<tr id='equipmenttr<?=$row['ID']?>' class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>' 
			onmouseover='RowHighlight("equipmenttr<?=$row['ID']?>");' onmouseout='UnRowHighlight("equipmenttr<?=$row['ID']?>");' onclick="add_contact('<?=$row['ID']?>','<?=encode($row['chrName'],'amp')?>','<?=encode($row['chrEmail'],'amp')?>');">
				<td><?=$row['chrName']?></td>
				<td><?=$row['chrEmail']?></td>
			</tr>
<?			
		}
	} else {
?>
			<tr>
				<td colspan='3' style='text-align:center;height:20px;vertical-align:middle;'>No records found in the database.</td>
			</tr> 	
<?
	}
?>
		</tbody>
	</table>
	<div style='text-align:center;padding-top:10px;'><input type='button' value='Close Window' onclick='javascript:window.close();' /></div>
<?		
	}
?>