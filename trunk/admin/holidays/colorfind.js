/*

nioColorfind 1.0 BETA

By niodesign.com

Please help us by linking to nioDesign.com and reporting any problems you experience

Please refer to LICENCE.txt for licensing

###tech info###

the color is a value between 0 and 257.
output is in #rrggbb hex format

*/

var colorfinder = new Array();

var colorfinder_store = new Array('cover.png','bg.png','rainbow.png','rainbow_cursor.gif','overlay.png','aim.gif','blank.gif');

function show_colorfind(field) {
	if (colorfinder['opened']) return;
	colorfinder['opened'] = true;
	browser_detect();
	colorfind_preload_images()
	colorfinder['field'] = field;
	colorfinder['docbody'] = document.getElementsByTagName('body').item(0);
	colorfinder['colorpicker'] = document.createElement('div');
	colorfinder['colorpicker'].style.position = 'absolute';
	colorfinder['colorpicker'].style.left = 0;
	colorfinder['colorpicker'].style.top = 0;
	var screen_dimensions = find_screen_dimensions();
	colorfinder['colorpicker'].style.width = screen_dimensions[0] + 'px';
	colorfinder['colorpicker'].style.height = screen_dimensions[1] + 'px';
	colorfinder['colorpicker'].style.zIndex = 100;
	colorfinder['colorpicker'].style.backgroundImage = 'url(' + colorfinder['images']['cover.png'].src + ')';
	colorfinder['colorpicker'].style.backgroundRepeat = 'repeat';
	colorfinder['colorpicker_inner'] = document.createElement('div');
	colorfinder['colorpicker_inner'].style.width = '408px';
	colorfinder['colorpicker_inner'].style.height = '416px';
	colorfinder['colorpicker_inner'].style.position = 'absolute';
	var left = Math.round((screen_dimensions[0] - 408) / 2);
	if (left < 0) {
		left = '0';
	}
	var top = Math.round((screen_dimensions[1] - 416) / 2);
	if (top < 0) {
		top = '0';
	}
	colorfinder['colorpicker_inner'].style.top = top + 'px';
	colorfinder['colorpicker_inner'].style.left = left + 'px';
	colorfinder['colorpicker_inner'].style.backgroundImage = 'url(' + colorfinder['images']['bg.png'].src + ')';
	colorfinder['colorpicker_inner'].style.backgroundRepeat = 'no-repeat';
	colorfinder['colorpicker'].appendChild(colorfinder['colorpicker_inner']);
	colorfinder['rainbow'] = document.createElement('div');
	colorfinder['rainbow'].style.height = '23px';
	colorfinder['rainbow'].style.position = 'absolute';
	colorfinder['rainbow'].style.top = '23px';
	colorfinder['rainbow'].style.right = '24px';
	colorfinder['rainbow'].style.width = '258px';
	colorfinder['rainbow'].style.backgroundImage = 'url(' + colorfinder['images']['rainbow.png'].src + ')';
	colorfinder['rainbow'].style.backgroundRepeat = 'repeat-y';
	colorfinder['rainbow'].onclick = colorfind_setcolor;
	colorfinder['rainbow_cursor'] = document.createElement('img');
	colorfinder['rainbow_cursor'].src = colorfinder['images']['rainbow_cursor.gif'].src;
	colorfinder['rainbow_cursor'].style.position = 'absolute';
	colorfinder['rainbow_cursor'].style.left = '-1px';
	colorfinder['rainbow_cursor'].style.top = '0';
	colorfinder['rainbow'].appendChild(colorfinder['rainbow_cursor']);
	colorfinder['colorpicker_inner'].appendChild(colorfinder['rainbow']);
	colorfinder['endcolor'] = document.createElement('div');
	colorfinder['endcolor'].style.height = '55px';
	colorfinder['endcolor'].style.width = '98px';
	colorfinder['endcolor'].style.position = 'absolute';
	colorfinder['endcolor'].style.top = '141px';
	colorfinder['endcolor'].style.left = '24px';
	colorfinder['endcolor'].style.backgroundColor = 'rgb(255,0,0)';
	colorfinder['colorpicker_inner'].appendChild(colorfinder['endcolor']);
	colorfinder['picker'] = document.createElement('div');
	colorfinder['picker'].style.backgroundImage = 'url(' + colorfinder['images']['overlay.png'].src + ')';
	colorfinder['picker'].style.backgroundColor = 'rgb(255,0,0)';
	colorfinder['picker'].style.width = '256px';
	colorfinder['picker'].style.height = '256px';
	colorfinder['picker'].style.position = 'absolute';
	colorfinder['picker'].style.top = '64px';
	colorfinder['picker'].style.left = '128px';
	colorfinder['picker'].style.overflow = 'hidden';
	colorfinder['picker'].style.zIndex = 1;
	if (colorfinder['version'] < 7) {
		colorfinder['picker'].style.backgroundImage = 'none';
		colorfinder['picker'].style.filter = 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + colorfinder['images']['overlay.png'].src + '\', sizingMethod=\'scale\')';
	}
	colorfinder['aim'] = document.createElement('img');
	colorfinder['aim'].src = colorfinder['images']['aim.gif'].src;
	colorfinder['aim'].style.position = 'absolute';
	colorfinder['aim'].style.top = '-63px';
	colorfinder['aim'].style.left = '244px';
	colorfinder['picker'].appendChild(colorfinder['aim']);
	colorfinder['colorpicker_inner'].appendChild(colorfinder['picker']);
	colorfinder['picker_img'] = document.createElement('img');
	colorfinder['picker_img'].src = colorfinder['images']['blank.gif'].src;
	colorfinder['picker_img'].style.position = 'absolute';
	colorfinder['picker_img'].style.top = '66px';
	colorfinder['picker_img'].style.left = '129px';
	colorfinder['picker_img'].style.width = '256px';
	colorfinder['picker_img'].style.height = '256px';
	colorfinder['picker_img'].style.zIndex = 2;
	colorfinder['picker_img'].onclick = colorfind_pickcolor;
	colorfinder['colorpicker_inner'].appendChild(colorfinder['picker_img']);
	colorfinder['ok'] = document.createElement('a');
	colorfinder['ok'].style.display = 'block';
	colorfinder['ok'].style.position = 'absolute';
	colorfinder['ok'].style.top = '202px';
	colorfinder['ok'].style.left = '56px';
	colorfinder['ok'].style.width = '67px';
	colorfinder['ok'].style.height = '28px';
	colorfinder['ok'].style.cursor = 'pointer';
	colorfinder['ok'].onclick = close_colorfind_pick;
	colorfinder['colorpicker_inner'].appendChild(colorfinder['ok']);
	colorfinder['exit'] = document.createElement('a');
	colorfinder['exit'].style.display = 'block';
	colorfinder['exit'].style.position = 'absolute';
	colorfinder['exit'].style.top = '325px';
	colorfinder['exit'].style.left = '258px';
	colorfinder['exit'].style.width = '122px';
	colorfinder['exit'].style.height = '16px';
	colorfinder['exit'].style.cursor = 'pointer';
	colorfinder['exit'].onclick = close_colorfind;
	colorfinder['colorpicker_inner'].appendChild(colorfinder['exit']);
	colorfinder['base_rgb'] = new Array(255,0,0);
	colorfinder['rgb'] = new Array(255,0,0);
	colorfinder['docbody'].appendChild(colorfinder['colorpicker']);
}

