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
				
						<div class="colHeader">Country Information</div>

						<?=form_text(array('caption'=>'Name','required'=>'true','name'=>'chrCountry','size'=>'30','maxlength'=>'70'))?>
						
						<?=form_text(array('caption'=>'Abbriviation','required'=>'true','name'=>'chrCountryShort','size'=>'10','maxlength'=>'5'))?>
						
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
					
						<div class="colHeader">Templates Information</div>
					
						<?=form_text(array('caption'=>'From E-mail Address','required'=>'true','name'=>'chrEmail','size'=>'30','maxlength'=>'150'))?>
						
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