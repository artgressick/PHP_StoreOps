<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		if(access_check(5,3)) {
			$tableHeaders['opt_other'] = 'bShow';
		}
		
		$tableHeaders['chrStore'] = array('displayName' => 'Store','default' => 'asc');
		$tableHeaders['chrStoreNum'] = array('displayName' => 'Store Number');
		$tableHeaders['chrCountryShort'] = array('displayName' => 'Country');
		$tableHeaders['chrRegion'] = array('displayName' => 'Region');
		$tableHeaders['chrDivision'] = array('displayName' => 'Division');
		
		if(access_check(5,4)) {
			$tableHeaders['opt_del'] = 'chrStore';
		}
		
		sortList('Stores',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(5,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'Stores'
		);
	}
?>