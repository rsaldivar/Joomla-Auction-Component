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

class jereverseauctionModelUserwins extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'win.id',
				'amount', 'win.amount',
				'published', 'win.published',
				'ended', 'win.ended',
				'user_name', 'us.username',
				'email', 'us.email',
				'prod_id', 'win.prod_id',
				'prod_name', 'pro.prod_name',
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


		// List state information.
		parent::populateState('win.published', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
			$id	.= ':'.$this->getState('filter.search');
			$id	.= ':'.$this->getState('filter.published');

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
			$query->select( 'win.*' );
			$query->from( '#__jereverseauction_wins as win' );

		// Filter by published state
			$fpublished		= $this->getState('filter.published');
			if (is_numeric($fpublished)) {
				$query->where('win.published = ' . (int) $fpublished);
			} else if ($fpublished === '') {
				$query->where('(win.published = 0 OR win.published = 1)');
			}

		// Filter by search in title & microblog.
			$search			= $this->getState('filter.search');
			if (!empty($search)) {
				if (stripos($search, 'id:') === 0) {
					$query->where('win.id = '.(int) substr($search, 3));
				} else {
					$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
					$query->where('(us.username LIKE '.$search.' OR us.email LIKE '.$search.')');
				}
			}

			// Join over the categories.
			$query->select( 'us.username , us.email' );
			$query->join( 'LEFT', '#__users AS us ON us.id = win.uid' );

			// Join over the categories.
			$query->select( 'pro.prod_name' );
			$query->join( 'LEFT', '#__jereverseauction_products AS pro ON pro.id = win.prod_id' );

			// Add the list ordering clause.
			$orderCol		= $this->state->get('list.published');
			$orderDirn		= $this->state->get('list.direction');

			if ($orderCol == 'win.published') {
				$orderCol	= 'win.published';
				$query->order($db->getEscaped($orderCol.' '.$orderDirn));
			}
		return $query;
	}

	public function filterPaymentstatus()
	{
		$options			= array();

		$payment_status		= array(JText::_('COM_JEREVERSE_AUCTION_PAYMENTSTATUS_OPTION_PENDING'), JText::_('COM_JEREVERSE_AUCTION_PAYMENTSTATUS_OPTION_COMPLETED'));

		foreach ($payment_status as $key=>$value) {
			$options[]		= JHTML::_('select.option', $value, $value);
		}

		return $options;
	}

}
?>
