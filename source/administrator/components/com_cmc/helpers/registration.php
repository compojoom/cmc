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
	 * @param   array   $postdata  - only cmc data
	 * @param   int     $plg       - which plugin triggerd the save method
	 *
	 * @return void
	 */
	public static function saveTempUser($user, $postdata, $plg = _CPLG_JOOMLA)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$toSave['listid'] = $postdata['cmc']['listid'];
		$toSave['OPTINIP'] = $_SERVER['REMOTE_ADDR'];

		if (isset($postdata['cmc_groups']))
		{
			$toSave['groups'] = $postdata['cmc_groups'];
		}

		if (isset($postdata['cmc_interests']))
		{
			$toSave['interests'] = $postdata['cmc_interests'];
		}

		$query->insert("#__cmc_register")->columns("user_id, params, plg, created")
			->values(
				$db->quote($user->id) . ',' . $db->quote(json_encode($toSave))
				. ',' . $db->quote($plg) . ',' . $db->quote(JFactory::getDate()->toSql())
			);

		$db->setQuery($query);
		$db->execute();
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

		// Hidden field
		$listId = $params['listid'];

		// Activate E-Mail in mailchimp
		if (isset($params['groups']))
		{
			foreach ($params['groups'] as $key => $group)
			{
				$mergeVars[$key] = $group;
			}
		}

		$mergeVars['GROUPINGS'] = array();
		if (isset($params['interests']))
		{
			$mergeVars['GROUPINGS'] = CmcHelperList::createInterestsObject($params['interests'], $listId);
		}

		$mergeVars['OPTINIP'] = $_SERVER['REMOTE_ADDR'];

		// Double OPTIN false
		$chimp->listSubscribe($listId, $user->email, $mergeVars, $mergeVars['GROUPINGS'], 'html', false, true, true, false);

		if ($chimp->getLastError())
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_CMC_YOU_WERE_NOT_SUBSCRIBED_TO_NEWSLETTER') . ':' . $chimp->getLastError());

			return false;
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
		$db = JFactory::getDbo();
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

		// Hidden field
		$listId = $params['listid'];

		$isSubscribed = $chimp->isSubscribed($listId, $user->email);

		if ($isSubscribed)
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

		$mergeVars['GROUPINGS'] = array();
		if (isset($params['interests']))
		{
			$mergeVars['GROUPINGS'] = CmcHelperList::createInterestsObject($params['interests'], $listId);
		}

		$mergeVars['OPTINIP'] = $params['OPTINIP'];

		// Double OPTIN false
		$chimp->listSubscribe($listId, $user->email, $mergeVars, $mergeVars['GROUPINGS'], 'html', false, true, true, false);

		if (!$chimp->getLastError())
		{
			$query->update('#__cmc_users')->set('merges = ' . $db->quote(json_encode($mergeVars)))
				->where('email = ' . $db->quote($user->email) . ' AND list_id = ' . $db->quote($listId));
			$db->setQuery($query);
			$db->execute();
		}
		else
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_CMC_YOU_WERE_NOT_SUBSCRIBED_TO_NEWSLETTER') . ':' . $chimp->errorMessage);

			return false;
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
		$db = JFactory::getDbo();
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

		$chimp->listUnsubscribe($res->list_id, $user->email);

		return true;
	}

	/**
	 * Updates an existing subscription
	 *
	 * @param   JUser  $user      - The JUser Obj
	 * @param   array  $postdata  - The params
	 *
	 * @return  boolean
	 */
	public static function updateSubscription($user, $postdata)
	{
		// We just update the users subscription to mailchimp (not db checking here)
		if (isset($postdata['cmc_groups']))
		{
			$postdata['groups'] = $postdata['cmc_groups'];
			unset($postdata['cmc_groups']);
		}

		if (isset($postdata['cmc_interests']))
		{
			$postdata['interests'] = $postdata['cmc_interests'];
			unset($postdata['cmc_interests']);
		}

		$chimp = new cmcHelperChimp;

		// Hidden field
		$listId = $postdata['listid'];

		if ($chimp->isSubscribed($listId, $user->email))
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

		$mergeVars['GROUPINGS'] = array();
		// Interests
		if (isset($params['interests']))
		{
			$mergeVars['GROUPINGS'] = CmcHelperList::createInterestsObject($params['interests'], $listId);
		}

		$result = $chimp->listSubscribe($listId, $user->email, $mergeVars, $mergeVars['GROUPINGS'], 'html', false, true, true, false);

		if (!$chimp->getLastError())
		{
			return true;
		}
		else
		{
			echo "Error: " . $chimp->getLastError();

			return false;
		}
	}

	/**
	 * Checks if the user is already subscribed to the list
	 *
	 * @param   string  $listId  - The listid
	 * @param   string  $email   - The E-Mail
	 *
	 * @return bool
	 */

	public static function isSubscribed($listId, $email)
	{
		return false;
	}
}
