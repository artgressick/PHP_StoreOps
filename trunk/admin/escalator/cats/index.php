<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
?>
	<form action="" method="post" id="idForm" style="padding:0px; margin:0px;">
<?
		$tableHeaders = array(
			'chrCategory'		=> array('displayName' => 'Category Name','default' => 'asc','sorttype'=>'alpha')
		);
		if(access_check(9,3)) {
			$tableHeaders['opt_other'] = 'dOrder';
		}
		if(access_check(9,4)) {
			$tableHeaders['opt_del'] = 'chrCategory';
		}
		
		sortList('EscalatorCats',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(9,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'EscalatorCats'
		);
?>
		<div class='FormButtons' style='padding:5px;'>
			<?=form_button(array('type'=>'submit','name'=>'submit','value'=>'Save Order'))?>
		</div>
	</form>
<?	
	}
?>