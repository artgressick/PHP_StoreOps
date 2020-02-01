<?
	if (isset($_POST['auth_form_name'])) {  // check to see if this is a submission of the login form
		$auth_form_name = strtolower($_REQUEST['auth_form_name']);

		$q = "SELECT *
			FROM Users
			WHERE !bDeleted AND chrEmail='" . $auth_form_name . "'
		";
		$result = db_query($q, "auth_check: verifying Email.");
		
		if (mysqli_num_rows($result)) {
			$pass = sha1($_POST['auth_form_password']);
			$row = mysqli_fetch_assoc($result);
			
			if($pass == $row['chrPassword'] && !$row['bLocked']) {
				
				# Set the session variables that will be used in the rest of the site
				$_SESSION['chrEmail'] = $row["chrEmail"];
				$_SESSION['idUser'] = $row["ID"];
				$_SESSION['chrFirst'] = $row["chrFirst"];
				$_SESSION['chrLast'] = $row["chrLast"];
				$_SESSION['auto_logon'] = false;
				$_SESSION['dtLogin'] = date('m/d/Y H:m:s');
				
				if(isset($row['bGlobalAdmin']) && $row['bGlobalAdmin'] != 1 && $row['txtSecurity'] != '') { // Not Global
					$_SESSION['bGlobal'] = 0;
					
					$tmp = explode('|', $row['txtSecurity']);
					$_SESSION['Rights'] = array();
					foreach($tmp AS $k => $values) {
						$temp2 = explode(':',$values);
						$_SESSION['Rights'][$temp2[0]] = $temp2[1];	
					}
				} else if(isset($row['bGlobalAdmin']) && $row['bGlobalAdmin'] == 1) { // Is Global
					$_SESSION['bGlobal'] = 1;
				} else { // No Record
					errorPage('You do not have authorization to access this page.');
				}
				
				# This resets their login attempts after the total amount of failed attempts was logged
				db_query("set session group_concat_max_len=10120;","Setting Max Group Concat to 10k");
				db_query("UPDATE Users SET intAttempts=0, dtLastLogin=NOW() WHERE ID=". $row['ID'],'increment logins');
				db_query("INSERT INTO LoginAttempts SET idUser='". $row['ID'] ."', dtCreated=now(), idLoginAttemptType=1",'success login record');
				if(isset($intAttempts) && $intAttempts > 0) {
					$_SESSION['infoMessages'][] = "NOTICE! There has been ".$intAttempts." failed login attempts on this account since your last login!";
				}
				$_SESSION['dtLastSecurityCheck'] = date('m/d/Y H:i:s');
				# This sends the user to whatever page they were originally trying to get to before being stopped to login
				header('Location: ' . $_SERVER['REQUEST_URI']);
				die();
			} else {
				if(!$row['bLocked']) {
					if($row['intAttempts'] == 4) {
						# If the account failed to log in 5 times, lock their account and send them to the "Blocked" page.
						db_query("UPDATE Users SET intAttempts=intAttempts+1,bLocked=1 WHERE ID=". $row['ID'],'increment logins');
						db_query("INSERT INTO LoginAttempts SET idUser='". $row['ID'] ."', dtCreated=now(), idLoginAttemptType=3",'insert login attempt record');
						header('Location: '. $BF .'locked.php');
						die();
					} else {
						# If the aacount failed to log in, but is under 5 attempts, show them the generic message and log the attempt
						$_SESSION['errorMessages'][] = "Authentication failed<!--(1)-->.";
						db_query("INSERT INTO LoginAttempts SET idUser='". $row['ID'] ."', dtCreated=now(), idLoginAttemptType=2",'insert login attempt record');
						db_query("UPDATE Users SET intAttempts=intAttempts+1 WHERE ID=". $row['ID'],'increment logins');
					}
				} else {
					# If the account is locked, send them to the "Blocked" page.
					header('Location: '. $BF .'locked.php');
					die();
				}
			}
		} else {
			# Nothing came back for this email address in the DB.  Generic message ensues.
			$_SESSION['errorMessages'][] = "Authentication failed<!--(2)-->.";
		}
	
	}

	# if they need to be log in for the current page and currently are not yet logged in, send them to the login page.
	include_once($BF.'components/formfields.php');
	include($BF . "login.php");
	# The template to use (should be the last thing before the break)
	$title = 'Administrative Login';
	$section = "admin";
	$leftlink = "";
	$page_background = "darkred";
	$headerlink_color = "white";
	// Banner Information
	$banner_title = $_SESSION['chrLanguage']['welcome_to_storeops']; // Title of this page. (REQUIRED)
	$banner_instructions = ''; // Instructions or description. (NOT REQUIRED)
	
	include($BF ."includes/homelinks.php");
	include($BF ."models/template.php");
	die();
?>
