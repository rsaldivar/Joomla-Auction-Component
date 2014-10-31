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
<div id="error" name="error"></div>
<table border="0" id="products" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td id="top-left"></td>
			<td id="top-center"></td>
			<td id="top-right"></td>
		</tr>
		<tr>
			<td id="middle-left"></td>
			<td>
				<table width="100%" cellspacing="0" cellpadding="0" id="myproductlist">
					<tr id="headingrow">
						<th id="heading_td"><?php echo JText::_('COM_JEREVERSE_AUCTION_ID'); ?></th>
						<th id="heading_td"><?php echo JText::_('COM_JEREVERSE_AUCTION_NAME'); ?></th>
						<th id="heading_td"><?php echo JText::_('COM_JEREVERSE_AUCTION_PRODUCT_IMAGE'); ?></th>
						<!--th id="heading_td">< ?php echo JText::_('COM_JEREVERSE_AUCTION_BIDAMOUNT'); ?></th-->
						<th id="heading_td"><?php echo JText::_('Oferta Inicial'); ?></th>
						<th id="heading_td1"><?php echo JText::_('COM_JEREVERSE_AUCTION_TIMELEFT'); ?></th>
					</tr>

				<?php
				if($n){

					 foreach($value as $i =>$val){
					 	$date_end		= JFactory::getDate($val->end_time);
					 	$end_date		= trim($date_end->toFormat('%Y/%m/%d %H:%M:%S'));

					 	$buy_link 		= JURI::Base().'index.php?option=com_jereverseauction&view=products&layout=default_buynow&id='.$val->id;
						$bid_link 		= JURI::Base().'index.php?option=com_jereverseauction&view=products&layout=default_detail&id='.$val->id;
						$ship_link 		= JURI::Base().'index.php?option=com_jereverseauction&view=products&layout=default_shipping&id='.$val->id;
				?>
					<tr class="row<?php echo $i % 2; ?>" >
						<td id="content_td">
							<?php echo $val->id; ?>
						</td>
						<td id="content_td">
							<div id="prod_name"><a id="button_link" OnClick='window.location="<?php echo $bid_link;?>"'><?php echo $this->escape($val->prod_name); ?></a></div>
						</td>
						<td align="center" id="content_td">
							<?php
							$img_link ='components/com_jereverseauction/assets/images/products/thumbnail_';
							$link = $val->prod_image ? $val->prod_image : 'no-image.jpg'; ?>
							<div id="thumb_image_back"><img id="thumb_image" src="<?php echo $img_link.$link; ?>" /></div>
						</td>
						<td align="center" id="content_td">
							<!-- CAMBIADO PARA MOSTRAR EL MINIMO ?php echo $currency." ".$this->escape($val->max_bid_amount); ?-->
							<?php echo $currency." ".$this->escape($val->min_bid_amount); ?>
						</td>
						<td align="center" id="content_td1" width="10%">
							<div id="<?php echo "product_".$val->id ?>" class="products" >
								<div id="<?php echo "time_left".$val->id ?>" class="timer"><?php echo $val->end_time; ?></div>
							</div>
							<div>
								<img class="bid_button_rev" src="components/com_jereverseauction/assets/images/moreBtn.png" OnClick='window.location="<?php echo $bid_link;?>"' />
							</div>
						</td>

						<input type="hidden" name="boxchecked" value="0" />
						<input id="uid" 		type="hidden" 	name="uid" 		value="<?php echo $uid; ?>">
						<input id="path" 		type="hidden" 	name="path" 	value="<?php echo JURI::Base(); ?>">
						<input id="id" 			type="hidden" 	name="id" 		size="40" 		value="<?php echo $val->id; ?>">
						<input id="<?php echo "left".$val->id ?>" 			type="hidden" 	name="<?php echo "left".$val->id ?>" 		size="40" value="">
						<input id="<?php echo "timer".$val->id ?>" 		type="hidden" 	name="<?php echo "timer".$val->id ?>" 		size="40" value="<?php echo $val->timer; ?>">
					    <input id="<?php echo "end_time".$val->id ?>" 		type="hidden" 	name="<?php echo "end_time".$val->id ?>" 	size="40" value="<?php echo $end_date; ?>">
						<input id="<?php echo "start_time".$val->id ?>" 	type="hidden" 	name="<?php echo "start_time".$val->id ?>" size="40" value="<?php echo $val->start_time; ?>">
						<input id="<?php echo "end_date".$val->id ?>" 		type="hidden" 	name="<?php echo "end_date".$val->id ?>" 	size="40" value="<?php echo $val->end_time; ?>">

					</tr>
				<?php
					 }
				}?>
				</table>
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