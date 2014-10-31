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

JHtml::addIncludePath( JPATH_COMPONENT.DS.'helpers' );

?>

<div class="categories-list<?php echo $this->pageclass_sfx;?>">
	<table border="0" id="products" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td id="top-left"></td>
			<td id="top-center"></td>
			<td id="top-right"></td>
		</tr>
		<tr>
			<td id="middle-left"></td>
			<td>
				<div id="categories_page">
					<div id="category_page_heading">
						<?php echo JText::_('COM_JEREVERSE_AUCTION_CATEGORY_LISTING'); ?>
						<img align="right" src="components/com_jereverseauction/assets/images/arr.jpg" />
					</div>

					<?php
						echo $this->loadTemplate('items');
					?>
				</div>
			</td>
			<td id="middle-right"></td>
		</tr>
		<tr>
			<td id="bottom-left"></td>
			<td id="bottom-center"></td>
			<td id="bottom-right"></td>
		</tr>
	</table>
</div>
<?php
if($this->params->get('show_footertext', 1)) {
?>
	<p class="copyright" style="text-align : right; font-size : 10px;">
		<?php require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' ); ?>
	</p>
<?php
}
?>
