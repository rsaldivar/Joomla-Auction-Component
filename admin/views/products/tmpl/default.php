<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $this->user->authorise('core.edit.state', 'com_jereverseauction.product');
$saveOrder	= $listOrder == 'pro.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_jereverseauction'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search">
				<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
			</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('com_jereverseauction_SEARCH_IN_NAME'); ?>" />
			<button type="submit">
				<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>
			</button>
			<button type="button" onclick="document.getElementById('filter_published').value='';document.getElementById('filter_cat_id').value=''; document.getElementById('filter_access').value='';  document.id('filter_search').value='';this.form.submit();">
				<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
			</button>
		</div>

		<div class="filter-select fltrt">
			<select name="filter_published" id="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value="">
					<?php echo JText::_('JOPTION_SELECT_PUBLISHED');?>
				</option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
			<select name="filter_cat_id" id="filter_cat_id" class="inputbox" onchange="this.form.submit()">
				<option value="">
					<?php echo JText::_('JOPTION_SELECT_CATEGORY');?>
				</option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_jereverseauction'), 'value', 'text', $this->state->get('filter.catid'));?>
			</select>

	        <select name="filter_access" id="filter_access" class="inputbox" onchange="this.form.submit()">
				<option value="">
					<?php echo JText::_('JOPTION_SELECT_ACCESS');?>
				</option>
				<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'));?>
			</select>
		</div>
	</fieldset>

	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
			    <th width="20">
			    	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php  echo count($this->items); ?>);" />
			    </th>
				<th align="left" style="text-align:left;">
					<?php echo JHtml::_('grid.sort',  'COM_JEREVERSE_AUCTION_PRODUCT_NAME', 'pro.prod_name', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_JEREVERSE_AUCTION_CAT', 'pro.catid', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_JEREVERSE_AUCTION_PRODUCT_PRICE', 'pro.prod_price', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_JEREVERSE_AUCTION_STATUS', 'pro.status', $listDirn, $listOrder); ?>
				</th>

				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'pro.published', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'pro.ordering', $listDirn, $listOrder); ?>
					<?php if ($canOrder && $saveOrder) :?>
					<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'products.saveorder'); ?>
					<?php endif; ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'pro.access', $listDirn, $listOrder); ?>
				</th>
				<th width="5">
					<?php echo JHtml::_('grid.sort', 'COM_JEREVERSE_AUCTION_ID', 'pro.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<td colspan="11">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>

		<tbody>
			<?php
			$n = count($this->items);//echo "<pre>"; print_r($this->items); echo "</pre>";exit;
			foreach ($this->items as $i => $item) :
				$ordering	= $listOrder == 'pro.ordering';
				$canCreate	= $this->user->authorise('core.create',		'com_jereverseauction.product.'.$item->id);
				$canEdit	= $this->user->authorise('core.edit',		'com_jereverseauction.product.'.$item->id);
				$canEditOwn	= $this->user->authorise('core.edit.own',	'com_jereverseauction.product.'.$item->id);
				$canChange	= $this->user->authorise('core.edit.state',	'com_jereverseauction.product.'.$item->id);

?>
				<tr class="row<?php echo $i % 2; ?>">
					<td>
			                        <?php echo JHtml::_('grid.id', $i, $item->id);?>
			                </td>

							<td>
								<?php if ($canEdit || $canEditOwn) : ?>
									<a href="<?php echo JRoute::_('index.php?option=com_jereverseauction&task=product.edit&id='.(int) $item->id); ?>">
										<?php echo $this->escape($item->prod_name); ?></a>
								<?php else : ?>
									<?php echo $this->escape($item->prod_name); ?>
								<?php endif; ?>
								<p class="smallsub">
									<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?></p>
							</td>
			       			<td class="center">
								<?php echo $this->escape($item->category_title); ?>
							</td>
			       			<td class="center">
								<?php echo $this->escape($item->min_bid_amount)." to ".$this->escape($item->max_bid_amount); ?>
							</td>
			       			<td class="center">
								<?php $status = $this->escape($item->status);
								if($status == 0) echo "Running";
								else echo "Finished"; ?>
							</td>

					<td align="center">

						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'products.', $canChange, 'cb'); ?>
					</td>
					<td class="order">
						<?php if ($canChange) : ?>
							<?php if ($saveOrder) :?>
								<?php if ($listDirn == 'asc') : ?>
									<span>
										<?php echo $this->pagination->orderUpIcon($i, ($item->catid == @$this->items[$i-1]->catid),'products.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?>
									</span>
									<span>
										<?php echo $this->pagination->orderDownIcon($i, $n, ($item->catid == @$this->items[$i+1]->catid), 'products.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?>
									</span>
								<?php elseif ($listDirn == 'desc') : ?>
									<span>
										<?php echo $this->pagination->orderUpIcon($i, ($item->catid == @$this->items[$i-1]->catid),'products.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?>
									</span>
									<span>
										<?php echo $this->pagination->orderDownIcon($i, $n, ($item->catid == @$this->items[$i+1]->catid), 'products.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?>
									</span>
								<?php endif; ?>
							<?php endif; ?>
							<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
							<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
						<?php else : ?>
							<?php echo $item->ordering; ?>
						<?php endif; ?>
					</td>
					<td align="center">
						<?php echo $item->access_level; ?>
					</td>
					<td align="center">
						<?php echo $item->id; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<p class="copyright" align="center">
	<?php require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' ); ?>
</p>