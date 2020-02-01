<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<div class="colHeader100">Manual Information</div>
			<table class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='width:100%;'>
				<tr>
					<td class="tcleft">
					
						<div style='padding-bottom:5px;'>
							<div class='FormName'>Enable Manual <span class='FormRequired'>(Required)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'No','value'=>'0','checked'=>'true'))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'Yes','value'=>'1','checked'=>'false'))?>
						</div>

						<?=form_text(array('caption'=>'Manual Name','required'=>'true','name'=>'chrManual','size'=>'30','maxlength'=>'200'))?>

						<div style='padding-bottom:5px;'>
							<div class='FormName'>Put in Resource Tab <span class='FormRequired'>(Required)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bResource','title'=>'Yes','value'=>'1','checked'=>'true'))?>	
							<?=form_checkbox(array('type'=>'radio','name'=>'bResource','title'=>'No','value'=>'0','checked'=>'false'))?>
						</div>

					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
	
						<? $languages = db_query("SELECT ID,chrLanguage AS chrRecord FROM Languages WHERE !bDeleted ORDER BY dOrder, chrLanguage","Getting Languages"); ?>
						<?=form_select($languages,array('caption'=>'Language','required'=>'true','name'=>'idLanguage','value'=>$_SESSION['idManLang']))?>

						<div class='FormName'>Background Color <span class='FormRequired'>(Required)</span></div>
						<div><?=form_text(array('nocaption'=>'true','caption'=>'Background Color','required'=>'true','name'=>'chrBGColor','size'=>'30','maxlength'=>'255','value'=>'LightGreen'))?> 
						<a href='#' onclick="show_colorfind(document.getElementById('chrBGColor'));"><img src='<?=$BF?>images/colorpallet.gif' /></a></div>
						<div class='FormName'>Header Link Color <span class='FormRequired'>(Required)</span></div>
						<div><?=form_text(array('nocaption'=>'true','caption'=>'Header Link Color','required'=>'true','name'=>'chrLinkColor','size'=>'30','maxlength'=>'255','value'=>'Black'))?>
						<a href='#' onclick="show_colorfind(document.getElementById('chrLinkColor'));"><img src='<?=$BF?>images/colorpallet.gif' /></a></div>
						<div id='preview' style='margin-top:10px; border:1px solid #000; text-align:center; width:100px; line-height:30px; background:LightGreen; color:Black;' >Preview</div>
						
	
					</td>
				</tr>
				<tr>
					<td colspan='3'>
						<?=form_textarea(array('caption'=>'Manual Landing Page','required'=>'true','name'=>'txtPage','rows'=>'35','style'=>'width:100%;'))?>
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