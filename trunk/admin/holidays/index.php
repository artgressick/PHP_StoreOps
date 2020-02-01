<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
		if(access_check(14,3)) {
			$tableHeaders['opt_other'] = 'bShow';
		}
		$tableHeaders['chrHoliday'] = array('displayName' => 'Holiday');
		$tableHeaders['dBegin'] = array('displayName' => 'Begin Date','default' => 'asc');
		$tableHeaders['dEnd'] = array('displayName' => 'End Date');
		$tableHeaders['intStores'] = array('displayName' => 'Store Hours Completed');
		$tableHeaders['opt_link'] = array('address'=>'_excel.php?key=','display'=>'Export to Excel','style'=>'color:blue; white-space:nowrap;');
		
		if(access_check(14,4)) {
			$tableHeaders['opt_del'] = 'chrHoliday';
		}
		
		sortList('Holidays',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(14,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'Holidays'
		);
	}
?>