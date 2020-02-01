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
				
						<div class="colHeader">Language Information</div>

						<div style='padding-bottom:5px;'>
							<div class='FormName'>Enable Language <span class='FormRequired'>(Required)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'No','value'=>'0','checked'=>(!$info['bShow']?'true':'false')))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'Yes','value'=>'1','checked'=>($info['bShow']?'true':'false')))?>
						</div>

						<?=form_text(array('caption'=>'Name','required'=>'true','name'=>'chrLanguage','size'=>'30','maxlength'=>'200','value'=>$info['chrLanguage']))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
						<div class="colHeader">Language Icon</div>
						<table cellspacing='0' cellpadding='0' style='width:100%;'>
							<tr>
								<td>
									<div class='FormName'>Icon <span class='FormRequired'>(Required)</span></div>
									<div class='FormField'>
										     <select id="chrIcon" name="chrIcon" style="width:200px;" onchange="javascript:flagchange(this.value);">
			    		              			<option value="">Select a Icon</option>
<?
											if ($handle = opendir($BF.'images/geoflags')) { //put here your own folder e.g. opendir('c:\\temp')
												while (false !== ($file = readdir($handle))) {
													if ($file != "." && $file != ".." && $file != '.svn') {
														$filename = explode('.',$file);
														$flags[$filename[0]] = $file;
													}
												}
											}
			
											foreach($flags AS $name => $file) {
?>                  				
			    		              				<option value="<?=$file?>"<?=($info['chrIcon']==$file?' selected="selected"':'')?>><?=$name?></option>
<?
											}
?>
			                  				</select>						
									</div>
								</td>
								<td>
									<div id='filepreview'><img src='<?=$BF?>images/geoflags/<?=$info['chrIcon']?>' alt='<?=$info['chrIcon']?>' /></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan='3' class="tcleft">
						<div class="colHeader100">Landing Page</div>
						<?=form_textarea(array('caption'=>'Landing Page Body','required'=>'true','name'=>'txtLandingPage','rows'=>'35','style'=>'width:100%;','value'=>$info['txtLandingPage']))?>
						<div style='padding: 10px 0;'><?=form_button(array('type'=>'submit','value'=>'Update Information'))?></div>
						<div class="colHeader100">Site Lanaguage (Word/Sentance Replacement)</div>
					</td>
				</tr>
				<tr>
					<td class="tcleft" colspan='3' style='padding:5px;'>
<?
				#Lets pull in the master language
				$master = db_query("SELECT chrVar, chrLabel, chrDescription, chrGroup FROM MasterLang ORDER BY dOrder, chrGroup","Getting Master Language");
				$replacements = db_query("SELECT * FROM Langs WHERE idLanguage=".$info['ID'],"Getting Replacements");
				while($row = mysqli_fetch_assoc($replacements)) {
					$replace[$row['chrVar']] = $row['chrLabel'];
				}
				$masterlist = array();
				while($row = mysqli_fetch_assoc($master)) {
					$masterlist[$row['chrGroup']][] = $row;
				}
				foreach($masterlist AS $group => $values) {
?>
						<div style='padding:5px; background:#EEE; margin-top:10px; font-weight:bold;'><?=$group?></div>
						<table cellpadding='0' cellspacing='0' style='width:100%;'>
							<tr>
<?						
					$cnt=1;
					foreach($values AS $k => $row) {
						if($cnt++%2) {
?>
							</tr>
							<tr>
<?						
						}
?>
							<td style='width:50%;'>
								<?=form_text(array('caption'=>$row['chrDescription'],'name'=>$row['chrVar'],'size'=>'50','maxlength'=>'200','value'=>(isset($replace[$row['chrVar']]) ? $replace[$row['chrVar']] : $row['chrLabel'])))?>
							</td>
<?						
					}
?>
							</tr>						
						</table>
<?						
				}
?>
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