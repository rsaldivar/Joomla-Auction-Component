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

$amount_c 	= $this->params->get("commission");
$currency 	= $this->params->get("currency", "&#8377;");
?>
<div class="buy_product" >
<table border="0" id="products" cellpadding="0" cellspacing="0">
		<tr>
			<td id="top-left"></td>
			<td id="top-center"></td>
			<td id="top-right"></td>
		</tr>
		<tr>
			<td id="middle-left"></td>
			<td>
				<table cellpadding="0" cellspacing="0" width="98%" border="0" class="paymentdetails">
					<tr>
						<td style="padding:10px;">
							<div id="category_page_heading"><?php echo JText::_("COM_JEREVERSE_AUCTION_PAYMENT_METHODS"); ?>
							<img align="right" src="components/com_jereverseauction/assets/images/arr.jpg" /></div>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<h4><?php echo JText::_("COM_JEREVERSE_AUCTION_PAY_COMMISSION")."&nbsp;".$currency."&nbsp;".$amount_c; ?></h4>
						</td>
					</tr>
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" width="100%" border="0">
								<?php
								$i = 0;
									foreach($this->PaymentMethodDetails AS $pminfo)
									{
										$paymentLogoPath = JURI::root()."plugins/wdcauctionpayment/".$this->plugin_info[$i]->name."/images/".$pminfo["logo"];
										?>
											<tr>
												<td style="border:solid 1px #e5eff8;background-color:#f4f9fe;padding:5px;">
													<?php echo $pminfo; ?>
												</td>
											</tr>
											<tr>
												<td>&nbsp;</td>
											</tr>
										<?php
										$i++;
									}
								?>
							</table>
						</td>
					</tr>
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
</div>
