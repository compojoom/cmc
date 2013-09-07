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
 * Class JFormFieldPhone
 *
 * @since  1.4
 */
class JFormFieldPhone extends JFormFieldText
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'Phone';

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
		$title = JText::_((string) $this->element['label']);
		$id = (string) $this->element['id'];
		$class = 'phone ';
		$class .= $this->element['class'] ? (string) $this->element['class'] : '';

		if ($this->required)
		{
			$class .= ' requried';
			$title = $title . ' *';
		}

		if (strstr($class, 'inter'))
		{
			$phone = '<input name="' . $this->group . '[groups][' . $name . ']' . '" id="' . $id . '" '
				. ' class="' . $class . '"'
				. ' type="text" size="25" value="" title="' . $title . '" size="2" maxlength="3" />';
		}
		else
		{
			$phone = '<input name="' . $this->group . '[groups][' . $name . '][area]" id="' . $id . '" '
				. ' class="' . $class . '"'
				. ' type="text" size="25" value="" title="' . $title . '" size="2" maxlength="3" />';

			$phone .= '<input name="' . $this->group . '[groups][' . $name . '][detail1]" id="' . $id . '" '
				. ' class="' . $class . '"'
				. ' type="text" size="25" value="" title="' . $title . '" size="2" maxlength="3" />';


			$phone .= '<input name="' . $name . '[groups][' . $name . '][detail2]" id="' . $this->id . '" '
				. ' class="' . $class . '"'
				. ' type="text" size="25" value="" title="' . $title . '" size="2" maxlength="4" />';
		}

		return $phone;
	}
}
