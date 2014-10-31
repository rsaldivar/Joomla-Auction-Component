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

jimport('joomla.application.component.modellist');

class jereverseauctionModelMailtemplates extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'mail.id',
				'title', 'mail.subject',
				'published', 'mail.published',
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

		// List state information.
			parent::populateState('mail.id', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 */
	protected function getStoreId($id = '')
{
		// Compile the store id.
			$id	.= ':'.$this->getState('filter.search');
			$id	.= ':'.$this->getState('filter.published');
			$id	.= ':'.$this->getState('filter.language');

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
			$query->select( $this->getState(
				'list.select',
				'mail.id AS id, ' .
				'mail.subject AS subject, ' .
				'mail.mailbody AS mailbody, '.
				'mail.published AS published'
			) );
			$query->from( '#__jereverseauction_mailtemplates as mail' );

		// Filter by search in title & microblog.
			$search			= $this->getState('filter.search');
			if (!empty($search)) {
				if (stripos($search, 'id:') === 0) {
					$query->where('mail.id = '.(int) substr($search, 3));
				} else {
					$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
					$query->where('(mail.subject LIKE '.$search.' OR mail.mailbody LIKE '.$search.')');
				}
			}


		// Add the list ordering clause.
			$orderCol		= $this->state->get('list.ordering');
			$orderDirn		= $this->state->get('list.direction');

			if ($orderCol == 'mail.id') {
				$orderCol	= 'mail.id';
			}

			$query->order($db->getEscaped($orderCol.' '.$orderDirn));

		return $query;
	}
}
?>
