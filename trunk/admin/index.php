<?php
	include('_controller.php');
	
	function sitm() {
		global $BF;
		$q = "SELECT txtPage FROM LandingPages WHERE ID=1";
		$landingpage = db_query($q,"Getting Landing Page",1);
?>
		<div class='index'>
			<?=decode($landingpage['txtPage'])?>
		</div>
<?	} ?>