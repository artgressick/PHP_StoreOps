//dtn:  Set up the Ajax connections

function startAjax() {
	var ajax = false;
	try { 
		ajax = new XMLHttpRequest(); // Firefox, Opera 8.0+, Safari
	} catch (e) {
	    // Internet Explorer
	    try { ajax = new ActiveXObject("Msxml2.XMLHTTP");
	    } catch (e) {
			try { ajax = new ActiveXObject("Microsoft.XMLHTTP");
	        } catch (e) {
	        	alert("Your browser does not support AJAX!");
	        }
	    }
	}
	return ajax;
}

//dtn: This is the revert for the Warning Overlay page... it turns it from the dark background back to the normal view.
function revert() {
	document.getElementById('overlaypage').style.display = "none";
	document.getElementById('warning').style.display = "block";
}

//dtn: This is the warning window.  It sets up the gay overlay background with the window in the middle asking if you are sure you want to deleted whatever.
function warning(id,val1,chrKEY,val2) {

	// This specifically finds the height of the entire internal window (the page) that you are currently in.
	if( typeof( window.innerWidth ) == 'number' ) {
		//Non-IE
		myWidth = window.innerWidth;
		myHeight = window.innerHeight;
	} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		//IE 6+ in 'standards compliant mode'
		myWidth = document.documentElement.clientWidth;
		myHeight = document.documentElement.clientHeight;
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		//IE 4 compatible
		myWidth = document.body.clientWidth;
		myHeight = document.body.clientHeight;
	}

	// This specifically find the SCROLL height.  Example, you have scrolled down 200 pixels
	if( typeof( window.pageYOffset ) == 'number' ) {
		//Netscape compliant
		scrOfY = window.pageYOffset;
		scrOfX = window.pageXOffset;
	} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
		//DOM compliant
		scrOfY = document.body.scrollTop;
		scrOfX = document.body.scrollLeft;
	} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
		//IE6 standards compliant mode
		scrOfY = document.documentElement.scrollTop;
		scrOfX = document.documentElement.scrollLeft;
	} else {
		scrOfY = 0;
		scrOfX = 0;
	}

	// document.body.scrollHeight <-- Finds the entire SCROLLable height of the document.
	if (window.innerHeight && window.scrollMaxY) { // Firefox
		document.getElementById('gray').style.height = (window.innerHeight + window.scrollMaxY) + "px";
	} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
		document.getElementById('gray').style.height = yWithScroll = document.body.scrollHeight + "px";
	} else { // works in Explorer 6 Strict, Mozilla (not FF) and Safari
		document.getElementById('gray').style.height = document.body.scrollHeight + "px";
  	}

	document.getElementById('gray').style.width = (myWidth + scrOfX) + "px";
	
//	if(scrOfY != 0) {
		document.getElementById('message').style.top = scrOfY+"px";
//	} 
	
	document.getElementById('delName').innerHTML = val1;
	document.getElementById('idDel').value = id;
	document.getElementById('chrKEY').value = chrKEY;
	document.getElementById('overlaypage').style.display = "block";
	document.getElementById('tblName').value = val2;
}

//dtn: This is the basic delete item script.  It uses GET's instead of Posts
function delItem(address) {
	var id = document.getElementById('idDel').value;
	var chrKEY = document.getElementById('chrKEY').value;
	ajax = startAjax();
	
	if(ajax) {
		ajax.open("GET", address + id + "&chrKEY=" + chrKEY);
	
		ajax.onreadystatechange = function() { 
			if(ajax.readyState == 4 && ajax.status == 200) { 
				showNotice(id,ajax.responseText);
				// alert(ajax.responseText);
			} 
		} 
		ajax.send(null); 
	}
} 

//dtn: This is used to erase a line from the sort list.
function showNotice(id, type) {
	var tbl = '';
	tbl = document.getElementById('tblName').value;
	document.getElementById(tbl + 'tr' + id).style.display = "none";
	if(document.getElementById('resultCount')) {
		var rc = document.getElementById('resultCount');
		rc.innerHTML = parseInt(rc.innerHTML) - 1;
	}
	
	repaint(tbl);
	revert();
}

