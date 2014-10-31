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

jimport('joomla.application.component.view');

//view for wdc auction products
class jereverseauctionViewAddproduct extends JView
{
	protected $form;
	protected $item;
	protected $return_page;
	protected $terms;
	protected $params;

	public function display($tpl = null)
	{

		$user								= JFactory::getUser();
		$app								= JFactory::getApplication();

		$url = base64_encode(JRoute::_('index.php?option=com_jereverseauction&view=addproduct'));

		if ( $user->get('guest'))
		{
			$link = JRoute::_('index.php?option=com_users&view=login&return='.$url);
			$app->Redirect($link , JText::_( 'COM_JEREVERSE_AUCTION_PLEASE_LOGIN' ));
		}

		$lay = $this->getLayout();
		if($lay == 'paypal') {
			$lay = $this->setLayout('default_paypal');
		}
		//check for authorize

		$this->item							= $this->get('Item');
		$this->form							= $this->get('Form');
		$this->return_page					= $this->get('ReturnPage');
		$this->terms						= $this->get('Paypalconf');
		$this->params						= $app->getParams();

		$userGroups = $user->get('groups');
		foreach($userGroups as $groupid) {
			$this->gid = $groupid;
		}

		$lay = $this->getLayout();
		if (empty($this->item->id)) {
			$authorised							= $user->authorise('core.create', 'com_jereverseauction') || (count($user->getAuthorisedCategories('com_jereverseauction', 'core.create')));
		} else {
			$authorised							= $this->item->params->get('access-edit');
		}

		if ($authorised !== true) {
			JError::raiseWarning('', JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		$result 			= JRequest::getVar("result","");
		$action				= JRequest::getVar('action','');
		$ptype				= JRequest::getVar('ptype','');
		$proid 				= JRequest::getVar('prod_id');
		$view				= JRequest::getVar('view');

		$url = base64_encode(JRoute::_('index.php?option=com_jereverseauction&view=addproduct&layout=default_shipping&prod_id='.$proid));
		if($lay == 'default_shipping')
		{
			if ( $user->get('guest'))
			{
				$link = JRoute::_('index.php?option=com_users&view=login&return='.$url);
				$app->Redirect($link);
			}

			//called the model fuction to add the product details before payment
			//$this->model->insertpaymentproduct($proid);

			$val["id"]				= $proid;
			$val["view"]			= $view;
			$val["amount"]			= $this->params->get('commission');
			$options				= array( $val);

			//print_r($options);exit;

			// Triggering the payment method
			JPluginHelper::importPlugin( 'jereverseauctionpayment' );
			$dispatcher				= & JDispatcher::getInstance();
			$PaymentMethodDetails	= $dispatcher->trigger( 'onPaymentMethodList',$options);
			$plugin_info			= JPluginHelper::getPlugin('jereverseauctionpayment', $plugin=null);
			$PaymentMethodMessage	= "";

			if($action=="showresult")
			{
				$inv_arg["inv_id"] = $proid;
				$arg = ($inv_arg);
				$PaymentMethodMessage = $dispatcher->trigger( 'onAfterFailedPayment',$arg);
				$message=$this->getPaymentPluginStatus($PaymentMethodMessage,$plugin_info,$ptype);
			}

			$this->assignRef("PaymentMethodDetails",$PaymentMethodDetails);
			$this->assignRef("plugin_info",$plugin_info);

		} elseif($lay == 'default_process'){
			JPluginHelper::importPlugin("jereverseauctionpayment");
			$dispatcher =& JDispatcher::getInstance();
			$results = $dispatcher->trigger( 'onProcessPayment');
			$text = trim(implode("\n", $results));
			echo $text;
		}

		parent::display($tpl);
	}
}
?>
