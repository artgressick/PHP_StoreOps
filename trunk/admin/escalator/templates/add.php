<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<div class="colHeader100">Template Information</div>
			<table class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='width:100%; border-bottom:1px solid #999; padding-bottom:5px;'>
				<tr>
					<td class="tcleft">

						<div style='padding-bottom:5px;'>
							<div class='FormName'>Enable Template <span class='FormRequired'>(Required)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'No','value'=>'0','checked'=>'true'))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'Yes','value'=>'1','checked'=>'false'))?>
						</div>

						<?=form_text(array('caption'=>'Template Name','required'=>'true','name'=>'chrTitle','size'=>'30','maxlength'=>'200'))?>

						<? $categories = array(); ?>
						<?=form_select($categories,array('caption'=>'Category','required'=>'true','name'=>'idCategory'))?>

					</td>
					<td class="tcgutter"></td>
					<td class="tcright">

						<? $languages = db_query("SELECT ID,chrLanguage AS chrRecord FROM Languages WHERE !bDeleted ORDER BY dOrder, chrLanguage","Getting Languages"); ?>
						<?=form_select($languages,array('caption'=>'Language','required'=>'true','name'=>'idLanguage','value'=>$_SESSION['idEscLang'],'extra'=>'onchange="changelanguage(this.value);"'))?>

						<div style='padding-bottom:5px;'>
							<div class='FormName'>Allow Uploads <span class='FormRequired'>(Required, Users can attach multiple files)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bUploads','title'=>'No','value'=>'0','checked'=>'false'))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bUploads','title'=>'Yes','value'=>'1','checked'=>'true'))?>
						</div>
						
						<div style='padding-bottom:5px;'>
							<div class='FormName'>Plain Text E-mail <span class='FormRequired'>(Required, Sends the e-mail in plain text vs HTML)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bPlainEmail','title'=>'No','value'=>'0','checked'=>'true'))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bPlainEmail','title'=>'Yes','value'=>'1','checked'=>'false'))?>
						</div>

						<div style='padding-bottom:5px;'>
							<div class='FormName'>Private Employee to Manager Template <span class='FormRequired'>(Required)</span></div>
							<div class='FormName'><span class='FormRequired'>(A field for the employees name and e-mail will be added automatically)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bManager','title'=>'No','value'=>'0','checked'=>'false'))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bManager','title'=>'Yes','value'=>'1','checked'=>'true'))?>
						</div>
					

					</td>
				</tr>
				<tr>
					<td class="tcleft">
						
						<?=form_textarea(array('caption'=>'Template Instructions','required'=>'true','name'=>'txtDirections','cols'=>'30','rows'=>'10','style'=>'width:100%;'))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">

						<table class='colHeader100' style='width: 100%;' cellpadding="0" cellspacing="0">
							<tr>
								<td>Distribution Group</td>
								<td style='text-align: right;'><input type='button' value='Add' onclick='javascript:newwin = window.open("adddistro.php?tbl=DistroGroup","new","width=600,height=400,resizable=1,scrollbars=1"); newwin.focus();' /></td>
							</tr>
						</table>
						<table id='DistroGroup' class='List'  style='width: 100%;' cellpadding='0' cellspacing='0'>
							<thead>
								<tr>
									<th>Name</th>
									<th>E-mail</th>
									<th class="options"><img src='<?=$BF?>images/options.gif' alt='options' /></th>
									
								</tr>
							</thead>
							<tbody id='DistroGrouptbody'>
							</tbody>
							<tfoot id='DistroGrouptfoot'>
								<tr>
									<td colspan='5' style='height:20px;text-align:center;vertical-align:middle;'>No contacts have been added.</td>
								</tr>
							</tfoot>
						</table>	

					</td>
				</tr>
			</table>
			<div class="colHeader100">Template Questions</div>
			<div id='questions' style='margin-bottom: 10px;'>
			
			</div>
			<div><input type='button' value='Add New Question' onclick='javascript:addNew();' /></div>
			
			<div class='FormButtons' style='padding-top:10px; border-top:1px solid #999;'>
				<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
				<input type='hidden' name='intCount' id='intCount' value='0' />
			</div>
		</form>
	</div>

<?
	}
?>