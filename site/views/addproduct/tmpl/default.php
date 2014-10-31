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

 $multi =$this->params->get("multi", "1");
 $user  = JFactory::getUser();
 $uid   = $user->id;
?>
<dl id="system-message" style="display : none">
	<dd class="warning message">
		<ul>
			<li><div id="je-error-message"></div></li>
		</ul>
	</dd>
</dl>
<?php if($multi == 1){
		if($uid != 0){ ?>
<div id="add-product">
	<?php
		echo $this->loadTemplate('fields');
	?>
</div>
<?php }else {
?>
<dl id="system-message">
	<dd class="warning message">
		<ul>
			<li><div id="je-error-message"><?php echo JText::_( 'COM_JEREVERSE_AUCTION_PLEASE_LOGIN' );?></div></li>
		</ul>
	</dd>
</dl>
<?php
	  }
} else {
?>
<dl id="system-message">
	<dd class="warning message">
		<ul>
			<li><div id="je-error-message"><?php echo JText::_( 'JE_CONTACT_ADMIN' );?></div></li>
		</ul>
	</dd>
</dl>
<?php
}?>
<?php
if($this->params->get('show_footertext', 1)) {
?>
	<p class="copyright" style="text-align : right; font-size : 10px;">
		<?php require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' ); ?>
	</p>
<?php
}
?>