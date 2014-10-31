<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_jereverseauction')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// For themes
	JHtml::_('stylesheet','administrator/components/com_jereverseauction/assets/css/style.css', false);

// require helper file
JLoader::register('jereverseauctionHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'jereverseauction.php');

// Include dependancies
jimport('joomla.application.component.controller');

// Execute the task.
$controller	= JController::getInstance('jereverseauction');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
?>