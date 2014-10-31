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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<form action="<?php echo JRoute::_('index.php?option=com_jereverseauction&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="subscription-form" class="form-validate">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend>
				<?php echo empty($this->item->id) ? JText::_('COM_JEREVERSE_AUCTION_NEW_MAILTEMPLATES') : JText::sprintf('COM_JEREVERSE_AUCTION_EDIT_MAILTEMPLATES', $this->item->id); ?>
			</legend>
			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('id'); ?>
					<?php echo $this->form->getInput('id'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('subject'); ?>
					<?php echo $this->form->getInput('subject'); ?>
				</li>
			</ul>

			<div class="clr"></div>

			<?php echo $this->form->getLabel('mailbody'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('mailbody'); ?>
		</fieldset>
	</div>


	<input type="hidden" name="task" value="mailtemplate.edit" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<div class="clr"></div>

<br/>
<p class="copyright" align="center">
	<?php require_once( JPATH_COMPONENT . DS . 'copyright' . DS . 'copyright.php' ); ?>
</p>