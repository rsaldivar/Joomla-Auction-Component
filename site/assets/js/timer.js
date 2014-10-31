/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

function get_time_difference(earlierDate,laterDate)
{
var nTotalDiff = laterDate.getTime() - earlierDate.getTime();
var diff = new Object();

diff.days = Math.floor(nTotalDiff/1000/60/60/24);
nTotalDiff -= diff.days*1000*60*60*24;

diff.hours = Math.floor(nTotalDiff/1000/60/60);
nTotalDiff -= diff.hours*1000*60*60;

diff.minutes = Math.floor(nTotalDiff/1000/60);
nTotalDiff -= diff.minutes*1000*60;

diff.seconds = Math.floor(nTotalDiff/1000);

return diff;

}

function time_difference()
{
	allDiv 			= document.getElementsByTagName("div");
	var now 		= new Date();
	var prod_id_all = new Array();
	var j			= 0;

	for ( i=0; i<allDiv.length; i++ )
	{
		if ( allDiv[i].id.indexOf("product_") == 0 ) {

			var prod_id  = allDiv[i].id.replace("product_","");
			var end		 = document.getElementById('end_time'+prod_id).value;
			timer_display_function(end , prod_id);
			prod_id_all[j]    	= prod_id;
			j=j+1;
		}
	}
	check_amount(prod_id_all);
	setTimeout("time_difference()",1000)
}

function timer_display_function(end_time_new , prod_id)
{
	var now = new Date();
	currentDate = new Date();
	dateTo   	= new Date(end_time_new);
	diff 		= get_time_difference(currentDate, dateTo);

	var end_time = (dateTo - now) / 1000;

	if(parseInt(end_time) < -1){
		document.getElementById('time_left'+prod_id).innerHTML="Ended";
		update_product(prod_id);
	}else{
		if(isNaN(diff.days) || isNaN(diff.hours) || isNaN(diff.minutes) || isNaN(diff.seconds) ){

		}else{
			document.getElementById('time_left'+prod_id).innerHTML = diff.days + ":"+diff.hours+":"+diff.minutes+":"+diff.seconds;
		}
	}

}

function update_product(prod_id)
{
	var ajaxRequest;  // The variable that makes Ajax possible!
			try{
				// Opera 8.0+, Firefox, Safari
				ajaxRequest = new XMLHttpRequest();
			} catch (e){
				// Internet Explorer Browsers
				try{
					ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					try{
						ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e){
						// Something went wrong
						//alert("Your browser broke!");
						return false;
					}
				}
			}

			ajaxRequest.onreadystatechange = function(){
				if(ajaxRequest.readyState == 4){
					var ajaxDisplay                                 =  ajaxRequest.responseText;
					document.getElementById( 'error' ).innerHTML  	=  ajaxDisplay;

				}
			}

		var path	= document.getElementById('path').value;

		var url='index.php?option=com_jereverseauction&view=products&prod_id='+prod_id+'&task=products.updateStatus';
		ajaxRequest.open("GET", path+url, true);
		ajaxRequest.send(null);
}

//bidding scripts

function get_difference_time(prod_id)
{
	var end			= document.getElementById('end_time'+prod_id).value;
	var timer	 	= document.getElementById("timer"+prod_id).value;

	currentDate 	= new Date();
	dateTo   		= new Date(end);
	diff 			= get_time_difference(currentDate, dateTo);

	if(diff.days == 0 && diff.hours == 0 && diff.minutes == 0 )
	{
		if(diff.seconds < timer) {
			return 1;
		} else {
			return 0;
		}
	}else {
			return 0;
	}

}

function bid(prod_id)
{
	var values_id			= prod_id;
	var uid 				= document.getElementById("uid").value;
	var bid_value			= document.getElementById("amount").value;
	var message				= document.getElementById("Bidmessage").value;
	var path				= document.getElementById('path').value;

	document.getElementById('amount').style.border="1px solid grey";
	document.getElementById('message').style.border="1px solid grey";

	var ajaxRequest;  // The variable that makes Ajax possible!

	if(uid == 0){

		document.getElementById('displayerror'+values_id).innerHTML="Inicie la sesión para pujar el Producto";

	}else if(isNaN(bid_value) || bid_value == null || bid_value.length == 0){

		document.getElementById('displayerror'+values_id).innerHTML="Por favor, introduzca la cantidad válida";
		document.getElementById('amount').style.border="1px solid red";

	}else if( message.length == 0 || message == null ){

		document.getElementById('displayerror'+values_id).innerHTML="Por favor, escriba su mensaje";
		document.getElementById('message').style.border="1px solid red";

	}else{
			try{
				// Opera 8.0+, Firefox, Safari
				ajaxRequest = new XMLHttpRequest();
			} catch (e){
				// Internet Explorer Browsers
				try{
					ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					try{
						ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e){
						// Something went wrong
						//alert("Your browser broke!");
						return false;
					}
				}
			}

			ajaxRequest.onreadystatechange = function(){
				if(ajaxRequest.readyState == 4){

					var ajaxDisplay                                 =  ajaxRequest.responseText;
					document.getElementById( 'error' ).innerHTML  	=  ajaxDisplay;
					var amount 	=	document.getElementById( 'amount' ).value;
					var user 	=	document.getElementById( 'uname' ).value;
					document.getElementById( 'bid_amt'+prod_id ).innerHTML  		=  amount;
					document.getElementById( 'bid_amt'+prod_id ).style.background  	=  "#E3414D";
					document.getElementById( 'latest_bidder'+prod_id ).innerHTML  	=  user;
					window.location.reload()
				}
			}

		var url='index.php?option=com_jereverseauction&view=products&prod_id='+values_id+'&bid_value='+bid_value+'&message='+message+'&task=products.bid';
		ajaxRequest.open("GET", path+url, true);
		ajaxRequest.send(null);
	}
}

