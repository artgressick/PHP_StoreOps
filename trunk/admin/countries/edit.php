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

						<?=form_text(array('caption'=>'Name','required'=>'true','name'=>'chrCountry','size'=>'30','maxlength'=>'70','value'=>$info['chrCountry']))?>
						
						<?=form_text(array('caption'=>'Abbriviation','required'=>'true','name'=>'chrCountryShort','size'=>'10','maxlength'=>'5','value'=>$info['chrCountryShort']))?>
						
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
					
						<div class="colHeader">Templates Information</div>
					
						<?=form_text(array('caption'=>'From E-mail Address','required'=>'true','name'=>'chrEmail','size'=>'30','maxlength'=>'150','value'=>$info['chrEmail']))?>
					
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