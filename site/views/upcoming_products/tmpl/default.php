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

$user   = $this->user;
$uid    = $user->id;
?>
<body onload="time_difference()">

<?php
$params =$this->params;
$value =$this->data;
$currency =$this->params->get("currency", "&#8377;");
$n    = count($value);
?>
<form action="<?php echo JRoute::_('index.php?option=com_jereverseauction'); ?>" method="post" name="adminForm" id="adminForm">
	<table border="0" id="products" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td id="top-left"></td>
			<td id="top-center"></td>
			<td id="top-right"></td>
		</tr>
		<tr>
			<td id="middle-left"></td>
			<td>
				<div id="products">
					<div id="page_title"><?php echo JText::_('COM_JEREVERSE_AUCTION_UPCOMING_PRODUCTS'); ?></div>
					<?php
					if($n){
						foreach($value as $val){
					?>
					<div id="prod_container">
						<div id="closed_prod_name"><?php echo substr ( $val->prod_name, 0, 15 ) . '...'; ?></div>
						<?php
							$img_link ='components/com_jereverseauction/assets/images/products/';
							$link = $val->prod_image ? $val->prod_image : 'no-image.jpg'; ?>
							<div id="thumb_image_back_ended"><img id="thumb_image_ended" src="<?php echo $img_link.$link; ?>" /></div>
						<div id="opens"><?php echo JText::_('COM_JEREVERSE_AUCTION_OPENS_ON'); ?></div>
						<div id="prod_price"><?php echo $val->start_time;?></div>
						<div id="prod_price"><?php echo JText::_('COM_JEREVERSE_AUCTION_VALUE')." "; ?><span id="currency"><?php echo $currency;?></span><?php  echo $this->escape($val->min_bid_amount)." to ".$this->escape($val->max_bid_amount); ?></div>
						<input id="path" 		type="hidden" 	name="path" 	value="<?php echo JURI::Base(); ?>">
					</div>
					<?php
						 }
					}
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
<?php
if( $n > 0 ) {
?>
<!-- Area for pagination -->
<div id="jeauction-paginationarea" style="text-align : center;">
	<!-- Limit Box Drop down -->
	<div class="je-limitbox">
		<label for="limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
		</label>
		<?php echo $this->pagination->getLimitBox(); ?>
	</div>

	<?php
	if( $this->pagination->get('pages.total') > 1) {
	?>
		<!-- Page counter display -->
		<div class="je-pagecounter">
			<?php
				echo $this->pagination->getPagesCounter();
			?>
		</div>

		<!-- Pagination with page links -->
		<div  id="je-pagination" class="pagination">
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php
	}
	?>
	</div>
	<?php
}
?>
</form>
</body>
<?php
if($this->params->get('show_footertext', 1)) {
?>
	<p class="copyright" style="text-align : right; font-size : 10px;">
		<?php require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' ); ?>
	</p>
<?php
}
?>
