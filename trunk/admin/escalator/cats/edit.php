<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<div class="colHeader100">Category Information</div>
			<table class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='width:100%;'>
				<tr>
					<td class="tcleft">

						<?=form_text(array('caption'=>'Category Name','required'=>'true','name'=>'chrCategory','size'=>'30','maxlength'=>'100','value'=>$info['chrCategory']))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">

						<? $languages = db_query("SELECT ID,chrLanguage AS chrRecord FROM Languages WHERE !bDeleted ORDER BY dOrder, chrLanguage","Getting Languages"); ?>
						<?=form_select($languages,array('caption'=>'Language','required'=>'true','name'=>'idLanguage','value'=>$info['idLanguage']))?>

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