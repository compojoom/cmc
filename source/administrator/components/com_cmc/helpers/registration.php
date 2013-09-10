<?php
/**
 * @package    Cmc
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

define('_CPLG_JOOMLA', 0);
define('_CPLG_CB', 1);
define('_CPLG_JOMSOCIAL', 2);

/**
 * Helper class for Registration plugins
 * Class CmcHelperRegistration
 *
 * @since  1.4
 */
class CmcHelperRegistration
{
	private static $instance;

	/**
	 * Temporary saves the user merge_vars after the registration, no processing
	 * Does not check if user E-Mail already exists (this has to be done before!)
	 *
	 * @param   object  $user      - joomla user obj
	 * @param   object  $postdata  - only cmc data
	 * @param   int     $plg       - which plugin triggerd the save method
	 *
	 * @return void
	 */
	public static function saveTempUser($user, $postdata, $plg = _CPLG_JOOMLA)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$postdata['OPTINIP'] = $_SERVER['REMOTE_ADDR'];

		if (isset($postdata['cmc_groups']))
		{
			$postdata['groups'] = $postdata['cmc_groups'];
		}

		if (isset($postdata['cmc_interests']))
		{
			$postdata['interests'] = $postdata['cmc_interests'];
		}

		$query->insert("#__cmc_register")->columns("user_id, params, plg")
			->values(
				$db->quote($user->id) . ',' . $db->quote(json_encode($postdata))
				. ',' . $db->quote($plg)
			);

		$db->setQuery($query);
		$db->query();
	}

	/**
	 * Directly activates the user with Mailchimp
	 *
	 * @param   object  $user    - The Joomla user Object
	 * @param   object  $params  - The cmc post data
	 * @param   int     $plg     - Which plugin triggerd the save method
	 *
	 * @return  bool
	 */
	public static function activateDirectUser($user, $params, $plg = _CPLG_JOOMLA)
	{
		$chimp = new cmcHelperChimp;

		$userlists = $chimp->listsForEmail($user->email);

		// Hidden field
		$listId = $params['listid'];

		if ($userlists && in_array($listId, $userlists))
		{
			// Already in list, TODO update subscription here
			return null;
		}

		// Activate E-Mail in mailchimp
		if (isset($params['groups']))
		{
			foreach ($params['groups'] as $key => $group)
			{
				$mergeVars[$key] = $group;
			}
		}

		if (isset($params['interests']))
		{
			foreach ($params['interests'] as $key => $interest)
			{
				// Take care of interests that contain a comma (,)
				array_walk($interest, create_function('&$val', '$val = str_replace(",","\,",$val);'));
				$mergeVars['GROUPINGS'][] = array('id' => $key, 'groups' => implode(',', $interest));
			}
		}

		$mergeVars['OPTINIP'] = $params['OPTINIP'];

		// Double OPTIN false
		$chimp->listSubscribe($listId, $user->email, $mergeVars, 'html', true, true, true, false);

		if (!$chimp->errorCode)
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			$query->update('#__cmc_users')->set('merges = ' . $db->quote(json_encode($mergeVars)))
				->where('email = ' . $db->quote($user->email) . ' AND list_id = ' . $db->quote($listId));
			$db->setQuery($query);
			$db->query();
		}
		else
		{
			echo "Error: " . $chimp->errorMessage;
		}

		return true;
	}

	/**
	 * Activates the temporary user, checks if user is in the temporary table
	 * and also checks if the E-Mail address is already activated
	 *
	 * @param   object  $user  - the users data
	 *
	 * @return  boolean
	 */
	public static function activateTempUser($user)
	{
		// Check if user wants newsletter and is in our temp table
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query->select("*")->from("#__cmc_register")->where(
			"user_id = " . $db->quote($user->id)
		);

		$db->setQuery($query);

		$res = $db->loadObject();

		if ($res == null)
		{
			// Nothing to delete
			return null;
		}

		// Check if user is already activated
		// We want a assoc array here
		$params = json_decode($res->params, true);

		$chimp = new cmcHelperChimp;

		$userlists = $chimp->listsForEmail($user->email);

		// Hidden field
		$listId = $params['listid'];

		if ($userlists && in_array($listId, $userlists))
		{
			// Already in list, we don't update here, we update on form send
			return null;
		}

		// Activate E-Mail in mailchimp
		if (isset($params['groups']))
		{
			foreach ($params['groups'] as $key => $group)
			{
				$mergeVars[$key] = $group;
			}
		}

		if (isset($params['interests']))
		{
			foreach ($params['interests'] as $key => $interest)
			{
				// Take care of interests that contain a comma (,)
				array_walk($interest, create_function('&$val', '$val = str_replace(",","\,",$val);'));
				$mergeVars['GROUPINGS'][] = array('id' => $key, 'groups' => implode(',', $interest));
			}
		}

		$mergeVars['OPTINIP'] = $params['OPTINIP'];

		// Double OPTIN false
		$chimp->listSubscribe($listId, $user->email, $mergeVars, 'html', false, true, true, false);

		if (!$chimp->errorCode)
		{
			$query->update('#__cmc_users')->set('merges = ' . $db->quote(json_encode($mergeVars)))
				->where('email = ' . $db->quote($user->email) . ' AND list_id = ' . $db->quote($listId));
			$db->setQuery($query);
			$db->query();
		}
		else
		{
			echo "Error: " . $chimp->errorMessage;
		}

		return true;
	}

	/**
	 * Deletes users subscription, does check if the table exists before
	 *
	 * @param   object  $user  - The Joomla user object
	 *
	 * @return  boolean
	 */

	public static function deleteUser($user)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Check for email, more valid then id
		$query->select("*")->from("#__cmc_users")->where(
			"email = " . $db->quote($user->email)
		);
		$db->setQuery($query);

		$res = $db->loadObject();

		if ($res == null)
		{
			// Nothing to delete
			return null;
		}

		$chimp = new cmcHelperChimp;
		$userlists = $chimp->listsForEmail($user->email);

		if ($userlists && in_array($res->list_id, $userlists))
		{
			$chimp->listUnsubscribe($res->list_id, $user->email, false, false, true);
		}

		return true;
	}
}
