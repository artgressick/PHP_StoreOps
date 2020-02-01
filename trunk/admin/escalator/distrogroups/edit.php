<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<div class="colHeader100">Distro Group Information</div>
			<table class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='width:100%;'>
				<tr>
					<td class="tcleft">

						<?=form_text(array('caption'=>'Name','required'=>'true','name'=>'chrName','size'=>'30','maxlength'=>'90','value'=>$info['chrName']))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">

						<?=form_text(array('caption'=>'E-mail Address','required'=>'true','name'=>'chrEmail','size'=>'30','maxlength'=>'90','value'=>$info['chrEmail']))?>

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