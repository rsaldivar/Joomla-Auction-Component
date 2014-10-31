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

class jereverseauctionTableMailtemplate extends JTable
{
	public function __construct(& $db)
	{
		parent::__construct( '#__jereverseauction_mailtemplates', 'id', $db );
	}
}
?>
