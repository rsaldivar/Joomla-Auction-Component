<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('_JEXEC') or die('Restricted access');

class jereverseauctionHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public static function addSubmenu($submenu)
	{

		JSubMenuHelper::addEntry(JText::_('COM_JEREVERSE_AUCTION_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_jereverseauction', $submenu == 'categories');
		JSubMenuHelper::addEntry(JText::_('COM_JEREVERSE_AUCTION_PRODUCTS'), 'index.php?option=com_jereverseauction&view=products', $submenu == 'products');
		JSubMenuHelper::addEntry(JText::_('COM_JEREVERSE_AUCTION_USER_WINS'), 'index.php?option=com_jereverseauction&view=userwins', $submenu == 'userwins');
		JSubMenuHelper::addEntry(JText::_('COM_JEREVERSE_AUCTION_COMMISSION'), 'index.php?option=com_jereverseauction&view=commissions', $submenu == 'commissions');
		JSubMenuHelper::addEntry(JText::_('COM_JEREVERSE_AUCTION_MAIL_TEMPLATES'), 'index.php?option=com_jereverseauction&view=mailtemplates', $submenu == 'mailtemplates');

		// set some global property
		$document = JFactory::getDocument();

		if ($submenu == 'categories')
		{
			$document->setTitle(JText::_('COM_JEREVERSE_AUCTION_ADMINISTRATION_CATEGORIES'));
		}
	}
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($messageId)) {
			$assetName = 'com_jereverseauction';
		}
		else {
			$assetName = 'com_jereverseauction.message.'.(int) $messageId;
		}
		//echo $assetName;exit;
		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
}
