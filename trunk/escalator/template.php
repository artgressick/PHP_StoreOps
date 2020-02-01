<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$storeinfo,$info,$results;
?>
		<form method='post' action='' id='idForm' enctype="multipart/form-data" onsubmit="return error_check();">
		<table cellpadding='0' cellspacing='0' class='template'>
			<tr>
				<td class='left'><?=$_SESSION['chrLanguage']['email_to']?>:</td>
<?
	if($info['bManager']) {
		$to = $storeinfo['chrManager'];
	} else {
		$to = $_SESSION['chrLanguage']['email_distro_list'].', '.$storeinfo['chrStore'];	
	}
?>
				<td class='right'><input type='text' style='width:100%;' disabled='disabled' value='<?=$to?>' /></td>
			</tr>
			<tr>
				<td class='left'><?=$_SESSION['chrLanguage']['email_cc']?>:</td>
				<td class='right'><input type='text' style='width:100%;' name='chrCC' id='chrCC' /></td>
			</tr>
			<tr>
				<td class='left'><?=$_SESSION['chrLanguage']['email_subject']?>:</td>
				<td class='right'><input type='text' style='width:100%;' disabled='disabled' value='<?=$_SESSION['chrLanguage']['escalator']?>: <?=$info['chrTitle']?> - <?=$storeinfo['chrStore']?>' /></td>
			</tr>
			<tr>
				<td colspan='2' class='both'>
<?
		$i = 1;
		if($info['bManager']) {
?>
				<div style='margin-top: 10px;'>
					<div style='font-weight: bold;'><?=$i++.". ".$_SESSION['chrLanguage']['your_name'].' <span class="FormRequired">('.$_SESSION['chrLanguage']['required'].')</span>'?></div>
					<div style='padding-left:12px;padding-top:3px;'><input type='text' name='chrEmployeeName' id='chrEmployeeName' style='width: 400px;' /></div>
				</div>
				<div style='margin-top: 10px;'>
					<div style='font-weight: bold;'><?=$i++.". ".$_SESSION['chrLanguage']['your_email'].' <span class="FormRequired">('.$_SESSION['chrLanguage']['required'].')</span>'?></div>
					<div style='padding-left:12px;padding-top:3px;'><input type='text' name='chrEmployeeEmail' id='chrEmployeeEmail' style='width: 400px;' /></div>
				</div>

<?
		}
		while($row = mysqli_fetch_assoc($results)) {
			if($row['idFieldType'] == 6) {
?>
			<div class="colHeader100" style='margin-top:15px;'><?=$row['chrQuestion']?></div>
<?				
			} else {
?>
				<div style='margin-top: 10px;'>
					<div style='font-weight: bold;'><?=$i++.". ".$row['chrQuestion'].($row['bRequired'] ? ' <span class="FormRequired">('.$_SESSION['chrLanguage']['required'].')</span>' : '')?></div>
<?			
				if($row['idFieldType'] == 1 || $row['idFieldType'] == 7) {
?>
				<div style='padding-left:12px;padding-top:3px;'><input type='text' name='<?=$row['ID']?>' id='<?=$row['ID']?>' style='width: 400px;' /></div><?			
				} else if($row['idFieldType'] == 2) {
?>
				<div style='padding-left:12px;padding-top:3px;'><textarea name='<?=$row['ID']?>' id='<?=$row['ID']?>' cols='100' rows='5'></textarea></div><?			
				} else if($row['idFieldType'] == 3) {
?>
				<div style='padding-left:12px;padding-top:3px;'><select name='<?=$row['ID']?>' id='<?=$row['ID']?>'>
						<option value=''>-<?=$_SESSION['chrLanguage']['select_answer']?>-</option>
<?
					$tmp_options = explode('|||',$row['txtOptions']);
					$j = 0;
					foreach($tmp_options as $v) {
?>
					<option value='<?=$j?>'><?=$v?></option><?
						$j++;
					}
?>						
					</select></div>
<?
				} else if($row['idFieldType'] == 4) {
	
					$tmp_options = explode('|||',$row['txtOptions']);
					$j = 0;
					foreach($tmp_options as $v) {
?>
				<label for='<?=$row['ID']?>-<?=$j?>' style='padding-left: 10px;'><input type='checkbox' name='<?=$row['ID']?>[]' id='<?=$row['ID']?>-<?=$j?>' value='<?=$j?>' > <?=$v?></label><?
						$j++;
					}
	
				} else if($row['idFieldType'] == 5) {
	
					$tmp_options = explode('|||',$row['txtOptions']);
					$j = 0;
					foreach($tmp_options as $v) {
?>				<label for='<?=$row['ID']?>-<?=$j?>' style='padding-left: 10px;'><input type='radio' name='<?=$row['ID']?>[]' id='<?=$row['ID']?>-<?=$j?>' value='<?=$j?>' > <?=$v?></label><?
						$j++;
					}
				}
?>
				</div>				
<?
			}
		}
?>				
<?
		if($info['bUploads']) {
?>
					<div class="colHeader100" style='margin-top:15px;'><?=$_SESSION['chrLanguage']['attach_files']?></div>
					<table id='Files' cellspacing="0" cellpadding="0" style='margin-top: 10px;'>
						<tbody id="Filestbody">
						<tr>
							<td><?=$_SESSION['chrLanguage']['file']?> 1:&nbsp;&nbsp;</td>
							<td id='Filesfile1'><input type='file' name='chrFilesFile1' id='chrFilesFile1' /></td>
						</tr>
						</tbody>
					</table>
					<div style='padding: 5px 10px;'><input type='button' onclick='javascript:newOption(2,"Files");' value='<?=$_SESSION['chrLanguage']['add_another_file']?>' /></div>
					<input type='hidden' name='intFiles' id='intFiles' value='1' />
<?
		}
?>
				</td>
			</tr>
			<tr>
				<td colspan='2' style='padding-top:10px;'> 
					<div style='margin-top: 10px;'><input type='button' value='<?=$_SESSION['chrLanguage']['submit_information']?>' onclick='document.getElementById("btnpress").value=1; error_check();' /></div>
					<input type='hidden' name='key' id='key' value='<?=$_REQUEST['key']?>'>
					<input type='hidden' name='id' id='id' value='<?=$info['ID']?>'>
					<input type='hidden' name='btnpress' id='btnpress' value='0'>
				</td>
			</tr>
		</table>
		</form>

<?	
	}
?>