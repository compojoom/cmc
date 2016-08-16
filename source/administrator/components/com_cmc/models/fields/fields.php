<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
JLoader::register('CmcField', JPATH_ADMINISTRATOR . '/components/com_cmc/models/fields/field.php');
JLoader::register('MCAPI', JPATH_ADMINISTRATOR . '/components/com_cmc/helper/MCAPI.class.php');
JLoader::discover('CmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

/**
 * Class JFormFieldFields
 *
 * @since  1.0
 */
class JFormFieldFields extends CmcField
{
	/**
	 * Method to get the field input markup.
	 *
	 * @return string
	 */
	public function getInput()
	{
		$listid = $this->form->getValue('listid', 'params');

		if (!$listid)
		{
			return "";
		}

		$options = CmcHelperList::getMergeFields($listid);
		$key = 'tag';
		$val = 'name';

		$attribs = 'multiple="multiple" size="8" class="chzn-none chzn-done"';

		if ($options)
		{
			$content = JHtml::_(
				'select.genericlist', $options, 'jform[params][fields][]',
				$attribs, $key, $val, $this->value, $this->id
			);
		}
		else
		{
			$content = '<div style="float:left;">' . JText::_('MOD_CMC_NO_FIELDS') . '</div>';
		}

		return $content;
	}
}
