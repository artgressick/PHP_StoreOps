document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
		totalErrors = 0;
		if(errEmpty('chrCountry', "You must enter a Country Name.")) { totalErrors++; }
		if(errEmpty('chrCountryShort', "You must enter a Country Abbriviation.")) { totalErrors++; }
		if(errEmpty('chrEmail',"You must enter a E-mail Address.")) { 
			totalErrors++; 
		} else {
			if(errEmail('chrEmail','','This is not a valid E-mail Address.')) { totalErrors++; }
		}
	if(totalErrors > 0) { window.scrollTo(0,0); }
	return (totalErrors == 0 ? true : false);
}