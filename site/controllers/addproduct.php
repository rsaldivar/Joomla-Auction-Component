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

class jereverseauctionControllerAddproduct extends JControllerForm
{
	protected $view_item 		= 'addproduct';
	protected $view_list		= 'addproduct';

	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$user				= JFactory::getUser();

		$allow					= null;

		return parent::allowAdd();
	}

	protected function allowEdit($data = array(), $key = 'prod_id')
	{

		// Initialise variables.
			$recordId			= (int) isset($data[$key]) ? $data[$key] : 0;
			$user				= JFactory::getUser();
			$userId				= $user->get('id');
			$asset				= 'com_jereverseauction.products.'.$recordId;

		// Check general edit permission first.
		if ($user->authorise('core.edit', $asset)) {
			return true;
		}

		// Fallback on edit.own.
		// First test if the permission is available.
			if ($user->authorise('core.edit.own', $asset)) {
				// Now test the owner is the user.
				$ownerId		= (int) isset($data['posted_by']) ? $data['posted_by'] : 0;
				if (empty($ownerId) && $recordId) {
					// Need to do a lookup from the model.
					$record		= $this->getModel()->getItem($recordId);

					if (empty($record)) {
						return false;
					}

					$ownerId	= $record->posted_by;
				}

				// If the owner matches 'me' then do the test.
				if ($ownerId == $userId) {
					return true;
				}
			}

		// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
	}

	/**
	 * Method to edit an existing record.
	 */
	public function edit($key = null, $urlVar = 'a_id')
	{
		$result					= parent::edit($key, $urlVar);

		return $result;
	}

	public function save($key = null, $urlVar = 'id')
	{
		$return		=  parent::save($key, $urlVar);
		$id			= $this->lastinsertid();

		if($return) {
			$this->setRedirect('index.php?option=com_jereverseauction&view=addproduct&layout=default_shipping&prod_id='.$id);
		} else {
			$this->setRedirect('index.php?option=com_jereverseauction&view=addproduct');
		}
	}

	public function lastinsertid()
	{
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$user 		= JFactory::getUser();
		$query_no 	= "SELECT max(id) FROM #__jereverseauction_products WHERE user_id=".$user->id;
		$db->setQuery($query_no);
		$db->query();
		$nobids 	= $db->loadResult();

		//insert data into commission table
		$app					= JFactory::getApplication();
		$params					= $app->getParams();
		$amount_c 				= $params->get("commission","10");
		$comm 					= new stdClass();
		$comm->user_id 			= $user->id;
		$comm->prod_id			= $nobids;
		$comm->amount			= $amount_c;
		$comm->payment_type		= "Paypal";
		$comm->status			= 0;
		$userstored 			= $db->insertObject('#__jereverseauction_commission', $comm);
		$last_id				= $db->insertid();

		return $last_id;
	}

	//payment
	public function paymentAuction()
	{
		$id = JRequest::getVar("bid_id","");
		JRequest::setVar("view","addproduct");
		JRequest::setVar("layout","default_shipping");
		JRequest::setVar("buy_id",$id);
		parent::display();
	}

	function processPayment()
	{		
		JRequest::setVar( 'view', 'addproduct' );
		JRequest::setVar( 'layout', 'default_process' );
		parent::display();
	}
	
	function processNotify()
	{		
		$pactiontype	= JRequest::getVar('pactiontype');
		
		JPluginHelper::importPlugin("jereverseauctionpayment");
		$dispatcher =& JDispatcher::getInstance();
		$results = $dispatcher->trigger( 'onProcessPayment');
		$text = trim(implode("\n", $results));

		parent::display();
	}

	function paymentDetails()
	{
		$id = JRequest::getInt("id","");
		JRequest::setVar("view","addproduct");
		JRequest::setVar("layout","default_shipping");
		JRequest::setVar("id",$id);
		parent::display();
	}

	function processPaymentmail()
	{
		$mainframe  = & Jfactory::getApplication();
		$email      = $mainframe->getCfg( 'mailfrom' );
		$site       = $mainframe->getCfg( 'fromname' );

		$prod_id	= JRequest::getVar('id');
		$commission	= $this->getdata($prod_id);
		$user_id	= $commission->user_id;
		$user		= $this->getuser_details($user_id);
		$user_email = $user->email;

		$mail_template	= $this->getmailtemplate(5);

		if($commission->status == 0){
			$status ="Pending";
		}else{
			$status ="Completed";
		}

		$price			= $commission->min_bid_amount." to ".$commission->max_bid_amount;

		$dec_id			= base64_encode($prod_id);
		$path			= JURI::Base();
		$link			= $path.'index.php?option=com_jereverseauction&view=products&layout=default_detail&id='.$dec_id;
		$find           = array ("[name]","[sitename]","[productid]","[productname]","[price]","[status]","[paymethod]","[trans_id]","[customerid]","[email]","[total]","[paymethod]");
		$replace        = array ("$user->name","$site","$commission->prod_id","$commission->prod_name","$price","$status","$commission->payment_type","$commission->trans_id","$user_id","$user_email","$commission->amount","$commission->payment_type");

		$body           = str_replace($find,$replace,$mail_template->mailbody);
		$subject        = str_replace($find,$replace,$mail_template->subject);

		$check 			= JUtility::sendMail($email,$site ,"$user->email",$subject, $body , true,$email, null);

		$this->setRedirect('index.php?option=com_jereverseauction&view=products');
	}

	public function getdata($prod_id)
	{
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$query->select('com.*');
		$query->from('#__jereverseauction_commission AS com');
		$query->where('com.id ='.$prod_id);
		$query->select( 'pro.prod_name, pro.max_bid_amount , pro.min_bid_amount' );
		$query->join( 'LEFT', '#__jereverseauction_products AS pro ON pro.id = com.prod_id' );
		$db->setQuery($query);
		$faq			= $db->loadObject();

		return $faq;
	}

	public function getuser_details($user_id)
	{
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$query->select('*');
		$query->from('#__users');
		$query->where('id ='.$user_id);
		$db->setQuery($query);
		$user			= $db->loadObject();
		return $user;
	}

	public function getmailtemplate($mail_id)
	{
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$query->select('*');
		$query->from('#__jereverseauction_mailtemplates');
		$query->where('id ='.$mail_id);
		$db->setQuery($query);
		$user			= $db->loadObject();
		return $user;
	}


}
?>
