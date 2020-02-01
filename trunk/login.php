<?
	function litm() { 
		global $BF,$title,$instructions;
?>
						<form id="form1" name="form1" method="post" action="">
							<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
									<?=messages()?>
									<?=form_text(array('caption'=>'Email Address','required'=>'true','value'=>(isset($_REQUEST['auth_form_name']) ? $_REQUEST['auth_form_name'] : ''),'name'=>'auth_form_name','size'=>'30','maxlength'=>'35'))?>
									<?=form_text(array('caption'=>'Password','required'=>'true','name'=>'auth_form_password','size'=>'30','maxlength'=>'30','type'=>'password'))?>
									<div style="padding-top:10px;">
										<?=form_button(array('type'=>'submit','name'=>'Submit','value'=>'Log In'))?>
							   		</div>
							        <p class="FormRequired">Problems? Contact The Administrator</p>
							    	 </td>
								</tr>
							</table>
						</form>
<?	} ?>