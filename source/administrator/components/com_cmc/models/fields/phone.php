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
		$class = $this->element['class'] ? (string) $this->element['class'] : '';

		if ($this->required)
		{
			$class .= ' requried';
			$title = $title . ' *';
		}

		if (strstr($class, 'inter'))
		{
			$phone = '<input name="' . $this->group . '[' . $name . ']' . '" id="' . $id . '" '
				. ' class="' . $class . '"'
				. ' type="text" value="" title="' . $title . '" />';
		}
		else
		{
			$phone = '(<input name="' . $this->group . '[' . $name . '][area]" id="' . $id . '-area" '
				. ' class="' . $class . ' cmc-us-format-area"'
				. ' type="text" value="" title="' . $title . '" size="2" maxlength="3" />)';

			$phone .= ' <input name="' . $this->group . '[' . $name . '][detail1]" id="' . $id . '-detail1" '
				. ' class="' . $class . ' cmc-us-format-detail1"'
				. ' type="text" value="" title="' . $title . '" size="2" maxlength="3" />';


			$phone .= ' - <input name="' . $this->group . '[' . $name . '][detail2]" id="' . $this->id . '-detail2" '
				. ' class="' . $class . ' cmc-us-format-detail2"'
				. ' type="text" value="" title="' . $title . '" size="2" maxlength="4" />';
		}

		return $phone;
	}
}
