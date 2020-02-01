<?php
	$toolbar_title = $_SESSION['chrLanguage']['navigation_menu'];
	
	$toolbar_links = array();
	
	$toolbar_links[] = array('link'=>linkto(array('address'=>'', 'display'=>$_SESSION['chrLanguage']['home'])));
if(isset($_COOKIE['idStore']) && is_numeric($_COOKIE['idStore'])) {
	$toolbar_links[] = array('link'=>linkto(array('address'=>'escalator/', 'display'=>$_SESSION['chrLanguage']['escalator'])));
}
//	$toolbar_links[] = array('link'=>linkto(array('address'=>'stores.php', 'display'=>(isset($_COOKIE['idStore']) ? $_SESSION['chrLanguage']['change_store'] : $_SESSION['chrLanguage']['choose_store']))));
?>