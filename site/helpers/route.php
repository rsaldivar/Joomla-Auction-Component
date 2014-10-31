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

jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * WDC Auction Route Helper
 */
abstract class jereverseauctionHelperRoute
{
	protected static $lookup;

	/**
	 * @param	int	The route of the content item
	 */
	public static function getjereverseauctionRoute($id, $catid = 0)
	{
		$needles = array(
			'products'  => array((int) $id)
		);
		//Create the link
		$link = 'index.php?option=com_jereverseauction&view=product&layout=default_details&id='. $id;

		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid='.$item;
		}
		elseif ($item = self::_findItem()) {
			$link .= '&Itemid='.$item;
		}

		return $link;
	}
	public static function getCategoryRoute($catid)
	{
		if ($catid instanceof JCategoryNode) {
			$id 						= $catid->id;
			$category					= $catid;
		} else {
			$id							= (int) $catid;
			$category					= JCategories::getInstance('jereverseauction')->get($id);
		}

		if($id < 1) {
			$link						= '';
		} else {
			$needles 					= array(
											'category' => array($id)
										);

			if ($item = self::_findItem($needles)) {
				$link					= 'index.php?Itemid='.$item;
			} else {

					$link 				= 'index.php?option=com_jereverseauction&view=category&id='.$id;
				if($category) {
					$catids				= array_reverse($category->getPath());
					$needles			= array(
											'category' => $catids,
											'categories' => $catids
										);
					if ($item = self::_findItem($needles)) {
						$link 			.= '&Itemid='.$item;
					} elseif ($item = self::_findItem()) {
						$link 			.= '&Itemid='.$item;
					}
				}
			}
		}

		return $link;
	}
	public static function getProductDetailsRoute($catid)
	{
		if ($catid instanceof JCategoryNode) {
			$id 						= $catid->id;
			$category					= $catid;
		} else {
			$id							= (int) $catid;
			$category					= JCategories::getInstance('productdetails')->get($id);
		}

		if($id < 1) {
			$link						= '';
		} else {
			$needles 					= array(
											'products' => array($id)
										);

			if ($item = self::_findItem($needles)) {
				$link					= 'index.php?Itemid='.$item;
			} else {
				//Create the link
					$link 				= 'index.php?option=com_jereverseauction&view=products&id='.$id;
				if($category) {
					$catids				= array_reverse($category->getPath());
					$needles			= array(
												'products' => $catids
										);
					if ($item = self::_findItem($needles)) {
						$link 			.= '&Itemid='.$item;
					} elseif ($item = self::_findItem()) {
						$link 			.= '&Itemid='.$item;
					}
				}
			}
		}

		return $link;
	}

	public static function getFormRoute($id)
	{
		//Create the link
		if ($id) {
			$link = 'index.php?option=com_jereverseauction&task=addproduct.edit&a_id='. $id;
		} else {
			$link = 'index.php?option=com_jereverseauction&task=addproduct.edit&a_id=0';
		}

		return $link;
	}

	protected static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component	= JComponentHelper::getComponent('com_jereverseauction');
			$items		= $menus->getItems('component_id', $component->id);
			$c = count($items);
			if($c != 0)
			{
				foreach ($items as $item)
				{
					if (isset($item->query) && isset($item->query['view']))
					{
						$view = $item->query['view'];
						if (!isset(self::$lookup[$view])) {
							self::$lookup[$view] = array();
						}
						if (isset($item->query['id'])) {
							self::$lookup[$view][$item->query['id']] = $item->id;
						}
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view]))
				{
					foreach($ids as $id)
					{
						if (isset(self::$lookup[$view][(int)$id])) {
							return self::$lookup[$view][(int)$id];
						}
					}
				}
			}
		}
		else
		{
			$active = $menus->getActive();
			if ($active && $active->component == 'com_jereverseauction') {
				return $active->id;
			}
		}

		return null;
	}
}
?>
