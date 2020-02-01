<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
?>
	<form action="" method="post" id="idForm" style="padding:0px; margin:0px;">
<?
		if(access_check(13,3)) {
			$tableHeaders['opt_other1'] = 'bShow';
		}

		$tableHeaders['chrManual'] = array('displayName' => 'Manual Name','sorttype'=>'alpha');

		if(access_check(13,3)) {
			$tableHeaders['opt_other2'] = 'dOrder';
			$tableHeaders['opt_link'] = array('display'=>'Edit','address'=>'edit.php?key=');
		}
		
		if(access_check(13,4)) {
			$tableHeaders['opt_del'] = 'chrManual';
		}
		
		sortList('Manuals',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'pages/?key=',		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'Manuals'
		);
?>
	<? if(access_check(13,3)) { ?>
		<div class='FormButtons' style='padding:5px;'>
			<?=form_button(array('type'=>'submit','name'=>'submit','value'=>'Save Order'))?>
		</div>
	<? } ?>
	</form>
<?	
	}
?>