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