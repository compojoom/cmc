<?php
/**
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       22.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
JLoader::register('CmcField', JPATH_ADMINISTRATOR . '/components/com_cmc/models/fields/field.php');
JLoader::register('cmcHelperChimp', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/chimp.php');

/**
 * Class JFormFieldCmclists
 *
 * @since  1.2
 */
class JFormFieldCmclists extends CmcField
{
	/**
	 * Method to get the field input markup.
	 *
	 * @return string
	 */
	public function getInput()
	{
		$content = '';
		$api = new cmcHelperChimp;
		$lists = $api->lists();

		$key = 'id';
		$val = 'name';
		$options[] = array($key => '', $val => '-- ' . JText::_('MOD_CMC_PLEASE_SELECT_A_LIST') . ' --');

		foreach ($lists['data'] as $list)
		{
			$options[] = array($key => $list[$key], $val => $list[$val]);
		}

		$option = JFactory::getApplication()->input->get('option');
		$controller = in_array($option, array('com_modules', 'com_advancedmodules')) ? 'module' : 'plugin';

		$attribs = "onchange='submitbutton(\"$controller.apply\")'";

		if ($options)
		{
			$content = JHtml::_('select.genericlist', $options, 'jform[params][listid]', $attribs, $key, $val, $this->value, $this->id);
		}

		return $content;
	}
}
