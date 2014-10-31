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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$path = JURI::root();
?>
<form action="<?php echo JRoute::_('index.php?option=com_jereverseauction&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="category-form" class="form-validate" enctype="multipart/form-data">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_JEREVERSE_AUCTION_COMMISSION_DETAILS' ); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('user_id'); ?>
				<?php echo $this->form->getInput('user_id'); ?></li>

				<li><?php echo $this->form->getLabel('prod_id'); ?>
				<?php echo $this->form->getInput('prod_id'); ?></li>

				<li><?php echo $this->form->getLabel('published'); ?>
				<?php echo $this->form->getInput('published'); ?></li>

				<li><?php echo $this->form->getLabel('status'); ?>
				<?php echo $this->form->getInput('status'); ?></li>

				<li><?php echo $this->form->getLabel('amount'); ?>
				<?php echo $this->form->getInput('amount'); ?></li>

				<li><?php echo $this->form->getLabel('trans_id'); ?>
				<?php echo $this->form->getInput('trans_id'); ?></li>

				<li><?php echo $this->form->getLabel('payment_type'); ?>
				<?php echo $this->form->getInput('payment_type'); ?></li>

				<li><?php echo $this->form->getLabel('paid_date'); ?>
				<?php echo $this->form->getInput('paid_date'); ?></li>

			</ul>
		</fieldset>

	</div>

	<div>
		<input type="hidden" name="task" value="commission.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<input type="hidden" name="site_path" id="site_path" value="<?php echo $path; ?>"/>
</form>

<p class="copyright" align="center">
	<?php require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' ); ?>
</p>