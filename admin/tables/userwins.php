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

class jereverseauctionTableUserwins extends JTable
{
	public function __construct(& $db)
	{
		parent::__construct( '#__jereverseauction_wins', 'id', $db );
	}

}
?>
