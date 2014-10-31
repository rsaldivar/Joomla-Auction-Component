<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class jereverseauctionController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false , $urlparams = false)
	{
		// set default view if not set
        JRequest::setVar('view', JRequest::getCmd('view', 'products'));
 		// call parent behavior
        parent::display($cachable);
		// Set the submenu

		// Load the submenu.
		jereverseauctionHelper::addSubmenu(JRequest::getCmd('view', 'products'));
	}
}
