<?php
/**
 * @package    CMC
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CmcHelperList
 *
 * @since  1.2
 */
class CmcHelperList
{
	/**
	 * Deletes a list
	 *
	 * @param   string  $mcId  - the mailchimp ID of the list
	 *
	 * @return mixed
	 */
	public static function delete($mcId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->delete('#__cmc_lists')->where('mc_id = ' . $db->quote($mcId));
		$db->setQuery($query);

		return $db->execute();
	}

	public static function getMergeFields($listId)
	{
		$api = new cmcHelperChimp;
		$fields = $api->listMergeVars($listId);
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

		return $options;
	}

	public static function getInterestsFields($listId)
	{
		$api = new cmcHelperChimp;
		$interests = $api->listInterestGroupings($listId);
		$key = 'id';
		$val = 'name';
		$options = false;

		if ($interests)
		{
			foreach ($interests as $interest)
			{
				if ($interest['form_field'] != 'hidden')
				{
					$groups = '';

					foreach ($interest['groups'] as $ig)
					{
						$groups .= $ig['name'] . '##' . $ig['name'] . '####';
					}

					$groups = substr($groups, 0, -4);
					$options[] = array($key => $interest[$key] . ';' . $interest['form_field'] . ';' . $interest['name'] . ';' . $groups, $val => $interest[$val]);
				}
			}
		}

		return $options;
	}

	/**
	 * Merge the post data
	 *
	 * @param   array  $form  - the newsletter form
	 *
	 * @return mixed
	 */
	public static function mergeVars($form)
	{
		if (isset($form['cmc_groups']))
		{
			foreach ($form['cmc_groups'] as $key => $group)
			{
				$mergeVars[$key] = $group;
			}
		}

		if (isset($form['cmc_interests']))
		{
			foreach ($form['cmc_interests'] as $key => $interest)
			{
				// Take care of interests that contain a comma (,)
				if (is_array($interest))
				{
					array_walk($interest, create_function('&$val', '$val = str_replace(",","\,",$val);'));
					$mergeVars['GROUPINGS'][] = array('id' => $key, 'groups' => implode(',', $interest));
				}
				else
				{
					$mergeVars['GROUPINGS'][] = array('id' => $key, 'groups' => $interest);
				}
			}
		}

		$mergeVars['OPTINIP'] = $_SERVER['REMOTE_ADDR'];

		return $mergeVars;
	}
}
