<?php
/**
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       22.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Copyright (C) 2011  freakedout (www.freakedout.de)
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 **/

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
		$val = 'name';

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
