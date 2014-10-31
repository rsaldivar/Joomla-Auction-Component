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

jimport('joomla.application.component.model');

/**
 * This models supports retrieving lists of JE Testimonial categories.
 */
class jereverseauctionModelCategories extends JModel
{
	/**
	 * Model context string.
	 */
	public $_context				= 'com_jereverseauction.categories';

	/**
	 * The category context (allows other extensions to derived from this model).
	 */
	protected $_extension			= 'com_jereverseauction';

	private $_parent				= null;

	private $_items					= null;




	function __construct()
	{
		parent::__construct();

		//Get configuration
			$app		= JFactory::getApplication();
			$config		= JFactory::getConfig();

		// Get the pagination request variables
			$this->setState('limit', $app->getUserStateFromRequest('com_jereverseauction.limit', 'limit', $config->get('list_limit'), 'int'));
			$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
	}

	function getTotal()
	{
		return $this->_total;
	}

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






	/**
	 * Method to auto-populate the model state.
	 */
	protected function populateState()
	{
		$app						= JFactory::getApplication();
		$this->setState('filter.extension', $this->_extension);

		// Get the parent id if defined.
			$parentId				= JRequest::getInt('id');
			$this->setState('filter.parentId', $parentId);



		$params						= $app->getParams();
		$this->setState('params', $params);

		$this->setState('filter.published',	1);
		$this->setState('filter.access',	true);
	}

	/**
	 * redefine the function an add some properties to make the styling more easy
	 */
	public function getItems()
	{
		if(!count($this->_items))
		{
			$app					= JFactory::getApplication();
			$menu					= $app->getMenu();
			$active					= $menu->getActive();
			$params					= new JRegistry();

			if($active)	{
				$params->loadJSON($active->params);
			}

			$options				= array();
			$options['countItems']	= $params->get('show_cat_items_cat', 1) || !$params->get('show_empty_categories_cat', 0);

			$categories				= JCategories::getInstance('jereverseauction', $options);

			$this->_parent			= $categories->get($this->getState('filter.parentId', 'root'));

			if(is_object($this->_parent)) {
				$this->_items		= $this->_parent->getChildren();
				$this->_total	= count($this->_items);
			} else {
				$this->_items		= false;
			}

			if ($this->getState('limit') > 0) {
				$this->_data	= array_splice($this->_items, $this->getState('limitstart'), $this->getState('limit'));
			} else {
				$this->_data = $this->_items;
			}

		}

		return $this->_data;
	}

	public function getParent()
	{
		if(!is_object($this->_parent)) {
			$this->getItems();
		}
		return $this->_parent;
	}
}