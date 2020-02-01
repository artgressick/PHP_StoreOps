<?php
	include('_controller.php');
	
	function sitm() {
		global $BF;

		$q = "SELECT S.ID, CONCAT(S.chrStore, ' (', S.chrStoreNum, ')') AS chrRecord 
				FROM Stores AS S
				JOIN Regions AS R ON S.idRegion=R.ID
				JOIN Divisions AS D ON S.idDivision=D.ID
				JOIN Countries AS C ON S.idCountry=C.ID
				WHERE S.bShow AND !S.bDeleted AND !R.bDeleted AND !D.bDeleted AND !C.bDeleted ORDER BY chrRecord";
		
		$stores = db_query($q,"Getting Stores");
?>
	<div class='innerbody'>
		<form action="" method="post" id="idForm">
		
			<?=form_select2($stores,array('caption'=>$_SESSION['chrLanguage']['select_your_store'],'nocaption'=>'true','required'=>'true','name'=>'idStore','extra'=>'onchange="document.getElementById(\'idForm\').submit();"'))?>
	
			<?=form_button(array('type'=>'submit','value'=>$_SESSION['chrLanguage']['submit']))?>
			<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'chrRefer', 'value'=>$_SERVER['HTTP_REFERER']))?>
			
		</form>
	</div>	
		
<?
	}
?>