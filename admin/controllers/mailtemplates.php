<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

class jereverseauctionControllerMailtemplates extends JControllerAdmin
{
	/**
	 * Constructor.
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Proxy for getModel.
	 */
	public function getModel($name = 'Mailtemplate', $prefix = 'jereverseauctionModel', $config = array('ignore_request' => true))
	{
		$model		= parent::getModel($name, $prefix, $config);

		return $model;
	}
}
?>
