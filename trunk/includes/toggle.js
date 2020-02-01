function toggle(id) {
    ul = document.getElementById(id);
    img = document.getElementById("img_" + id);
    if (ul){
        if(ul.className == 'closed'){
            ul.className = "open";
            img.src = BF + "images/open.png";
            setCookie(id, "open");
        } else {
            ul.className = "closed";
            img.src = BF + "images/closed.png";
            setCookie(id, "closed");
        }
    }
}

function forceToggle(name,state) {
	if(getCookie(name) != 'open' && getCookie(name) != 'closed') { 
		setCookie(name,state); 
	} else {
		ul = document.getElementById(name);
		img = document.getElementById("img_" + name);
		if (ul){
			if(state == 'open'){
				ul.className = "open";
				img.src = BF + "images/open.png";				
				setCookie(name, "open");
			} else {
				ul.className = "closed";
				img.src = BF + "images/closed.png";
				setCookie(name, "closed");
			}
		}
	}
}

function setToggle(name, state) {
	if(getCookie(name) != 'open' && getCookie(name) != 'closed') { 
		setCookie(name,state); 
	} else {
		ul = document.getElementById(name);
		img = document.getElementById("img_" + name);
		if (ul){
			if(getCookie(name) == 'open'){
				ul.className = "open";
				img.src = BF + "images/open.png";				
				setCookie(name, "open");
			} else {
				ul.className = "closed";
				img.src = BF + "images/closed.png";
				setCookie(name, "closed");
			}
		}
	}
}

// This function sets a cookie to the root path
function setCookie(name, value) {
	var today = new Date();
	today.setTime(today.getTime());
	
	expires = 10000000;
	var expires_date = new Date( today.getTime() + (expires) );
	
	document.cookie = name + "=" +escape( value ) +
		";expires=" + expires_date.toGMTString() + 
		";path=/;";
	}
	
// this function gets the cookie, if it exists
function getCookie(name) {
	
	var start = document.cookie.indexOf(name + "=");
	var len = start + name.length + 1;
	if((!start) && (name != document.cookie.substring(0, name.length)))	{
		return null;
	}
	if(start == -1) return null;
	
	var end = document.cookie.indexOf( ";", len );
	if(end == -1) end = document.cookie.length;
	
	return unescape(document.cookie.substring(len, end));
}
