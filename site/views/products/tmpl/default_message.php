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

$prod_id			= JRequest::getVar('id');
$msg_id				= JRequest::getVar('uid');
$bidders			= $this->model->getbidders($prod_id);
$currency 	=$this->params->get('currency', '&#8377;');

foreach($bidders as $bidder_details){

	if($bidder_details->id == $msg_id){

	$user   	 = JFactory::getUser($bidder_details->user_id);
	$user_email  = $user->email;
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
			<div id="category_page_heading">
				<?php echo JText::_('COM_JEREVERSE_AUCTION_MESSAGE_PAGE'); ?>
				<img align="right" src="components/com_jereverseauction/assets/images/arr.jpg" />
			</div>
			<br />
			<table id="message_table">
				<tr>
					<td id="td_buynow_new"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_USERNAME'); ?></div></td>
					<td><?php echo $this->escape($bidder_details->user_name); ?></td>
				</tr>
				<tr>
					<td id="td_buynow_new"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_BIDAMOUNT'); ?></div></td>
					<td><span id="currency"><?php echo $currency;?></span><?php echo $this->escape($bidder_details->amount); ?></td>
				</tr>
				<tr>
					<td id="td_buynow_new"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_CONTACT_USER'); ?></div></td>
					<td>						
						<a onclick="contactuser()"><?php echo JText::_('COM_JEREVERSE_AUCTION_CONTACT_USER_FUL'); ?></a>
						<div id="contact_user" style="display:none;padding:5px;">
							<table>
								<tr style="height:35px;">
									<td><div><?php echo JText::_('COM_JEREVERSE_AUCTION_EMAIL_ID'); ?></td>
									<td><input class="input_box" type="text" name="emailid" id="emailid" size="25" value="" /></td>
								</tr>
								<tr style="height:35px;">
									<td><div><?php echo JText::_('COM_JEREVERSE_AUCTION_SUBJECT'); ?></td>
									<td><input class="input_box" type="text" name="subject" id="subject" size="25" value="" /></td>
								</tr>
								<tr style="height:35px;">
									<td><div><?php echo JText::_('COM_JEREVERSE_AUCTION_MESSAGE_TEXT'); ?></td>
									<td><textarea rows="4" cols="20" id="mail_message" name="mail_message"></textarea></td>
								</tr>
								<tr style="height:35px;">
									<td colspan="2" align="right"><button onClick="send_email()" value="Contact User" class="button"/>Contact User</button></td>
								</tr>
							</table>
							<input class="input_box" type="hidden" name="path" id="path" value="<?php echo JURI::Base(); ?>" />
							<input class="input_box" type="hidden" name="tomail" id="tomail" value="<?php echo $user_email; ?>" />

						</div>
						<div id="message_text"></div>
					
					</td>
				</tr>
				<tr>
					<td id="td_buynow_new"><div><?php echo JText::_('COM_JEREVERSE_AUCTION_USERMESSAGE'); ?></div></td>
					<td><?php echo $this->escape($bidder_details->message); ?></td>
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
<?php
	}
}
if($this->params->get('show_footertext', 1)) {
?>
	<p class="copyright" style="text-align : right; font-size : 10px;">
		<?php require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' ); ?>
	</p>
<?php
}
?>