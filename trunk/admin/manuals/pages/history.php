<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$info2,$results;
?>
	<div class='index' style='padding:5px 0;'>
		<?=form_button(array('type'=>'button','name'=>'Back','value'=>'Back','extra'=>'onclick="javascript: history.go(-1);"'))?>
<?
	if(mysqli_num_rows($results) == 0) {
?>
		<div style='text-align:center; padding:10px;'>No History Found for this Page</div>
<?
	} else {
		$cnt = mysqli_num_rows($results);
		$count = 0;
		while($row = mysqli_fetch_assoc($results)) {
?>
		<div style='border:1px solid #999; margin-top:10px;'>
			<table cellpadding='0' cellspacing='0' style='padding:5px; border-bottom:1px solid #999; font-weight:bold; width:100%;'>
				<tr>
					<td>Version: <?=$info2['ID']?>.<?=$cnt?> -- On <?=date('l, F, j, Y - g:i a',strtotime($row['dtDateTime'])).', '.$row['chrFirst'].' '.$row['chrLast']?> made the following edits:</td>
					<td style='text-align:right;'>
						<input type='button' value='View <?=$info2['ID']?>.<?=$cnt?>' onclick='javascript:newwin = window.open("viewhistory.php?id=<?=$row['ID']?>&amp;idPage=<?=$info2['ID']?>&amp;ver=<?=$cnt?>&amp;type=new","new<?=$info2['ID'].$cnt?>","width=700,height=600,resizable=1,scrollbars=1"); newwin.focus();'/>
<?
					if($count++ != 0 && access_check(13,3)) {
?>
						&nbsp;<input type='button' value='Revert to <?=$info2['ID']?>.<?=$cnt?>' onclick='javascript:location.href = "reverthistory.php?id=<?=$row['ID']?>&amp;idPage=<?=$info2['ID']?>&amp;ver=<?=$cnt?>&amp;type=new"' />
<?
					}
?>
					</td>
				</tr>
			</table>  
			<div style='overflow:auto; height:200px; padding:5px; white-space:nowrap;'>
<?
				$nl = '#**!)@#';
				$diff = inline_diff(nl2br(encode($row['txtOldValue'],'tags')), nl2br(encode($row['txtNewValue'],'tags')), $nl);
?>
				<?=nl2br($diff)?>
			</div>
		</div>
<?		
		$lastid = $row['ID'];
		$cnt--;
		}
?>
		<div style='text-align:right; padding-top:5px;'>
			<input type='button' value='View <?=$info2['ID']?>.0'  onclick='javascript:newwin = window.open("viewhistory.php?id=<?=$lastid?>&amp;idPage=<?=$info2['ID']?>&amp;ver=0&amp;type=old","new<?=$info2['ID'].'0'?>","width=700,height=600,resizable=1,scrollbars=1"); newwin.focus();' />
<?
		if(access_check(13,3)) {
?>			
			&nbsp;<input type='button' value='Revert to <?=$info2['ID']?>.0' onclick='javascript:location.href = "reverthistory.php?id=<?=$lastid?>&amp;idPage=<?=$info2['ID']?>&amp;ver=<?=$cnt?>&amp;type=old"' />
<?
		}
?>
		</div>
<?
	}
?>
	</div>
<?		
	}
	
?>