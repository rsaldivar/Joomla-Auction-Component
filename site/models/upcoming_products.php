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

// Joomla predefined functions.
	jimport('joomla.application.component.modelitem');

class jereverseauctionModelUpcoming_products extends JModelItem
{
	/**
	 * @var		object	The user plans data.
	 */
	protected $data;

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

	public function getItems()
	{
		// Connect db
			$db			= $this->getDbo();
			$query		= $db->getQuery(true);

			$cid = JRequest::getInt("cid","");

		$query->select('p.*');
		$query->from('#__jereverseauction_products AS p');
		if($cid > 0)
		{
			$query->where('p.cat_id ='.$cid);
		}
		$query->order($db->getEscaped('p.published DESC'));

		$db->setQuery($query);
		$faq			= $db->loadObjectList();

		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}

		if( empty($faq) )
		{
			JError::raiseNotice(404, JText::_('COM_JEREVERSE_AUCTION_ERROR_PRODUCTS_NOT_FOUND'));
		} else
		{
			$now_date  = date("Y-m-d H:i:s",time());
			$query->where('p.published = 1 AND p.status=2 AND p.start_time > '."'$now_date'");

			$faqs			= $db->loadObjectList();

			if( empty( $faqs )) {
				JError::raiseNotice(404, JText::_('COM_JEREVERSE_AUCTION_ERROR_NO_UPCOMING_PRODUCTS'));
			}

			$this->_total	= count($faqs);

			if ($this->getState('limit') > 0) {
				$this->_data	= array_splice($faqs, $this->getState('limitstart'), $this->getState('limit'));
			} else {
				$this->_data = $faqs;
			}

			return $this->_data;
		}
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
				$this->_pagination				= new JPagination(@$this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
			}

		return $this->_pagination;
	}



}
?>
