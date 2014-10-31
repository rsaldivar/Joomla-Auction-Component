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

jimport('joomla.application.categories');

/**
 * JE Testimonial Component Category Tree
 */
class jereverseauctionCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__jereverseauction_products';
		$options['extension'] = 'com_jereverseauction';
		$options['published'] = 'published';
		parent::__construct($options);
	}
}