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
 * Class CmcHelperUsers
 *
 * @since  1.2
 */
class CmcHelperUsers
{
	/**
	 * Saves a batch of users to the db
	 *
	 * @param   array   $users     - the users to save in the db
	 * @param   int     $jListId   - the joomla list id
	 * @param   string  $mcListId  - the list id
	 *
	 * @return mixed
	 */
	public static function save($users, $jListId, $mcListId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();
		$members = array();

		foreach ($users as $member)
		{
			$item = array();
			$item['mc_id'] = $db->quote(null);
			$item['list_id'] = $db->quote($mcListId);
			$item['email'] = $db->quote($member['email']);
			$item['timestamp'] = $db->quote($member['timestamp']);
			$item['status'] = $db->quote('subscribed');
			$item['created_user_id'] = $db->quote($user->id);
			$item['created_time'] = $db->quote(JFactory::getDate()->toSql());
			$item['modified_user_id'] = $db->quote($user->id);
			$item['modified_time'] = $db->quote(JFactory::getDate()->toSql());
			$item['query_data'] = $db->quote(json_encode($member));

			$members[] = implode(',', $item);
		}

		$query->insert('#__cmc_users')
			->columns('mc_id,list_id,email,timestamp,status,created_user_id,created_time,modified_user_id,modified_time, query_data')
			->values($members);

		$db->setQuery($query);

		return $db->execute();
	}

	/**
	 * Delete users from the db belonging to the mailchimp list
	 *
	 * @param   int  $listId  - the list id
	 *
	 * @return mixed
	 */
	public static function delete($listId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->delete($db->qn('#__cmc_users'))->where($db->qn('list_id') . '=' . $db->quote($listId));
		$db->setQuery($query);

		return $db->execute();
	}
}