//My Watch List
function watchList(prod_id)
{
	var uid 		= document.getElementById("uid").value;
	if(uid == 0){
		document.getElementById('error').innerHTML="Por favor, inicia sesión para añadir el producto en listat";
	}else{
		var ajaxRequest;  // The variable that makes Ajax possible!
		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					//alert("Your browser broke!");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				var ajaxDisplay                                 =  ajaxRequest.responseText;
				window.location.reload()
			}
		}

		var path	= document.getElementById('path').value;
		var url		='index.php?option=com_jereverseauction&view=products&prod_id='+prod_id+'&task=products.watchList';
		ajaxRequest.open("GET", path+url, true);
		ajaxRequest.send(null);
	}
}

function check_amount(prod_id)
{
	var ajaxRequest;  // The variable that makes Ajax possible!
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				//alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){

			ajaxDisplay         =  ajaxRequest.responseText;
			var myarray1		=  ajaxDisplay.split("+");
			
			for(  var i=0 ; i < myarray1.length ; i++)
			{
				var array_new	= myarray1[i].split(",");
				var prod_new_id	= array_new[0];
				var new_amount  = array_new[2];
				var user_name   = array_new[3];
				var end_time    = array_new[1];
				var user_name_new	=  '';
				if(user_name){
					user_name		= user_name.split("*")
					user_name_new	= user_name[0];
				}
				var bid_amount  = document.getElementById('bid_amt'+prod_new_id);

				var end		 	= document.getElementById('end_time'+prod_new_id);
				if(end_time){
					var end_time_new = end_time.replace(/-/g,"/");
				}

				if(bid_amount){
					var bid_amount  = document.getElementById('bid_amt'+prod_new_id).innerHTML;
					if(end.value < end_time_new )
					{
						end.value   = end_time_new;
					}
			        if(bid_amount < new_amount)
			        {
						document.getElementById( 'bid_amt'+prod_new_id ).innerHTML  		=  new_amount;
						document.getElementById( 'bid_amt'+prod_new_id ).style.background  	=  "#E3414D";
						document.getElementById( 'latest_bidder'+prod_new_id ).innerHTML  	=  user_name_new;

			        }else {
						document.getElementById( 'bid_amt'+prod_new_id ).style.background  	=  "none";

			        }
		        }
	        }
			return 1;
		}
	}

	var path	= document.getElementById('path').value;
	var url		='index.php?option=com_jereverseauction&view=products&prod_id='+prod_id+'&task=products.checkamount';
	ajaxRequest.open("GET", path+url, true);
	ajaxRequest.send(null);

}

function displayImageIn(image)
{
	document.getElementById('prod_image_new').src = image;
}


function contactuser()
{
	document.getElementById('contact_user').style.display = "block";
}

function send_email()
{
	var frommail 				= document.getElementById("emailid").value;
	var message					= document.getElementById("mail_message").value;
	var subject					= document.getElementById("subject").value;
	var tomail					= document.getElementById("tomail").value;

	var ajaxRequest;  // The variable that makes Ajax possible!
		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					//alert("Your browser broke!");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				var ajaxDisplay            =  ajaxRequest.responseText;
				document.getElementById('message_text').innerHTML = ajaxDisplay;
				window.location.reload()
			}
		}

	var path	= document.getElementById('path').value;
	var data    = 'fromemail='+frommail+'&tomail='+tomail+'&message='+message+'&subject='+subject;
	var url		= 'index.php?option=com_jereverseauction&view=products&task=products.contactuser';
	ajaxRequest.open("POST", path+url, true);
	ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	ajaxRequest.send(data);
	document.getElementById('contact_user').style.display = "none";
	document.getElementById('message_text').innerHTML = "Estamos enviando tus message.Please espere unos segundos";
}