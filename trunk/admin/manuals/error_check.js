document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
		totalErrors = 0;
		if(errEmpty('chrManual', "You must enter a Manual Name.")) { totalErrors++; }
		if(errEmpty('idLanguage', "You must select a Language.")) { totalErrors++; }
		if(errEmpty('chrBGColor', "You must enter a Background Color.")) { totalErrors++; }
		if(errEmpty('chrLinkColor', "You must enter a Header Link Color.")) { totalErrors++; }
		if(errCustom('txtPage','You must fill in the Landing Page','tinyMCE')) { totalErrors++; }
	if(totalErrors > 0) { window.scrollTo(0,0); }
	return (totalErrors == 0 ? true : false);
}