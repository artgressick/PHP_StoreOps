<?php

// This is needed for the Date Functions
if(phpversion() > '5.0.1') { date_default_timezone_set('America/Los_Angeles'); }

// The configuration file that connects us to the mysql servers
include('storeops-conf.php');

// set up error reporting
require_once($BF. 'components/ErrorHandling/error_handler.php');

if(!isset($host)) {
	error_report("Include database conf failed");
	$connected = false;
} else {
	$connected = true;
	if($mysqli_connection = @mysqli_connect($host, $user, $pass)) {
		if(!@mysqli_select_db($mysqli_connection, $db)) {
			error_report("mysqli_select_db(): " . mysqli_error($mysqli_connection));
		}
	} else {
		error_report("mysqli_connect(): " . mysqli_connect_error($mysqli_connection));
	}
}
// clean up so that these variables aren't exposed through the debug console
unset($host, $user, $pass, $db);

// Set and use the session
session_name(str_replace(' ','_',$PROJECT_NAME));
session_start();

// Let pull in the Lanaguage files
if(!isset($_COOKIE['StoreOpsLanguage']) || !is_numeric($_COOKIE['StoreOpsLanguage'])) {
	$lang = db_query("SELECT ID FROM Languages WHERE bShow AND !bDeleted ORDER BY dOrder","Getting First Language",1);
	setcookie("StoreOpsLanguage", $lang['ID'], time()+60*60*24*180, '/');  /* expire in 180 days */
	$_COOKIE['StoreOpsLanguage'] = $lang['ID'];

	$master = db_query("SELECT chrVar, chrLabel FROM MasterLang","Getting Master Language");
	while($row = mysqli_fetch_assoc($master)) {
		$_SESSION['chrLanguage'][$row['chrVar']] = $row['chrLabel'];
	}
	$langreplace = db_query("SELECT chrVar, chrLabel 
							 FROM Languages
							 JOIN Langs ON Langs.idLanguage=Languages.ID
							 WHERE !Languages.bDeleted AND Languages.ID=".$_COOKIE['StoreOpsLanguage'],"Getting Replacements");
		
	while ($row = mysqli_fetch_assoc($langreplace)) {
		$_SESSION['chrLanguage'][$row['chrVar']] = $row['chrLabel'];
	}
}

if((isset($_REQUEST['idSetLanguage']) && is_numeric($_REQUEST['idSetLanguage'])) || !isset($_SESSION['chrLanguage'])) {
	if(isset($_REQUEST['idSetLanguage']) && is_numeric($_REQUEST['idSetLanguage'])) {
		setcookie("StoreOpsLanguage", $_REQUEST['idSetLanguage'], time()+60*60*24*180, '/');  /* expire in 180 days */ 
		$_COOKIE['StoreOpsLanguage'] = $_REQUEST['idSetLanguage']; 
	}

	$master = db_query("SELECT chrVar, chrLabel FROM MasterLang","Getting Master Language");
	while($row = mysqli_fetch_assoc($master)) {
		$_SESSION['chrLanguage'][$row['chrVar']] = $row['chrLabel'];
	}
	$langreplace = db_query("SELECT chrVar, chrLabel 
							 FROM Languages
							 JOIN Langs ON Langs.idLanguage=Languages.ID
							 WHERE !Languages.bDeleted AND Languages.ID=".$_COOKIE['StoreOpsLanguage'],"Getting Replacements");
		
	while ($row = mysqli_fetch_assoc($langreplace)) {
		$_SESSION['chrLanguage'][$row['chrVar']] = $row['chrLabel'];
	}

	if(isset($_COOKIE['idStore']) && is_numeric($_COOKIE['idStore'])) {
		$temp = db_query("SELECT S.ID, S.chrStore, S.txtLanguage 
					FROM Stores AS S
					JOIN Regions AS R ON S.idRegion=R.ID
					JOIN Divisions AS D ON S.idDivision=D.ID
					JOIN Countries AS C ON S.idCountry=C.ID
					WHERE !S.bDeleted AND !R.bDeleted AND !D.bDeleted AND !C.bDeleted AND S.ID=".$_COOKIE['idStore'],"Get Store Info",1);
		if($temp['ID'] != '') {
			$_COOKIE['idStore'] = $temp['ID'];
			$_SESSION['chrStore'] = $temp['chrStore'];
			$langs = db_query("SELECT ID, chrIcon, chrLanguage FROM Languages WHERE !bDeleted AND bShow AND ID IN (".$temp['txtLanguage'].") ORDER BY dOrder","Get Languages");
			if(!in_csv($_COOKIE['StoreOpsLanguage'],$temp['txtLanguage'])) {
				$tmp = explode(',',$temp['txtLanguage']);
				$_COOKIE['StoreOpsLanguage'] = $tmp[0];
				$langreplace = db_query("SELECT chrVar, chrLabel 
										 FROM Languages
										 JOIN Langs ON Langs.idLanguage=Languages.ID
										 WHERE !Languages.bDeleted AND Languages.ID=".$_COOKIE['StoreOpsLanguage'],"Getting Replacements");
					
				while ($row = mysqli_fetch_assoc($langreplace)) {
					$_SESSION['chrLanguage'][$row['chrVar']] = $row['chrLabel'];
				}
			}
			$_SESSION['LangIcons'] = array();
			while($row = mysqli_fetch_assoc($langs)) {
				$_SESSION['LangIcons'][$row['ID']] = array('icon'=>$row['chrIcon'],'chrLang'=>$row['chrLanguage']);
			} 
		} else {
			unset($_SESSION['chrStore']);
			unset($_COOKIE['idStore']);
		}
	}
	
	
	if(isset($_REQUEST['idSetLanguage'])) {
		header("Location: ".$BF."index.php");
		die();	
	}
}
if(isset($_COOKIE['idStore']) && is_numeric($_COOKIE['idStore']) && (!isset($_SESSION['chrStore']) || $_SESSION['chrStore'] == '')) {
	$temp = db_query("SELECT S.ID, S.chrStore, S.txtLanguage
				FROM Stores AS S
				JOIN Regions AS R ON S.idRegion=R.ID
				JOIN Divisions AS D ON S.idDivision=D.ID
				JOIN Countries AS C ON S.idCountry=C.ID
				WHERE !S.bDeleted AND !R.bDeleted AND !D.bDeleted AND !C.bDeleted AND S.ID=".$_COOKIE['idStore'],"Get Store Info",1);
	if($temp['ID'] != '') {
		$_COOKIE['idStore'] = $temp['ID'];
		$_SESSION['chrStore'] = $temp['chrStore'];
		$langs = db_query("SELECT ID, chrIcon, chrLanguage FROM Languages WHERE !bDeleted AND bShow AND ID IN (".$temp['txtLanguage'].") ORDER BY dOrder","Get Languages");
		$_SESSION['LangIcons'] = array();
		while($row = mysqli_fetch_assoc($langs)) {
			$_SESSION['LangIcons'][$row['ID']] = array('icon'=>$row['chrIcon'],'chrLang'=>$row['chrLanguage']);
		} 
	} else {
		unset($_SESSION['chrStore']);
		unset($_COOKIE['idStore']);
	}
}

// If Logout is set in the URL bar, destroy the session and cookies.
if(isset($_REQUEST['logout'])) {
	$logoutReason = $_REQUEST['logout'];
	$_SESSION = array();
	session_unset();
	session_destroy();
	if(!in_csv($logoutReason,'1,2,3')) {
		$logoutReason = 0;
	}
	header("Location: ".$BF."logout.php?id=".$logoutReason);
	die();
}

/*  This is to create temp variables that SHOULD be erased from the sessions which might not be */
if(isset($_SESSION['tmp'])) {
	foreach($_SESSION['tmp'] as $k => $v) {
		if(!isset($_SESSION['tmp'][$k]['count'])) { 
			$_SESSION['tmp'][$k]['count'] = 3; 
		} else {
			if(--$_SESSION['tmp'][$k]['count'] == 0) { unset($_SESSION['tmp'][$k]); }
		}
		if(count($_SESSION['tmp']) == 0) { unset($_SESSION['tmp']); }
	}
}

function tmp_val($name,$type,$value) {
	if($type == 'get') { 
		return $_SESSION['tmp'][$name]['value']; 
	} else if($type == 'set') {
		if(!isset($_SESSION['tmp'])) { $_SESSION['tmp'] = array(); }
		$_SESSION['tmp'][$name]['value'] = $value;
		$_SESSION['tmp'][$name]['count'] = 3;
	}
}



function error_report($message) {
	ob_start();
	print_r(debug_backtrace());
	$trace = ob_get_contents();
	ob_end_clean();

	$emailto = (defined('BUG_REPORT_ADDRESS') ? constant('BUG_REPORT_ADDRESS') : 'bugs@techitsolutions.com');
	mail($emailto, '['.$PROJECT_NAME.'] Error',
		"- ERROR\n----------------\n" . $message . "\n\n\n- STACK\n----------------\n" . $trace
		);

?>
	<h1>We're Sorry...</h1>
	<p>Could not connect to the database server.  We could be experiencing trouble, or the site may be down for maintenance.</p>
	<p>You can press the Refresh button to see if the site is available again.</p>
<?
	die();
}

function db_query($query, $description, $fetch=0, $ignore_warnings=false, $connection=null) {

	global $mysqli_connection, $database_time;
	if($connection == null) {
		$connection = $mysqli_connection;
	}

	$begin_time = microtime(true);
	$result = mysqli_query($connection, $query);
	$end_time = microtime(true);

	$database_time += ($end_time-$begin_time);

	if(!is_bool($result)) {
		$num_rows = mysqli_num_rows($result);
		$str = $num_rows . " rows";
	} else {
		$affected = mysqli_affected_rows($connection);
		$str = $affected . " affected";
	}

	if ($result === false) {
		_error_debug(array('error' => mysqli_error($connection), 'query' => $query), "MySQL ERROR: " . $description, __LINE__, __FILE__, E_ERROR);
	} else {
		
		if(mysqli_warning_count($connection) && !$ignore_warnings) {
			$warnings = mysqli_get_warnings($connection);
			_error_debug(array('query' => $query, 'warnings' => $warnings), "MySQL WARNING(S): " . $description, __LINE__, __FILE__, E_WARNING);
		} else {
			_error_debug(array('query' => $query), "MySQL (" . $str . ", " . (round(($end_time-$begin_time)*1000)/1000) . " sec): " . $description, __LINE__, __FILE__);
		}
	}
	return(($fetch != 0 ? mysqli_fetch_assoc($result) : $result));
}

function auth_check($sitm='',$file='',$option='') {
	global $BF;
	if(!isset($_SESSION['idUser']) || !is_numeric($_SESSION['idUser'])) {  // if this variable is set, they are already authenticated in this session
		include($BF. 'includes/auth_check.php');
	} else { // Checking Security
		// First Lets see if they have been logged in longer then 12 hours
		if ($_SESSION['dtLogin'] <= date('m/d/Y H:i:s',strtotime("-12 hours"))) {
			header("Location: index.php?logout=3");
			die();
		} 
		if ($_SESSION['dtLastSecurityCheck'] <= date('m/d/Y H:i:s',strtotime("-15 minutes"))) {  // Do we need to do a security check update

			$userinfo = db_query("SELECT bGlobalAdmin, txtSecurity FROM Users WHERE !bDeleted AND ID=".$_SESSION['idUser'],"Get User Information",1);
			
			if(isset($userinfo['bGlobalAdmin']) && $userinfo['bGlobalAdmin'] != 1 && $userinfo['txtSecurity'] != '') { // Not Global
				$_SESSION['bGlobalAdmin'] = 0;
				$_SESSION['Rights'] = array();
				
				$tmp = explode('|', $userinfo['txtSecurity']);
				foreach($tmp AS $k => $values) {
					$temp2 = explode(':',$values);
					$_SESSION['Rights'][$temp2[0]] = $temp2[1];	
				}
			} else if(isset($userinfo['bGlobalAdmin']) && $userinfo['bGlobalAdmin'] == 1) { // Is Global
				$_SESSION['bGlobalAdmin'] = 1;
			} else { // No Record
				errorPage('You do not have authorization to access this page.');
			}
						
			$_SESSION['dtLastSecurityCheck'] = date('m/d/Y H:i:s');
			
			$master = db_query("SELECT chrVar, chrLabel FROM MasterLang","Getting Master Language");
			while($row = mysqli_fetch_assoc($master)) {
				$_SESSION['chrLanguage'][$row['chrVar']] = $row['chrLabel'];
			}
			$langreplace = db_query("SELECT chrVar, chrLabel 
									 FROM Languages
									 JOIN Langs ON Langs.idLanguage=Languages.ID
									 WHERE !Languages.bDeleted AND Languages.ID=".$_COOKIE['StoreOpsLanguage'],"Getting Replacements");
				
			while ($row = mysqli_fetch_assoc($langreplace)) {
				$_SESSION['chrLanguage'][$row['chrVar']] = $row['chrLabel'];
			}
			
			
			if(isset($_COOKIE['idStore']) && is_numeric($_COOKIE['idStore'])) {
				$temp = db_query("SELECT S.ID, S.chrStore, S.txtLanguage 
							FROM Stores AS S
							JOIN Regions AS R ON S.idRegion=R.ID
							JOIN Divisions AS D ON S.idDivision=D.ID
							JOIN Countries AS C ON S.idCountry=C.ID
							WHERE !S.bDeleted AND !R.bDeleted AND !D.bDeleted AND !C.bDeleted AND S.ID=".$_COOKIE['idStore'],"Get Store Info",1);
				if($temp['ID'] != '') {
					$_COOKIE['idStore'] = $temp['ID'];
					$_SESSION['chrStore'] = $temp['chrStore'];
					$langs = db_query("SELECT ID, chrIcon, chrLanguage FROM Languages WHERE !bDeleted AND bShow AND ID IN (".$temp['txtLanguage'].") ORDER BY dOrder","Get Languages");
					if(!in_csv($_COOKIE['StoreOpsLanguage'],$temp['txtLanguage'])) {
						$tmp = explode(',',$temp['txtLanguage']);
						$_COOKIE['StoreOpsLanguage'] = $tmp[0];
						$langreplace = db_query("SELECT chrVar, chrLabel 
												 FROM Languages
												 JOIN Langs ON Langs.idLanguage=Languages.ID
												 WHERE !Languages.bDeleted AND Languages.ID=".$_COOKIE['StoreOpsLanguage'],"Getting Replacements");
							
						while ($row = mysqli_fetch_assoc($langreplace)) {
							$_SESSION['chrLanguage'][$row['chrVar']] = $row['chrLabel'];
						}
					}
					$_SESSION['LangIcons'] = array();
					while($row = mysqli_fetch_assoc($langs)) {
						$_SESSION['LangIcons'][$row['ID']] = array('icon'=>$row['chrIcon'],'chrLang'=>$row['chrLanguage']);
					} 
				} else {
					unset($_SESSION['chrStore']);
					unset($_COOKIE['idStore']);
				}
			}
		} 
		
		// Do we have permission to access this page?  Check Against $_SESSION['idLevel']
		if(!access_check($file,$option)) {
			noAccess();
		}
	}
}

function in_csv($needle,$haystack) { return preg_match('/(^|,)'.$needle.'(,|$)/',$haystack); } 

function access_check($file='',$option='') {
	if(isset($_SESSION['bGlobal']) && !$_SESSION['bGlobal']) {
		return in_csv($option,$_SESSION['Rights'][$file]);
	} else if(isset($_SESSION['bGlobal']) && $_SESSION['bGlobal']) {
		return true; 
	} else {
		return false;
	}
}

function check_idStore($id='') {
	global $BF;
	if($id != '') { 
		$storeinfo = db_query("SELECT * FROM Stores WHERE !bDeleted AND ID='".$id."'","Getting Store Info",1);
		return $storeinfo;
	} else if(isset($_COOKIE['idStore']) && is_numeric($_COOKIE['idStore'])) {
		$storeinfo = db_query("SELECT * FROM Stores WHERE !bDeleted AND ID='".$_COOKIE['idStore']."'","Getting Store Info",1);
		if($storeinfo['ID'] != '') {
			return $storeinfo;
		} else {
			header("Location: ".$BF."stores.php");
			die();	
		}
	} else {
		header("Location: ".$BF."stores.php");
		die();	
	}
}

//-----------------------------------------------------------------------------------------------
// New Functions designed by Jason Summers and written by Daniel Tisza-Nitsch
// ** These functions were created to simplify the uploading of information to the database.
//    With these functions, you can send encode/decode all quotes from a given text and ONLY the quotes.
//      This script assumes that you are setting up database tables to accept UTF-8 characters for all 
//		entities.
//-----------------------------------------------------------------------------------------------

function encode($val,$extra="") {
	$val = str_replace("'",'&#39;',stripslashes($val));
	$val = str_replace('"',"&quot;",$val);
	if($extra == "tags") { 
		$val = str_replace("<",'&lt;',stripslashes($val));
		$val = str_replace('>',"&gt;",$val);
	}
	if($extra == "amp") { 
		$val = str_replace("&",'&amp;',stripslashes($val));
	}
	return $val;
}

function decode($val,$extra="") {
	$val = str_replace('&quot;','"',$val);
	$val = str_replace("&#39;","'",$val);
	if($extra == "tags") { 
		$val = str_replace('&lt;',"<",$val);
		$val = str_replace("&gt;",'>',$val);
	}
	if($extra == "amp") { 
		$val = str_replace("&amp;",'&',stripslashes($val));
	}
	if($extra == "export") {
		//$val = mb_convert_encoding($val, "UTF-8", mb_detect_encoding($val, "UTF-8, ISO-8859-1, ISO-8859-15", true));
		//$val = iconv('UTF-8', 'macintosh', $val);
		//$val = mb_convert_encoding($val, 'HTML-ENTITIES', "UTF-8");
		//$val = iconv("UTF-8", "-8", $val);
		//$val = chr(255).chr(254).mb_convert_encoding($val, 'UTF-16LE', 'UTF-8'); 
	}
	return $val;
}

//-----------------------------------------------------------------------------------------------
// New Function designed by Jason Summers
// ** These function was created to call the error page and pass information to it.
//-----------------------------------------------------------------------------------------------
function errorPage($msg) {
	global $BF;
	if(isset($msg)) {$_SESSION['chrErrorMsg'] = $msg;}
	header("Location: ".$BF."error.php");
	die;
}

function noAccess() {
	global $BF;
	header("Location: ".$BF."noaccess.php");
	die;
}

#############################################################################################
## New Function for hyperlinks and images
## function linkto Example:
## linkto(
##			 'address' => 'edit.php',						// Automatically looks in the current folder.  If there is a '/' in
##															//  the address, it will look for the address in the ROOT/address folder.
##															//  Ex:  address'=>' myprofile/edit.php  -->  ROOT/myprofile/edit.php
##			 'display' => 'Display Name',					// Text shown for link
##			 'title' => 'Title',							// Displays this text when mouse is hovered over field 
##			 'id' => 'Field ID',							// ID of field for JS, (ie. chrFirst)
##			 'class' => 'Field Class',						// Class for the hyperlink
##			 'style' => 'CSS Style',						// Extra Styles for the hyperlink
##			 'extra' => 'Extra Code, Javascript, etc',		// For additional JS, to Disable Field, or any additional options can be entered here.
##			 'img' => 'picture.jpg',						// Adds in an image for you defaulting to ROOT/images/
##			 'imgclass' => 'Field Class',					// Class for the image
##			 'imgstyle' => 'CSS Style'						// Extra Styles for the image
##			);
####################################################################
function linkto($args) {
	if(is_array($args)) { 
		global $BF;

		$addysrc = (preg_match('/\//',$args['address']) ? (preg_match('/^\//',$args['address']) ? $BF.substr($args['address'],1) : $BF.$args['address']) : $args['address']);
		$address = $addysrc;
		$display = (isset($args['display']) ? $args['display'] : '');
		$imgsrc = (isset($args['img']) && preg_match('/\//',$args['img']) ? $BF.$args['img'] : (isset($args['img']) ? $BF.'images/'.$args['img'] : ''));
		$img = (isset($args['img']) ? $imgsrc : '');
		$id = (isset($args['id']) ? $args['id'] : '');
		$extra = (isset($args['extra']) ? $args['extra'] : '');
		$title = (isset($args['title']) ? $args['title'] : $display);
	
		$class = (isset($args['class']) ? $args['class'] : '');
		$style = (isset($args['style']) ? $args['style'] : '');
		$imgclass = (isset($args['imgclass']) ? $args['imgclass'] : '');
		$imgstyle = (isset($args['imgstyle']) ? $args['imgstyle'] : '');
		
		if($img == '') { 
			return "<a href='".$address."' title='".$title."' ".($id!=''? " id='".$id."'":'').($class!=''? " class='".$class."'" :'').($style!=''? " style='".$style."'" :'').($extra!=''? " ".$extra :'').">".$display."</a>";
		} else {
			preg_match('/(\w)*\.(\w)*$/',$img,$title);
			return "<a href='".$address."'".($id!=''? " id='".$id."'":'').($display!=''? " title='".$title."'" : '').($class!=''? " class='".$class."'" :'').($style!=''? " style='".$style."'" :'').($extra!=''? " ".$extra :'')."><img src='".$img."' alt='".$title[0]."'".($display!=''? " title='".$display."'" :'').($imgclass!=''? " class='".$imgclass."'" :'').($imgstyle!=''? " style='".$imgstyle."'" :'')." /></a>";
		}
	} else {
		return '<script type="text/javascript">alert("No Arguments were supplied to the Linkto function");</script>';
	}
}

#############################################################################################
## New Function for images
## function img Example:
## img(
##			 'src' => 'edit.png',							// Adds in an image for you defaulting to ROOT/images/
##															//  the address, it will look for the address in the ROOT/address folder.
##															//  Ex:  address'=>' myprofile/edit.png  -->  ROOT/myprofile/edit.png
##			 'alt' => 'Display Name',						// Displays this text when mouse is hovered over field 
##			 'id' => 'Field ID',							// ID of field for JS, (ie. chrFirst)
##			 'class' => 'Field Class',						// Class for the hyperlink
##			 'style' => 'CSS Style',						// Extra Styles for the hyperlink
##			 'extra' => 'Extra Code, Javascript, etc'		// For additional JS, to Disable Field, or any additional options can be entered here.
##			);
####################################################################
function img($args) {
	if(is_array($args)) { 
		global $BF;

		$imgsrc = (preg_match('/\//',$args['src']) ? $BF.$args['src'] : $BF.'images/'.$args['src']);
		$src = $imgsrc;
		if(isset($args['alt'])) {
			$alt = $args['alt'];
		} else {
			preg_match('/(?!(.*\/)).*(?=(\.\w*$))/',$args['src'],$title);
			$alt = $title[0];
		}
		$id = (isset($args['id']) ? $args['id'] : '');
		$extra = (isset($args['extra']) ? $args['extra'] : '');
	
		$class = (isset($args['class']) ? $args['class'] : '');
		$style = (isset($args['style']) ? $args['style'] : '');
		
		return "<img src='".$src."' alt='".$alt."' title='".$alt."'".($id!=''? " id='".$id."'":'').($class!=''? " class='".$class."'" :'').($style!=''? " style='".$style."'" :'').($extra!=''? " ".$extra :'')." />";
	} else {
		return '<script type="text/javascript">alert("No Arguments were supplied to the Img function");</script>';
	}
}
	
####################################################################
## function form_button Example:
## form_button(array(
##			 'type' => 'button OR submit',					// Default is button, For Submit Field use submit
##			 'title' => 'Roll Over Title',					// Displays this text when mouse is hovered over button 
##			 'value' => encode(Field Value),			// Value of the button, be sure to encode this
##			 'name' => 'Field Name',						// Name of button for Post, (ie. submit)
##			 'id' => 'Field ID',							// ID of button for JS, (ie. submit)
##			 'class' => 'Field Class',						// Class of Text Field
##			 'style' => 'CSS Style',						// Extra Styles for Text Box
##			 'extra' => 'Extra Code, Javascript, etc',		// For additional JS, to Disable Field, or any additional options can be entered here.
##			));
####################################################################
function form_button($args) {
	if(is_array($args)) { 
			
		$name = (isset($args['name']) ? $args['name'] : '');
		$value = (isset($args['value']) ? $args['value'] : '');
		$title = (isset($args['title']) ? $args['title'] : $value);
		$id = (isset($args['id']) ? $args['id'] : $name);
		
		$class = (isset($args['class']) ? $args['class'] : '');
		$style = (isset($args['style']) ? $args['style'] : '');
		
		$extra = (isset($args['extra']) ? $args['extra'] : '');
		
		return "<span><input type='".(isset($args['type'])?$args['type']:'button')."' ".($name!=''? " name='".$name."'":'').($id!=''? " id='".$id."'":'').($title!=''? " title='".$title."'" :'').($value!=''? " value='".$value."'" :'').($class!=''? " class='".$class."'" :'').($style!=''? " style='".$style."'" :'').($extra!=''? " ".$extra :'')." /></span>";
	} else {
		return '<script type="text/javascript">var name=\''.$name.'\';if(name==\'\') { alert("No Arguments were supplied to the button"); }</script>';
	}
}


#############################################################################################
# New Function for the info and error messages
#   Call messages to add in the errors div for JS and for the Info/Error php messages
#   CSS included for portability
#############################################################################################
if (!isset($_SESSION['infoMessages'])) { $_SESSION['infoMessages'] = array(); }
if (!isset($_SESSION['errorMessages'])) { $_SESSION['errorMessages'] = array(); }
function messages() {
	if(isset($_SESSION['infoMessages']) && count($_SESSION['infoMessages'])) { 
		foreach($_SESSION['infoMessages'] as $v) {
			?><table class='infMessage' cellpadding='0' cellspacing='0'><tr><td class='icon'><!-- Icon --></td><td class='msg'><?=$v?></td></tr></table><?
		}
		unset($_SESSION['infoMessages']);
	}
	if(isset($_SESSION['errorMessages']) && count($_SESSION['errorMessages'])) { 
		foreach($_SESSION['errorMessages'] as $v) {
			?><table class='errMessage' cellpadding='0' cellspacing='0'><tr><td class='icon'><!-- Icon --></td><td class='msg'><?=$v?></td></tr></table><?
		}
		unset($_SESSION['errorMessages']);
	}
?><div id='errors'></div>
<?
}

function framebox($content, $width='100%', $color='white', $drawtop=1, $drawmiddle=1, $drawbottom=1) {
	global $BF;
	$box = '';
	if($drawtop) {
		$box .= "<table cellpadding='0' cellspacing='0' class='framebox' style='width:".$width.";'><tr><td class='fbtl'><!-- BLANK --></td><td class='fbtm'><!-- BLANK --></td><td class='fbtr'><!-- BLANK --></td></tr>
";
	}
	if($drawmiddle) {
		$box .= "<tr><td class='fblm' style='background: url(".$BF."images/frame-lm-".$color.".png) repeat-y;'><!-- BLANK --></td><td class='fbm' style='background: url(".$BF."images/frame-m-".$color.".png);'>".encode($content)."</td><td class='fbrm' style='background: url(".$BF."images/frame-rm-".$color.".png) repeat-y;'><!-- BLANK --></td></tr>
";
	}
	if($drawbottom) {
		$box .= "<tr><td class='fbbl'><!-- BLANK --></td><td class='fbbm'><!-- BLANK --></td><td class='fbbr'><!-- BLANK --></td></tr></table>
";
	}
	return decode($box);
}

function manual_breadcrumbs($idPage, $bShow=true, $expand=false,$link=false) {
	global $BF;
	$q = "SELECT P.ID, P.chrKEY, P.chrTitle, P.idParent, M.ID AS idManual, M.chrKEY AS ManualKey, M.chrManual, L.chrLanguage
			FROM Manuals AS M
			JOIN Languages AS L ON M.idLanguage=L.ID
			JOIN Pages AS P
			WHERE !M.bDeleted AND !P.bDeleted AND M.ID = (SELECT idManual FROM Pages WHERE ID=".$idPage.")".($bShow?" AND M.bShow AND P.bShow":"");
	$results = db_query($q,"Getting Pages");
	$pages = array();
	while($row = mysqli_fetch_assoc($results)) {
		$pages[$row['ID']] = $row;
	}
	$breadcrumbs = ' -> '.$pages[$idPage]['chrTitle'];
	$idParent = $pages[$idPage]['idParent'];
	$_SESSION['bodyParams'] = '';
	while ($idParent != 0) {
		$breadcrumbs = ' -> '.($link==true?linkto(array('address'=>$BF.'manuals/page.php?key='.$pages[$idParent]['chrKEY'],'display'=>$pages[$idParent]['chrTitle'],'style'=>'text-decoration:none;color:black;')):$pages[$idParent]['chrTitle']).$breadcrumbs;
		if($expand==true) {
		$_SESSION['bodyParams'] .= "forceToggle('P".$pages[$idParent]['chrKEY']."', 'open');";
		}
		$idParent = $pages[$idParent]['idParent'];
	}
	$breadcrumbs = ($link==true?linkto(array('address'=>$BF.'manuals/index.php?key='.$pages[$idPage]['ManualKey'],'display'=>$pages[$idPage]['chrManual'],'style'=>'text-decoration:none;color:black;')):$pages[$idPage]['chrManual'].(!$bShow?' - '.$pages[$idPage]['chrLanguage']:'')).$breadcrumbs;
	return $breadcrumbs;
}