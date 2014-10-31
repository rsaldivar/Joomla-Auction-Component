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

// Code for form validator
	JHtml::_('behavior.formvalidation');
	JHtml::_('behavior.modal', 'a.modal');
?>
<form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option=com_jereverseauction&id='.(int) $this->item->id); ?>" method="post" class="form-validate" enctype="multipart/form-data">
<table border="0" id="products" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td id="top-left"></td>
			<td id="top-center"></td>
			<td id="top-right"></td>
		</tr>
		<tr>
			<td id="middle-left"></td>
			<td>
				<fieldset class="add_product">
				<div id="category_page_heading">
					<?php echo JText::_('COM_JEREVERSE_AUCTION_ADD_PRODUCT'); ?>
					<img align="right" src="components/com_jereverseauction/assets/images/arr.jpg" />
				</div>
					<?php $i = 0;
						foreach ($this->form->getFieldset('addproduct') as $key=>$field):
							if ($field->name != 'jform[id]' && $field->name != 'jform[published]' && $field->name != 'jform[access]' && $field->name != 'jform[ordering]'):
								echo "<table id=\"addprod\">";

						?>
							<tr width="100%" class="formelm" <?php if($i == 4) echo "id = pro_image"?> >
								<td width="30%"><?php echo $field->label; ?></td>
								<?php if($field->fieldname=='description'){ ?>
								<td width="50%"><?php echo $field->input; ?></td>
								<?php } else { ?>
								<td width="50%"><?php echo $field->input; ?></td>
								<?php } ?>
							</tr>
						<?php
							   echo "</table>";
						    endif;
						?>
					<?php endforeach; ?>

				</fieldset>
			</td>
			<td id="middle-right"></td>
		</tr>
		<tr>
			<td id="bottom-left"></td>
			<td id="bottom-center"></td>
			<td id="bottom-right"></td>
		</tr>
	</table>

		<input type="hidden" name="option" value="com_jereverseauction" />
		<input type="hidden" name="front-end" value="1" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="je-errorwarning-message" id="je-errorwarning-message" value="<?php echo JText::_('COM_JEREVERSE_AUCTION_VALIDATION_FORM_FAILED'); ?>"/>
		<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
		<?php echo JHtml::_( 'form.token' ); ?>
</form>

<fieldset>
	<div class="formelm-buttons">
		<button type="button" class="button" onclick="Joomla.submitbutton('addproduct.save')">
			<?php echo JText::_('JSAVE') ?>
		</button>
		<button type="button" class="button" onclick="Joomla.submitbutton('addproduct.cancel')">
			<?php echo JText::_('JCANCEL') ?>
		</button>
	</div>
</fieldset>
