document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
		totalErrors = 0;
		if(errEmpty('chrTitle', "You must enter a Page Title.")) { totalErrors++; }
		if(errCustom('txtPage','You must fill in the Landing Page','tinyMCE')) { totalErrors++; }

	return (totalErrors == 0 ? true : false);
}