//dtn: This is the quick delete used on the sort list pages.  It's the little hoverover x on the right side.
function quickdel(address, idEntity, fatherTable, attribute) {
	ajax = startAjax();
	
	if(ajax) {
		ajax.open("GET", address);
	
		ajax.onreadystatechange = function() { 
			if (ajax.readyState == 4 && ajax.status == 200) { 
				alert(ajax.responseText);
				document.getElementById(fatherTable + 'tr' + idEntity).style.display = "none";
				repaintmini(fatherTable);
			} 
		} 
		ajax.send(null); 
	}
} 

//dtn: Function added to get rid of the first line in the sort columns if there are no values in the sort table yet.
//		Ex: "There are no People in this table" ... that gets erased and replaced with a real entry
function noRowClear(fatherTable) {
	var val = document.getElementById(fatherTable).getElementsByTagName("tr");
	if(val.length <= 2 && val[1].innerHTML.length < 100) {
		var tmp = val[0].innerHTML
		document.getElementById(fatherTable).innerHTML = "";
		document.getElementById(fatherTable).innerHTML = tmp;
	}
}

//dtn: This is the main function to POST information through Ajax
function postInfo(url, parameters) {
	ajax = startAjax();
	ajax.open('POST', url, true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.setRequestHeader("Content-length", parameters.length);
	ajax.setRequestHeader("Connection", "close");
	ajax.send(parameters);
	
	ajax.onreadystatechange = function() { 
   		if(ajax.readyState == 4 && ajax.status == 200) {
			//alert(ajax.responseText);
   			//document.getElementById('showinfo').innerHTML = ajax.responseText;
   		}
  	}
}

// This will mark something as show or not
function update_bShow(table,id) {
	ajax = startAjax();
	var bShow = document.getElementById('bShowValue'+id).value;
	if(bShow == 1) { bShowNew = 0; } else { bShowNew = 1; }  
	var address = BF + "ajax_delete.php?postType=UpdatebShow&tbl=" + table + "&bShow=" + bShowNew + "&bShowOld=" + bShow + "&id=";
	if(ajax) {
		ajax.open("GET", address + id);
		ajax.onreadystatechange = function() { 
			if(ajax.readyState == 4 && ajax.status == 200) { 
				//alert(ajax.responseText);
				if(ajax.responseText == 3) {
					if(bShowNew == 1) {
						document.getElementById('bShow'+id).src = BF + 'images/icon_on.png';
						document.getElementById('bShow'+id).alt = "Active (Click to make De-Active)";
						document.getElementById('bShow'+id).title = "Active (Click to make De-Active)";
						document.getElementById('bShowValue'+id).value = bShowNew;
					} else {
						document.getElementById('bShow'+id).src = BF + 'images/icon_off.png';
						document.getElementById('bShow'+id).alt = "De-Active (Click to make Active)";
						document.getElementById('bShow'+id).title = "De-Active (Click to make Active)";
						document.getElementById('bShowValue'+id).value = bShowNew;
					}
				}
			} 
		} 
		ajax.send(null); 
	}
} 

function getData(bf,chrKEY,table,id) {
	ajax = startAjax();
	var address = bf + "ajax_delete.php?postType=get" + table + "&tbl=" + table + "&chrKEY=" + chrKEY + "&id=";

	if(ajax) {
		ajax.open("GET", address + id);
	
		ajax.onreadystatechange = function() { 
			if(ajax.readyState == 4 && ajax.status == 200) { 
//				alert(ajax.responseText);
				document.getElementById(table+id).innerHTML = ajax.responseText;
			} 
		} 
		ajax.send(null); 
	}
}

function update_quantity(BF,id,table,quantity) {
	ajax = startAjax();
	if(quantity=='') { quantity=0; }
	var address = BF + "ajax_delete.php?postType=updatequantity&id=" + id + "&intQuantity=" + quantity + "&tbl=" + table;
	//alert(address);
	if(ajax) {
		ajax.open("GET", address);	
		ajax.send(null); 
	}
}