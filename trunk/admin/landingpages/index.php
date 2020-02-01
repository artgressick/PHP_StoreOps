<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrLandingPage'			=> array('displayName' => 'Landing Page','default' => 'asc')
		);
		
		sortList('LandingPages',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'edit.php?key=',		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			''
		);
	}
?>