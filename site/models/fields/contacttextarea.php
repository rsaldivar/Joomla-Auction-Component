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

class JFormFieldContacttextarea extends JFormField
{
	protected $type = 'Contacttextarea';

	protected function getInput()
	{
		// Initialize some field attributes.
		$lay  		= JRequest::getvar('layout' );
		$class		= $lay == 'paypal' ? 'inputbox required' : 'inputbox' ;
		$columns	= $this->element['cols'] ? ' cols="'.(int) $this->element['cols'].'"' : '';
		$rows		= $this->element['rows'] ? ' rows="'.(int) $this->element['rows'].'"' : '';
		$size		= $this->element['size'] ? 'width:'.(int) $this->element['size'].'%' : '';

		// Initialize JavaScript field attributes.
		$onchange	= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		return '<textarea style="'.$size.'" name="'.$this->name.'" class="'.$class.'" id="'.$this->id.'"' .
			$columns.$rows.'>' .
			htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') .
			'</textarea>';
	}
}
