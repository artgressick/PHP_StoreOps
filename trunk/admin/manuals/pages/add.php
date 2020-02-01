<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info, $info2;
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<div class="colHeader100">Page Information</div>
			<table class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='width:100%;'>
				<tr>
					<td class="tcleft">
					
						<div style='padding-bottom:5px;'>
							<div class='FormName'>Enable Page <span class='FormRequired'>(Required)</span></div>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'No','value'=>'0','checked'=>'true'))?>
							<?=form_checkbox(array('type'=>'radio','name'=>'bShow','title'=>'Yes','value'=>'1','checked'=>'false'))?>
						</div>

						<?=form_text(array('caption'=>'Page Title','required'=>'true','name'=>'chrTitle','size'=>'30','maxlength'=>'200'))?>

					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
<?
						$page_results = db_query("SELECT ID, chrTitle, idParent, dOrder FROM Pages WHERE !bDeleted AND idManual='".$info['ID']."' ORDER BY idParent,dOrder, chrTitle","Getting Pages");
						$pages = array();
						if(mysqli_num_rows($page_results) != 0) {
							while($row = mysqli_fetch_assoc($page_results)) {
								if(isset($temp_pages[$row['idParent']]) && $row['idParent'] != 0) { 
									$dOrder = $temp_pages[$row['idParent']]['order'].','.$row['dOrder'];
								} else { $dOrder = $row['dOrder']; }
								$temp_pages[$row['ID']] = array('order'=>$dOrder,'ID'=>$row['ID'],'chrTitle'=>$row['chrTitle']);
							}
							foreach($temp_pages AS $k => $data) {
								$temp[$data['order']] = $data;
							}
							unset($temp_pages); ksort($temp);
							foreach($temp AS $k => $data) {
								$pages[$data['ID']]	= str_pad($data['chrTitle'],(strlen($data['chrTitle'])+substr_count($k,',')),'-',STR_PAD_LEFT); 
							}
						}
?>						
						<div style='padding-bottom:5px;'>
							<div class='FormName'>Parent</div>
							<?=form_select($pages,array('caption'=>'No Parent','nocaption'=>'true','name'=>'idParent','value'=>$info2['ID']))?>
						</div>

					</td>
				</tr>
				<tr>
					<td colspan='3'>
						<?=form_textarea(array('caption'=>'Page Content','required'=>'true','name'=>'txtPage','rows'=>'35','style'=>'width:100%;'))?>
					</td>
				</tr>
			</table>
			<div class='FormButtons'>
				<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'pagekey','value'=>(isset($_REQUEST['key']) && $_REQUEST['key'] != '' ? $_REQUEST['key'] : '')))?>
			</div>
		</form>
	</div>
<?
	}
?>