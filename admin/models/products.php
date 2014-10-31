<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class jereverseauctionModelProducts extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'prod_id', 'pro.id',
				'prod_name', 'pro.prod_name',
				'catid', 'pro.catid','category_title',
				'prod_price', 'pro.prod_price',
				'status', 'pro.status',
				'access', 'pro.access','access_level',
				'prod_option','pro.prod_option',
				'published', 'pro.published',
				'ordering', 'pro.ordering',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
			$app		= JFactory::getApplication();

		// Adjust the context to support modal layouts.
			if ($layout = JRequest::getVar('layout')) {
				$this->context .= '.'.$layout;
			}

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published		= $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$categoryId		= $this->getUserStateFromRequest($this->context.'.filter.catid', 'filter_catid');
		$this->setState('filter.catid', $categoryId);

		$access			= $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);
		// List state information.
		parent::populateState('pro.ordering', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
			$id	.= ':'.$this->getState('filter.search');
			$id	.= ':'.$this->getState('filter.published');
			$id	.= ':'.$this->getState('filter.catid');
			$id	.= ':'.$this->getState('filter.access');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 */
	protected function getListQuery()
	{
		// Create a new query object.
			$db				= $this->getDbo();
			$query			= $db->getQuery(true);

		// Select the required fields from the table.
			$query->select( 'pro.*' );
			$query->from( '#__jereverseauction_products as pro' );

		// Filter by published state
			$published		= $this->getState('filter.published');
			if (is_numeric($published)) {
				$query->where('pro.published = ' . (int) $published);
			} else if ($published === '') {
				$query->where('(pro.published = 0 OR pro.published = 1)');
			}

		// Filter by category state
			$category		= $this->getState('filter.catid');
			if (is_numeric($category)) {
				$query->where('pro.catid = ' . (int) $category);
			}

		// Filter by access state
			if ($access = $this->getState('filter.access')) {
				$query->where('pro.access = ' . (int) $access);
			}

		// Filter by search in title & microblog.
			$search			= $this->getState('filter.search');
			if (!empty($search)) {
				if (stripos($search, 'prod_id:') === 0) {
					$query->where('pro.id = '.(int) substr($search, 3));
				} else {
					$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
					$query->where('(pro.prod_name LIKE '.$search.' OR pro.description LIKE '.$search.')');
				}
			}

			// Join over the categories.
			$query->select( 'cat.title AS category_title' );
			$query->join( 'LEFT', '#__categories AS cat ON cat.id = pro.catid' );

		// Join over the asset groups.
			$query->select( 'ag.title AS access_level' );
			$query->join( 'LEFT', '#__viewlevels AS ag ON ag.id = pro.access' );


			// Add the list ordering clause.
			$orderCol		= $this->state->get('list.ordering');
			$orderDirn		= $this->state->get('list.direction');

			if ($orderCol == 'pro.ordering') {
				$orderCol	= 'pro.ordering';
			}

			$query->order($db->getEscaped($orderCol.' '.$orderDirn));

		return $query;
	}

}
?>
