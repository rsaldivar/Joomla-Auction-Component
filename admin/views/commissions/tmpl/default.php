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
$commission	= $this->params->get('commission');
$currency	= $this->params->get('currency');

?>

<form action="<?php echo JRoute::_('index.php?option=com_jereverseauction&view=commissions'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search">
				<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
			</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('com_jereverseauction_SEARCH_IN_NAME'); ?>" />
			<button type="submit">
				<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>
			</button>
			<button type="button" onclick="document.getElementById('filter_published').value='';document.id('filter_search').value='';this.form.submit();">
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
			<select name="filter_status" id="filter_status" class="inputbox" onchange="this.form.submit()">
				<option value="">
					<?php echo JText::_('COM_JEREVERSE_AUCTION_PAYMENT_STATUS');?>
				</option>
				<?php echo JHtml::_('select.options', $this->paymentstatus, 'value', 'text', $this->state->get('filter.status'), true);?>
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
					<?php echo JHtml::_('grid.sort', 'COM_JEREVERSE_AUCTION_OWNER_NAME', 'pro.user_id', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_JEREVERSE_AUCTION_USER_NAME', 'udetail.username', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_JEREVERSE_AUCTION_USER_EMAIL', 'udetail.email', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_JEREVERSE_AUCTION_PRODUCT_PRICE', 'pro.prod_amount', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_JEREVERSE_AUCTION_COMMISSION_PAYMENT'); ?>
				</th>
				<th width="6%">
					<?php echo JHtml::_('grid.sort', 'COM_JEREVERSE_AUCTION_TRANSACTION_ID', 'win.trans_id', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_JEREVERSE_AUCTION_PAYMENT_STATUS', 'pro.status', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_JEREVERSE_AUCTION_PRODUCT_ID', 'pro.id', $listDirn, $listOrder); ?>
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
			$n = count($this->items);//echo "<pre>"; print_r($this->items); echo "</pre>";
			foreach ($this->items as $i => $item) :
				$ordering	= $listOrder == 'pro.ordering';
				$canCreate	= $this->user->authorise('core.create',		'com_jereverseauction.product.'.$item->id);
				$canEdit	= $this->user->authorise('core.edit',		'com_jereverseauction.product.'.$item->id);
				$canEditOwn	= $this->user->authorise('core.edit.own',	'com_jereverseauction.product.'.$item->id);
				$canChange	= $this->user->authorise('core.edit.state',	'com_jereverseauction.product.'.$item->id);
				$status= $this->escape($item->commission_paid);
?>
				<tr class="row<?php echo $i % 2; ?>">
					<td>
	                        <?php echo JHtml::_('grid.id', $i, $item->id);?>
	                </td>

					<td>
						<?php echo $this->escape($item->prod_name); ?>
					</td>
	       			<td class="center">
						<?php echo $this->escape($item->product_owner); ?>
					</td>
	       			<td class="center">
						<?php echo $this->escape($item->username); ?>
					</td>
					<td class="center">
						<?php echo $this->escape($item->email); ?>
					</td>
					<td class="center">
						<?php echo $currency." ".$this->escape($item->amount); ?>
					</td>
					<td class="center">
						<?php
							$user		= JFactory::getUser($item->user_id);
							$userGroups = $user->get('groups');
							foreach($userGroups as $groupid) {
								$user_group = $groupid;
							}

						  if($user_group == '13'){
							$commission = '0';
						  }
						  echo $currency." ".$commission;
						 ?>
					</td>
					<td align="center">
						<?php echo $item->trans_id; ?>
					</td>
					<td class="center">
						<?php
							if($status == 0){
								echo JText::_('COM_JEREVERSE_AUCTION_PENDING');
							}else{
								echo  JText::_('COM_JEREVERSE_AUCTION_COMPLETED');
							} ?>
					</td>
					<td align="center">
						<?php echo $item->prod_id; ?>
					</td>
				</tr>
				<input type="hidden" name="prod_id" value="<?php echo $item->prod_id; ?>" />
				<input type="hidden" name="paypal_email" value="<?php echo $this->escape($item->paypal_email); ?>" />
			<?php 
				endforeach; ?>
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