function color2rgb(color) {
	var r;
	var g;
	var b;
	if (color < 43) {
		r = 257;
		g = (color * 6) - 1;
		b = 0;
	}
	else if (color < 86) {
		color -= 43;
		r = 257 - (color * 6);
		g = 257;
		b = 0;
	}
	else if (color < 129) {
		color -= 86;
		r = 0;
		g = 257;
		b = (color * 6) - 1;
	}
	else if (color < 172) {
		color -= 129;
		r = 0;
		g = 257 - (color * 6);
		b = 257;
	}
	else if (color < 215) {
		color -= 172;
		r = (color * 6) - 1;
		g = 0;
		b = 257;
	}
	else {
		color -= 215;
		r = 257;
		g = 0;
		b = 257 - (color * 6);
	}
	r = Math.round((r / 257) * 255);
	g = Math.round((g / 257) * 255);
	b = Math.round((b / 257) * 255);
	return [r,g,b];
}

function browser_detect() {
	if (navigator.appVersion.indexOf("MSIE")!=-1){
		colorfinder['version'] = navigator.appVersion.split("MSIE");
		colorfinder['version'] = parseInt(colorfinder['version'][1]);
	}
	else {
		colorfinder['version'] = 8; //other browser
	}
}

function colorfind_preload_images() {
	var prefix;

	colorfinder['images'] = new Array();

	if (colorfinder['version'] < 7) {
		prefix = 'IE6_';
	}
	else {
		prefix = '';
	}

	i = 0;
	while(colorfinder_store[i]) {
		var temp = colorfinder_store[i]
		colorfinder['images'][temp] = new Image();
		colorfinder['images'][temp].src = 'images/' + prefix + temp;
		i++;
	}
}

