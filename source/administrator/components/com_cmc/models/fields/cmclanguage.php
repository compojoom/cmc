<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

$language = JFactory::getLanguage();
$language->load('com_cmc', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('com_cmc', JPATH_ADMINISTRATOR, $language->getDefault(), true);
$language->load('com_cmc', JPATH_ADMINISTRATOR, null, true);
$language->load('mod_cmc', JPATH_SITE, null, true);
$language->load('mod_cmc', JPATH_SITE, $language->getDefault(), true);
$language->load('mod_cmc', JPATH_SITE, null, true);

/**
 * Class JFormFieldCmcLanguage
 *
 * @since  1.3
 */
class JFormFieldCmcLanguage extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'CmcLanguage';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		return '';
	}
}
