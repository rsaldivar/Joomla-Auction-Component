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

/** Import library dependencies */
jimport('joomla.event.plugin');
$lang = & JFactory::getLanguage();
$lang->load('plg_jereverseauctionPaypal',JPATH_ROOT);
class plgjereverseauctionpaymentjereverseauctionPaypal extends JPlugin
{
	function plgjereverseauctionpaymentjereverseauctionPaypal( &$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage('plg_system_jereverseauctionpaypal');

		$params["plugin_name"] = "jereverseauctionpaypal";
		$params["icon"] = "paypal_icon.png";
		$params["logo"] = "paypal_overview.png";
		$params["description"] = JText::_("PAYMENT_METHOD_DESC");
		$params["payment_method"] = JText::_("PAYMENT_METHOD_NAME");
		$params["testmode"] = $this->params->get("test");
		$params["currency_code"] = $this->params->get("currency_code");
		$params["email_id"] = $this->params->get("email_id");
		$this->params = $params;

	}
	function onProcessPayment()
	{
		$ptype = JRequest::getVar('ptype','');
		$view = JRequest::getVar('view');
		$id = JRequest::getInt('id','');
		$html="";

		if($ptype == $this->params["plugin_name"])
		{
			$action = JRequest::getVar('pactiontype','');

			switch ($action)
			{
				case "process" :
					$html = $this->process($id,$view,$ptype);
				break;
				case "notify" :
					$html = $this->_notify_url();
				break;
				case "paymentmessage" :
					$html = $this->_paymentsuccess();
				break;
				default :
					$html =  $this->process($id,$view,$ptype);
				break;
			}
		}

		return $html;
	}
	function _notify_url()
	{
		$db 			= JFactory::getDBO();
		$account_type	= $this->params["testmode"];
		$user			= JFactory::getUser();
		$user_id		= JRequest::getInt('userid','0');
		$paypal_info 	= $_POST;
		$paypal_ipn 	= new paypal_ipn($paypal_info);

		foreach ($paypal_ipn->paypal_post_vars as $key=>$value)
		{
			if (getType($key)=="string")
			{
				eval("\$$key=\$value;");
			}
		}

		$paypal_ipn->send_response($account_type);
		if (!$paypal_ipn->is_verified())
		{
			die();
		}

		$paymentstatus=0;
		switch($paypal_ipn->get_payment_status())
		{
			case 'Pending':
				$msg 	= "Success";
				$id		= $paypal_ipn->paypal_post_vars['custom'];
				$txn_id	= $paypal_ipn->paypal_post_vars['txn_id'];
				$ptype	= JRequest::getVar('ptype');
				$prodid = JRequest::getVar('id');
				$view	= JRequest::getVar('view');
				$pdate 	= gmdate("Y-m-d H:i:s");
				$user	= JFactory::getUser();

				$query = 'UPDATE #__jereverseauction_commission SET status=1, trans_id="'.$txn_id.'", paid_date="'.$pdate.'" WHERE id='.$prodid;
				$db->setQuery($query);
				$db->query();
						
				$sql 	="SELECT prod_id FROM #__jereverseauction_commission WHERE id=".$prodid;
				$db->setQuery($sql);
				$id_prod= $db->loadResult();
				
				$query2 = 'UPDATE #__jereverseauction_products SET published=1, commission_paid=1 WHERE id='.$id_prod;
				$db->setQuery($query2);

				if ($error = $db->getErrorMsg()) {
					throw new Exception($error);
				}

				$db->query();
			break;

			case 'Completed':
				$msg 	= "Success";
				$id		= $paypal_ipn->paypal_post_vars['custom'];
				$txn_id	= $paypal_ipn->paypal_post_vars['txn_id'];
				$ptype	= JRequest::getVar('ptype');
				$prodid = JRequest::getVar('id');
				$view	= JRequest::getVar('view');
				$pdate 	= gmdate("Y-m-d H:i:s");
				$user	= JFactory::getUser();

				$query = 'UPDATE #__jereverseauction_commission SET status=1, trans_id="'.$txn_id.'", paid_date="'.$pdate.'" WHERE id='.$prodid;
				$db->setQuery($query);
				$db->query();
						
				$sql 	="SELECT prod_id FROM #__jereverseauction_commission WHERE id=".$prodid;
				$db->setQuery($sql);
				$id_prod= $db->loadResult();
				
				$query2 = 'UPDATE #__jereverseauction_products SET published=1, commission_paid=1 WHERE id='.$id_prod;
				$db->setQuery($query2);

				if ($error = $db->getErrorMsg()) {
					throw new Exception($error);
				}

				$db->query();
			break;

			case 'Failed':
				$msg = "Failed Payment";
			break;

			case 'Denied':
				$msg = "Denied Payment";
			break;

			case 'Refunded':
				$msg = "Refunded Payment";
			break;

			case 'Canceled':
				$msg = "Cancelled reversal";
			break;

			default:
				$msg = "Unknown Payment Status";
			break;
		}
	}

