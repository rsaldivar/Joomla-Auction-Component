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

jimport('joomla.application.component.view');

//view for wdc auction products
class jereverseauctionViewCategory extends JView
{
	protected $data;
	protected $form;
	protected $params;
	protected $state;
	protected $pagination;

	/**
	 * Method to display the view.
	 */
	public function display($tpl = null)
	{
		// Get the view data.
		$app				= JFactory::getApplication();
		$user=$this->user				= JFactory::getUser();
		$this->data			= $this->get('Items');
		$this->state		= $this->get('State');
		$this->pagination	= $this->get('Pagination');
		$this->params		= $app->getParams();
		$this->model		= $this->getModel();

		//print_r($this->pagination);
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseNotice(404, implode('<br />', $errors));
			return false;
		}

		if(count($this->data)){
			parent::display($tpl);
		}
	}


}
?>
