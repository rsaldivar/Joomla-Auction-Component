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

$app				= JFactory::getApplication();
$params				= $app->getParams();
$enable				= $params->get("enable_header_refresh", "0");
$refresh			= $params->get("header_refresh", "10");

if($enable == 1) { header("Refresh: $refresh"); }

jimport('joomla.application.component.controller');

// Include helperfile
	require_once(JPATH_COMPONENT.'/helpers/route.php');

// Include stylesheet into the header
	JHtml::_('stylesheet','components/com_jereverseauction/assets/css/style.css');

//Include Script into the header
	JHtml::_('script','components/com_jereverseauction/assets/js/validate.js');
	JHtml::_('script','components/com_jereverseauction/assets/js/timer.js');

// Launch the controller.
	$controller	= JController::getInstance('jereverseauction');
	$controller->execute(JRequest::getCmd('task', 'display'));
	$controller->redirect();