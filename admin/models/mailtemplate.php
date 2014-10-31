<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

class jereverseauctionModelMailtemplate extends JModelAdmin
{
	/**
	 * Method to test whether a record can be deleted.
	 */
	protected function canDelete($record)
	{
		$user		= JFactory::getUser();

		return parent::canDelete($record);
	}

	/**
	 * Method to test whether a record can be deleted.
	 */
	protected function canEditState($record)
	{
		$user		= JFactory::getUser();

		// Default to component settings if category not known.
			return parent::canEditState($record);
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 */
	public function getTable($type = 'Mailtemplate', $prefix = 'jereverseauctionTable', $config = array())
{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the row form.
	 */
	public function getForm($data = array(), $loadData = true)
	{
		jimport('joomla.form.form');

		// Get the form.
			$form	= $this->loadForm('com_jereverseauction.mailtemplate', 'mailtemplate', array('control' => 'jform', 'load_data' => $loadData));
			if (empty($form)) {
				return false;
			}

		// Modify the form based on access controls.
			if (!$this->canEditState((object) $data)) {
				// Disable fields for display.
					$form->setFieldAttribute('ordering', 'disabled', 'true');
					$form->setFieldAttribute('published', 'disabled', 'true');
					$form->setFieldAttribute('language', 'disabled', 'true');

				// Disable fields while saving.
				// The controller has already verified this is a record you can edit.
					$form->setFieldAttribute('ordering', 'filter', 'unset');
					$form->setFieldAttribute('published', 'filter', 'unset');
					$form->setFieldAttribute('language', 'filter', 'unset');
			}

		return $form;
	}

	/**
	 * Method to get a single record.
	 */
	public function getItem($pk = null)
	{
		$item			= parent::getItem($pk);

		return $item;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
			$data		= JFactory::getApplication()->getUserState('com_jereverseauction.edit.mailtemplate.data', array());

		if (empty($data)) {
			$data		= $this->getItem();
		}

		return $data;
	}
}
?>
