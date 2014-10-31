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

// Import Joomla predefined functions
	jimport('joomla.application.component.controllerform');

class auctionControllerMyproducts extends JControllerForm
{
	protected $view_item 		= 'myproducts';
	protected $view_list		= 'categories';

	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$user				= JFactory::getUser();
		$allow					= null;
		return parent::allowAdd();
	}

}
?>
