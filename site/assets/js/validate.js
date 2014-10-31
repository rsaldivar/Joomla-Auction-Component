/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

Joomla.submitbutton = function(task) {
	if (task == 'addproduct.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		var isValid		= true;
		if (isValid) {
			Joomla.submitform(task);
			return true;
		} else {
			return false;
		}
	} else {
		var dl					= document.getElementById('system-message');
		dl.style.display 		= 'block';
		var div					= document.getElementById('je-error-message');
		var jeerror				= document.getElementById('je-errorwarning-message').value;
		div.innerHTML			= jeerror;
	}
}

function enableButton()
{
	if(document.id('jform_terms_condition').checked == true) {
		document.id('auction_pay_button').disabled = false;
	} else {
		document.id('auction_pay_button').disabled	= true;
	}
}