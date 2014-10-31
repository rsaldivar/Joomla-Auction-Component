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

class jereverseauctionViewUserwins extends JView
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
		$this->model		= $this->getModel();
		$this->paymentstatus= $this->model->filterPaymentstatus();
		$this->user			= JFactory::getUser();

		// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
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

			JToolBarHelper::title( JText::_('COM_JEREVERSE_AUCTION').' : '.JText::_('COM_JEREVERSE_AUCTION_USER_WINS'), 'wins.png');

			if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
				JToolBarHelper::deleteList('', 'userwins.delete','JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			} else if ($canDo->get('core.edit.state')) {
				JToolBarHelper::trash('userwins.trash','JTOOLBAR_TRASH');
			}

			if ($canDo->get('core.admin')) {
				JToolBarHelper::preferences('com_jereverseauction');
			}

	}
}
?>