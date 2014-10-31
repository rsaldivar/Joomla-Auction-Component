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

require_once JPATH_COMPONENT.'/controller.php';

/**
 * products controller class for auction.
 */
class auctionControllerMywins extends auctionController
{

	public function &getModel($name = 'mywins', $prefix = '', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	public function paymentAuction()
	{
		$id = JRequest::getVar("a_id","");
		JRequest::setVar("view","mywins");
		JRequest::setVar("layout","default_shipping");
		JRequest::setVar("buy_id",$id);
		parent::display();
	}

	function processPayment()
	{
		JRequest::setVar( 'view', 'mywins' );
		JRequest::setVar( 'layout', 'default_process' );
		parent::display();
	}

	function paymentDetails()
	{
		$id = JRequest::getInt("a_id","");
		JRequest::setVar("view","mywins");
		JRequest::setVar("layout","default_shipping");
		JRequest::setVar("buy_id",$id);
		parent::display();
	}

}
?>