	function _paymentsuccess(){

	}

	function process($id,$view,$ptype)
	{
		$db 		= &JFactory::getDBO();
		$app 		= JFactory::getApplication();
		$Itemid 	= JRequest::getInt("Itemid",'0');

		$user		=& JFactory::getUser();
		if ( $user->get('guest')) {
			$url 	= base64_encode(JRoute::_('index.php?option=com_jereverseauction&view=products'));
			$link 	= "index.php?option=com_users&view=login&return=".$url;
			$app->redirect( $link);
		}

		$sql 	="SELECT amount FROM #__jereverseauction_commission WHERE id=".$id;
		$db->setQuery($sql);
		$row 	= $db->loadResult();

		$urlpaypal="";

		?><link rel="stylesheet" type="text/css" href="<?php echo JURI::root()."components/com_jereverseauction/assets/css/style_pay.css"; ?>">
		<div id="loading-effect">
			<div id="pbar" ></div>
		</div>
		<script type = "text/javascript" src="<?php echo JURI::root()."components/com_jereverseauction/assets/js/progressbar.js"; ?>" ></script>
		<?php


		if ($this->params["testmode"]=="1")
		{
			$urlpaypal="https://www.sandbox.paypal.com/cgi-bin/webscr";
		}
		elseif ($this->params["testmode"]=="0")
		{
			$urlpaypal="https://www.paypal.com/cgi-bin/webscr";
		}
		$form ='<form id="paypalform" action="'.$urlpaypal.'" method="post">';
		$form .='<input type="hidden" name="cmd" value="_xclick">';
		$form .='<input id="custom" type="hidden" name="custom" value="1234567">';
		$form .='<input type="hidden" name="business" value="'.$this->params["email_id"].'">';
		$form .='<input type="hidden" name="currency_code" value="'.$this->params["currency_code"].'">';
		$form .='<input type="hidden" name="item_name" value="Commission Payment">';
		$form .='<input type="hidden" name="amount" value="'.$row.'">';
		$form .='<input type="hidden" name="cancel_return" value="'.JRoute::_(JURI::root().'index.php?option=com_jereverseauction&view='.$view.'&task='.$view.'.paymentDetails&action=showresult&ptype='.$this->params["plugin_name"].'&id='.$row.'&Itemid='.$Itemid).'">';
		$form .='<input type="hidden" name="notify_url" value="'.JRoute::_(JURI::root().'index.php?option=com_jereverseauction&view='.$view.'&task='.$view.'.processNotify&ptype='.$this->params["plugin_name"].'&pactiontype=notify&userid='.$user->id.'&id='.$id.'&Itemid='.$Itemid).'">';
		$form .='<input type="hidden" name="return" value="'.JRoute::_(JURI::root().'index.php?option=com_jereverseauction&view='.$view.'&task='.$view.'.processPaymentmail&ptype='.$this->params["plugin_name"].'&pactiontype=paymentmessage&ptype='.$this->params["plugin_name"].'&Itemid='.$Itemid.'&id='.$id).'">';
		$form .='</form>';
		echo $form;

	?>
		<script type="text/javascript">
			callpayment()
			function callpayment(){
				var id = document.getElementById('custom').value ;
				if ( id > 0 && id != '' ) {
					document.getElementById('paypalform').submit()
				}
			}
		</script>
	<?php
	}

