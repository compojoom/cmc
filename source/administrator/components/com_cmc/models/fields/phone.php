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
		$class = $this->element['class'] ? (string) $this->element['class'] : '';


		if (strstr($class, 'inter'))
		{
			$phone = parent::getInput();
		}
		else
		{
			$name = $this->name;
			$value = explode('-', $this->value);
			$this->name = $name . '[area]';
			$this->value = isset($value[0]) ? $value[0] : '';
			$phone = '(' . parent::getInput() . ')';

			$this->name = $name . '[detail1]';
			$this->value = isset($value[1]) ? $value[1] : '';
			$phone .= parent::getInput();

			$this->name = $name . '[detail2]';
			$this->value = isset($value[2]) ? $value[2] : '';

			$phone .= parent::getInput();
		}

		return $phone;
	}
}
