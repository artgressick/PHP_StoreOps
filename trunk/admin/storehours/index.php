<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
?>
		<table id='NSOCorpTaskAssoc' class='List sortable' style='width: 100%; border-top:none;' cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th class='ListHeadSortOn sorttable_sorted'>Store Name/Number&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
					<th class='options sorttable_nosort'>Sunday</th>
					<th class='options sorttable_nosort'>Monday</th>
					<th class='options sorttable_nosort'>Tuesday</th>
					<th class='options sorttable_nosort'>Wednesday</th>
					<th class='options sorttable_nosort'>Thursday</th>
					<th class='options sorttable_nosort'>Friday</th>
					<th class='options sorttable_nosort'>Saturday</th>
				</tr>
			</thead>
			<tbody>
<?
				$count = 0;
				while($row = mysqli_fetch_assoc($results)) { 
					$link = (access_check(15,3)?'edit.php?key='.$row['chrKEY']:'');
?>
				<tr id='Hourstr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
					onmouseover='RowHighlight("Hourstr<?=$row['ID']?>");' onmouseout='UnRowHighlight("Hourstr<?=$row['ID']?>");'>
					<td style='white-space:nowrap;<?=($link==''?' cursor:auto;':'')?>' <? if($link!='') { ?> onclick="location.href='<?=$link?>';"<? } ?>><?=$row['chrStore']?></td>
<?
				if($row['txtHours'] != '') {
					$tmphours = explode('|||',$row['txtHours']);
						foreach($tmphours AS $k => $value) {
							$hours = explode(':::',$value);
							if($hours[1]==0) {
?>
					<td style='white-space:nowrap;<?=($link==''?' cursor:auto;':'')?>' <? if($link!='') { ?> onclick="location.href='<?=$link?>';"<? } ?>>O: <?=date('g:i a',strtotime($hours[2]))?><br />C: <?=date('g:i a',strtotime($hours[3]))?></td>
<?								
							} else {
?>
					<td style='white-space:nowrap;<?=($link==''?' cursor:auto;':'')?>' <? if($link!='') { ?> onclick="location.href='<?=$link?>';"<? } ?>>Closed</td>
<?								
							}
						
						}
				} else {
?>
					<td colspan='7' style='white-space:nowrap;<?=($link==''?' cursor:auto;':'')?>' <? if($link!='') { ?> onclick="location.href='<?=$link?>';"<? } ?>>No Hours Entered</td>
<?					
				}
?>
				</tr>
<?
				}
?>	
			</tbody>
		</table>
		
<?		
	}
?>