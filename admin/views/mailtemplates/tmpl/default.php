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
$canOrder	= $this->user->authorise('core.edit.state', 'com_jereverseauction');
?>

<form action="<?php echo JRoute::_('index.php?option=com_jereverseauction'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search">
				<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
			</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JEMEMBERSHIP_SEARCH_IN_SUBSCRIPTIONNAME'); ?>" />
			<button type="submit">
				<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>
			</button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();">
				<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
			</button>
		</div>
	</fieldset>

	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="3%">
					<?php echo JText::_( 'COM_JEREVERSE_AUCTION_SERNO' ); ?>
				</th>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
				<th>
					<?php echo JHtml::_('grid.sort',  'COM_JEREVERSE_AUCTION_MAILTEMPLATE_SUBJECT', 'mail.subject', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'mail.published', $listDirn, $listOrder); ?>
				</th>
				<th width="1%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'mail.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<td colspan="8">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>

		<tbody>
			<?php
			$n = count($this->items);
			if($n > 0) {
				foreach ($this->items as $i => $item) :
					$canCreate	= $this->user->authorise('core.create', 'com_jereverseauction');
					$canEdit	= $this->user->authorise('core.edit', 'com_jereverseauction');
					$canEditOwn	= $this->user->authorise('core.edit.own', 'com_jereverseauction');
					$canChange	= $this->user->authorise('core.edit.state', 'com_jereverseauction');
			?>
					<tr class="row<?php echo $i % 2; ?>">
						<td align="center">
							<?php echo $i+1; ?>
						</td>
						<td class="center">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<td>
							<?php
							if ($canEdit || $canEditOwn) :
							?>
								<span class="editlinktip hasTip" title="<?php echo addslashes(htmlspecialchars(JText::_('COM_JEREVERSE_AUCTION_TOOLTIP_EDIT_MAILTEMPLATE').'::'.$item->subject)); ?>">
									<a href="<?php echo JRoute::_('index.php?option=com_jereverseauction&task=mailtemplate.edit&id='.(int) $item->id); ?>">
										<?php echo $item->subject; ?>
									</a> &nbsp;
								</span>
							<?php
							endif;
							?>
						</td>
						<td align="center">
							<?php echo JHtml::_('jgrid.published', $item->published, $i, 'mailtemplates.', $canChange, 'cb'); ?>
						</td>
						<td align="center">
							<?php echo $item->id; ?>
						</td>
					</tr>
			<?php
				endforeach;
			} else {
				?>
					<tr>
						<td colspan="8"><?php echo JText::_('COM_JEREVERSE_AUCTION_MAILTEMPLATES_NOITEM_FOUND'); ?></td>
					</tr>
				<?php
			}
			?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="view" value="mailtemplates" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<br/>
<p class="copyright" align="center">
	<?php require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' ); ?>
</p>