<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
$path = JURI::root();
?>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td>

<form action="<?php echo JRoute::_('index.php?option=com_jereverseauction&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="category-form" class="form-validate"  enctype="multipart/form-data">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_JEREVERSE_AUCTION_PRODUCT_DETAILS' ); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('prod_name'); ?>
				<?php echo $this->form->getInput('prod_name'); ?></li>

				<li><?php echo $this->form->getLabel('alias'); ?>
				<?php echo $this->form->getInput('alias'); ?></li>

				<li>
					<?php echo $this->form->getLabel('catid'); ?>
					<?php echo $this->form->getInput('catid'); ?>
				</li>

				<li><?php echo $this->form->getLabel('published'); ?>
				<?php echo $this->form->getInput('published'); ?></li>

				<li><?php echo $this->form->getLabel('access'); ?>
				<?php echo $this->form->getInput('access'); ?></li>

				<li><?php echo $this->form->getLabel('prod_image'); ?>
				<?php echo $this->form->getInput('prod_image'); ?></li>

				<li><?php echo $this->form->getLabel('prod_detail_image'); ?>
				<?php echo $this->form->getInput('prod_detail_image'); ?></li>

				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>

			</ul>
			<div class="clr"></div>
			<?php echo $this->form->getLabel('description'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('description'); ?>
	</div>

	<div class="width-40 fltrt">

		<?php echo JHtml::_('sliders.start', 'product-slider'); ?>
		<?php echo JHtml::_('sliders.panel', JText::_("COM_JEREVERSE_AUCTION_PRODUCT_EXTRA_FIELDS"), 'extrafields');?>
		<fieldset class="panelform" >
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('max_bid_amount'); ?>
				<?php echo $this->form->getInput('max_bid_amount'); ?></li>

				<li><?php echo $this->form->getLabel('min_bid_amount'); ?>
				<?php echo $this->form->getInput('min_bid_amount'); ?></li>

				<li><?php echo $this->form->getLabel('coupon_code'); ?>
				<?php echo $this->form->getInput('coupon_code'); ?></li>

				<li><?php echo $this->form->getLabel('coupon_validity'); ?>
				<?php echo $this->form->getInput('coupon_validity'); ?></li>

			</ul>
		</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>


		<?php echo JHtml::_('sliders.start', 'dealtime-slider'); ?>
		<?php echo JHtml::_('sliders.panel', JText::_("COM_JEREVERSE_AUCTION_DEAL_TIME_FIELDS"), 'dealtime');?>
		<fieldset class="panelform" >
			<ul class="adminformlist">

				<li><?php echo $this->form->getLabel('start_time'); ?>
				<?php echo $this->form->getInput('start_time'); ?></li>

				<li><?php echo $this->form->getLabel('end_time'); ?>
				<?php echo $this->form->getInput('end_time'); ?></li>

				<li><?php echo $this->form->getLabel('ordering'); ?>
				<?php echo $this->form->getInput('ordering'); ?></li>
			</ul>
		</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>
	</div>

	<div>
		<input type="hidden" name="task" value="product.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<input type="hidden" name="site_path" id="site_path" value="<?php echo $path; ?>"/>
</form>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
<td>
<p class="copyright" align="center">
	<?php require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' ); ?>
</p>
</td>
</tr>
</table>


