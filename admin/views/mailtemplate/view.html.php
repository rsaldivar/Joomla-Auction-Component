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

class jereverseauctionViewMailtemplate extends JView
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	function display( $tpl = null )
	{
		// Initialiase variables.
			$this->form			= $this->get('Form');
			$this->item			= $this->get('Item');
			$this->state		= $this->get('State');
			$this->script		= $this->get('Script');

		// Check for errors.
			if (count($errors	= $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
	}

			$this->addToolbar();

	parent::display( $tpl );

		// Set the document
		$this->setDocument();
}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);

		$user					= JFactory::getUser();
		$userId					= $user->get('id');
		$isNew					= ($this->item->id == 0);
		$canDo					= jereverseauctionHelper::getActions();

		$text					= $isNew ? JText::_('COM_JEREVERSE_AUCTION_MAILTEMPLATES_NEW') : JText::_('COM_JEREVERSE_AUCTION_MAILTEMPLATES_EDIT');

		$title					= JText::_('COM_JEREVERSE_AUCTION').' : '.JText::_('COM_JEREVERSE_AUCTION_MAIL_TEMPLATES')." : <em>[".$text."]</em>";

		JToolBarHelper::title($title, 'mailtemplate.png');

		if ($isNew) {
			// For new records, check the create permission.
				if ($canDo->get('core.create')) {
				JToolBarHelper::apply('mailtemplate.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('mailtemplate.save', 'JTOOLBAR_SAVE');
				JToolBarHelper::custom('mailtemplate.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			}
			JToolBarHelper::divider();
			JToolBarHelper::cancel('mailtemplate.cancel', 'JTOOLBAR_CANCEL');
		} else {
			if ($canDo->get('core.edit')) {
				// We can save the new record
				JToolBarHelper::apply('mailtemplate.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('mailtemplate.save', 'JTOOLBAR_SAVE');

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create')) {
					JToolBarHelper::custom('mailtemplate.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				}
			}

			if ($canDo->get('core.create')) {
				JToolBarHelper::custom('mailtemplate.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			}

			JToolBarHelper::divider();
			JToolBarHelper::cancel('mailtemplate.cancel', 'JTOOLBAR_CLOSE');
		}

		//JToolBarHelper::divider();
		//JToolBarHelper::help('JHELP_COMPONENTS_CONTACTS_CONTACTS_EDIT');
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		$isNew			= ($this->item->id == 0);
		$document		= JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_JEREVERSE_AUCTION_MAILTEMPLATES_NEW_OPTIONS') : JText::_('COM_JEREVERSE_AUCTION_MAILTEMPLATES_EDIT_OPTIONS'));

		$document->addScript(JURI::root() . $this->script);
		$document->addScript(JURI::root() . "/administrator/components/com_jereverseauction/assets/js/submitbutton.js");
	}
}
?>