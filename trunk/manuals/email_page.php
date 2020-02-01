<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$page,$info;
?>
		<form method='post' action='' id='idForm' enctype="multipart/form-data" onsubmit="return error_check();">
		<table cellpadding='0' cellspacing='0' class='template'>
			<tr>
				<td class='left'><?=$_SESSION['chrLanguage']['email_to']?>:</td>
				<td class='right'><input type='text' style='width:100%;' name='chrTo' id='chrTo' /></td>
			</tr>
			<tr>
				<td class='left'><?=$_SESSION['chrLanguage']['email_subject']?>:</td>
				<td class='right'><input type='text' style='width:100%;' name='chrSubject' id='chrSubject' value='<?=$info['chrManual'].': '.$page['chrTitle']?>' /></td>
			</tr>
			<tr>
				<td colspan='2' class='both'>
					<div>
						<div style='font-weight: bold;'><?=$_SESSION['chrLanguage']['email_body']?> <span class="FormRequired">(<?=$_SESSION['chrLanguage']['required']?>)</span></div>
						<textarea name='txtBody' id='txtBody' cols='100' rows='10' style='width:100%;'></textarea>		
					</div>				
				</td>
			</tr>
			<tr>
				<td colspan='2' style='padding-top:10px;'> 
					<div style='margin-top: 10px;'><input type='button' value='<?=$_SESSION['chrLanguage']['send_email']?>' onclick='document.getElementById("btnpress").value=1; error_check();' /></div>
					<input type='hidden' name='key' id='key' value='<?=$_REQUEST['key']?>'>
					<input type='hidden' name='id' id='id' value='<?=$info['ID']?>'>
					<input type='hidden' name='btnpress' id='btnpress' value='0'>
				</td>
			</tr>
		</table>
		</form>

<?	
	}
?>