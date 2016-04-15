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
 * Class CmcHelperUsers
 *
 * @since  1.2
 */
class CmcHelperUsers
{
	protected static $bindings = array(
		'mc_id'            => array(
			'column' => 'id'
		),
		'list_id'          => array(),
		'email'            => array(
			'column' => 'email_address'
		),
		'email_type'       => array(),
		'ip_signup'        => array(),
		'timestamp_signup' => array(),
		'timestamp_signup' => array(),
		'ip_opt'           => array(),
		'timestamp_opt'    => array(),
		'member_rating'    => array(),
		'info_changed'     => array(
			'column' => 'last_changed'
		),
		'web_id'           => array(
			'column' => 'unique_email_id'
		),
		'language'         => array(),
		// 'is_gmonkey'       => array(),
		'geo'              => array(
			'column' => 'location',
			'handle' => 'json_encode'
		),
		'clients'          => array(
			'column' => 'email_client'
		),
		'merges'           => array(
			'column' => 'merge_fields',
			'handle' => 'json_encode'
		),
		'timestamp'        => array(
			'column' => 'timestamp_opt'
		),
		'status'           => array(),
		'static_segments' => array(
			'column' => 'interests',
			'handle' => 'json_encode'
		)
	);


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

		$members = array();

		// Get all e-mails from the array
		$emails = array_map(
			function ($ar) {
				return $ar['email_address'];
			}, $users
		);

		// Find out if the users on the list are already members of the site
		$jUsers = self::getJoomlaUsers($emails);

		foreach ($users as $member)
		{

			$item = self::bind($member, $jUsers);

			array_walk(
				$item,
				function(&$value) use ($db) {
					// Escape the value
					$value = $db->quote($value);
				}
			);

			$members[] = implode(',', $item);
		}

		$query->insert('#__cmc_users')
			->columns(implode(',', array_keys(self::$bindings)) . ', user_id, firstname, lastname,created_user_id,created_time,modified_user_id,modified_time,query_data ')
			->values($members);

		$db->setQuery($query);

		return $db->execute();
	}

	/**
	 * Binds the information from the listMemberInfo function to the local user table structure
	 *
	 * @param   array  $member  - the member data
	 * @param   array  $jUsers  - joomla users array with email as key
	 *
	 * @return array
	 */
	public static function bind($member, $jUsers)
	{
		$user = JFactory::getUser();

		$item = array();

		// Nuts, just nuts! KISS!
		foreach (self::$bindings as $bkey => $bvalue)
		{
			if (!empty($bvalue))
			{
				$handle = isset($bvalue['handle']) ? $bvalue['handle'] : "";

				if (isset($bvalue['column']) && isset($member[$bvalue['column']]))
				{
					$item[$bkey] = $handle ? $handle($member[$bvalue['column']]) : $member[$bvalue['column']];
				}
				else
				{
					$item[$bkey] = $handle ? $handle($member[$bkey]) : $member[$bkey];
				}
			}
			else
			{
				$item[$bkey] = $member[$bkey];
			}
		}

		$item['user_id'] = isset($jUsers[$member['email_address']]) ? $jUsers[$member['email_address']]->id : 0;
		$item['firstname'] = $member['merge_fields']['FNAME'];
		$item['lastname'] = $member['merge_fields']['LNAME'];
		$item['created_user_id']  = $user->id;
		$item['created_time']     = JFactory::getDate()->toSql();
		$item['modified_user_id'] = $user->id;
		$item['modified_time']    = JFactory::getDate()->toSql();
		$item['query_data']       = json_encode($member);

		return $item;
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
