<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       07.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class JFormFieldBirthday
 *
 * @since  1.4
 */
class JFormFieldBirthday extends JFormFieldText
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'Birthday';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$name = (string) $this->element['name'];
		$label = (string) $this->element['label'];
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		$req = $this->required ? ' required="required" aria-required="true"' : '';

		$select = '<select name="' . $this->group . '[groups][' . $name . '][month]" id="'
			. $name . '_month" title="' . JText::_($label) . '" ' . $req . $class . '>';
		$select .= '<option value="">MM</option>';

		for ($i = 1; $i <= 12; $i++)
		{
			$select .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '">'
				. str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
		}

		$select .= '</select>';
		$select .= '<select name="' . $this->group . '[groups][' . $name . '][day]" id="'
			. $name . '_day" title="' . JText::_($label) . '" ' . $req . $class . '>';
		$select .= '<option value="">DD</option>';

		for ($i = 1; $i <= 31; $i++)
		{
			$select .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT)
				. '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
		}

		$select .= '</select>';

		return $select;
	}
}
