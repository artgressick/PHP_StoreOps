<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
?>
	<form action="" method="post" id="idForm" style="padding:0px; margin:0px;">
<?
		$tableHeaders = array(
			'chrCountry'		=> array('displayName' => 'Country','default' => 'asc'),
			'chrCountryShort'	=> array('displayName' => 'Abbriviation','default' => 'asc'),
			'chrEmail'			=> array('displayName' => 'From E-mail','default' => 'asc'),
		);
		if(access_check(8,3)) {
			$tableHeaders['opt_other'] = 'dOrder';
		}
		
		if(access_check(8,4)) {
			$tableHeaders['opt_del'] = 'chrCountry';
		}
		
		sortList('Countries',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(8,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'Countries'
		);
?>
		<div class='FormButtons' style='padding:5px;'>
			<?=form_button(array('type'=>'submit','name'=>'submit','value'=>'Save Order'))?>
		</div>
	</form>
<?	
	}
?>