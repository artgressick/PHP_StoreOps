<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
		$tableHeaders['chrHoliday'] = array('displayName' => $_SESSION['chrLanguage']['holiday']);
		$tableHeaders['dBegin'] = array('displayName' => $_SESSION['chrLanguage']['begin_date'],'default' => 'asc');
		$tableHeaders['dEnd'] = array('displayName' => $_SESSION['chrLanguage']['end_date']);
		$tableHeaders['chrStatus'] = array('displayName' => $_SESSION['chrLanguage']['status']);
		
	
		sortList('Holidays',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'holidayhours.php?key=',		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'Holidays'
		);
	}
?>