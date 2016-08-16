<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JLoader::discover('cmcHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/');

/**
 * Class JFormFieldInterests
 *
 * @since 1.0
 */
class JFormFieldInterests extends CmcField
{
	/**
	 * Method to get the field input markup.
	 *
	 * @return string
	 */
	public function getInput()
	{
		$listid = $this->form->getValue('listid', 'params');
		$options = CmcHelperList::getInterestsFields($listid);
		$key = 'id';
		$val = 'title';

		$attribs = 'multiple="multiple" size="8" class="chzn-none chzn-done"';

		if ($options)
		{
			$content = JHtml::_('select.genericlist', $options, 'jform[params][interests][]', $attribs, $key, $val, $this->value, $this->id);
		}
		else
		{
			$content = '<div style="float:left;">' . JText::_('MOD_CMC_NO_INTEREST_GROUPS') . '</div>';
		}

		return $content;
	}
}
