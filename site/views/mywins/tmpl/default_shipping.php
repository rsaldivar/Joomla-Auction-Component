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
?>
<div class="buy_product" >
<table border="0" id="products" cellspacing="0">
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
						<td>
							<h2 style="font-size:2.1em;line-height:30px;"><?php echo JText::_("COM_WDCAUCTION_PAYMENT_METHODS"); ?></h2>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
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
