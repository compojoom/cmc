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
	 * @param   string  $listId  - the list id
	 *
	 * @return mixed
	 *
	 * @since  3.0
	 */
	public static function convertMergesToFormData($merges, $listId)
	{
		$merges = json_decode($merges);
		$data = array();
		$groups = array();

		$interests = CmcHelperList::getInterestsFields($listId);

		foreach ($merges as $key => $value)
		{
			// Are we dealing with interests here?
			if ($key == 'GROUPINGS')
			{
				foreach ($value as $ikey => $ivalue)
				{
					// Go over the interests to get the interest-category-id
					foreach ($interests as $interest)
					{
						if (strstr($interest['id'], $ikey))
						{
							$id = explode(';', $interest['id'], 2);

							if ($ivalue == true)
							{
								$groups[$id[0]][] = $ikey;
							}
						}
					}

					// If the length of groups is 1, then we are dealing with a radio button and need to pass it as string
					foreach ($groups as $gkey => $vgroup)
					{
						if (count($vgroup) === 1)
						{
							$data['cmc_interests'][$gkey] = $vgroup[0];
						}
						else
						{
							$data['cmc_interests'][$gkey] = $vgroup;
						}
					}
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
