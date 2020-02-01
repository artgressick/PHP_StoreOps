<?php
	include('_controller.php');
	
	function sitm() {
		global $BF;
		$q = "SELECT txtLandingPage FROM Languages WHERE !bDeleted AND ID='".$_COOKIE['StoreOpsLanguage']."'";
		$landingpage = db_query($q,"Getting Landing Page",1);
?>
		<div class='index'>
			<?=decode($landingpage['txtLandingPage'])?>
		</div>
<?	} ?>