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

function com_install()
{
		$status			=  true;
	// Joomla predefiend function to connect db
		$db			= & JFactory::getDBO();
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		$query					= $db->getQuery(true);
		$query->select('count(id)');
		$query->from('#__jereverseauction_mailtemplates');
		$db->setQuery($query);
		$total_rows				= $db->loadResult();

		if(!$total_rows) {
		$ins_query = "INSERT INTO `#__jereverseauction_mailtemplates` (`id`, `subject`, `mailbody`, `published`) VALUES
(1, 'You won [productname] ', '<p>Hi [name],<br />Thank you very much for being a customer in our [sitename]. Now you have won [productname] ,Details of the product is as follows:<br /><br /><strong>Auction Details :</strong><br />Auction ID : [productid]<br />Auction Name: [productname]<br />Your Pay Only : [amount]<br />View Product: [link]<br /><br /><br /><strong>Owner Details :</strong><br />Customer ID : [ownerid]<br />Name : [ownername]<br />Email : [owneremail]</p>\r\n<p><br /><strong>Your Details :</strong><br />Customer ID : [customerid]<br />Name : [name]<br />Email : [email]<br /><br />Thanks,<br />[sitename]</p>', 1),
(2, 'Thank you for uploading product', '<p>Hi [name],<br />Thank you very much for being a customer in our [sitename].And also thank you for uploading [productname] ,Details of the product is as follows:<br /><br /><strong>Product Details :</strong><br />Product ID : [productid]<br />Product Name: [productname]<br />Market Price : [price]<br />View Product: [link]<br /><br /><br /><strong>User Details :</strong><br />Customer ID : [customerid]<br />Name : [name]<br />Email : [email]<br /><br />Thanks,<br />[sitename]</p>', 1),
(3, 'You won [productname] ,But it was expired', '<p>Hi [name],<br />Thank you very much for being a customer in our [sitename]. Now you have won [productname] , But it was expired So now you have lose a chance to get this product , Details of the product is as follows:<br /><br /><strong>Product Details :</strong><br />Product ID : [productid]<br />Product Name: [productname]<br />Market Price : [price]<br />Your Pay Only : [amount]<br />View Product: [link]<br /><br /><br /><strong>User Details :</strong><br />Customer ID : [customerid]<br />Name : [name]<br />Email : [email]<br /><br />Thanks,<br />[sitename]</p>', 1),
(4, '[winnername] You won [productname] ', '<p>Hi [name],<br />Thank you very much for being a customer in our [sitename]. [winnername]  won [productname] ,Details of the product is as follows:<br /><br /><strong>Auction Details :</strong><br />Auction ID : [productid]<br />Auction Name: [productname]<br />Your Pay Only : [amount]<br />View Product: [link]<br /><br /><br /><strong>Winner Details :</strong><br />Customer ID : [winnerid]<br />Name : [winnername]<br />Email : [winneremail]</p>\r\n<p><br /><strong>Your Details :</strong><br />Customer ID : [customerid]<br />Name : [name]<br />Email : [email]<br /><br />Thanks,<br />[sitename]</p>', 1),
(5, 'Thank you for paid the commission', '<p>Hi [name],<br />Thank you very much for being a customer in our [sitename]. Thank you for paid the commission for [productname],Details of you product is as follows:<br /><br /><strong>Product Details :</strong><br />Product ID : [productid]<br />Product Name: [productname]<br />Price : [price]</p>\r\n<p><strong>Commission Details :</strong><br /> Total Amount : [total]<br />Payment Status : [status]<br />Payment Method: [paymethod]<br />Transaction ID : [trans_id]</p>\r\n<p><strong>User Details :</strong><br />Customer ID : [customerid]<br />Name : [name]<br />Email : [email]<br /><br />Thanks,<br />[sitename]</p>', 1);";

		$db->setQuery($ins_query);
		if(!$db->query()) {
			$status			= false;
		}
		
}		
		// edit the fields
		$query2 		= "SELECT `message` FROM #__jereverseauction_probidding";
		$db->setQuery( $query2 );

		// Check whether this field name is already there.
		if(!$db->query()) {
			$query3 ="ALTER TABLE `#__jereverseauction_probidding` ADD `message` TEXT NOT NULL AFTER `prod_id`";
			$db->setQuery( $query3 );
			$db->query();
		}
		
		if(!JFolder::exists(JPATH_ROOT.DS.'plugins'.DS.'jereverseauctionpayment')) 
		{		
			JFolder::move(JPATH_ROOT.DS.'components'.DS.'com_jereverseauction'.DS.'plg_jereverseauctionpayment'.DS.'jereverseauctionpayment', JPATH_ROOT.DS.'plugins'.DS.'jereverseauctionpayment');
	
		$query = "INSERT INTO `#__extensions` (`extension_id` ,`name` ,`type` ,`element` ,`folder` ,`client_id` ,`enabled` ,`access` ,`protected` ,`manifest_cache` ,`params` ,`custom_data` ,`system_data` ,`checked_out` ,`checked_out_time` ,`ordering` ,`state`)VALUES (NULL , 'jereverseAuction - Paypal', 'plugin', 'jereverseauctionpaypal', 'jereverseauctionpayment', '0', '1', '1', '0', '{\"legacy\":true,\"name\":\"jereverseauction Paypal payment\",\"type\":\"plugin\",\"creationDate\":\"May 26, 2011\",\"author\":\"JExtension\",\"copyright\":\"[2011] JExtn\",\"authorEmail\":\"contact@jextn.com\",\"authorUrl\":\"http://www.jextn.com\",\"version\":\"1.0.0 Stable\",\"description\":\"jereverseauction Paypal payment\",\"group\":\"\"}', '', '', '', '0', '0000-00-00 00:00:00', '0', '0'),(NULL , 'jereverseauction-Authorizedotnet', 'plugin', 'jereverseauctionAuthorizedotnet', 'jereverseauctionpayment', '0', '1', '1', '0', '{\"legacy\":true,\"name\":\"JE Reverse Auction-Authorizedotnet payment\",\"type\":\"plugin\",\"creationDate\":\"May 26, 2011\",\"author\":\"JExtension\",\"copyright\":\"[2011] JExtn\",\"authorEmail\":\"contact@jextn.com\",\"authorUrl\":\"http://www.jextn.com\",\"version\":\"1.0.0 Stable\",\"description\":\"JE Reverse Auction-Authorizedotnet payment\",\"group\":\"\"}', '', '', '', '0', '0000-00-00 00:00:00', '0', '0')";
		
			$db->setQuery( $query );
			$db->query();	
		}else{
			if(!JFolder::exists(JPATH_ROOT.DS.'plugins'.DS.'jereverseauctionpayment'.DS.'jereverseauctionAuthorizedotnet')){
				JFolder::delete(JPATH_ROOT.DS.'plugins'.DS.'jereverseauctionpayment');
				JFolder::move(JPATH_ROOT.DS.'components'.DS.'com_jereverseauction'.DS.'plg_jereverseauctionpayment'.DS.'jereverseauctionpayment', JPATH_ROOT.DS.'plugins'.DS.'jereverseauctionpayment');
				$query = "INSERT INTO `#__extensions` (`extension_id` ,`name` ,`type` ,`element` ,`folder` ,`client_id` ,`enabled` ,`access` ,`protected` ,`manifest_cache` ,`params` ,`custom_data` ,`system_data` ,`checked_out` ,`checked_out_time` ,`ordering` ,`state`)VALUES (NULL , 'jereverseauction-Authorizedotnet', 'plugin', 'jereverseauctionAuthorizedotnet', 'jereverseauctionpayment', '0', '1', '1', '0', '{\"legacy\":true,\"name\":\"JE Reverse Auction-Authorizedotnet payment\",\"type\":\"plugin\",\"creationDate\":\"May 26, 2011\",\"author\":\"JExtension\",\"copyright\":\"[2011] JExtn\",\"authorEmail\":\"contact@jextn.com\",\"authorUrl\":\"http://www.jextn.com\",\"version\":\"1.0.0 Stable\",\"description\":\"JE Reverse Auction-Authorizedotnet payment\",\"group\":\"\"}', '', '', '', '0', '0000-00-00 00:00:00', '0', '0')";
			
				$db->setQuery( $query );
				$db->query();
			}
		}
				
		if(JFolder::exists(JPATH_ROOT.DS.'components'.DS.'com_jereverseauction'.DS.'plg_jereverseauctionpayment')) {
			JFolder::delete(JPATH_ROOT.DS.'components'.DS.'com_jereverseauction'.DS.'plg_jereverseauctionpayment');
		}
		
		// Install jereverse Auction System plugin for winner expire
		if(!JFolder::exists(JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jereverseauctionwinner')){
			if (!JFile::exists(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_system_jereverseauctionwinner.ini')) {
				JFile::move(JPATH_ROOT.DS.'components'.DS.'com_jereverseauction'.DS.'plg_jereverseauctionwinner'.DS.'jereverseauctionwinner'.DS.'en-GB.plg_system_jereverseauctionwinner.ini', JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_system_jereverseauctionwinner.ini');
			}

			if (!JFile::exists(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_system_jereverseauctionwinner.sys.ini')) {
				JFile::move(JPATH_ROOT.DS.'components'.DS.'com_jereverseauction'.DS.'plg_jereverseauctionwinner'.DS.'jereverseauctionwinner'.DS.'en-GB.plg_system_jereverseauctionwinner.sys.ini', JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_system_jereverseauctionwinner.sys.ini');
			}

			JFile::move(JPATH_ROOT.DS.'components'.DS.'com_jereverseauction'.DS.'plg_jereverseauctionwinner'.DS.'jereverseauctionwinner', JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jereverseauctionwinner');

			if( JFile::exists(JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jereverseauctionwinner'.DS.'jereverseauctionwinner.php')
			    && JFile::exists(JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jereverseauctionwinner'.DS.'jereverseauctionwinner.xml')) {

					$plg_renewal					= new stdClass();

					$plg_renewal->name				= 'plg_jereverseauctionwinner';
					$plg_renewal->type				= 'plugin';
					$plg_renewal->element			= 'jereverseauctionwinner';
					$plg_renewal->folder			= 'system';
					$plg_renewal->enabled			= '1';
					$plg_renewal->access			= '1';
					$plg_renewal->manifest_cache	= '{"legacy":false,"name":"plg_jereverseauctionwinner","type":"plugin","creationDate":"March 2012","author":"JExtension","copyright":"Copyright (C) 2012 - 2013 Open Source Matters. All rights reserved.","authorEmail":"contact@jextn.com","authorUrl":"www.jextn.com","version":"1.0.0","description":"PLG_JEREVERSE_AUCTION_WINNER_XML_DESCRIPTION","group":""}';
					$plg_renewal->params			= '{"email_days":"5"}';

					$plg_renewal_stored				= $db->insertObject('#__extensions', $plg_renewal);
					if (!$plg_renewal_stored) {
						JError::raiseWarning('', JText::_('COM_JEREVERSE_AUCTION_WINNERPLUGIN_NOTINSTALLED'));
						$status					=  false;
					}

				JFolder::delete(JPATH_ROOT.DS.'components'.DS.'com_jereverseauction'.DS.'plg_jereverseauctionwinner');
			}
		}

	if($status == true)
		echo '<p> <b> <span style="color:#009933"> jereverseauction Component has been installed successfully.</span></b> </p>';
}
?>

