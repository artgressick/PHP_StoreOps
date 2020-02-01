<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
?>
	<table id='Escalations' class='List' style='width: 100%; border-top: 0;' cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th>Template</th>
			<th style='width:50px;'>Open</th>
			<th style='width:50px;'>Closed</th>
		</tr>

		</thead>
		<tbody>
<?
	if(mysqli_num_rows($results) > 0) {
		$oldcat='';
		$count=0;
		while($row = mysqli_fetch_assoc($results)) {
			if($oldcat != $row['chrCategory']) {
				$oldcat = $row['chrCategory'];
?>
				<tr>
					<td colspan='3' class='category'><?=$row['chrCategory']?></td>
				</tr>
<?
			}
?>
			<tr id='Escalationstr<?=$row['ID']?>' class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>' 
			onmouseover='RowHighlight("Escalationstr<?=$row['ID']?>");' onmouseout='UnRowHighlight("Escalationstr<?=$row['ID']?>");' onclick='location.href="list.php?key=<?=$row['chrKEY']?>";'>
				<td style='padding-left:20px;'><?=$row['chrTitle']?></td>
				<td><?=number_format($row['intOpen'])?></td>
				<td><?=number_format($row['intClosed'])?></td>
			</tr>
<?
		}
	} else {
?>
			<tr>
				<td colspan='3' style='text-align:center;height:20px;vertical-align:middle;cursor:auto;'>No records found in the database.</td>
			</tr> 	
<?		
	}
?>
		</tbody>
	</table>	

<?	
	}
?>