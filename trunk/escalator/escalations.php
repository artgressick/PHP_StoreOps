<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrTitle'			=> array('displayName' => 'Title'),
			'chrCategory'		=> array('displayName' => 'Category'),
			'dtCreated'			=> array('displayName' => 'Date Submitted','default' => 'asc'),
			'idEscStatus'		=> array('displayName' => 'Status'),

		);
		
		sortList('Escalations',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'view.php?key=',		# The linkto page when you click on the row
			'width: 100%; border-top:none;', 			# Additional header CSS here
			''
		);
	}
?>