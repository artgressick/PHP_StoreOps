<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$storeinfo2,$info,$results,$storelang;
?>
		<table cellpadding='0' cellspacing='0' class='template' style='border-top:1px solid #999;'>
			<tr>
				<td class='left'><?=$storelang['email_subject']?>:</td>
				<td class='right'><input type='text' style='width:100%;' disabled='disabled' value='<?=$storelang['escalator']?>: <?=$info['chrTitle']?> - <?=$storeinfo2['chrStore']?>' /></td>
			</tr>
			<tr>
				<td class='left'><?=$storelang['email_date']?>:</td>
				<td class='right'><input type='text' style='width:100%;' disabled='disabled' value='<?=$info['dtCreated']?>' /></td>
			</tr>
			<tr>
				<td class='left'><?=$storelang['email_to']?>:</td>
<?
	if($info['bManager']) {
		$to = $storeinfo2['chrManager'];
	} else {
		$to = $_SESSION['chrLanguage']['email_distro_list'].', '.$storeinfo2['chrStore'];	
	}
?>
				<td class='right'><input type='text' style='width:100%;' disabled='disabled' value='<?=$to?>' /></td>
			</tr>
			<tr>
				<td class='left'><?=$storelang['email_cc']?>:</td>
				<td class='right'><input type='text' style='width:100%;' disabled='disabled' value='<?=$info['chrCC']?>' /></td>
			</tr>
			<tr>
				<td class='left'><?=$storelang['status']?>:</td>
				<td class='right'><input type='text' style='width:100%;' disabled='disabled' value='<?=$storelang['esc_status_'.$info['idStatus']]?>' /></td>
			</tr>
			<tr>
				<td colspan='2' class='both'>
<?
			$i = 1;
			if($info['bManager']) {
?>
					<div style='padding-bottom:10px;'><?=form_text(array('caption'=>$i++.'. '.$storelang['employee_name'],'display'=>'true','value'=>$info['chrEmployeeName'],'style'=>'color:blue;'))?></div>
					<div style='padding-bottom:10px;'><?=form_text(array('caption'=>$i++.'. '.$storelang['employee_email'],'display'=>'true','value'=>$info['chrEmployeeEmail'],'style'=>'color:blue;'))?></div>
<?			
			}

		$temp = db_query("SELECT idQuestion, txtAnswer FROM EscAnswers WHERE idEscalation=".$info['ID'],"Getting Answers");
		$answers = array();
		while($row = mysqli_fetch_assoc($temp)) {
			$answers[$row['idQuestion']] = $row['txtAnswer'];
		}

		while($row = mysqli_fetch_assoc($results)) {
			if($row['idFieldType'] == 6) {
?>
			<div class="colHeader100" style='margin-top:15px;'><?=$row['chrQuestion']?></div>
<?				
			} else {
?>
			<div style='padding-bottom:10px;'><?=form_text(array('caption'=>$i++.'. '.$row['chrQuestion'],'display'=>'true','value'=>(isset($answers[$row['ID']]) && $answers[$row['ID']] != ''?($row['idFieldType']!=7?nl2br($answers[$row['ID']]):$storelang['information_masked']):$storelang['n/a']),'style'=>'color:blue;'))?></div>
<?
			}
		}
		$files = db_query("SELECT ID, chrFileName FROM EscFiles WHERE idEscalation='".$info['ID']."'","Getting Files");
		if(mysqli_num_rows($files) > 0) {
?>
				<div class="colHeader100" style='margin-top:15px;'><?=$storelang['files_attached']?></div>
				<table class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='width:100%;'>
					<tr>
						<td class="tcleft"'>
				
<?
				$half_way = ceil(mysqli_num_rows($files) / 2);
				$count=0;
				$this_row=1;
				while($row = mysqli_fetch_assoc($files)) {
					if($count >= $half_way && $this_row==1) {
?>
						</td>
						<td class="tcgutter"></td>
						<td class="tcright"'>
<?
						$this_row=2;
					}
?>
							<div style='padding:5px;'><a href='<?=$BF?>escalator/files/<?=$row['chrFileName']?>' target='_blank' /><?=$row['chrFileName']?></a></div>
<?
					$count++;
				}
?>
						</td>
					</tr>
				</table>
<?
		}
?>
				</td>
			</tr>
<? 
		$comments = db_query("SELECT ID, txtComment, DATE_FORMAT(dtAdded,'".$storelang['date_time_format']."') AS dtDate FROM EscComments WHERE idEscalation='".$info['ID']."' ORDER BY dtAdded","Getting Comments");
		$count=0;
		if(mysqli_num_rows($comments) > 0) {
?>
			<tr>
				<td colspan='2' class='both'>
				<div class="colHeader100" style='margin-top:15px;'><?=$storelang['comments']?></div>
<?
				while($row = mysqli_fetch_assoc($comments)) {
?>
					<div class="colHeader100" style='margin-top:15px; font-size:10px;'><?=$row['dtDate']?></div>
					<div style='padding:5px;'><?=nl2br($row['txtComment'])?></div>
<?
					$count++;
				}
?>
				</td>
			</tr>
<?
		}
		if($info['idStatus'] != 2) {
?>
			<tr>
				<td colspan='2' class='both'>
					<form method='post' action='' id='idForm' onsubmit="return error_check();" style='padding:0;margin:0;'>
					<div class="colHeader100" style='margin-top:15px;'><?=$storelang['add_comments']?> <span class="FormRequired">(<?=$storelang['required']?>)</span></div>
					<textarea name='txtComments' id='txtComments' cols='100' rows='7' style='width:100%;'></textarea>
					<div style='font-weight: bold; padding-top:10px;'><?=$storelang['update_status']?></div>
					<select name='idStatus' id='idStatus'>
						<option value='1'<?=($info['idStatus']==1?' selected="selected"':'')?>><?=$storelang['esc_status_1']?></option>
						<option value='2'<?=($info['idStatus']==2?' selected="selected"':'')?>><?=$storelang['esc_status_2']?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2' style='padding-top:10px;'> 
					<div style='margin-top: 10px;'><input type='button' value='<?=$storelang['submit_information']?>' onclick='document.getElementById("btnpress").value=1; error_check();' /></div>
					<input type='hidden' name='key' id='key' value='<?=$_REQUEST['key']?>'>
					<input type='hidden' name='id' id='id' value='<?=$info['ID']?>'>
					<input type='hidden' name='btnpress' id='btnpress' value='0'>
					</form>
				</td>
			</tr>
<?
			}
?>
		</table>
<?	
	}
?>