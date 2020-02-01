<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$hours,$defaulttimes;
?>
		<div class='index'>
			<form method='post' action='' id='idForm' onsubmit="return error_check();">
			<table cellpadding='0' cellspacing='0' style='width:100%;' />
				<tr>
<?
			$dow = 0;
			while($dow < 7) {
				if(isset($hours[$dow])) {
					if($hours[$dow]['bClosed']==1) {
						$tBegin_val = '';
						$tBegin_dis = ' disabled="disabled"';
						$tClose_val = '';
						$tClose_dis = ' disabled="disabled"';
						$bClosed = ' checked="checked"';
					} else {
						$tBegin_val = $hours[$dow]['tOpening'];
						$tBegin_dis = '';
						$tClose_val = $hours[$dow]['tClosing'];
						$tClose_dis = '';
						$bClosed = '';
					}
				} else {
					$tBegin_val = $defaulttimes['tOpening'];
					$tBegin_dis = '';
					$tClose_val = $defaulttimes['tClosing'];
					$tClose_dis = '';
					$bClosed = '';
				}
?>
					<td style='vertical-align:top; border:1px solid #999; border-top:none; padding:5px 2px;'>
						<div style='text-align:center; font-weight:bold;'><?=$_SESSION['chrLanguage'][day_of_week($dow)]?></div>
						<table cellpadding='0' cellspacing='0' style='width:100%; padding-top:5px;' />
							<tr>
								<td><?=$_SESSION['chrLanguage']['open']?>:</td>
								<td><input type='text' name='tBegin<?=$dow?>' id='tBegin<?=$dow?>' value='<?=$tBegin_val?>'<?=$tBegin_dis?> style='width:60px;' /><input type='hidden' id='d_tBegin<?=$dow?>' value='<?=($tBegin_val!=''?$tBegin_val:$defaulttimes['tOpening'])?>' /></td>
							</tr>
							<tr>
								<td><?=$_SESSION['chrLanguage']['close']?>:</td>
								<td><input type='text' name='tClose<?=$dow?>' id='tClose<?=$dow?>' value='<?=$tClose_val?>'<?=$tClose_dis?> style='width:60px;' /><input type='hidden' id='d_tClose<?=$dow?>' value='<?=($tClose_val!=''?$tClose_val:$defaulttimes['tClosing'])?>' /></td>
							</tr>
						</table>
						<div style='padding-top:10px;text-align:center;'><input type='checkbox' name='bClosed<?=$dow?>' value='1' id='bClosed<?=$dow?>' <?=$bClosed?> onchange='closed(<?=$dow?>)'/> <?=$_SESSION['chrLanguage']['closed']?></div>
					</td>
<?				
				$dow++;
			}
?>				
				</tr>
			</table>
			<div style='margin-top: 10px;'><input type='button' value='<?=$_SESSION['chrLanguage']['submit_information']?>' onclick='document.getElementById("btnpress").value=1; error_check();' /></div>
			<input type='hidden' name='btnpress' id='btnpress' value='0'>
			</form>			
		</div>
<?	} ?>