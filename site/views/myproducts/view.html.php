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
	jimport( 'joomla.application.component.view' );

//view for wdc auction products
class jereverseauctionViewMyproducts extends JView
{
	protected $state;
	protected $items;
	protected $pagination;
	protected $total;
	protected $params;

	function display( $tpl = null )
	{
		$app						= JFactory::getApplication();
		$params 					= $app->getParams();
		$model						= $this->getModel();
		$user				    	= & JFactory::getUser();

		// Get some data from the models
		$this->state					= $this->get('State');
		$this->items					= $this->get('Items');
		$this->pagination				= $this->get('Pagination');
		$this->total					= $this->get('total');
		$this->params					= $app->getParams();
		$url = base64_encode(JRoute::_('index.php?option=com_jereverseauction&view=myproducts'));

		if ( $user->get('guest'))
		{
			$link = JRoute::_('index.php?option=com_users&view=login&return='.$url);
			$app->Redirect($link ,'' );
		}
		if(count($this->items)){
			parent::display($tpl);
		}
	}

}
?>
