/* START applesearch object */
		
if (!applesearch)	var applesearch = {};

applesearch.init = function ()
{
	// add applesearch css for non-safari, dom-capable browsers
	if ( navigator.userAgent.toLowerCase().indexOf('safari') < 0  && document.getElementById )
	{
		document.getElementById('srch_fld').style.paddingTop = '2px';
	}
		this.clearBtn = false;
		
		// add style sheet if not safari
//		var dummy = document.getElementById("dummy_css");
//		if (dummy)	dummy.href = BF+"includes/apple_search/applesearch.css";
		document.getElementById('srch_fld').style.width = '115px';
		if(document.getElementById('srch_fld').value != '') {
			var fldID = 'srch_fld';
			var btnID = 'srch_clear';
			var fld = document.getElementById( fldID );
			var btn = document.getElementById( btnID );
			btn.style.background = "white url('"+BF+"includes/apple_search/srch_r_f2.gif') no-repeat top left";
			btn.fldID = fldID; // btn remembers it's field
			btn.onclick = this.clearBtnClick;
			this.clearBtn = true;
		}
//	}
}

// called when on user input - toggles clear fld btn
applesearch.onChange = function (fldID, btnID)
{
	// check whether to show delete button
	var fld = document.getElementById( fldID );
	var btn = document.getElementById( btnID );
	if (fld.value.length > 0 && !this.clearBtn)
	{
		btn.style.background = "white url('"+BF+"includes/apple_search/srch_r_f2.gif') no-repeat top left";
		btn.fldID = fldID; // btn remembers it's field
		btn.onclick = this.clearBtnClick;
		this.clearBtn = true;
	} else if (fld.value.length == 0 && this.clearBtn)
	{
		btn.style.background = "white url('"+BF+"includes/apple_search/srch_r.gif') no-repeat top left";
		btn.onclick = null;
		this.clearBtn = false;
	}
}


// clears field
applesearch.clearFld = function (fldID,btnID)
{
	var fld = document.getElementById(fldID);
	fld.value = "";
	this.onChange(fldID,btnID);
	document.getElementById('idSearch').submit();
}

// called by btn.onclick event handler - calls clearFld for this button
applesearch.clearBtnClick = function ()
{
	applesearch.clearFld(this.fldID, this.id);
}


/* END applesearch object */