<?php
	include_once($BF.'includes/_emailer.php');
	//emailer($to,$subject,$message,$from='',$cc='',$bcc='',$mb_language='en',$mb_convert_encoding='UTF-8',$attachments=array())
	$emailinfo = db_query("SELECT * FROM Languages WHERE ID=".$_COOKIE['StoreOpsLanguage'],"Getting Lang Info",1);
	$_POST['txtBody'] .= "<p><a href='".$PROJECT_ADDRESS."manuals/page.php?key=".$page['chrKEY']."'>".$PROJECT_ADDRESS."manuals/page.php?key=".$page['chrKEY']."</a></p>";
	if(emailer($_POST['chrTo'],
			encode($_POST['chrSubject']),
			encode($_POST['txtBody']),
			$_SESSION['chrLanguage']['no_reply_email'],
			'',
			'',
			$emailinfo['email_mb_language'],
			$emailinfo['email_mb_convert_encoding'])) { 
				$_SESSION['infoMessages'][] = $_SESSION['chrLanguage']['email_sent'];
				header("Location: page.php?key=".$page['chrKEY']);
				die();	
			} else {
				errorPage($_SESSION['chrLanguage']['email_error']);
			}
	
?>