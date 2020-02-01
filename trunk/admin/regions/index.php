<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
		$tableHeaders = array(
			'chrRegion'			=> array('displayName' => 'Region','default' => 'asc'),
			'intCount'			=> array('displayName' => 'Stores in this Region')
		);
		
		if(access_check(6,4)) {
			$tableHeaders['opt_del'] = 'chrRegion';
		}
		
		sortList('Regions',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(6,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'Regions'
		);
	}
?>