	function onPaymentMethodList($val)
	{

		$bus_id= $this->params["email_id"];
		$paymentLogoPath = JURI::root()."plugins/jereverseauctionpayment/".$this->params["plugin_name"]."/".$this->params["plugin_name"]."/images/".$this->params["logo"];

		if(!empty($bus_id)){
			$form_action = JRoute :: _("index.php?option=com_jereverseauction&view=".$val['view']."&task=".$val['view'].".processPayment&ptype=".$this->params["plugin_name"]."&pactiontype=process&id=".$val["id"], false);
		}else{
			$form_action = JRoute :: _("index.php?option=com_jereverseauction&view=".$val['view']."&layout=default_shipping&task=".$val['view'].".processPayment&ptype=".$this->params["plugin_name"]."&pactiontype=process&id=".$val["id"], false);
			echo  "<div id=\"error\" name=\"error\">Admin Didn't Provide Payment Details</div>";
		}

		$html ='<table cellpadding="5" cellspacing="0" width="100%" border="0">
			<tr>';
				if($this->params["logo"] != ""){
			$html .='<td width="160" align="center">
					<img src="'.$paymentLogoPath.'" title="'. $this->params["payment_method"].'"/>
				</td>';
				 }
				$html .='<td>
					<p style="text-align:justify;">'.$this->params["description"].'</p>
				</td>
				<td width="100" align="center">
					<a  style="text-decoration:none;" href="'.$form_action.'"><img border="0" src="'.JURI::root().'components/com_jereverseauction/assets/images/buynow.png" width="89" height="28" ></a>
				</td>
			</tr>
		</table>';

		return $html;
	}
}
class paypal_ipn
{
	var $paypal_post_vars;
	var $paypal_response;
	var $timeout;
	var $error_email;
	function paypal_ipn($paypal_post_vars) {
		$this->paypal_post_vars = $paypal_post_vars;
		$this->timeout = 120;
	}
	function send_response($account_type)
	{

		$fp  = '';
		if($account_type == '1')
		{
			$fp = @fsockopen( "ssl://www.sandbox.paypal.com", "443", $errno, $errstr, 30 );
		}else if($account_type == '0')
		{
			$fp = @fsockopen( "ssl://www.paypal.com", "443", $errno, $errstr, 30 );
		}
		if (!$fp) {
			$this->error_out("PHP fsockopen() error: " . $errstr , "");
		} else {
			foreach($this->paypal_post_vars AS $key => $value) {
				if (@get_magic_quotes_gpc()) {
					$value = stripslashes($value);
				}
				$values[] = "$key" . "=" . urlencode($value);
			}
			$response = @implode("&", $values);
			$response .= "&cmd=_notify-validate";
			fputs( $fp, "POST /cgi-bin/webscr HTTP/1.0\r\n" );
			fputs( $fp, "Content-type: application/x-www-form-urlencoded\r\n" );
			fputs( $fp, "Content-length: " . strlen($response) . "\r\n\n" );
			fputs( $fp, "$response\n\r" );
			fputs( $fp, "\r\n" );
			$this->send_time = time();
			$this->paypal_response = "";

			while (!feof($fp)) {
				$this->paypal_response .= fgets( $fp, 1024 );

				if ($this->send_time < time() - $this->timeout) {
					$this->error_out("Timed out waiting for a response from PayPal. ($this->timeout seconds)" , "");
				}
			}
			fclose( $fp );
		}
	}
	function is_verified() {
		if( ereg("VERIFIED", $this->paypal_response) )
			return true;
		else
			return false;
	}
	function get_payment_status() {
		return $this->paypal_post_vars['payment_status'];
	}
	function error_out($message)
	{

	}
}
?>