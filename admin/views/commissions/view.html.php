<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted Access' );

jimport('joomla.application.component.view');

class jereverseauctionViewCommissions extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $params;
	protected $params_jay;


	/**
	 * Display the view
	 */
	function display( $tpl = null )
	{
		$app				= JFactory::getApplication();
		$this->params		= JComponentHelper::getParams('com_jereverseauction');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->user			= JFactory::getUser();
		$this->model		= $this->getModel();
		$this->paymentstatus= $this->model->filterPaymentstatus();

		// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}

		// Preprocess the list of items to find ordering divisions.
		// TODO: Complete the ordering stuff with nested sets
		if($this->items != null){
			foreach ($this->items as &$item) {
				$item->order_up = true;
				$item->order_dn = true;
			}
		}
		$this->addToolbar();

		parent::display( $tpl );
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		// Initialize variables
			$canDo		= jereverseauctionHelper::getActions();

			JToolBarHelper::title( JText::_('COM_JEREVERSE_AUCTION').' : '.JText::_('COM_JEREVERSE_AUCTION_COMMISSION'), 'commission.png');

			if ($canDo->get('core.create')) {
				JToolBarHelper::addNew('commission.add','JTOOLBAR_NEW');
			}

			if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
				JToolBarHelper::editList('commission.edit','JTOOLBAR_EDIT');
			}

			if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
				JToolBarHelper::deleteList('', 'commissions.delete','JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			} else if ($canDo->get('core.edit.state')) {
				JToolBarHelper::trash('commissions.trash','JTOOLBAR_TRASH');
			}

			if ($canDo->get('core.admin')) {
				JToolBarHelper::divider();
				JToolBarHelper::preferences('com_jereverseauction');
			}

	}
}
?>