<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
		$tableHeaders = array(
			'chrDivision'		=> array('displayName' => 'Division','default' => 'asc','sorttype'=>'alpha'),
			'intCount'			=> array('displayName' => 'Stores in this Division')
		);
		
		if(access_check(7,4)) {
			$tableHeaders['opt_del'] = 'chrDivision';
		}
		
		sortList('Divisions',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(7,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'Divisions'
		);
	}
?>