<?php
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
/** Import library dependencies */
jimport('joomla.event.plugin');

class plgjereverseauctionpaymentjereverseauctionAuthorizedotnet extends JPlugin
{
	function plgjereverseauctionpaymentjereverseauctionAuthorizedotnet( &$subject, $config)
	{
		parent::__construct($subject, $config);
		$lang = & JFactory::getLanguage();
		$lang->load('plg_system_jereverseauctionAuthorizedotnet',JPATH_ADMINISTRATOR);
		$params["plugin_name"]  	= "jereverseauctionAuthorizedotnet";
		$params["icon"] 			= "";
		$params["logo"] 			= "authorized.jpg";
		$params["description"] 		= JText::_("PAYMENT_METHOD_DESC");
		$params["payment_method"] 	= JText::_("PAYMENT_METHOD_NAME");

		$params["login_id"] 		= $this->params->get("login_id", "");
		$params["transaction_key"]  = $this->params->get("transaction_key", "");

		$params["currency_code"] 	= $this->params->get('currency_code', "USD");
		$params["account_type"] 	= $this->params->get('account_type', "test");

		$logid						= $params["login_id"];
		$trakey						= $params["transaction_key"];
		$this->params 				= $params;

	}
	function onProcessPayment()
	{
		$ptype 		= JRequest::getVar('ptype','');
		$id 		= JRequest::getInt('id','');
		$html		= "";
		if($ptype == $this->params["plugin_name"])
		{
			$action = JRequest::getVar('pactiontype','');
			switch ($action)
			{
				case "process" :
				$html = $this->process($id);
				break;
				case "notify" :
				$html = $this->_notify_url();
				break;
				case "paymentmessage" :
				$html = $this->_paymentsuccess();
				break;
				default :
				$html =  $this->process($id);
				break;
			}
		}
		return $html;
	}
	function _notify_url()
	{
		$db 			= JFactory::getDBO();
		$app 			= JFactory::getApplication();
		$account_type	= $this->params["account_type"];
		$login_id 		= $this->params["login_id"];
		$transaction_key= $this->params["transaction_key"];
		$currency 		= $this->params["currency_code"];
		$Itemid 		= JRequest::getInt("Itemid",'0');
		$user			= JFactory::getUser();
		$view			= JRequest::getVar('view');
		$ptype			= JRequest::getVar('ptype');
		$id				= JRequest::getVar('id');

		$sql 			="SELECT amount FROM #__jereverseauction_commission WHERE id=".$id;
		$db->setQuery($sql);
		$prod_price 	= $db->loadResult();

		$card_no 			= JRequest::getVar('card_no');
		if (isset($card_no))
		{
			//include_once "phpcreditcard.php";
			$card_num    = $card_no;
			$card_type   = JRequest::getVar('card_type');
			$exp_date    = JRequest::getVar('exp_date') . JRequest::getVar('exp_year');
			$amount      = $prod_price;
			$description = "Commission Payment"; //$_POST[''];

			$cvv		 = JRequest::getVar('card_code');
			if ( empty( $login_id ) || empty( $transaction_key ) )
			{
				return JError::raiseWarning( 'SOME_ERROR', JText::_( 'JE_NOTAUTHORISED' ) );
			}
			$post_url = "https://" . $account_type . ".authorize.net/gateway/transact.dll";

			$post_values = array(

			// the API Login ID and Transaction Key must be replaced with valid values
			"x_login"			=> $login_id,
			"x_tran_key"		=> $transaction_key,

			"x_version"			=> "3.1",
			"x_delim_data"		=> "TRUE",
			"x_delim_char"		=> "|",
			"x_relay_response"	=> "FALSE",
			"x_type"			=> "AUTH_CAPTURE",
			"x_method"			=> "CC",
			"x_card_num"		=> $card_num,
			"x_exp_date"		=> $exp_date,
			"x_card_code"       => $cvv,
			"x_amount"			=> $amount

			);

			if ( empty($post_values['x_card_num']) || empty($post_values['x_exp_date']) || empty($post_values['x_amount']) )
			{
				$msg="";
				$app->redirect(JRoute::_('index.php?option=com_jereverseauction&view='.$view.'&layout=default_shipping&id='.$id),$msg);
			}

			// This section takes the input fields and converts them to the proper format
			// for an http post.  For example: "x_login=username&x_tran_key=a1B2c3D4"
			$post_string = "";

			foreach( $post_values as $key => $value )
			{
				$post_string .= "$key=".urlencode($value)."&";
			}
			$post_string = rtrim( $post_string, "& " );

			$request = curl_init($post_url); // initiate curl object
			curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
			curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
			curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
			curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
			$post_response = curl_exec($request); // execute curl post and store results in $post_response

			curl_close ($request); // close curl object

			// This line takes the response and breaks it into an array using the specified delimiting character
			$response_array = explode($post_values["x_delim_char"],$post_response);


			$date		 									= & JFactory::getDate();
			$payment_date									= $date->toMySQL();
			$tn_id											= $response_array[6];

			if( $response_array[0] == '1' )
			{
				$ptype		= JRequest::getVar('ptype');
				$txn_id 	= $post_values["x_card_num"];
				$pdate 		= gmdate("Y-m-d H:i:s");
				$query 		= 'UPDATE #__jereverseauction_commission SET status=1, trans_id="'.$tn_id.'",payment_type="'.$this->params["plugin_name"].'",paid_date="'.$pdate.'" WHERE id='.$id;
				$db->setQuery($query);
				$db->query();

				$sql 	="SELECT prod_id FROM #__jereverseauction_commission WHERE id=".$id;
				$db->setQuery($sql);
				$id_prod= $db->loadResult();

				$query2 = 'UPDATE #__jereverseauction_products SET published=1, commission_paid=1 WHERE id='.$id_prod;
				$db->setQuery($query2);

				if ($error = $db->getErrorMsg()) {
					throw new Exception($error);
				}
				$db->query();
				$msg = $response_array[3] ;
			}
			else
			{
				$msg = $response_array[3] ;

			}

			$app->redirect('index.php?option=com_jereverseauction&view='.$view.'&task='.$view.'.processPaymentmail&id='.$id.'&ptype='.$this->params["plugin_name"].'&pactiontype=paymentmessage&ptype='.$this->params["plugin_name"].'&Itemid='.$Itemid,$msg);
		}
	}

