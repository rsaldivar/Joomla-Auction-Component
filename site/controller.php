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

jimport('joomla.application.component.controller');

/**
 * Base controller class for jemembership.
 */
class jereverseauctionController extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Method to display a view.
	 */
	public function display($cachable = false , $urlparams = false)
	{
		$cachable		= true;

		// Get the document object.
			$document	= JFactory::getDocument();

		// Set the default view name and format from the Request.
			$vName		= JRequest::getCmd('view', 'Products');

		JRequest::setVar('view', $vName);

		parent::display();

		return $this;
	}

}