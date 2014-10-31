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

class jereverseauctionViewProducts extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	function display( $tpl = null )
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->user			= JFactory::getUser();

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

			JToolBarHelper::title( JText::_('COM_JEREVERSE_AUCTION').' : '.JText::_('COM_JEREVERSE_AUCTION_PRODUCTS'), 'products.png');

			if ($canDo->get('core.create')) {
				JToolBarHelper::addNew('product.add','JTOOLBAR_NEW');
			}

			if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
				JToolBarHelper::editList('product.edit','JTOOLBAR_EDIT');
			}

			if ($canDo->get('core.edit.state')) {
				JToolBarHelper::divider();
				JToolBarHelper::custom('products.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('products.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('products.archive','JTOOLBAR_ARCHIVE');
			}

			if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
				JToolBarHelper::deleteList('', 'products.delete','JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			} else if ($canDo->get('core.edit.state')) {
				JToolBarHelper::trash('products.trash','JTOOLBAR_TRASH');
			}

			if ($canDo->get('core.admin')) {
				JToolBarHelper::divider();
				JToolBarHelper::preferences('com_jereverseauction');
			}

	}
}
?>