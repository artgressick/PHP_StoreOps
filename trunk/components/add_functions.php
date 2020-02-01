<?
	//-----------------------------------------------------------------------------------------------
	// New Function designed by Daniel Tisza-Nitsch
	// ** Random key generator.  This was make a rediculously secure key to search for values on.
	//-----------------------------------------------------------------------------------------------
	function makekey() {
		$email = (isset($_SESSION['chrEmail']) ? $_SESSION['chrEmail'] : 'unknown@emailadsa.com');
		$tTime = time();
		$length = (0 - strlen($tTime));
		$key = sha1(uniqid(mt_rand(1000000,9999999).$email.time(), true));
	    return substr($key,0,$length).$tTime;
	}
?>