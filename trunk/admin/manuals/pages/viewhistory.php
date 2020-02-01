<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$info,$history;
?>
		<hr />
		<div style='background: white; color:black;'>
			<?=decode(($_REQUEST['type']=='new'?$history['txtNewValue']:$history['txtOldValue']))?>
		</div>
<?
	}
?>