//the following function is from quirksmode.org

function find_screen_dimensions() {

	var x,y;
	if (self.innerHeight) // all except Explorer
	{
		x = self.innerWidth;
		y = self.innerHeight;
	}
	else if (document.documentElement && document.documentElement.clientHeight)
		// Explorer 6 Strict Mode
	{
		x = document.documentElement.clientWidth;
		y = document.documentElement.clientHeight;
	}
	else if (document.body) // other Explorers
	{
		x = document.body.clientWidth;
		y = document.body.clientHeight;
	}

	return [x,y];
}

function colorfind_setcolor(e) {
	var x;
	if (!e)  {
		if (event.offsetX) {
			x = event.offsetX;
		}
		else {
			x = event.layerX;
		}
	}
	else {
		if (e.offsetX) {
			x = e.offsetX;
		}
		else {
			x = e.layerX;
		}
	}
	var color = x - 1;
	var rgb = color2rgb(color);
	colorfinder['base_rgb'] = rgb;
	colorfinder['rainbow_cursor'].style.left = (x - 2) + 'px';
	colorfinder['picker'].style.backgroundColor = 'rgb(' + rgb[0] + ',' + rgb[1] + ',' + rgb[2] + ')';
	colorfinder['aim'].style.top = '-11px';
	colorfinder['aim'].style.left = '244px';
	colorfinder['endcolor'].style.backgroundColor = 'rgb(' + rgb[0] + ',' + rgb[1] + ',' + rgb[2] + ')';
}

function colorfind_pickcolor(e) {
	var x;
	var y
	if (!e)  {
		if (event.offsetX) {
			x = event.offsetX;
			y = event.offsetY;
		}
		else {
			x = event.layerX;
			y = event.layerY;
		}
	}
	else {
		if (e.offsetX) {
			x = e.offsetX;
			y = e.offsetY;
		}
		else {
			x = e.layerX;
			y = e.layerY;
		}
	}
	y--;
	x--;
	i = 0;
	while(i<3) {
		colorfinder['rgb'][i] = colorfinder['base_rgb'][i];
		colorfinder['rgb'][i] = Math.round(((colorfinder['rgb'][i] * (255 - y)) + (255 * y)) / 255);
		colorfinder['rgb'][i] = Math.round((colorfinder['rgb'][i] * x) / 255);
		i++;
	}
	colorfinder['endcolor'].style.backgroundColor = 'rgb(' + colorfinder['rgb'][0] + ',' + colorfinder['rgb'][1] + ',' + colorfinder['rgb'][2] + ')';
	colorfinder['aim'].style.left = (x - 11) + 'px';
	colorfinder['aim'].style.top = (y - 11) + 'px';
}

function close_colorfind_pick() {
	var hex = new Array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
	var r1 = Math.floor(((colorfinder['rgb'][0]) / 16));
	var r2 = hex[colorfinder['rgb'][0] - (r1 * 16)];
	r2 = r2.toString();
	r1 = hex[r1];
	r1 = r1.toString();
	var g1 = Math.floor(((colorfinder['rgb'][1]) / 16));
	var g2 = hex[colorfinder['rgb'][1] - (g1 * 16)];
	g2 = g2.toString();
	g1 = hex[g1];
	g1 = g1.toString();
	var b1 = Math.floor(((colorfinder['rgb'][2]) / 16));
	var b2 = hex[colorfinder['rgb'][2] - (b1 * 16)];
	b2 = b2.toString();
	b1 = hex[b1];
	b1 = b1.toString();

	colorfinder['field'].value = '#' + r1 + r2 + g1 + g2 + b1 + b2;
	close_colorfind();
}

function close_colorfind() {
	colorfinder['docbody'].removeChild(colorfinder['colorpicker']);
	colorfinder = new Array();
	document.getElementById('preview').style.color = document.getElementById('chrText').value;
	document.getElementById('preview').style.backgroundColor = document.getElementById('chrBack').value;
}

//onload stuff:

browser_detect();
colorfind_preload_images();