document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
		totalErrors = 0;
		if(errEmpty('chrTitle', "You must enter a Template Name.")) { totalErrors++; }
		if(errEmpty('idLanguage', "You must select a Language.")) { totalErrors++; }
		if(errEmpty('idCategory', "You must select a Category.")) { totalErrors++; }
		if(errEmpty('txtDirections', "You must enter some Instructions.")) { totalErrors++; }
	if(totalErrors > 0) { window.scrollTo(0,0); }
	return (totalErrors == 0 ? true : false);
}