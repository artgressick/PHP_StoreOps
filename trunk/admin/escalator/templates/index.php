<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
?>
	<form action="" method="post" id="idForm" style="padding:0px; margin:0px;">
<?
		if(access_check(10,3)) {
			$tableHeaders['opt_other1'] = 'bShow';
		}

		$tableHeaders['chrTitle'] = array('displayName' => 'Template','sorttype'=>'alpha');
		$tableHeaders['chrCategory'] = array('displayName' => 'Category','sorttype'=>'alpha');
		$tableHeaders['opt_link'] = array('address' => 'preview.php?key=','display'=>'Preview');

		if(access_check(10,3)) {
			$tableHeaders['opt_other2'] = 'dOrder';
		}
		if(access_check(10,4)) {
			$tableHeaders['opt_del'] = 'chrTitle';
		}
		
		sortList('EscalatorTemplates',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(10,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'EscalatorTemplates'
		);
?>
	<? if(access_check(10,3)) { ?>
		<div class='FormButtons' style='padding:5px;'>
			<?=form_button(array('type'=>'submit','name'=>'submit','value'=>'Save Order'))?>
		</div>
	<? } ?>
	</form>
<?	
	}
?>