<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<div class="colHeader100">Holiday Information</div>
			<table class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='width:100%;'>
				<tr>
					<td class="tcleft">
						<div style='padding-bottom:5px;'>
							<div class='FormName'>Show Holiday <span class='FormRequired'>(Required)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'No','value'=>'0','checked'=>'true'))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'Yes','value'=>'1','checked'=>'false'))?>
						</div>
						
						<?=form_text(array('caption'=>'Holiday Name','required'=>'true','name'=>'chrHoliday','size'=>'30','maxlength'=>'150'))?>

						<? $countries = db_query("SELECT ID,chrCountry AS chrRecord FROM Countries WHERE !bDeleted ORDER BY dOrder, chrCountry","Getting Countries"); ?>
						<?=form_select($countries,array('caption'=>'Country','required'=>'true','name'=>'idCountry'))?>

					</td>
					<td class="tcgutter"></td>
					<td class="tcright">

						<?=form_text(array('caption'=>'Begin Date','required'=>'Required) (MM/DD/YYYY','name'=>'dBegin','size'=>'30','maxlength'=>'30'))?>
						
						<?=form_text(array('caption'=>'End Date','required'=>'Required) (MM/DD/YYYY','name'=>'dEnd','size'=>'30','maxlength'=>'30'))?>

						<div class='FormName'>Text Color <span class='FormRequired'>(Required)</span></div>
						<div><?=form_text(array('nocaption'=>'true','caption'=>'Text Color','required'=>'true','name'=>'chrText','size'=>'10','maxlength'=>'20','value'=>'#333333','extra'=>'onchange="document.getElementById(\'preview\').style.color=this.value;"'))?> 
						<a href='#' onclick="show_colorfind(document.getElementById('chrText'));"><img src='<?=$BF?>images/colorpallet.gif' /></a></div>
						<div class='FormName'>Background Color <span class='FormRequired'>(Required)</span></div>
						<div><?=form_text(array('nocaption'=>'true','caption'=>'Background Color','required'=>'true','name'=>'chrBack','size'=>'10','maxlength'=>'20','value'=>'#CCCCCC','extra'=>'onchange="document.getElementById(\'preview\').style.backgroundColor=this.value;"'))?> 
						<a href='#' onclick="show_colorfind(document.getElementById('chrBack'));"><img src='<?=$BF?>images/colorpallet.gif' /></a></div>
						<div id='preview' style='margin-top:10px; border:1px solid #000; text-align:center; width:100px; line-height:30px; background:#CCCCCC; color:#333333;' >Preview</div>

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