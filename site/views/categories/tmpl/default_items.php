<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// No direct access
defined('_JEXEC') or die;
$path	 	= JURI::root();

$doc	 	= & JFactory::getDocument();
$cparams = & JComponentHelper::getParams('com_media');
$class					= ' class="first"';
$empty_categories_if	= 0;
$empty_categories_else	= 0;
$n    = count($this->items[$this->parent->id]);

if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) {

?>
<form action="<?php echo JRoute::_('index.php?option=com_jereverseauction'); ?>" method="post" name="adminForm" id="adminForm">
<table width="100%">
<?php
	foreach($this->items[$this->parent->id] as $id => $item){

		if($item->numitems > 0){
			$secid 			= $item->id;
			$category111 	= JCategories::getInstance('jereverseauction')->get($secid);
			$image_cat		= $category111 ->getParams()->get('image');
		?>
		<tr id="category" width="100%">
			<td id="cat_image_td" width="15%" >
				<div id="cat_image">
					<?php
					if ($image_cat!= '') : $cat_image = $category111->getParams()->get('image');
					else : $cat_image =  "components/com_jereverseauction/assets/images/no_photo_available.jpg";
					endif; ?>
					<div id="thumb_image_back"><img id="thumb_image" src="<?php echo $cat_image; ?>" /></div>
				</div>
			</td>
			<td id="cat_content_td" width="85%" >
				<div id="category_content">
					<a id="cat_title" href="<?php echo JRoute::_(jereverseauctionHelperRoute::getCategoryRoute($item->id));?>">
					<?php echo $item->title;?> </a>
					<div id="cat_desc">
						<?php echo JHtml::_('content.prepare', $item->description); ?>
					</div>
				</div>
			</td>

		</tr>
			<?php

				// subcategories
				if(count($item->getChildren()) > 0) {
					$this->items[$item->id] = $item->getChildren();
					$this->parent = $item;
					$this->maxLevelcat--;

				foreach($this->items[$this->parent->id] as $id => $item){

				if($item->numitems > 0){
					$secid 			= $item->id;
					$category111 	= JCategories::getInstance('jereverseauction')->get($secid);
					$image_cat		= $category111 ->getParams()->get('image');
				?>
				<tr id="category" width="100%">
					<td id="cat_image_td" width="15%">
						<div id="cat_image">
							<?php
							if ($image_cat!= '') : $cat_image = $category111->getParams()->get('image');
							else : $cat_image =  "components/com_jereverseauction/assets/images/no_photo_available.jpg";
							endif; ?>
							<div id="thumb_image_back"><img id="thumb_image" src="<?php echo $cat_image; ?>" /></div>
						</div>
					</td>
					<td id="cat_content_td" width="85%">
						<div id="category_content">
							<a  id="subcat_title" href="<?php echo JRoute::_(jereverseauctionHelperRoute::getCategoryRoute($item->id));?>">
							<?php echo $item->title;?> </a>
							<div id="cat_desc">
								<?php echo JHtml::_('content.prepare', $item->description); ?>
							</div>
						</div>
					</td>

				</tr>
			<?php
					}
				}
					//echo $this->loadTemplate('items');
					$this->parent = $item->getParent();
					$this->maxLevelcat++;
				}
			?>
		<?php
		  }
	}
	?>
	</table>
	<?php
}
if( $n > 0 ) {
?>
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
}
	?>
</div>
</form>
