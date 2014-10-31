/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

var percent = 15;   // adjust starting value to suit
var timePeriod = 110;  // adjust milliseconds to suit

function getBar() {

	var retBar = '';
	for (i = 0; i < percent; i++) {
		retBar += "|";
	}

	return retBar;
}

function progressBar() {
	if (percent < 100) {
		percent = percent + 1;
		var div = document.getElementById("pbar");
		var content ='Please be patient while you are being redirected...<br/>';
		content    += "&nbsp &nbsp &nbsp &nbsp Loading : " + percent + "%" + " " + getBar();
		div.innerHTML = content;
		window.status = "Loading : " + percent + "%" + " " + getBar();
		setTimeout ("progressBar()", timePeriod);
	} else {
		document.getElementById("pbar").innerHTML = "";
		window.status = "Please be patient while you are being redirected...";
		document.body.style.display = "";
	}
}

progressBar();