<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * JE GroupBuy View
 */
class jereverseauctionViewCommission extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 */
	public function display($tpl = null)
	{
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');
		$script = $this->get('Script');
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
		$this->script = $script;

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		JRequest::setVar('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId = $user->id;
		$isNew = $this->item->id == 0;

		$canDo = jereverseauctionHelper::getActions($this->item->id);
		JToolBarHelper::title($isNew ? JText::_('JE_COMPONENT_TITLE')." : ".JText::_('COM_JEREVERSE_AUCTION_PRODUCT')."&nbsp-&nbsp;"." <em>[".JText::_('COM_JEREVERSE_AUCTION_NEW')."]</em>" : JText::_('JE_COMPONENT_TITLE')." : ".JText::_('COM_JEREVERSE_AUCTION_PRODUCT')."&nbsp-&nbsp;"." <em>[".JText::_('COM_JEREVERSE_AUCTION_EDIT')."]</em>", 'products.png');
		// Built the actions for new and existing records.
		if ($isNew)
		{
			// For new records, check the create permission.
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::apply('commission.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('commission.save', 'JTOOLBAR_SAVE');
				JToolBarHelper::custom('commission.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			}
			JToolBarHelper::cancel('commission.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			if ($canDo->get('core.edit'))
			{
				// We can save the new record
				JToolBarHelper::apply('commission.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('commission.save', 'JTOOLBAR_SAVE');

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create'))
				{
					JToolBarHelper::custom('commission.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				}
			}
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::custom('product.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			}
			JToolBarHelper::cancel('commission.cancel', 'JTOOLBAR_CLOSE');
		}
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		$isNew = $this->item->id == 0;
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('JE_COMPONENT_TITLE')." ".JText::_('COM_JEREVERSE_AUCTION_PRODUCT')." ".JText::_('COM_JEREVERSE_AUCTION_NEW') : JText::_('JE_COMPONENT_TITLE')." ".JText::_('COM_JEREVERSE_AUCTION_PRODUCT')." ".JText::_('COM_JEREVERSE_AUCTION_EDIT'));
		$document->addScript(JURI::root() . $this->script);
		$document->addScript(JURI::root() . "/administrator/components/com_jereverseauction/assets/js/submitbutton.js");
		JText::script('JE_SCRIPT_ERROR');
	}
}
