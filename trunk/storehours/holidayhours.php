<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$info,$hours,$defaulttimes,$holidayhours;
?>
		<div class='index'>
			<form method='post' action='' id='idForm' onsubmit="return error_check();">
			<table cellpadding='2' cellspacing='0' style='width:100%;' />
				<tr>
					<td style='text-align:center; font-size:12px; font-weight:bold;'>Date</td>
					<td style='text-align:center; font-size:12px; font-weight:bold;'>Normal Hours</td>
					<td style='text-align:center; font-size:12px; font-weight:bold;'>New Hours</td>
				</tr>
			
<?
	$totalDays = (strtotime($info['dEnd']) - strtotime($info['dBegin']))/60/60/24;
	
	$i=0;
	$dCurrent = $info['dBegin'];
	while($i <= $totalDays) {
		$dow = date('w',strtotime($dCurrent));
		if(isset($holidayhours[$dCurrent])) {
			$tBegin_val = ($holidayhours[$dCurrent]['bClosed']==0?date($_SESSION['chrLanguage']['php_hours'],strtotime($holidayhours[$dCurrent]['tOpening'])):'');
			$tClose_val = ($holidayhours[$dCurrent]['bClosed']==0?date($_SESSION['chrLanguage']['php_hours'],strtotime($holidayhours[$dCurrent]['tClosing'])):'');
			$tBegin_dis = ($holidayhours[$dCurrent]['bClosed']==1?'disabled="disabled"':'');
			$tClose_dis = ($holidayhours[$dCurrent]['bClosed']==1?'disabled="disabled"':'');
			$bClosed = ($holidayhours[$dCurrent]['bClosed']==1?'checked="checked"':'');
		} else if($hours[$dow]['bClosed'] != 1) {
			$tBegin_val = $hours[$dow]['tOpening'];
			$tClose_val = $hours[$dow]['tClosing'];
			$tBegin_dis = '';
			$tClose_dis = '';
			$bClosed = '';
		} else {
			$tBegin_val	= '';
			$tClose_val = '';
			$tBegin_dis = 'disabled="disabled"';
			$tClose_dis = 'disabled="disabled"';
			$bClosed = 'checked="checked"';
		}
		
?>			
				<tr>
					<td style='font-size:12px; vertical-align:middle; font-weight:bold;' id='tag<?=$dCurrent?>'><?=$_SESSION['chrLanguage'][day_of_week($dow)]?>, <?=date($_SESSION['chrLanguage']['php_date'],strtotime($dCurrent))?></td>
					<td style='text-align:center; vertical-align:middle;'>
<?
						if($hours[$dow]['bClosed']==1) {
?>
							<?=$_SESSION['chrLanguage']['closed']?>
<?
						} else {
?>
						<?=$_SESSION['chrLanguage']['open']?>: <?=$hours[$dow]['tOpening']?> - <?=$_SESSION['chrLanguage']['close']?>: <?=$hours[$dow]['tClosing']?>
<?
						}	
?>
					</td>
					<td style='vertical-align:middle;'>
						<table cellpadding='0' cellspacing='0' style='width:100%; vertical-align:middle;'  />
							<tr>
								<td><?=$_SESSION['chrLanguage']['open']?>: <input type='text' name='tBegin<?=$dCurrent?>' id='tBegin<?=$dCurrent?>' value='<?=$tBegin_val?>'<?=$tBegin_dis?> style='width:60px;' /><input type='hidden' id='d_tBegin<?=$dCurrent?>' value='<?=($tBegin_val!=''?$tBegin_val:$defaulttimes['tOpening'])?>' /></td>
								<td><?=$_SESSION['chrLanguage']['close']?>: <input type='text' name='tClose<?=$dCurrent?>' id='tClose<?=$dCurrent?>' value='<?=$tClose_val?>'<?=$tClose_dis?> style='width:60px;' /><input type='hidden' id='d_tClose<?=$dCurrent?>' value='<?=($tClose_val!=''?$tClose_val:$defaulttimes['tClosing'])?>' /></td>
								<td><input type='checkbox' name='bClosed<?=$dCurrent?>' value='1' id='bClosed<?=$dCurrent?>' <?=$bClosed?> onchange='closed("<?=$dCurrent?>")'/> <?=$_SESSION['chrLanguage']['closed']?></td>
							</tr>
						</table>
					</td>
				</tr>
<?		
		$dCurrent = date('Y-m-d',strtotime($info['dBegin']." + ".($i++ + 1)." days"));
	}
?>
			</table>
			<div style='margin-top: 10px;'><input type='button' value='<?=$_SESSION['chrLanguage']['submit_information']?>' onclick='document.getElementById("btnpress").value=1; error_check();' /></div>
			<input type='hidden' name='btnpress' id='btnpress' value='0' />
			<input type='hidden' name='key' value='<?=$info['chrKEY']?>' />
			</form>			
		</div>
<?	} ?>