<?php
	$toolbar_title = 'Administrative Control';
	
	$toolbar_links = array();
	
	if(access_check(12,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/escalator/escalations/', 'display'=>'Escalations')),'cat'=>'Escalator'); }
	if(access_check(10,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/escalator/templates/', 'display'=>'Templates')),'cat'=>'Escalator'); }
	if(access_check(9,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/escalator/cats/', 'display'=>'Categories')),'cat'=>'Escalator'); }
	if(access_check(11,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/escalator/distrogroups/', 'display'=>'Distro Groups')),'cat'=>'Escalator'); }
	
	if(access_check(13,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/manuals/', 'display'=>'Manuals')),'cat'=>'Manuals'); }
	
	if(access_check(14,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/holidays/', 'display'=>'Holidays')),'cat'=>'Holidays/Hours'); }
	if(access_check(15,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/storehours/', 'display'=>'Store Hours')),'cat'=>'Holidays/Hours'); }
	
	if(access_check(4,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/languages/', 'display'=>'Languages')),'cat'=>'Languages Sections'); }
	if(access_check(5,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/stores/', 'display'=>'Stores')),'cat'=>'Store Sections'); }
	if(access_check(6,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/regions/', 'display'=>'Regions')),'cat'=>'Store Sections'); }
	if(access_check(7,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/divisions/', 'display'=>'Divisions')),'cat'=>'Store Sections'); }
	if(access_check(8,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/countries/', 'display'=>'Countries')),'cat'=>'Store Sections'); }
	if(access_check(3,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/users/', 'display'=>'Users')),'cat'=>'Users Sections'); }
	if(access_check(2,1)) { $toolbar_links[] = array('link'=>linkto(array('address'=>'admin/landingpages/', 'display'=>'Landing Pages')),'cat'=>'Other Sections'); }
?>