	function onPaymentMethodList($val)
	{
		$view	= $val['view'];
		$id		= $val['id'];
		$user	= JFactory::getUser();
		$Itemid = JRequest::getInt("Itemid",'0');
		$paymentLogoPath = JURI::root()."plugins/jereverseauctionpayment/".$this->params["plugin_name"]."/".$this->params["plugin_name"]."/images/".$this->params["logo"];

		if($this->params["transaction_key"] == null) {
			JError::raiseNotice('', JText::_('PLG_JEREVERSEAUCTIONPAYMENT_AUTHORIZE_NOTADDED_BUSINESSID'));
			$action_url		= 'javascript:void(0);';
		} else {

			$action_url = JRoute :: _('index.php?option=com_jereverseauction&view='.$view.'&task='.$view.'.processPayment&ptype='.$this->params["plugin_name"].'&pactiontype=notify&id='.$id);
		}

		$ADN_form = "<script language='javascript'>function adotnetSubmitForm(){document.getElementById('card_no').style.border='none';document.getElementById('card_code').style.border='';if(document.addtocart.card_no.value==''){document.getElementById('card_no').style.border='1px solid red';return;}else if(document.addtocart.card_code.value==''){document.getElementById('card_code').style.border='1px solid red';return;}else{document.addtocart.submit(); } }</script>" .
		"<form name='addtocart' action='".$action_url."' method='post'>
		<table align='left'>
		<th align='left'><b>Credit Cart Payment:</b></th>
		<tr>
		<td align='left'>Credit Card Type : </td>
		<td align='left'>
		<select name='card_type' id='card_type'>
		<option value='visa'>visa</option>
		<option value='Master_Card'>Master Card</option>
		<option value='American_Express'>American Express</option>
		</select>
		</td>
		</tr>

		<tr>
		<td align='left'>Credit Card Number : </td>
		<td align='left'><input type='text' name='card_no' id='card_no'/></td>
		</tr>

		<tr>
		<td align='left'>Credit Card Security Code  : </td>
		<td align='left'><input type='text' name='card_code' id='card_code'/></td>
		</tr>

		<tr>
		<td align='left'>Expiration Date : </td>
		<td align='left'><select name='exp_date' id='exp_date'>
		                  <option value='1'>January</option>
						  <option value='02'>February</option>
						  <option value='03'>March</option>
						  <option value='04'>April</option>
						  <option value='05'>May</option>
						  <option value='06'>June</option>
						  <option value='07'>July</option>
		                  <option value='08'>August</option>
						  <option value='09'>September</option>
						  <option value='10'>October</option>
						  <option value='11'>November</option>
						  <option value='12'>December</option>


		                  </select>
						  <select name='exp_year' id='exp_year'>
		                  <option value='12'>2012</option>
						  <option value='13'>2013</option>
						  <option value='14'>2014</option>
						  <option value='15'>2015</option>
						  <option value='16'>2016</option>
		                  <option value='17'>2017</option>
						  <option value='18'>2018</option>
						  <option value='19'>2019</option>
						  <option value='20'>2020</option>

		                  </select></td>
						  </tr>
						  <tr><td></td><td>";

						$ADN_form .= "<input type='hidden' name='option' value='com_jereverseauction' /><input type='hidden' name='task' value=$view'.processPayment' /><input type='hidden' name='ptype' value='".$this->params["plugin_name"]."' /><input type='hidden' name='pactiontype' value='notify' />";
						$ADN_form .= "</td></tr> </table>

		</form>";

		$html ='<table cellpadding="5" cellspacing="0" width="100%" border="0">
			<tr>';
				if($this->params["logo"] != ""){
			$html .='<td width="160" align="center">
					<img src="'.$paymentLogoPath.'" title="'. $this->params["payment_method"].'"/>
				</td>';
				 }
				$html .='<td>
					<p style="text-align:justify;">'.$this->params["description"]."<br/>".$ADN_form.'</p>
				</td>
				<td width="100" align="center">
					<a  style="text-decoration:none;cursor:pointer;" onclick="adotnetSubmitForm()"><img border="0" src="'.JURI::root().'components/com_jereverseauction/assets/images/buynow.png" width="89" height="28" ></a>
				</td>
			</tr>
		</table>';
		return $html;
	}

}


?>