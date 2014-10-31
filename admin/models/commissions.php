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

class jereverseauctionModelCommissions extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'prod_name', 'pro.prod_name',
				'prod_amount', 'pro.prod_amount',
				'trans_id', 'win.trans_id',
				'prod_owner_name', 'pro.user_id',
				'username', 'udetail.username',
				'email', 'udetail.email',
				'prod_price', 'pro.prod_price',
				'status', 'pro.status',
				'id', 'pro.id',
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

		$published		= $this->getUserStateFromRequest($this->context.'.filter.status', 'filter_status', '');
		$this->setState('filter.status', $published);

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
			$id	.= ':'.$this->getState('filter.status');

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
			$query->from( '#__jereverseauction_commission as win' );

		// Filter by published state
			$published		= $this->getState('filter.published');

			if (is_numeric($published)) {
				$query->where('win.published = ' . (int) $published);
			} else if ($published === '') {
				$query->where('(win.published = 0 OR win.published = 1)');
			}

		// Filter by published state
			$published1		= $this->getState('filter.status');
			if($published1 == 'Completed') $published1 = 1;
			else if($published1 == 'Pending')$published1 = 0;

			if (is_numeric($published1)) {
				$query->where('win.status = ' . (int) $published1);
			} else if ($published1 === '') {
				$query->where('(win.status = 0 OR win.status = 1)');
			}

		// Filter by search in title & microblog.
			$search			= $this->getState('filter.search');
			if (!empty($search)) {
				if (stripos($search, 'id:') === 0) {
					$query->where('pro.id = '.(int) substr($search, 3));
				} else {
					$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
					$query->where('(pro.prod_name LIKE '.$search.' OR pro.description LIKE '.$search.')');
				}
			}

			// Join over the categories.
			$query->select( 'pro.prod_name , pro.user_id , pro.prod_amount, pro.commission_paid' );
			$query->join( 'LEFT', '#__jereverseauction_products AS pro ON pro.id = win.prod_id' );

		// Join over the asset groups.
			$query->select( 'udetail.username,udetail.email' );
			$query->join( 'LEFT', '#__users AS udetail ON udetail.id = win.user_id' );

			$query->select( 'udetailnew.username AS product_owner' );
			$query->join( 'LEFT', '#__users AS udetailnew ON udetailnew.id = pro.user_id' );

			// Add the list ordering clause.
			$orderCol		= $this->state->get('list.ordering');
			$orderDirn		= $this->state->get('list.direction');

			if ($orderCol == 'pro.ordering') {
				$orderCol	= 'pro.ordering';
			}

			$query->order($db->getEscaped($orderCol.' '.$orderDirn));

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
