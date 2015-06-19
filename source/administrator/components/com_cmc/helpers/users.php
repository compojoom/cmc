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
		$db      = JFactory::getDbo();
		$query   = $db->getQuery(true);
		$user    = JFactory::getUser();
		$members = array();

		$bindings = array(
			'mc_id'            => array(
				'column' => 'id'
			),
			'list_id'          => array(),
			'email'            => array(),
			'email_type'       => array(),
			'ip_signup'        => array(),
			'timestamp_signup' => array(),
			'timestamp_signup' => array(),
			'ip_opt'           => array(),
			'timestamp_opt'    => array(),
			'member_rating'    => array(),
			'info_changed'     => array(),
			'web_id'           => array(),
			'language'         => array(),
			'is_gmonkey'       => array(),
			'geo'              => array(
				'handle' => 'json_encode'
			),
			'clients'          => array(
				'handle' => 'json_encode'
			),
			'merges'           => array(
				'handle' => 'json_encode'
			),
			'timestamp'        => array(),
			'status'           => array(),
			'static_segments' => array(
				'handle' => 'json_encode'
			)
		);

		// Get all e-mails from the array
		$emails = array_map(
			function ($ar) {
				return $ar['email'];
			}, $users
		);

		// Find out if the users on the list are already members of the site
		$jUsers = self::getJoomlaUsers($emails);

		foreach ($users as $member)
		{
			$item = array();

			foreach ($bindings as $bkey => $bvalue)
			{
				if (!empty($bvalue))
				{
					if (isset($bvalue['column']) && isset($member[$bvalue['column']]))
					{
						$item[$bkey] = $db->quote(isset($bvalue['handle']) ? $member[$bvalue['handle']]($member[$bvalue['column']]) : $member[$bvalue['column']]);
					}
					else
					{
						$item[$bkey] = $db->quote(isset($bvalue['handle']) ? $bvalue['handle']($member[$bkey]) : $member[$bkey]);
					}
				}
				else
				{
					$item[$bkey] = $db->quote($member[$bkey]);
				}
			}

			$item['user_id'] = isset($jUsers[$member['email']]) ? $jUsers[$member['email']]->id : 0;
			$item['firstname'] = $db->quote($member['merges']['FNAME']);
			$item['lastname'] = $db->quote($member['merges']['LNAME']);
			$item['created_user_id']  = $db->quote($user->id);
			$item['created_time']     = $db->quote(JFactory::getDate()->toSql());
			$item['modified_user_id'] = $db->quote($user->id);
			$item['modified_time']    = $db->quote(JFactory::getDate()->toSql());
			$item['query_data']       = $db->quote(json_encode($member));

			$members[] = implode(',', $item);
		}

		$query->insert('#__cmc_users')
			->columns(implode(',', array_keys($bindings)) . ',user_id,firstname, lastname,created_user_id,created_time,modified_user_id,modified_time,query_data ')
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
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->delete($db->qn('#__cmc_users'))->where($db->qn('list_id') . '=' . $db->quote($listId));
		$db->setQuery($query);

		return $db->execute();
	}

	/**
	 * Load a user subscription from the db
	 *
	 * @param   string  $email   - the email of the user
	 * @param   string  $listId  - the list id
	 *
	 * @return bool|mixed
	 */
	public static function getSubscription($email, $listId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from('#__cmc_users')
			->where($db->qn('list_id') . '=' . $db->q($listId))
			->where($db->qn('email') . '=' . $db->q($email));
		$db->setQuery($query);

		$subscription = $db->loadObject();

		return $subscription ? $subscription : false;
	}

	/**
	 * Get the ids of any Joomla users we already have on our list
	 *
	 * @param   array  $emails  - emails to search for
	 *
	 * @return array|mixed
	 */
	public static function getJoomlaUsers($emails)
	{
		$users = array();

		if (count($emails))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('id, email')->from('#__users')->where(CompojoomQueryHelper::in('email', $emails, $db));

			$db->setQuery($query);

			return $db->loadObjectList('email');
		}

		return $users;
	}
}
