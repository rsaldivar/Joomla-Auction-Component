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
<table border="0" id="products" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td id="top-left"></td>
			<td id="top-center"></td>
			<td id="top-right"></td>
		</tr>
		<tr>
			<td id="middle-left"></td>
			<td>
				<table id="myproductlist" width="100%">
					<thead>
						<tr>
							<th align="center" width="10%" id="heading_td">
								<?php echo JText::_('COM_JEREVERSE_AUCTION_ID'); ?>
							</th>
							<th align="center" id="heading_td">
								<?php echo JText::_('COM_JEREVERSE_AUCTION_PRODUCT_NAME'); ?>
							</th>
							<th align="center" id="heading_td">
								<?php echo JText::_('COM_JEREVERSE_AUCTION_PRODUCT_IMAGE'); ?>
							</th>
							<th align="center" width="16%" id="heading_td">
								<?php echo JText::_('COM_JEREVERSE_AUCTION_CAT'); ?>
							</th>
							<th align="center" width="16%" id="heading_td">
								<?php echo JText::_('COM_JEREVERSE_AUCTION_MAX_BIDAMOUNT'); ?>
							</th>
							<th align="center" width="16%" id="heading_td1">
								<?php echo JText::_('COM_JEREVERSE_AUCTION_STATUS'); ?>
							</th>

						</tr>
					</thead>

					<tbody>
						<?php
						$n = count($this->items);
						foreach ($this->items as $i => $item) :
						$uri				=  JURI::ROOT().'index.php?option=com_jereverseauction&view=myproducts';
						$url				= 'index.php?task=addproduct.edit&a_id='.(int) $item->id.'&return='.base64_encode($uri);
						if($item->comm_id){
							$link2			= 'index.php?option=com_jereverseauction&view=addproduct&layout=default_shipping&prod_id='.$item->comm_id;
						}else{
							$link2			= '#';
						}
						?>
						<tr class="row<?php echo $i % 2; ?>">
							<td align="center" id="content_td">
								<?php echo $item->id; ?>
							</td>
							<td id="content_td">
								<?php echo $this->escape($item->prod_name); ?>

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
								<?php echo $currency." ".$this->escape($item->max_bid_amount); ?>
							</td>
			       			<td align="center" id="content_td1">
								<?php
								$status = $this->escape($item->status);
								if($status == 0) echo JText::_('COM_JEREVERSE_AUCTION_RUNNING');
								else echo JText::_('COM_JEREVERSE_AUCTION_FINISHED');

								$pub = $this->escape($item->published);
								echo "<div id='status_pro'>";
								if($pub == 1) echo JText::_('JPUBLISHED');
								else if($pub == 0)echo "<a href='$link2'>".JText::_('JUNPUBLISHED')."</a>";
								echo "</div>";
								 ?>
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