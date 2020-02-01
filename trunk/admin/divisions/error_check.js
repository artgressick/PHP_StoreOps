document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
		totalErrors = 0;
		if(errEmpty('chrDivision', "You must enter a Division Name.")) { totalErrors++; }

	if(totalErrors > 0) { window.scrollTo(0,0); }
	return (totalErrors == 0 ? true : false);
}