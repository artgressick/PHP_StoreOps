<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info, $info2;

		$q = "SELECT ID, chrKEY, chrTitle, dOrder, bShow, (SELECT COUNT(P.ID) FROM Pages AS P WHERE !bDeleted AND P.idParent=Pages.ID) as intChildren,
			CONCAT(ID,'.',(SELECT COUNT(Audit.ID) FROM Audit WHERE Audit.idType=2 AND Audit.idRecord=Pages.ID AND chrTableName='Pages' AND chrColumnName='txtPage')) as chrVersion
			FROM Pages
			WHERE !bDeleted AND idParent='".$info2['ID']."'
			ORDER BY dOrder, chrTitle";
		$results = db_query($q,"getting Pages");
		if(mysqli_num_rows($results) > 0) {
?>
	<form action="" method="post" id="idForm" style="padding:0px; margin:0px;">
<?
		if(access_check(13,3)) {
			$tableHeaders['opt_other1'] = 'bShow';
		}

		$tableHeaders['chrTitle'] = array('displayName' => 'Page Name','sorttype'=>'alpha');
		$tableHeaders['chrVersion'] = array('displayName' => 'Version');
		$tableHeaders['intChildren'] = array('displayName' => 'Child Pages','sorttype'=>'alpha');
		$tableHeaders['opt_link'] = array('address'=>'history.php?key=','display'=>'History','style'=>'color:blue;');

		if(access_check(13,3)) {
			$tableHeaders['opt_other2'] = 'dOrder';
		}
		
		if(access_check(13,4)) {
			$tableHeaders['opt_del'] = 'chrTitle';
		}
		
		sortList('Pages',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(13,3)?'edit.php?key=':''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'Pages'
		);
?>
	<? if(access_check(13,3)) { ?>
		<div class='FormButtons' style='padding:5px;'>
			<?=form_button(array('type'=>'submit','name'=>'save','value'=>'Save Order'))?>
		</div>
	<? } ?>
	</form>
<?
		}
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<div class="colHeader100">Page Information</div>
			<table class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='width:100%;'>
				<tr>
					<td class="tcleft">
					
						<div style='padding-bottom:5px;'>
							<div class='FormName'>Enable Page <span class='FormRequired'>(Required)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'No','value'=>'0','checked'=>'true','checked'=>(!$info2['bShow']?'true':'false')))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'Yes','value'=>'1','checked'=>'false','checked'=>($info2['bShow']?'true':'false')))?>
						</div>

						<?=form_text(array('caption'=>'Page Title','required'=>'true','name'=>'chrTitle','size'=>'30','maxlength'=>'200','value'=>$info2['chrTitle']))?>

					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
<?
						$page_results = db_query("SELECT ID, chrTitle, idParent, dOrder FROM Pages WHERE !bDeleted AND idManual='".$info2['idManual']."' ORDER BY idParent,dOrder, chrTitle","Getting Pages");
						$pages = array();
						if(mysqli_num_rows($page_results) > 0) {
							$disabledids = array();
							$disabledids[] = $info2['ID'];
							while($row = mysqli_fetch_assoc($page_results)) {
								if(in_array($row['ID'],$disabledids)) { $disabledids[] = $row['ID']; }
								if(in_array($row['idParent'],$disabledids)) { $disabledids[] = $row['ID']; }
								if(!in_array($row['ID'],$disabledids)) {  
									if(isset($temp_pages[$row['idParent']]) && $row['idParent'] != 0) { 
										$dOrder = $temp_pages[$row['idParent']]['order'].','.$row['dOrder'];
									} else { $dOrder = $row['dOrder']; }
									$temp_pages[$row['ID']] = array('order'=>$dOrder,'ID'=>$row['ID'],'chrTitle'=>$row['chrTitle']);
								}
							}
							if(isset($temp_pages)) {
								foreach($temp_pages AS $k => $data) {
									$temp[$data['order']] = $data;
								}
								unset($temp_pages); ksort($temp);
								foreach($temp AS $k => $data) {
									$pages[$data['ID']]	= str_pad($data['chrTitle'],(strlen($data['chrTitle'])+substr_count($k,',')),'-',STR_PAD_LEFT); 
								}
							}
						}						
?>						
						<div style='padding-bottom:5px;'>
							<div class='FormName'>Parent</div>
							<?=form_select($pages,array('caption'=>'No Parent','nocaption'=>'true','name'=>'idParent','value'=>$info2['idParent']))?>
						</div>


					</td>
				</tr>
				<tr>
					<td colspan='3'>
						<?=form_textarea(array('caption'=>'Page Content','required'=>'true','name'=>'txtPage','rows'=>'35','style'=>'width:100%;','value'=>$info2['txtPage']))?>
					</td>
				</tr>
			</table>
			<div class='FormButtons'>
				<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'refer','value'=>$_SERVER['HTTP_REFERER']))?>
			</div>
		</form>
	</div>

<?
	}
?>