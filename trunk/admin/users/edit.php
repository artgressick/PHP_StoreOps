<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<table class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='width:100%;'>
				<tr>
					<td class="tcleft">
				
						<div class="colHeader">Personal Information</div>

						<?=form_text(array('caption'=>'First Name','required'=>'true','name'=>'chrFirst','size'=>'30','maxlength'=>'100','value'=>$info['chrFirst']))?>
						<?=form_text(array('caption'=>'Last Name','required'=>'true','name'=>'chrLast','size'=>'30','maxlength'=>'100','value'=>$info['chrLast']))?>
						<?=form_text(array('caption'=>'Email Address','required'=>'true','name'=>'chrEmail','size'=>'30','maxlength'=>'150','value'=>$info['chrEmail']))?>

						<div class="colHeader">Password</div>
						
						<?=form_text(array('caption'=>'Password','type'=>'password','required'=>'Only Required if Changing','name'=>'chrPassword','size'=>'30','maxlength'=>'100','value'=>'','title'=>'Enter New Password'))?>
						<?=form_text(array('caption'=>'Confirm Password','type'=>'password','required'=>'Only Required if Changing','name'=>'chrPassword2','size'=>'30','maxlength'=>'100','value'=>''))?>

						<div class="colHeader">Account Options</div>

						<?=form_checkbox(array('type'=>'radio','caption'=>'Account Locked','title'=>'No','name'=>'bLocked','id'=>'bLocked0','value'=>'0','required'=>'This is marked Yes with 5 consecutive bad login attempts','checked'=>(!$info['bLocked']?'true':'false')))?>&nbsp;&nbsp;&nbsp;
						<?=form_checkbox(array('type'=>'radio','title'=>'Yes','name'=>'bLocked','id'=>'bLocked1','value'=>'1','checked'=>($info['bLocked']?'true':'false')))?>
	
						<div class="colHeader">Account Statistics</div>
						
						<?=form_text(array('caption'=>'Last Login','value'=>($info['dtLastLogin'] != "" && $info['dtLastLogin'] != "0000-00-00 00:00:00" ? date('l, F j, Y - g:i a', strtotime($info['dtLastLogin'])) : "Never Logged In"),'display'=>'true'))?>

	
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">

						<div class="colHeader">Security Access</div>

						<div class='FormName'>Global Admin Access <span class='FormRequired'>(Required)</span></div>
						<?=form_checkbox(array('type'=>'radio','name'=>'bGlobalAdmin','title'=>'No','value'=>'0','extra'=>'onchange="javascript:document.getElementById(\'security_options\').style.display=\'\'"','checked'=>(!$info['bGlobalAdmin']?'true':'false')))?>
						<?=form_checkbox(array('type'=>'radio','name'=>'bGlobalAdmin','title'=>'Yes','value'=>'1','extra'=>'onchange="javascript:document.getElementById(\'security_options\').style.display=\'none\'"','checked'=>($info['bGlobalAdmin']?'true':'false')))?>

						<div id='security_options' style='<?=($info['bGlobalAdmin']?'display:none;':'')?>'>
						<div class="colHeader">Permissions</div>					
<?
	$access[1] = 'View';
	$access[2] = 'Add';
	$access[3] = 'Edit';
	$access[4] = 'Delete';
	
	$q = "SELECT ID, chrOptions, chrDescription
			FROM Security
			WHERE !bDeleted
			ORDER BY dOrder
			";

	$tmp = explode('|', $info['txtSecurity']);
	$sec = array();
	foreach($tmp AS $k => $values) {
		$temp2 = explode(':',$values);
		$sec[$temp2[0]] = explode(',',$temp2[1]);	
	}
	$securityoptions = db_query($q, "Getting Security Options");
	while($row = mysqli_fetch_assoc($securityoptions)) {
?>
							<table cellpadding='5' cellspacing='0' style='width:100%;'>
								<tr>
									<td style='width:125px;' class='FormName'>
										<?=$row['chrDescription']?>
									</td>
<?
							$options = explode(',',$row['chrOptions']);
							foreach($options AS $k => $v) {
?>
									<td style='width:40px; white-space:nowrap;'>
										<?=form_checkbox(array('name'=>'secure'.$row['ID'].'[]','title'=>$access[$v],'value'=>$v,'checked'=>(in_array($v,$sec[$row['ID']]))))?>
									</td>
<?
							}
?>
									<td>&nbsp;</td>
								</tr>
							</table>
<?
	}	
?>
						</div>
					</td>
				</tr>
			</table>
			<div class='FormButtons'>
				<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
			</div>
		</form>
	</div>

<?
	}
?>