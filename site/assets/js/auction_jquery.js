/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

var pos_value_r  = 80;
var pos_value_l  = -90;

function previous(){

	var right_value = pos_value_l+pos_value_r;
	if(pos_value_l <= -90 ){

		var right_value = pos_value_l+pos_value_r;

		if(right_value <= 0 ){
			right_value_new = right_value;
		}else{
			right_value_new = "0";
		}

	}else{
			right_value_new="0";
	}

	document.getElementById('centerimage').style.left=right_value_new+"px";
	pos_value_r = pos_value_r + 90;

}

function next(){
	var ci_with   = document.getElementById('centerimage').style.width;
	ci_with       = parseInt(ci_with);
	var avg_width = ( ci_with - 280 )+ 20;

	if(avg_width >= -pos_value_l){
		pos_value_l  = pos_value_l;
		var left_new = pos_value_l - 80;
	}else{
		pos_value_l = "0";
		left_new	= "0";
	}
	document.getElementById('centerimage').style.left=pos_value_l+"px";
	pos_value_l = left_new ;

}

function showdesc(){

	var path	 	 = document.getElementById('path').value;
	var img_path   	 = path+"components/com_jereverseauction/assets/images/buttonRed.png";
	var img_path2 	 = path+"components/com_jereverseauction/assets/images/btnBlue.png";

	document.getElementById('description').style.display			="block";
	document.getElementById('additional_details').style.display		="none";
	document.getElementById('bidder_details').style.display			="none";
	document.getElementById('desc').style.background				="url('"+img_path+"') no-repeat";
	document.getElementById('bidder').style.background				="url('"+img_path2+"') no-repeat";
	document.getElementById('prod_details').style.background		="url('"+img_path2+"') no-repeat";
}

function showdetails(){

	var path	 	 = document.getElementById('path').value;
	var img_path   	 = path+"components/com_jereverseauction/assets/images/buttonRed.png";
	var img_path2 	 = path+"components/com_jereverseauction/assets/images/btnBlue.png";

	document.getElementById('additional_details').style.display		="block";
	document.getElementById('description').style.display			="none";
	document.getElementById('bidder_details').style.display			="none";
	document.getElementById('prod_details').style.background		="url('"+img_path+"') no-repeat";
	document.getElementById('desc').style.background				="url('"+img_path2+"') no-repeat";
	document.getElementById('bidder').style.background				="url('"+img_path2+"') no-repeat";
}

function showbidder(){
	var path	 	 = document.getElementById('path').value;
	var img_path   	 = path+"components/com_jereverseauction/assets/images/buttonRed.png";
	var img_path2 	 = path+"components/com_jereverseauction/assets/images/btnBlue.png";

	document.getElementById('bidder_details').style.display			="block";
	document.getElementById('additional_details').style.display		="none";
	document.getElementById('description').style.display			="none";
	document.getElementById('bidder').style.background				="url('"+img_path+"') no-repeat";
	document.getElementById('desc').style.background				="url('"+img_path2+"') no-repeat";
	document.getElementById('prod_details').style.background		="url('"+img_path2+"') no-repeat";
}