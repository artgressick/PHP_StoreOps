document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
		totalErrors = 0;
		if(errEmpty('chrLanguage', "You must enter a Language Name.")) { totalErrors++; }
		if(errEmpty('chrIcon', "You must select a Language Icon.")) { totalErrors++; }
		if(errCustom('txtLandingPage',"You must enter a Main Landing Page.",'tinyMCE')) {	totalErrors++; }
		if(page=='add') {
			if(!document.getElementById('bTechIT').checked) {
				errCustom('',"You must check the box that you have or will contact TechIT Solutions that you are creating this Language. See instructions for details.")
			}
		}

	if(totalErrors > 0) { window.scrollTo(0,0); }
	return (totalErrors == 0 ? true : false);
}