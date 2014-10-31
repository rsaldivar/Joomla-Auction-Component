<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.form.formfield');

class JFormFieldCemail extends JFormField
{
	protected $type = 'Cemail';

	protected function getInput()
   	{
		$lay  		= JRequest::getvar('layout' );
		$class		= $lay == 'paypal' ? 'inputbox required validate-email' : 'inputbox' ;
		$return = '<input type="text" id="'.$this->id.'" class="'.$class.'" name="'.$this->name.'" value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'">';

		return $return;
	}
}
