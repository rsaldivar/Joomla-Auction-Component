<?php
/**
 * JE Reverse Auction package
 * @author JExtension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2012 - 2013 JExtension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of contacts
 */
class JFormFieldOrdering extends JFormField
{
	/**
	 * The form field type.
	 */
	protected $type = 'Ordering';

	/**
	 * Method to get the field input markup.
	 */
	protected function getInput()
	{
		// Initialize variables.
			$html = array();
			$attr = '';

		// Initialize some field attributes.
			$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
			$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
			$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

		// Initialize JavaScript field attributes.
			$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// Get some field values from the form.
			$contactId	= (int) $this->form->getValue('id');
			$categoryId	= (int) $this->form->getValue('catid');


		// Build the query for the ordering list.
			$query = 'SELECT ordering AS value, prod_name AS text' .
					' FROM #__jereverseauction_products' .
					' WHERE catid = ' . (int) $categoryId .
					' ORDER BY ordering';

		// Create a read-only list (no name) with a hidden input to store the value.
			if ((string) $this->element['readonly'] == 'true') {
				$html[] = JHtml::_('list.ordering', '', $query, trim($attr), $this->value, $contactId ? 0 : 1);
				$html[] = '<input type="hidden" name="'.$this->text.'" value="'.$this->value.'"/>';
			}

		// Create a regular list.
			else {
				$html[] = JHtml::_('list.ordering', $this->text, $query, trim($attr), $this->value, $contactId ? 0 : 1);
			}

		return implode($html);
	}
}