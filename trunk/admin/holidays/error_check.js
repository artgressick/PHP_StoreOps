document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
		totalErrors = 0;
		if(errEmpty('chrHoliday', "You must enter a Holiday Name.")) { totalErrors++; }
		if(errEmpty('idCountry', "You must select a Country.")) { totalErrors++; }
		if(errEmpty('dBegin', "You must enter a Begin Date.")) { totalErrors++; }
		if(errEmpty('dEnd', "You must enter a End Date.")) { totalErrors++; }
		if(errEmpty('chrText', "You must enter a Text Color.")) { totalErrors++; }
		if(errEmpty('chrBack', "You must enter a Background Color.")) { totalErrors++; }
	if(totalErrors > 0) { window.scrollTo(0,0); }
	return (totalErrors == 0 ? true : false);
}