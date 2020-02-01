<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
		
		$type_results = db_query("SELECT ID,chrFieldType FROM FieldTypes WHERE !bDeleted ORDER BY dOrder","getting field types");	
		
		$types = '<option value="">-Select Option Type-</option>';
		while($row = mysqli_fetch_row($type_results)) {
			$types .= '<option value="'.$row[0] .'">'.$row[1].'</option>';
		}
		$types .= "</select>";
		
		$messages[1] = "<em>A text box will appear for this question with a space limit of 255 characters.</em>";
		$messages[2] = "<em>A text area will appear for this question.  This will be big enough to hold multiple paragraphs of information.</em>";
		$messages[3] = "<em>A select box appear for this question.  Please fill in the names of the options you would like to use.</em>";
		$messages[4] = "<em>A set of checkboxes will appear for this question.  Please fill in the names of the options you would like to appear for the checkboxes.</em>";
		$messages[5] = "<em>A set of radio boxes will appear for this question.  Please fill in the names of the options you would like to appear for the radio boxes.</em>";
		$messages[6] = "<em>Question will be used as a Section Header. This can be used to seperate Sections.</em>";
		$messages[7] = "<em>A text box will appear for this question with a space limit of 255 characters, however the data will be masked in the e-mail.</em>";		
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<div class="colHeader100">Template Information</div>
			<table class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='width:100%; border-bottom:1px solid #999; padding-bottom:5px;'>
				<tr>
					<td class="tcleft">

						<div style='padding-bottom:5px;'>
							<div class='FormName'>Enable Template <span class='FormRequired'>(Required)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'No','value'=>'0','checked'=>(!$info['bShow']?'true':'false')))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'Yes','value'=>'1','checked'=>($info['bShow']?'true':'false')))?>
						</div>

						<?=form_text(array('caption'=>'Template Name','required'=>'true','name'=>'chrTitle','size'=>'30','maxlength'=>'200','value'=>$info['chrTitle']))?>

						<? $categories = db_query("SELECT ID,chrCategory AS chrRecord FROM EscalatorCats WHERE !bDeleted AND idLanguage='".$info['idLanguage']."' ORDER BY dOrder,chrRecord","Getting Cats"); ?>
						<?=form_select($categories,array('caption'=>'Category','required'=>'true','name'=>'idCategory','value'=>$info['idCategory']))?>

					</td>
					<td class="tcgutter"></td>
					<td class="tcright">

						<? $languages = db_query("SELECT ID,chrLanguage AS chrRecord FROM Languages WHERE !bDeleted ORDER BY dOrder, chrLanguage","Getting Languages"); ?>
						<?=form_select($languages,array('caption'=>'Language','required'=>'true','name'=>'idLanguage','value'=>$info['idLanguage'],'extra'=>'onchange="changelanguage(this.value);"'))?>

						<div style='padding-bottom:5px;'>
							<div class='FormName'>Allow Uploads <span class='FormRequired'>(Required, Users can attach multiple files)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bUploads','title'=>'No','value'=>'0','checked'=>(!$info['bUploads']?'true':'false')))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bUploads','title'=>'Yes','value'=>'1','checked'=>($info['bUploads']?'true':'false')))?>
						</div>
						<div style='padding-bottom:5px;'>
							<div class='FormName'>Plain Text E-mail <span class='FormRequired'>(Required, Sends the e-mail in plain text vs HTML)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bPlainEmail','title'=>'No','value'=>'0','checked'=>(!$info['bPlainEmail']?'true':'false')))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bPlainEmail','title'=>'Yes','value'=>'1','checked'=>($info['bPlainEmail']?'true':'false')))?>
						</div>
						<div style='padding-bottom:5px;'>
							<div class='FormName'>Private Employee to Manager Template <span class='FormRequired'>(Required)</span></div>
							<div class='FormName'><span class='FormRequired'>(A field for the employees name and e-mail will be added automatically)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bManager','title'=>'No','value'=>'0','checked'=>(!$info['bManager']?'true':'false')))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bManager','title'=>'Yes','value'=>'1','checked'=>($info['bManager']?'true':'false')))?>
						</div>

					</td>
				</tr>
				<tr>
					<td class="tcleft">
						
						<?=form_textarea(array('caption'=>'Template Instructions','required'=>'true','name'=>'txtDirections','cols'=>'30','rows'=>'10','style'=>'width:100%;','value'=>$info['txtDirections']))?>
						
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
<?
						if($info['txtDistro'] != '') {
							$distro = db_query("SELECT ID, chrName,chrEmail FROM DistroGroups WHERE !bDeleted AND ID IN (".$info['txtDistro'].") ORDER BY chrName,chrEmail","Getting Distro List");
							$dcnt = 0;
							while($row = mysqli_fetch_assoc($distro)) {
?>
									<tr id='DistroGroupID<?=$row['ID']?>' class='<?=($dcnt%2?'ListOdd':'ListEven')?>'>
										<td><?=$row['chrName']?></td>
										<td><?=$row['chrEmail']?></td>
										<td class='options'><span class='deleteImage'><a href='javascript:remove_contact("<?=$row['ID']?>", "DistroGroup");' title='Delete: <?=$row['chrName']?>'><img id='deleteButtonDistroGroup<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png";' onmouseout='this.src="<?=$BF?>images/button_delete.png";' /></a></span><input type='hidden' id='bDelete<?=$row['ID']?>' name='bDelete<?=$row['ID']?>' value='0' /><input type='hidden' name='idDistro[]' value='<?=$row['ID']?>' /></td>
									</tr>
<?
							$dcnt++;
							}
						}
