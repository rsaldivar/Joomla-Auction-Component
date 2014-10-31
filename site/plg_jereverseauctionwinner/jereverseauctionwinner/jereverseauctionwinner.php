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

jimport( 'joomla.plugin.plugin' );

/**
 * Example system plugin
 */
class plgSystemjereverseauctionwinner extends JPlugin
{
    function onAfterInitialise()
	{

		// Require Helpers files from jemembership component
			jimport( 'joomla.filesystem.folder' );
			$helpersPath	= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jereverseauction'.DS.'helpers';
			$exists			= JFolder::exists( $helpersPath );

			if ( $exists ) {
				$helperFiles	= JFolder::files($helpersPath, '\.php$', false, true);

				if (count($helperFiles) > 0) {
					// iterate through the helper files
					foreach ($helperFiles as $helperFile) {
						require_once($helperFile);
					}

					$email_object     = new jereverseauctionEmailsystem();
					$email_object->emailtoExpirewonproduct();
					$email_object->updatedate();
				}
			}
	}
}
