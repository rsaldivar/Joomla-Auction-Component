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

function com_uninstall()
{
jimport('joomla.filesystem.folder');
	$db			=& JFactory::getDBO();

	// Code for uninstall jetestimonial plugin.
	if (JFolder::exists(JPATH_ROOT.DS.'plugins'.DS.'jereverseauctionpayment')) {
		JFolder::delete(JPATH_ROOT.DS.'plugins'.DS.'jereverseauctionpayment');
	}

	if (JFile::exists(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_jereverseauctionpaypal.ini')) {
		JFile::delete(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_jereverseauctionpaypal.ini');
	}

	if (JFile::exists(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_jereverseauctionpaypal.sys.ini')) {
		JFile::delete(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_jereverseauctionpaypal.sys.ini');
	}

	$query = "DELETE  FROM `#__extensions` WHERE `element`='jereverseauctionPaypal' AND `folder` = 'jereverseauctionpayment' AND `type` = 'plugin' AND `name` = 'jereverseauction - paypal'";
	$db->setQuery( $query );
	$db->query();



	if(JFolder::exists(JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jereverseauctionwinner')){
		JFolder::delete(JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jereverseauctionwinner');
	}

	if (JFile::exists(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_system_jereverseauctionwinner.ini')) {
		JFile::delete(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_system_jereverseauctionwinner.ini');
	}

	if (JFile::exists(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_system_jereverseauctionwinner.sys.ini')) {
		JFile::delete(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_system_jereverseauctionwinner.sys.ini');
	}

	$query = "DELETE  FROM `#__extensions` WHERE `element`='jereverseauctionwinner' AND `folder` = 'system' AND `type` = 'plugin' AND `name` = 'plg_jereverseauctionwinner'";
	$db->setQuery( $query );
	$db->query();

	echo '<p> <b> <span style="color:#009933"> JE Reverse Auction Component has been Uninstalled successfully </span></b> </p>';
}
?>

