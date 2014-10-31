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

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$currency =$this->params->get("currency", "&#8377;");
?>

	<div class="clr"> </div>
<table border="0" id="myproducts" width="100%" cellspacing="0">
	<tr>
		<td id="top-left"></td>
		<td id="top-center"></td>
		<td id="top-right"></td>
	</tr>
	<tr>
		<td id="middle-left"></td>
		<td>
			<table class="myproductlist" width="100%">
				<thead>
					<tr>
						<th id="heading_td" width="5%"><?php echo JText::_('COM_JEREVERSE_AUCTION_ID'); ?></th>
						<th id="heading_td" width="20%">
							<?php echo JText::_('COM_JEREVERSE_AUCTION_PRODUCT_NAME'); ?>
						</th>
						<th id="heading_td" width="20%">
							<?php echo JText::_('COM_JEREVERSE_AUCTION_PRODUCT_IMAGE'); ?>
						</th>
						<th id="heading_td" width="20%">
							<?php echo JText::_('COM_JEREVERSE_AUCTION_CAT'); ?>
						</th>
						<th id="heading_td" width="20%">
							<?php echo JText::_('COM_JEREVERSE_AUCTION_PRODUCT_PRICE_RANGE'); ?>
						</th>
						<th id="heading_td" width="15%">
							<?php echo JText::_('COM_JEREVERSE_AUCTION_WON_PRICE'); ?>
						</th>
					</tr>
				</thead>

				<tbody>
					<?php
					$n = count($this->items);
					foreach ($this->items as $i => $item) :
					$bid_link 		= JURI::Base().'index.php?option=com_jereverseauction&view=products&layout=default_detail&id='.$item->id;
					?>
					<tr class="row<?php echo $i % 2; ?>" >
						<td id="content_td" align="center">
							<?php echo $item->id; ?>
						</td>
						<td id="content_td" align="center">
							<div id="prod_name"><?php echo $this->escape($item->prod_name); ?></div>
						</td>
						<td align="center" id="content_td">
							<?php
							$img_link ='components/com_jereverseauction/assets/images/products/thumbnail_';
							$link = $item->prod_image ? $item->prod_image : 'no-image.jpg'; ?>
							<div id="thumb_image_back"><img id="thumb_image" src="<?php echo $img_link.$link; ?>" /></div>
						</td>
						<td align="center" id="content_td">
							<?php echo $this->escape($item->category_title); ?>
						</td>
						<td align="center" id="content_td">
							<?php echo $currency." ".$this->escape($item->min_bid_amount)." to ".$this->escape($item->max_bid_amount); ?>
						</td>
						<td align="center" id="content_td1" >
							<div class="timer"><?php echo $currency." ".$item->amount; ?></div>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<div>
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
				<?php echo JHtml::_('form.token'); ?>
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


<p class="copyright" align="center">
	<?php
	//require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' );
	?>
</p>