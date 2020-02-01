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

						<?=form_text(array('caption'=>'First Name','required'=>'true','name'=>'chrFirst','size'=>'30','maxlength'=>'100'))?>
						<?=form_text(array('caption'=>'Last Name','required'=>'true','name'=>'chrLast','size'=>'30','maxlength'=>'100'))?>
						<?=form_text(array('caption'=>'Email Address','required'=>'true','name'=>'chrEmail','size'=>'30','maxlength'=>'150'))?>

						<div class="colHeader">Password</div>
						
						<?=form_text(array('caption'=>'Password','type'=>'password','required'=>'true','name'=>'chrPassword','size'=>'30','maxlength'=>'100','value'=>'','title'=>'Enter New Password'))?>
						<?=form_text(array('caption'=>'Confirm Password','type'=>'password','required'=>'true','name'=>'chrPassword2','size'=>'30','maxlength'=>'100','value'=>''))?>

					</td>
					<td class="tcgutter"></td>
					<td class="tcright">

						<div class="colHeader">Security Access</div>

						<div class='FormName'>Global Admin Access <span class='FormRequired'>(Required)</span></div>
						<?=form_checkbox(array('type'=>'radio','name'=>'bGlobalAdmin','title'=>'No','value'=>'0','extra'=>'onchange="javascript:document.getElementById(\'security_options\').style.display=\'\'"','checked'=>'true'))?>
						<?=form_checkbox(array('type'=>'radio','name'=>'bGlobalAdmin','title'=>'Yes','value'=>'1','extra'=>'onchange="javascript:document.getElementById(\'security_options\').style.display=\'none\'"','checked'=>'false'))?>

						<div id='security_options' style=''>
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
										<?=form_checkbox(array('name'=>'secure'.$row['ID'].'[]','title'=>$access[$v],'value'=>$v))?>
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
				<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
			</div>
		</form>
	</div>

<?
	}
?>