?>
							</tbody>
							<tfoot id='DistroGrouptfoot'>
<?
						if($dcnt == 0) {
?>
								<tr>
									<td colspan='5' style='height:20px;text-align:center;vertical-align:middle;'>No contacts have been added.</td>
								</tr>
<?
						}
?>
							</tfoot>
						</table>	

					</td>
				</tr>
			</table>
			<div class="colHeader100">Template Questions</div>
<?
		$q = "SELECT ID,bDeleted,bRequired,idFieldType,dOrder,chrQuestion,txtOptions
			FROM EscalatorQuestions
			WHERE idTemplate = (SELECT ID FROM EscalatorTemplates WHERE chrKEY='". $_REQUEST['key'] ."')
			ORDER BY dOrder,idFieldType,chrQuestion";
		$results = db_query($q,"getting previous questions");
?>
			<div id='questions' style='margin-bottom: 10px;'>
<?
		$i = 1;
		while($row = mysqli_fetch_assoc($results)) {
?>
										
											<table cellspacing="0" cellpadding="0" class='questions' id='question<?=$i?>'<?=($row['bDeleted']?" style='display:none;'":'')?>>
												<tr>
													<td class='lheader'><strong>Question <?=$i?></strong></td>
													<td class='loption'><input type='text' name='chrQuestion<?=$i?>' id='chrQuestion<?=$i?>' style='width: 325px;' value='<?=$row['chrQuestion']?>' /></td>
													<td class='rheader'>Required Field</td>
													<td class='roption'><input type='checkbox' name='bRequired<?=$i?>' id='bRequired<?=$i?>'<?=($row['bRequired'] == 1 && $row['idFieldType'] != 6 ? ' checked="checked"' : '').($row['idFieldType']==6?' disabled="disabled"':'')?>  /></td>
												</tr>
												<tr>
													<td class='lheader'>Answer Option Types:</td>
													<td class='loption'><select name='idFieldType<?=$i?>' id='idFieldType<?=$i?>' onchange='showOptions(this.value,<?=$i?>)'><?=str_replace('value="'.$row['idFieldType'].'"','value="'.$row['idFieldType'].'" selected="selected"',$types)?></td>
													<td class='rheader'>Display Order</td>
													<td class='roption'><input type='text' name='dOrder<?=$i?>' id='dOrder<?=$i?>' value='<?=$row['dOrder']?>' style='width: 25px;' /></td>
												</tr>
												<tr>
													<td colspan='4' id='options<?=$i?>' class='additional'>
														<div id='optionset<?=$i?>'>
														<?=$messages[$row['idFieldType']]?>
<?			
				if($row['txtOptions'] != "") {
?>
														<table id='optionsetTbl<?=$i?>' cellpadding="0" cellspacing="0">
<?					$tmp_options = explode('|||',$row['txtOptions']);
					$len = count($tmp_options);
					$k = 1;
					while($k <= $len) {
?>
															<tr>
																<td class='optionlabel'>Option <?=$k?>:</td>
																<td class='optionBox' id='optionBox<?=$i?>-<?=$k?>'><input type='text' name='optionval<?=$i?>-<?=$k?>' id='optionval<?=$i?>-<?=$k?>' value='<?=$tmp_options[$k-1]?>' /></td>
																<td class='optionExtra'><input type='button' id='removeOption<?=$i?>-<?=$k?>' onclick='javascript:eraseOption("<?=$i?>-<?=$k?>")' value='Remove Option' /></td>
															</tr>
<?						$k++;
					}
?>
														</table>
														</div><input type='hidden' name='optionval<?=$i?>' id='optionval<?=$i?>' value='<?=$len?>' /><div style='padding: 5px 10px;'><input type='button' onclick='javascript:newOption(<?=$i?>);' value='Add Another Option' /></div> 
<?
				}
?>
														</div>
													</td>
												</tr>
											</table>
											<input type="hidden" name="bDeleted<?=$i?>" id="bDeleted<?=$i?>" value="<?=($row['bDeleted']?'1':'0')?>" />
											<div style="text-align: right; padding-top:2px;"><input type='button' onclick="javascript:eraseQuestion(<?=$i?>);" id="addremove<?=$i?>" value='<?=($row['bDeleted']?'Re-Add Question '.$i:'Remove Question '.$i)?>' /></div>
											<input type="hidden" name="QID-<?=$i?>" value="<?=$row['ID']?>" />
<?	
			$i++;
		} 
?>
			</div>
			<div><input type='button' value='Add New Question' onclick='javascript:addNew();' /></div>
			
			<div class='FormButtons' style='padding-top:10px; border-top:1px solid #999;'>
				<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
				<input type='hidden' name='intCount' id='intCount' value='<?=--$i?>' />
			</div>
		</form>
	</div>

<?
	}
?>