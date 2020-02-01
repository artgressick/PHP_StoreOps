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
		$tableHeaders['chrTitle'] = array('displayName' => 'Page Name','sorttype'=>'alpha');
		$tableHeaders['chrVersion'] = array('displayName' => 'Version');
		$tableHeaders['intChildren'] = array('displayName' => 'Child Pages','sorttype'=>'alpha');

		$tableHeaders['opt_link'] = array('address'=>'history.php?key=','display'=>'History','style'=>'color:blue;');
		
		if(access_check(13,3)) {
			$tableHeaders['opt_other2'] = 'dOrder';
		}
		
		if(access_check(13,4)) {
			$tableHeaders['opt_del'] = 'chrTitle';
		}
		
		sortList('Pages',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(13,3)?'edit.php?key=':''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'Pages'
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