document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
		totalErrors = 0;
		if(errEmpty('chrFirst', "You must enter a First Name.")) { totalErrors++; }
		if(errEmpty('chrLast', "You must enter a Last Name.")) { totalErrors++; }
		if(errEmpty('chrEmail',"You must enter a E-mail Address.")) { 
			totalErrors++; 
		} else {
			if(errEmail('chrEmail','','This is not a valid E-mail Address.')) { totalErrors++; }
		}
		
		if(page == 'add') {
			if(errPasswordsEmpty('chrPassword','chrPassword2',"You Must enter a Password")) { totalErrors++; }
			else if (errPasswordsMatch('chrPassword','chrPassword2',"Passwords must match")) { totalErrors++; }
		} else {
			if(errPasswordsMatch('chrPassword','chrPassword2',"Passwords must match")) { totalErrors++; }
		}
	if(totalErrors > 0) { window.scrollTo(0,0); }
	return (totalErrors == 0 ? true : false);
}