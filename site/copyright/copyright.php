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

$pathToXML_File = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jereverseauction' . DS . 'jereverseauction.xml';
$xml	 		= JFactory::getXMLParser('Simple');
$xml->loadFile($pathToXML_File);
$document 		= & $xml->document;

$name 			= $document->name;
$version 		= $document->version;
$author 		= $document->author;
$authorurl 		= $document->authorUrl;

echo $name['0']->_data."&nbsp;".$version['0']->_data."&nbsp;-&nbsp;"; ?>
<a href="http://www.jextn.com/" title="<?php echo JText::_('JE_DEVELOPED'); ?>" target="_blank">
	<?php echo JText::_('JE_DEVELOPED'); ?>
</a>