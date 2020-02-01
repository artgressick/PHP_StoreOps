<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrFirst'			=> array('displayName' => 'First Name','default' => 'asc'),
			'chrLast'			=> array('displayName' => 'Last Name','default' => 'asc'),
			'chrEmail'			=> array('displayName' => 'E-mail Address','default' => 'asc')
		);
		if(access_check(3,4)) {
			$tableHeaders['opt_del'] = 'chrFirst,chrLast';
		}
		
		sortList('Users',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(3,3) ? 'edit.php?key=' : ''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'Users'
		);
	}
?>