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

// Include script into the header
	JHtml::_('script','components/com_jereverseauction/assets/js/auction_jquery.js');
	$id   =JRequest::getVar('id');
	JHTML::_('behavior.modal', 'a.mymodal');
?>
<body onload="time_difference()">
<?php
$value 		=$this->data;
$currency 	=$this->params->get('currency', '&#8377;');
$user     	=& JFactory::getUser();
$uid		=$user->id;

foreach($value as $val){
	 $date_end			= JFactory::getDate($val->end_time);
	 $end_date			= trim($date_end->toFormat('%Y/%m/%d %H:%M:%S'));

	if($id == $val->id ){

	 $url				= JURI::Base().'index.php?option=com_jereverseauction&view=products&layout=default_detail&id='.$val->id;
	 $ship_link 		= 'index.php?option=com_jereverseauction&view=products&layout=default_shipping&id='.$val->id;

	 $user_val     		=&JFactory::getUser($val->user_id);
	 $bidders			= $this->model->getbidders($val->id);

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
				<div id="error" name="error"></div>
				<div id="<?php echo "product_".$val->id ?>" class="buy_product" >
					<div id="prod_container_buy">
						<div id="<?php echo 'displayerror'.$val->id ?>" class="displayerror"></div>
						<table width="100%" id="myproductlist">
							<tr>
								<td valign="top" width="60%" style="float: left; width: 400px">
									<table id="product_heading" width="100%" >
										<tr>
											<td colspan="2">
												<div id="prod_name"><?php echo $val->prod_name; ?></div>
											</td>
										</tr>
									</table>
									<div id="reverse_backgroud_detail_top">
										<div id="reverse_backgroud_1">
											<div id="reverse_backgroud_2">
												<div id="reverse_backgroud_3_detail_left">
													<table width="100%" id="myproductlist">
														<tr id="tr_buynow">
															<td id="td_buynow"><div id="<?php echo "bid_amt" ?>"><?php echo JText::_('COM_JEREVERSE_AUCTION_BEST_DEAL');?></div></td>
															<td id="tr_buynow"><span id="currency"><?php echo $currency;?></span><span class="currency" id="<?php echo "bid_amt".$val->id ?>"><?php echo $val->prod_amount;?></span></td>
														</tr>
														<tr id="tr_buynow">
															<td id="td_buynow"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_LATEST_BIDDER');?></div></td>
															<td id="tr_buynow"><div id="<?php echo "latest_bidder".$val->id ?>"><?php echo $val->user_name;?></div></td>
														</tr>
														<tr id="tr_buynow">
															<td id="td_buynow"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_TIME_LEFT');?></div></td>
															<td id="tr_buynow"><div id="<?php echo "time_left".$val->id ?>"><?php echo $val->end_time; ?></div></td>
														</tr>
														<tr id="tr_buynow">
															<td id="td_buynow"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_BIDDERS');?></div></td>
															<td id="tr_buynow"><div><?php echo count($bidders) ?></div></td>
														</tr>
													</table>
												</div>
											</div>
										</div>
									</div>
									<br />
									<div id="reverse_backgroud_detail_top">
										<div id="reverse_backgroud_1">
											<div id="reverse_backgroud_2">
												<div id="reverse_backgroud_3_detail_top">
													<table id="right_buynow" width="100%" >
														<tr>
															<td colspan="2">
																<div id="prod_name">
																	<?php echo JText::_('COM_JEREVERSE_AUCTION_BID_HERE'); ?>
																</div>
															</td>
														</tr>
														<tr>
															<td align="right" class="key" id="bid_here">
																<label for="name">
																	<?php echo JText::_( 'COM_JEREVERSE_AUCTION_BID_AMOUNT' ); ?>:
																</label>
															</td>
															<td align="left" id="bid_here">
																<input class="input_box" type="text" name="amount" id="amount" size="22" maxlength="256" value="" />
															</td>
														</tr>
														<tr >
															<td colspan="2">
																<div style="color: #FF5F05; text-transform: uppercase; font-size: 9px; margin: -16px 0px; font-weight: bold; padding-right: 53px; float: right;">(Subasta con efectos legales)</div>
															</td>
														</tr>
														<tr>
															<td align="right" class="key" id="bid_here">
																<label for="name">
																	<?php echo JText::_( 'COM_JEREVERSE_AUCTION_MESSAGE' ); ?>:
																</label>
															</td>
															<td align="left" id="bid_here">
																<textarea rows="4" cols="20" id="Bidmessage" name="message"></textarea>
															</td>
														</tr>
														<tr>
															<td align="center" colspan="2">
																<img id="bid_button1" src="components/com_jereverseauction/assets/images/bidNow.png" alt="Bid Now" onClick="bid( <?php echo $val->id; ?>)"/>
															</td>
														</tr>
													</table>
											 	</div>
											</div>
										</div>
									</div>

								</td>
								<td valign="top" width="40%" style="float: left; width: 435px;">
									<?php $link_image = $val->prod_image ? 'components/com_jereverseauction/assets/images/products/product_'.$val->prod_image : 'components/com_jereverseauction/assets/images/no_photo_available.jpg'; ?>
									<div id="prod_image"><img id="prod_image_new" src="<?php echo $link_image; ?>" alt="product image"  /></div>
									<div id="related_images">

										<div id="Previous" onclick="previous()"></div>
										<div id="slidercontainer">
											<?php $details = explode(',' ,$val->prod_detail_image);

											$width = ( count($details) * 80) + 90;
											echo '<div id="centerimage" style="width:'.$width.'px">';
											echo "<img style=\"float:left;padding:5px;\" id=\"thumbnail_image\" src=\"$link_image\" alt=\"product image\" height=\"70\" width=\"70\" onClick=\"displayImageIn('$link_image')\"/>";
											if($val->prod_detail_image){
											for($i=0 ; $i< count($details);$i++){
											$link_image_new  = "components/com_jereverseauction/assets/images/products/product_".$details[$i];
											$link_image_new1  = "components/com_jereverseauction/assets/images/products/thumbnail_".$details[$i];
											?>
												<img style="float:left;padding:5px;" id="thumbnail_image" src="<?php echo $link_image_new1; ?>" alt="product image" height="70" width="70" onClick="displayImageIn( <?php echo "'$link_image_new'"; ?>)"/>
											<?php }}	?>
											</div>
										</div>
										<div id="Next" onclick="next()"></div>
										<br style="clear:both"/>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div>
										<ul class="product_tab">
											<li id="desc" onclick="showdesc()"><?php echo JText::_('COM_JEREVERSE_AUCTION_PRODUCT_DESC'); ?></li>
											<li id="prod_details" onclick="showdetails()"><?php echo JText::_('COM_JEREVERSE_AUCTION_PRODUCT_DETAILS'); ?></li>
											<li id="bidder" onclick="showbidder()"><?php echo JText::_('COM_JEREVERSE_AUCTION_BIDDER_DETAILS'); ?></li>
										</ul>
									</div>
									<br style="clear:both;" />
									<div id="reverse_backgroud_detail">
										<div id="reverse_backgroud_1">
											<div id="reverse_backgroud_2">
												<div id="reverse_backgroud_3_detail">
													<div id="description">
														<div id="description_heading"><?php echo JText::_('COM_JEREVERSE_AUCTION_PRODUCT_DESC'); ?></div>
														<div id="description_text"><?php echo $val->description; ?></div>
													</div>

													<div id="additional_details">
														<div id="description_heading"><?php echo JText::_('COM_JEREVERSE_AUCTION_PRODUCT_DETAILS'); ?></div>
														<table id="additional_details_info">

															<tr>
																<td id="td_buynow"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_ADDEDBY'); ?></div></td>
																<td><?php echo $user_val->name; ?></td>
															</tr>
															<tr>
																<td id="td_buynow"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_MAX_BID_PRICE'); ?></div></td>
																<td><span id="currency"><?php echo $currency;?></span><?php echo $val->max_bid_amount; ?></td>
															</tr>
															<tr>
																<td id="td_buynow"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_MIN_BID_PRICE'); ?></div></td>
																<td><span id="currency"><?php echo $currency;?></span><?php echo $val->min_bid_amount; ?></td>
															</tr>
															<tr>
																<td id="td_buynow"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_BIDDERS'); ?></div></td>
																<td><?php echo count($bidders); ?></td>
															</tr>
															<tr>
																<td id="td_buynow"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_STRAT_BID_DATE'); ?></div></td>
																<td>
																	<?php   $date_start			= JFactory::getDate($val->start_time);
																			echo trim($date_start->toFormat('%d/%m/%Y %H:%M:%S '));?>
																</td>
															</tr>
															<tr>
																<td id="td_buynow"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_END_DATE'); ?></div></td>
																<td><?php echo trim($date_end->toFormat('%d/%m/%Y %H:%M:%S ')); ?></td>
															</tr>
														</table>
													</div>
													<div id="bidder_details">
														<div id="description_heading"><?php echo JText::_('COM_JEREVERSE_AUCTION_BIDDER_DETAILS'); ?></div>

														<?php if( count($bidders) > 0 ){
															$message_link		= JURI::Base().'index.php?option=com_jereverseauction&view=products&layout=default_message&tmpl=component&id='.$val->id.'&uid=';
															?>
															<table width="100%" cellspacing="0" cellpadding="0" id="default_product_table">
																<tr id="headingrow">
																	<th id="heading_td1"><?php echo JText::_('COM_JEREVERSE_AUCTION_RNO'); ?></th>
																	<th id="heading_td1"><?php echo JText::_('COM_JEREVERSE_AUCTION_USERNAME'); ?></th>
																	<th id="heading_td1"><?php echo JText::_('COM_JEREVERSE_AUCTION_USERMESSAGE'); ?></th>
																	<th id="heading_td1"><?php echo JText::_('COM_JEREVERSE_AUCTION_BIDAMOUNT'); ?></th>
																</tr>
															<?php  for($i = 0 ; $i < count($bidders) ; $i++ ){
															?>
															<tr class="row<?php echo $i % 2; ?>" >
																<td id="pro_name" align="center">
																	<?php echo $i+1; ?>
																</td>
																<td align="center">
																	<?php echo $this->escape($bidders[$i]->user_name); ?>
																</td>
																<td align="center">
																	<div>
																		 <a rel="{handler: 'iframe',size: {x: 550, y: 550}}" class="mymodal" href="<?php echo $message_link.$bidders[$i]->id; ?>" ><?php echo substr ( $bidders[$i]->message, 0, 50 ) . '...' ?></a>
																	</div>
																</td>
																<td align="center">
																	<?php echo $this->escape($bidders[$i]->amount); ?>
																</td>
															</tr>
															<?php
																	}
															?>
															</table>
														<?php
															}else{
														?>
															<div id=""><?php echo JText::_('COM_JEREVERSE_AUCTION_NO_BIDS_YET'); ?></div>
														<?php
																}
														?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</td>
							</tr>
						</table>
					</div>
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
	}?>
	<input id="uid" 		type="hidden" 	name="uid" 		value="<?php echo $uid; ?>">
	<input id="path" 		type="hidden" 	name="path" 	value="<?php echo JURI::Base(); ?>">
	<input id="id" 			type="hidden" 	name="id" 		size="40" 		value="<?php echo $val->id; ?>">
	<input id="<?php echo "left".$val->id ?>" 			type="hidden" 	name="<?php echo "left".$val->id ?>" 		size="40" value="">
	<input id="<?php echo "timer".$val->id ?>" 		type="hidden" 	name="<?php echo "timer".$val->id ?>" 		size="40" value="<?php echo $val->timer; ?>">
	<input id="<?php echo "end_time".$val->id ?>" 		type="hidden" 	name="<?php echo "end_time".$val->id ?>" 	size="40" value="<?php echo $end_date; ?>">
	<input id="<?php echo "start_time".$val->id ?>" 	type="hidden" 	name="<?php echo "start_time".$val->id ?>"  size="40" value="<?php echo $val->start_time; ?>">
	<input id="<?php echo "end_date".$val->id ?>" 		type="hidden" 	name="<?php echo "end_date".$val->id ?>" 	size="40" value="<?php echo $val->end_time; ?>">

<?php
}
?>

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
