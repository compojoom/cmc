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
}
