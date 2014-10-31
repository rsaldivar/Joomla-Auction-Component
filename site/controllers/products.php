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
class jereverseauctionControllerProducts extends jereverseauctionController
{

	public function &getModel($name = 'products', $prefix = '', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	public function bid()
	{
		$db 			= & JFactory::getDBO();
		$prod_id 		= JRequest::getVar('prod_id');
		$message	 	= JRequest::getVar('message');
		$bid_value 		= JRequest::getVar('bid_value');
		$post			= $this->getdata($prod_id);
		$user			= JFactory::getUser();
		$model			= $this->getModel('products');

			//VALIDACION DE QUE SEA MAYOR A LO PEDIDO
		if($bid_value <= $post->min_bid_amount ){
			echo JText::_('COM_JEREVERSE_AUCTION_LESS_BID_AMOUNT').' '.$post->min_bid_amount;
			exit;
		}

		//VALIDAR QUE SEA MAYOR A LA PUJA ANTERIOR
		//		$pro_details    = $this->latest_bidder($prod_id);
		// 	ASC
		$pro_details    = $this->latest_bidder($prod_id);
		if($bid_value < $pro_details->amount ){
			echo JText::_('COM_JEREVERSE_AUCTION_LESS_BID_AMOUNT').' '. $pro_details->amount ;
			exit;
		}

		echo 		"<input type=\"hidden\" id=\"amount\" value=\"$bid_value\">";
		echo 		"<input type=\"hidden\" id=\"uname\" value=\"$user->name\">";

			$query	= 'UPDATE '.$db->nameQuote('#__jereverseauction_products').'
			SET '.$db->nameQuote('prod_amount').' = '.$bid_value.','.$db->nameQuote('user_name').' = "'.$user->name.'" WHERE id = '.$post->id;
			$db->setQuery( $query );
			$db->query();

			$query2	= "INSERT INTO ".$db->nameQuote('#__jereverseauction_probidding')." VALUES ('','$user->id','$bid_value','$user->name','$post->id','$message')";
			$db->setQuery( $query2 );
			$db->query();

		exit;
	}

	public function getdata($prod_id)
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

	public function latest_bidder($prod_id)
	{
		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$query->select('*');
		$query->from('#__jereverseauction_probidding');
		$query->where('prod_id ='.$prod_id);
		$query->order('id DESC');
		
		//$query->order('amount DESC');
		//$query->order('amount ASC');

		$db->setQuery($query);
		$faq			= $db->loadObject();
		return $faq;
	}

	function updateStatus()
	{
		$prod_id 	= JRequest::getVar('prod_id');
		$user 		= & JFactory::getUser();
		$db			= & JFactory::getDBO();
		$post		= JRequest::get( 'post' );
		$query		= 'UPDATE '.$db->nameQuote('#__jereverseauction_products').'SET '.$db->nameQuote('status').' = "1" WHERE id = '.$prod_id;
		$db->setQuery( $query );
		$db->query();

		$pro_details    = $this->latest_bidder($prod_id);
		$ended		    = $this->getdata($prod_id);
		$win_list	    = $this->getwins($prod_id);

		if($pro_details){
			if(!$win_list){
				$wins 			= new stdClass();
				$wins->uid 		= $pro_details->user_id;
				$wins->prod_id	= $prod_id;
				$wins->amount	= $pro_details->amount;
				$wins->ended	= "$ended->end_time";
				$userstored 	= $db->insertObject('#__jereverseauction_wins', $wins);

				if($userstored){
					$this->winner_mail($prod_id);
				}
			}else{

			}
		}
		exit;
	}

	function winner_mail($prod_id)
	{
		$mainframe 		= & Jfactory::getApplication();
		$email      	= $mainframe->getCfg( 'mailfrom' );
		$site       	= $mainframe->getCfg( 'fromname' );

		$ended			= $this->getdata($prod_id);
		$user_wins		= $this->getwins($prod_id);
		$user_id		= $user_wins->uid;

		/*winner details */
		$user			= $this->getuser_details($user_id);
		$user_email 	= $user->email;

		/* owner details */
		$owner			= $this->getuser_details($ended->user_id);

		$mail_template	= $this->getmailtemplate(1);

		$dec_id			= base64_encode($prod_id);
		$path			= JURI::Base();
		$link			= $path.'index.php?option=com_jereverseauction&view=mywins&id='.$dec_id;
		$find           = array ("[productname]","[name]","[sitename]","[productid]","[amount]","[link]","[customerid]","[email]","[ownerid]","[owneremail]","[ownername]");
		$replace        = array ("$ended->prod_name","$user->name","$site","$prod_id","$user_wins->amount","$link","$user_id","$user_email","$owner->id","$owner->email","$owner->name");

		$body           = str_replace($find,$replace,$mail_template->mailbody);
		$subject        = str_replace($find,$replace,$mail_template->subject);

		$check 			= JUtility::sendMail($email,$site ,"$user_email",$subject, $body , true , null, null);

		//mail to owner of the product.

		$mail_template1	= $this->getmailtemplate(4);

		$find1          = array ("[productname]","[winnername]","[sitename]","[productid]","[amount]","[link]","[winnerid]","[winneremail]","[customerid]","[email]","[name]");
		$replace1        = array ("$ended->prod_name","$user->name","$site","$prod_id","$user_wins->amount","$link","$user_id","$user_email","$owner->id","$owner->email","$owner->name");

		$body1           = str_replace($find1,$replace1,$mail_template1->mailbody);
		$subject1        = str_replace($find1,$replace1,$mail_template1->subject);

		$mail =& JFactory::getMailer();
		$mail->setSender($site);
		$mail->addRecipient($owner->email);
		$mail->addCC( $email );
		$mail->setSubject($subject1);
		$mail->setBody( $body1);
		$mail->IsHTML('1');
		$mail->Send();

	}

	public function getwins($prod_id)
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

	public function checkamount()
	{
		$prod_id  = JRequest::get('prod_id');
		$amount   = JRequest::getVar('amount');
		$return_val = array();
		$prod_ids = $prod_id['prod_id'];

		$db			= JFactory::getDBO();
		$query		= $db->getQuery(true);
		$query->select('id,end_time,prod_amount,user_name');
		$query->from('#__jereverseauction_products');
		$query->where('id IN ('.$prod_ids.')');
		$db->setQuery($query);
		$faq			= $db->loadAssocList();

		for($i=0 ; $i < count($faq);$i++ ){
			$return_val[] = implode(',' , $faq[$i]);
		}

		$new_return = implode('+' , $return_val);

		$model 		= & $this->getModel();
	
		echo $new_return;
		exit;

	}
	
	public function contactuser(){
		$post   = JRequest::get('post');
		$mail =& JFactory::getMailer();
		$mail->setSender($post['fromemail']);
		$mail->addRecipient($post['tomail']);
		$mail->setSubject($post['subject']);
		$mail->setBody( $post['message']);
		$mail->IsHTML('1');
		if( $mail->Send()){
			echo JText::_('COM_JEREVERSE_AUCTION_MESSAGE_SUCCESS');
		}else{
			echo JText::_('COM_JEREVERSE_AUCTION_MESSAGE_FAILED');
		}
		exit;
	}


}
?>
