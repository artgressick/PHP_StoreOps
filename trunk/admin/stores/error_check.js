document.write('<script type="text/javascript" src="'+ BF + 'includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
		totalErrors = 0;
		if(errEmpty('chrStore', "You must enter a Store Name.")) { totalErrors++; }
		if(errEmpty('chrStoreNum', "You must enter a Store Number.")) { totalErrors++; }
		if(errEmpty('chrEmail',"You must enter a E-mail Address.")) { 
			totalErrors++; 
		} else {
			if(errEmail('chrEmail','','This is not a valid E-mail Address.')) { totalErrors++; }
		}
		if(errEmpty('idRegion', "You must select a Region.")) { totalErrors++; }
		if(errEmpty('idDivision', "You must select a Division.")) { totalErrors++; }
		if(errEmpty('chrAddress', "You must enter a Address.")) { totalErrors++; }
		if(errEmpty('chrPostalCode', "You must enter a Postal Code.")) { totalErrors++; }
		if(errEmpty('idCountry', "You must select a Country.")) { totalErrors++; }
		if(errEmpty('chrManager', "You must enter a the Managers Name.")) { totalErrors++; }
		if(errEmpty('chrManagerEmail', "You must enter the Managers E-mail.")) { totalErrors++; }
		//if(errEmpty('idLanguage', "You must select a Escalator Language.")) { totalErrors++; }
		var langids=document.getElementById('langids').value.split(',');
		var langsselected = 0;
		for(i=0;i<langids.length;i++) {
			if(document.getElementById('txtLanguage'+langids[i]).checked==true) { langsselected++; }
  		}
  		if(langsselected==0) { errCustom('txtLanguage','You must select at least one Language.'); totalErrors++; }

	if(totalErrors > 0) { window.scrollTo(0,0); }
	return (totalErrors == 0 ? true : false);
}