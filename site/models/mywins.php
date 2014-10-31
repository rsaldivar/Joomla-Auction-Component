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

// Import Joomla predefined functions
	jimport('joomla.application.component.modelitem');

class jereverseauctionModelMywins extends JModelItem
{
	/**
	 * total Testimonials
	 */
	var $_total									= null;

	/**
	 * Pagination object
	 */
	var $_pagination							= null;

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		//Get configuration
			$app								= JFactory::getApplication();
			$config								= JFactory::getConfig();

		// Get the pagination request variables
			$this->setState('limit', $app->getUserStateFromRequest('com_jereverseauction.limit', 'limit', $config->get('list_limit'), 'int'));
			$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
	}

	protected function populateState($ordering = null, $direction = null)
	{

	}

	/**
	 * Method to get Testimonials data.
	 */
	public function getItems()
	{
		// Connect db
			$db									= $this->getDbo();
			$query								= $db->getQuery(true);
			$user				  				= & JFactory::getUser();

		$query->select('tm.prod_name,tm.prod_image,tm.description,tm.id,tm.min_bid_amount,tm.max_bid_amount');
		$query->from('#__jereverseauction_products AS tm');

		$query->select( 'aw.amount' );
		$query->join( 'LEFT', '#__jereverseauction_wins AS aw ON tm.id = aw.prod_id' );

		$query->where('aw.uid = '.$user->id);
		$db->setQuery($query);
		$product									= $db->loadObjectList();

		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}

		if( empty($user->id) ) {
			JError::raiseNotice(404, JText::_('COM_JEREVERSE_AUCTION_ERROR_PLEASE_LOGIN'));
		}
		else if(empty($product)) {
			JError::raiseNotice(404, JText::_('COM_JEREVERSE_AUCTION_ERROR_NO_WINS'));
		}
		else {
			// Join over the categories.
			$query->select( 'cat.title AS category_title' );
			$query->join( 'LEFT', '#__categories AS cat ON cat.id = tm.catid' );

			$products								= $db->loadObjectList();

			$this->_total						= count($products);

			if ($this->getState('limit') > 0) {
				$this->_data					= array_splice($products, $this->getState('limitstart'), $this->getState('limit'));
			} else {
				$this->_data					= $products;
			}

			return $this->_data;
		}
	}

	/**
	 * Method to get the total number of weblink items for the category
	 */
	function getTotal()
	{
		return $this->_total;
	}

	/**
	 * Method to get a pagination object of the weblink items for the category
	 */
	function getPagination()
	{
		// Lets load the content if it doesn't already exist
			if (empty($this->_pagination))
			{
				jimport('joomla.html.pagination');
				$this->_pagination				= new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
			}

		return $this->_pagination;
	}
}
?>
