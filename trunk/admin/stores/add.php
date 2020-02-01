<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<div class="colHeader100">Store Information</div>
			<table class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='width:100%;'>
				<tr>
					<td class="tcleft">

						<div style='padding-bottom:5px;'>
							<div class='FormName'>Show Store <span class='FormRequired'>(Required)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'No','value'=>'0','checked'=>'true'))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'Yes','value'=>'1','checked'=>'false'))?>
						</div>

						<?=form_text(array('caption'=>'Store Name','required'=>'true','name'=>'chrStore','size'=>'30','maxlength'=>'150'))?>
						
						<?=form_text(array('caption'=>'Store Number','required'=>'true','name'=>'chrStoreNum','size'=>'15','maxlength'=>'10'))?>
						
						<?=form_text(array('caption'=>'E-mail Address','required'=>'true','name'=>'chrEmail','size'=>'30','maxlength'=>'150'))?>
						
						<? $regions = db_query("SELECT ID,chrRegion AS chrRecord FROM Regions WHERE !bDeleted ORDER BY chrRegion","Getting Regions"); ?>
						<?=form_select($regions,array('caption'=>'Region','required'=>'true','name'=>'idRegion'))?>
						
						<? $divisions = db_query("SELECT ID,chrDivision AS chrRecord FROM Divisions WHERE !bDeleted ORDER BY chrDivision","Getting Divsions"); ?>
						<?=form_select($divisions,array('caption'=>'Division','required'=>'true','name'=>'idDivision'))?>
						
						<?=form_text(array('caption'=>'Phone Number','name'=>'chrPhone','size'=>'20','maxlength'=>'40'))?>
						
						<?=form_text(array('caption'=>'Fax Number','name'=>'chrFax','size'=>'20','maxlength'=>'40'))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">

						<?=form_text(array('caption'=>'Address','required'=>'true','name'=>'chrAddress','size'=>'30','maxlength'=>'200'))?>
						<?=form_text(array('caption'=>'Address2','nocaption'=>'true','name'=>'chrAddress2','size'=>'30','maxlength'=>'200','style'=>'margin-bottom:5px;'))?>
						<?=form_text(array('caption'=>'Address3','nocaption'=>'true','name'=>'chrAddress3','size'=>'30','maxlength'=>'200'))?>
						
						<?=form_text(array('caption'=>'City','name'=>'chrCity','size'=>'30','maxlength'=>'150'))?>
						
						<?=form_text(array('caption'=>'State / Local / Provence','name'=>'chrLocal','size'=>'30','maxlength'=>'120'))?>
						
						<?=form_text(array('caption'=>'Potal Code','name'=>'chrPostalCode','required'=>'true','size'=>'20','maxlength'=>'40'))?>
						
						<? $countries = db_query("SELECT ID,chrCountry AS chrRecord FROM Countries WHERE !bDeleted ORDER BY dOrder, chrCountry","Getting Countries"); ?>
						<?=form_select($countries,array('caption'=>'Country','required'=>'true','name'=>'idCountry'))?>

						<?=form_text(array('caption'=>'Manager Name','required'=>'true','name'=>'chrManager','size'=>'30','maxlength'=>'150'))?>
						<?=form_text(array('caption'=>'Manager E-mail','required'=>'true','name'=>'chrManagerEmail','size'=>'30','maxlength'=>'150'))?>
<?
						$languages = db_query("SELECT ID,chrLanguage FROM Languages WHERE !bDeleted ORDER BY dOrder, chrLanguage","Getting Languages");
						$half = ceil(mysqli_num_rows($languages)/2);
						$cnt = 0;
?>
						<div style='padding-bottom:5px;'>
							<div class='FormName'>Languages <span class='FormRequired'>(One Required)</span></div>
							<table cellpadding='0' cellspacing='0' style='width:100%;'>
								<tr>
									<td style='width:50%; white-space:nowrap;'>
<?
								$ids = '';
								while($row = mysqli_fetch_assoc($languages)) {
									$ids .= $row['ID'].',';
									if($cnt++ == $half) {
?>
									</td>
									<td style='width:50%; white-space:nowrap;'>
<?										
									}
?>										
										<div style='padding:5px;'><?=form_checkbox(array('array'=>'true','name'=>'txtLanguage','title'=>$row['chrLanguage'],'value'=>$row['ID'],'checked'=>(in_csv($row['ID'],1)?'true':'false')))?></div>
<?
								}
?>						
									</td>
								</tr>
							</table>
						</div>

					</td>
				</tr>
			</table>
			<div class='FormButtons'>
				<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'langids','value'=>substr($ids,0,-1)))?>
			</div>
		</form>
	</div>

<?
	}
?>