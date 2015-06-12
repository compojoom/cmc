<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       05.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CmcHelperSubscription
 *
 * @since  1.2
 */
class CmcHelperSubscription
{
	/**
	 * Saves a batch of users to the db
	 *
	 * @param   string  $merges  - json represantation of the merges
	 *
	 * @return mixed
	 */
	public static function convertMergesToFormData($merges)
	{
		$merges = json_decode($merges);
		$data = array();

		foreach ($merges as $key => $value)
		{
			// Are we dealing with interests here?
			if ($key == 'GROUPINGS')
			{
				foreach ($value as $ikey => $ivalue)
				{
					/**
					 * Alright! A total brain fuck!
					 * If you have interests with a , then they are stored in the db with \, .
					 * When we want to output this we cannot simply pass the string to the JFormField checkboxes
					 * because it does an explode on , and then we end up with wrong checked checkboxes.
					 * So first we replace \, with the html code for comma &#44;, then we explode
					 * Then we go through each value and replace &#44; with comma
					 */
					$groups = array_map(
						function($value) {
							return str_replace('&#44;', ',', $value);
						},
						explode(',', str_replace('\,', '&#44;', $ivalue->groups))
					);

					// If the length of groups is 1, then we are dealing with a radio button and need to pass it as string
					$data['cmc_interests'][$ivalue->id] = count($groups) === 1 ? $groups[0] : $groups;
				}
			}
			else
			{
				// Groups are easy!
				$data['cmc_groups'][$key] = $value;
			}
		}

		return $data;
	}
}
