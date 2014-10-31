<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

class jereverseauctionEmailsystem
{
	function emailtoExpirewonproduct()
	{

		$params = JComponentHelper::getParams('com_jereverseauction');
		$edays  = $params->get('won_expire');
		$user   = JFactory::getUser();
		if($edays && $user->id) {
			$db		= JFactory::getDBO();
			$query  = "SELECT id,prod_id,ended FROM #__jereverseauction_wins WHERE published = 1 AND uid='".$user->id."'";
			$db->setQuery( $query );
			if ( !$db->Query() ) {
				//echo $db->getErrorMsg();
			}
			$allproducts = $db->loadObjectList();

			if($allproducts){
				foreach($allproducts as $allproduct) {
					 $date       =& JFactory::getDate($allproduct->ended).'<br>';
					 $expire_days="+".$edays." day";
					 $date->modify("$expire_days");
					 $expirydate = $date->format("Y-m-d H:i:s");
					 $now        = JFactory::getDate();

					 if($expirydate < $now) {
						jereverseauctionEmailsystem::blockExpiredproduct($allproduct->id,$allproduct->prod_id);
					 }
				}
			}
		}
	}

	function updatedate()
	{
			$db		= JFactory::getDBO();
			$query  = "SELECT start_time,id FROM #__jereverseauction_products WHERE status = 2 AND published = 1";
			$db->setQuery( $query );
			if ( !$db->Query() ) {
				//echo $db->getErrorMsg();
			}
			$allproducts = $db->loadObjectList();

			if($allproducts){
				foreach($allproducts as $allproduct) {
					 $date       =& JFactory::getDate($allproduct->start_time);
					 $startdate = $date->format("Y-m-d H:i:s");
					 $now        = JFactory::getDate();

					 if($startdate <= $now) {
						$query  = "UPDATE #__jereverseauction_products SET status = 0 WHERE id=$allproduct->id";
						$db->setQuery( $query );
						$db->Query();
					 }
				}
			}

	}

	function blockExpiredproduct($id,$prod_id) {

		$db		= & JFactory::getDBO();

		$query1  = "SELECT * FROM #__jereverseauction_wins WHERE published = 1 AND id = '".$id."'";
		$db->setQuery( $query1);
		$db->Query();
		$result = $db->loadObject();

		if(!$result){
			$query  = "UPDATE #__jereverseauction_wins SET published = 1 where id = '".$id."'";
			$db->setQuery( $query );
			if ( !$db->Query() ) {
				echo $db->getErrorMsg();
			}
			else {
				jereverseauctionEmailsystem::sendmail($id,$prod_id);
			}
		}
	}

	function sendmail($id,$prod_id) {

		$mainframe 		= & Jfactory::getApplication();
		$email      	= $mainframe->getCfg( 'mailfrom' );
		$site       	= $mainframe->getCfg( 'fromname' );

		$mail_template	= jereverseauctionEmailsystem::getmailtemplate(3);
		$ended			= jereverseauctionEmailsystem::getdata($prod_id);
		$user			= jereverseauctionEmailsystem::getuser_details($ended->user_id);
		$user_wins		= jereverseauctionEmailsystem::getwins($prod_id);

		$dec_id			= base64_encode($prod_id);
		$path			= JURI::Root();
		$link			= $path.'index.php?option=com_jereverseauction&view=mywins&id='.$dec_id;
		$link			= "<a href=".$link.">$link</a>";
		$find           = array ("[productname]","[name]","[sitename]","[productid]","[price]","[amount]","[link]","[customerid]","[email]");
		$replace        = array ("$ended->prod_name","$user->name","$site","$prod_id","$ended->prod_price","$user_wins->amount","$link","$user->id","$user->email");

		$body           = str_replace($find,$replace,$mail_template->mailbody);
		$subject        = str_replace($find,$replace,$mail_template->subject);
		$check 			= JUtility::sendMail($email,$site ,"$user->email",$subject, $body , true , null, null);
	}

	 function getmailtemplate($mail_id)
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

	 function getdata($prod_id)
	{
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$query->select('*');
		$query->from('#__jereverseauction_products');
		$query->where('id ='.$prod_id);
		$db->setQuery($query);
		$faq			= $db->loadObject();
		return $faq;
	}

	 function getuser_details($user_id)
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

	 function getwins($prod_id)
	{
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$query->select('*');
		$query->from('#__jereverseauction_wins');
		$query->where('prod_id ='.$prod_id);
		$db->setQuery($query);
		$faq			= $db->loadObject();
		return $faq;
	}
}
?>
