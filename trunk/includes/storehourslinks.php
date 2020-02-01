<?php
	$toolbar_title = $_SESSION['chrLanguage']['storehour_menu'];
	
	$toolbar_links = array();
	
	$toolbar_links[] = array('link'=>linkto(array('address'=>'index.php', 'display'=>$_SESSION['chrLanguage']['home'])));
	$toolbar_links[] = array('link'=>linkto(array('address'=>'holidays.php', 'display'=>$_SESSION['chrLanguage']['holidays'])));
	$toolbar_links[] = array('link'=>linkto(array('address'=>'updatehours.php', 'display'=>$_SESSION['chrLanguage']['update_hours'])));
//	$toolbar_links[] = array('link'=>linkto(array('address'=>'stores.php', 'display'=>(isset($_COOKIE['idStore']) ? $_SESSION['chrLanguage']['change_store'] : $_SESSION['chrLanguage']['choose_store']))));
?>