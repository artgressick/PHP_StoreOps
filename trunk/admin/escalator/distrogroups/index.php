<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
		$tableHeaders = array(
			'chrName'			=> array('displayName' => 'Name','default' => 'asc','sorttype'=>'alpha'),
			'chrEmail'			=> array('displayName' => 'E-mail Address')
		);
		
		if(access_check(11,4)) {
			$tableHeaders['opt_del'] = 'chrName';
		}
		
		sortList('DistroGroups',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(11,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'DistroGroups'
		);
	}
?>