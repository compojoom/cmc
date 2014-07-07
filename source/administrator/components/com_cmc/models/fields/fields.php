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
JLoader::register('MCAPI', JPATH_ADMINISTRATOR . '/components/com_cmc/helper/MCAPI.class.php');

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
		$api = new cmcHelperChimp;
		$fields = $api->listMergeVars($listid);
		$key = 'tag';
		$val = 'name';
		$options = false;

		if ($fields)
		{
			foreach ($fields as $field)
			{
				$choices = '';

				if (isset($field['choices']))
				{
					foreach ($field['choices'] as $c)
					{
						$choices .= $c . '##';
					}

					$choices = substr($choices, 0, -2);
				}

				$req = ($field['req']) ? 1 : 0;

				if ($req)
				{
					$options[] = array($key => $field[$key] . ';' . $field['field_type'] . ';' . $field['name']
						. ';' . $req . ';' . $choices, $val => $field[$val] . "*"
					);
				}
				else
				{
					$options[] = array($key => $field[$key] . ';' . $field['field_type'] . ';' . $field['name'] . ';' . $req . ';' . $choices, $val => $field[$val]);
				}
			}
		}

		$attribs = 'multiple="multiple" size="8" class="chzn-none chzn-done"';

		if ($options)
		{
			$content = JHtml::_('select.genericlist', $options, 'jform[params][fields][]', $attribs, $key, $val, $this->value, $this->id);
		}
		else
		{
			$content = '<div style="float:left;">' . JText::_('MOD_CMC_NO_FIELDS') . '</div>';
		}

		return $content;
	}
}
