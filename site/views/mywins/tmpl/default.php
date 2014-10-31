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

<div id="je-myproduct">
	<?php
	if( $this->total > 0 ) {
		?>
		<form action="<?php echo JRoute::_('index.php?option=com_jereverseauction'); ?>" method="post" name="adminForm" id="adminForm">
			<?php
				echo "<div id=\"my_products\">".$this->loadTemplate('mywins')."</div>";
				echo "<div id=\"my_prod_pagination\">".$this->loadTemplate('mywinspagination')."</div>";
			?>
		</form>
	<?php
	}
	?>
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
