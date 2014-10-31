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

jimport('joomla.application.categories');

/**
 * Build the route for the com_jereverseauction component
  */
function jereverseauctionBuildRoute(&$query)
{
	$segments	= array();

	// get a menu item based on Itemid or currently active
		$app		= JFactory::getApplication();
		$menu		= $app->getMenu();
		$params		= JComponentHelper::getParams('com_jereverseauction');
		$advanced	= $params->get('sef_advanced_link', 0);

	// we need a menu item.  Either the one specified in the query, or the current active one if none specified
		if (empty($query['Itemid'])) {
			$menuItem = $menu->getActive();
			$menuItemGiven = false;
		} else {
			$menuItem = $menu->getItem($query['Itemid']);
			$menuItemGiven = true;
		}


		$mView							= (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
		$mCatid							= (empty($menuItem->query['catid'])) ? null : $menuItem->query['catid'];
		$mId							= (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];
		if (isset($query['view'])) {
			$view = $query['view'];
		if (empty($query['Itemid'])) {
			$segments[]				= $query['view'];
		}
		unset($query['view']);
	};

	// are we dealing with an newsfeed that is attached to a menu item?
		if (isset($query['view']) && ($mView == $query['view']) and (isset($query['id'])) and ($mId == intval($query['id']))) {
			unset($query['view']);
				unset($query['catid']);
			unset($query['id']);
        		return $segments;
		}

	//	exit;

	if (isset($view) and ($view == 'category')) {
		if ($mId != intval($query['id']) || $mView != $view) {
			if($view == 'category' && isset($query['catid'])) {
					$catid = $query['catid'];
			} elseif(isset($query['id'])) {
					$catid = $query['id'];
			}
			$menuCatid				= $mId;
			$categories				= JCategories::getInstance('jereverseauction');
			$category = $categories->get($catid);
			if ($category) {
				$path				= $category->getPath();
				$path				= array_reverse($path);

			$array = array();
				foreach($path as $id) {
					if((int) $id == (int)$menuCatid) {
					break;
				}

					if($advanced) {
				list($tmp, $id) = explode(':', $id, 2);
					}
				$array[] = $id;
			}
				$segments			= array_merge($segments, array_reverse($array));
			}

			if($view == 'products') {
				if ($advanced) {
					list($tmp, $id) = explode(':', $query['id'], 2);
				} else {
					$id = $query['id'];
				}
				$segments[] = $id;
			}
		}

			unset($query['id']);
			unset($query['catid']);
		}

		if ( $mView == 'article')
		{
			if (!$menuItemGiven) {
				$segments[] = $view;
			}

			unset($query['view']);

			if ($mView == 'article') {
				if (isset($query['id']) && isset($query['catid']) && $query['catid']) {
					$catid = $query['catid'];
					$id = $query['id'];
				} else {
					// we should have these two set for this view.  If we don't, it is an error
					return $segments;
				}
			} else {
				if (isset($query['id'])) {
					$catid = $query['id'];
				} else {
					// we should have id set for this view.  If we don't, it is an error
					return $segments;
				}
			}

			if ($menuItemGiven && isset($menuItem->query['id'])) {
				$mCatid = $menuItem->query['id'];
			} else {
				$mCatid = 0;
			}

			$categories = JCategories::getInstance('Content');
			$category = $categories->get($catid);

			if (!$category) {
				// we couldn't find the category we were given.  Bail.
				return $segments;
			}

			$path = array_reverse($category->getPath());

			$array = array();

			foreach($path AS $id) {
				if ((int)$id == (int)$mCatid) {
					break;
				}

				list($tmp, $id) = explode(':', $id, 2);

				$array[] = $id;
			}

			$array = array_reverse($array);

			if (!$advanced && count($array)) {
				$array[0] = $catid.':'.$array[0];
			}

			$segments = array_merge($segments, $array);

			if ($mView == 'article') {
				if ($advanced) {
					list($tmp, $id) = explode(':', $query['id'], 2);
				}
				else {
					$id = $query['id'];
				}
				$segments[] = $id;
			}
			unset($query['id']);
			unset($query['catid']);
		}

		if ($mView == 'archive') {
			if (!$menuItemGiven) {
				$segments[] = $view;
				unset($query['view']);
			}

			if (isset($query['year'])) {
				if ($menuItemGiven) {
					$segments[] = $query['year'];
					unset($query['year']);
				}
			}

			if (isset($query['year']) && isset($query['month'])) {
				if ($menuItemGiven) {
					$segments[] = $query['month'];
					unset($query['month']);
				}
			}
		}

	// if the layout is specified and it is the same as the layout in the menu item, we
	// unset it so it doesn't go into the query string.
		if (isset($query['layout'])) {
			if ($menuItemGiven && isset($menuItem->query['layout'])) {
				if ($query['layout'] == $menuItem->query['layout']) {

					unset($query['layout']);
				}
			} else {
				if ($query['layout'] == 'default') {
					unset($query['layout']);
				}
			}
		}

	return $segments;
}



/**
 * Parse the segments of a URL.
 */
function jereverseauctionParseRoute($segments)
{
	$vars = array();

	//Get the active menu item.
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$item	= $menu->getActive();
	$params = JComponentHelper::getParams('com_jereverseauction');
	$advanced = $params->get('sef_advanced_link', 0);
	$db = JFactory::getDBO();

	// Count route segments
		$count = count($segments);

		if (!isset($item)) {
			$vars['view']	= $segments[0];
			$vars['id']		= $segments[$count - 1];

			return $vars;
		}
		if($count>0)
		{
		// From the categories view, we can only jump to a category.
		$id							= (isset($item->query['id']) && $item->query['id'] > 1) ? $item->query['id'] : 'root';
		$categories					= JCategories::getInstance('jereverseauction')->get($id)->getChildren();
		$vars['catid'] = $id;
		$vars['id'] = $id;
		$found = 0;
		foreach($segments as $segment) {
			$segment				= $advanced ? str_replace(':', '-',$segment) : $segment;
			foreach($categories as $category) {
				if ($category->slug == $segment || $category->alias == $segment) {
				$vars['id'] = $category->id;
				$vars['catid'] = $category->id;
				$vars['view'] = 'category';
				$categories = $category->getChildren();
				$found = 1;
				break;
			}
		}

		if ($found == 0) {
			if ($advanced) {
				$db = JFactory::getDBO();
				$query = 'SELECT id FROM #__jereverseauction_products WHERE catid = '.$vars['catid'];
				$db->setQuery($query);
					$nid			= $db->loadResult();
			} else {
					$nid			= $segment;
			}
				$vars['id']			= $nid;
				$vars['view']		= 'myproducts';
			}
		        $found = 0;
	}
			return $vars;


}


		if ($count == 1) {
			// we check to see if an alias is given.  If not, we assume it is an article
			if (strpos($segments[0], ':') === false) {
				$vars['view'] = 'article';
				$vars['id'] = (int)$segments[0];
				return $vars;
			}

			list($id, $alias) = explode(':', $segments[0], 2);

			// first we check if it is a category
			$category = JCategories::getInstance('Content')->get($id);

			if ($category && $category->alias == $alias) {
				$vars['view'] = 'category';
				$vars['id'] = $id;

				return $vars;
			} else {
				$query = 'SELECT alias, catid FROM #__content WHERE id = '.(int)$id;
				$db->setQuery($query);
				$article = $db->loadObject();

				if ($article) {
					if ($article->alias == $alias) {
						$vars['view'] = 'article';
						$vars['id'] = (int)$id;

						return $vars;
					}
				}
			}
		}

	if (!$advanced) {
		$cat_id = (int)$segments[0];

		$article_id = (int)$segments[$count - 1];

		if ($article_id > 0) {
			$vars['view'] = 'article';
			$vars['catid'] = $cat_id;
			$vars['id'] = $article_id;
		} else {
			$vars['view'] = 'category';
			$vars['id'] = $cat_id;
		}

		return $vars;
	}

	// we get the category id from the menu item and search from there
		$id = $item->query['id'];
		$category = JCategories::getInstance('Content')->get($id);

		if (!$category) {
			JError::raiseError(404, JText::_('COM_CONTENT_ERROR_PARENT_CATEGORY_NOT_FOUND'));
			return $vars;
		}

		$categories = $category->getChildren();
		$vars['catid'] = $id;
		$vars['id'] = $id;
		$found = 0;

	foreach($segments as $segment)
	{
		$segment = str_replace(':', '-',$segment);

		foreach($categories as $category)
		{
			if ($category->alias == $segment) {
				$vars['id'] = $category->id;
				$vars['catid'] = $category->id;
				$vars['view'] = 'category';
				$categories = $category->getChildren();
				$found = 1;
				break;
			}
		}

		if ($found == 0) {
			if ($advanced) {
				$db = JFactory::getDBO();
				$query = 'SELECT id FROM #__jereverseauction_products WHERE cat_id = '.$vars['catid'];
				$db->setQuery($query);
				$cid = $db->loadResult();
			} else {
				$cid = $segment;
			}

			$vars['id'] = $cid;

			if ($item->query['view'] == 'archive' && $count != 1){
				$vars['year']	= $count >= 2 ? $segments[$count-2] : null;
				$vars['month'] = $segments[$count-1];
				$vars['view']	= 'archive';
			}
			else {
				$vars['view'] = 'faq';
			}
		}

		$found = 0;
	}

	return $vars;
}


