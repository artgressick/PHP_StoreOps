document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;

	if(errEmpty('chrFirst', "You must enter your First Name.")) { totalErrors++; }
	if(errEmpty('chrLast', "You must enter your Last name.")) { totalErrors++; }
	if(errEmpty('chrEmail',"You must enter your E-mail Address.")) { 
		totalErrors++; 
	} else {
		if(errEmail('chrEmail','','This is not a valid Email Address.')) { totalErrors++; }
	}
	
	if(errPasswordsMatch('chrPassword','chrPassword2',"Passwords must match")) { totalErrors++; }
	return (totalErrors == 0 ? true : false);
}