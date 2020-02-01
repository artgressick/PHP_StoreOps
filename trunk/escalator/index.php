<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$storeinfo;
		$q = "SELECT txtPage FROM LandingPages WHERE idType=2 AND idLanguage='".$_COOKIE['StoreOpsLanguage']."'";
		$landingpage = db_query($q,"Getting Landing Page",1);
?>
		<div class='index'>
			<?=decode($landingpage['txtPage'])?>
		</div>
<?	} ?>