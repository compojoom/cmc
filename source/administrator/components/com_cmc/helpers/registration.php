<?php
/**
 * Compojoom Community-Builder Plugin
 * @package Joomla!
 * @Copyright (C) 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/

defined('_JEXEC') or die('Restricted access');

/**
 * Helper class for Registration plugins
 * Class CmcHelperRegistration
 */

define('_CPLG_JOOMLA', 0);
define('_CPLG_CB', 1);
define('_CPLG_JOMSOCIAL', 2);

class CmcHelperRegistration
{
	private static $instance;

	/**
	 * Temporary saves the user merge_vars after the registration, no processing
	 * Does not check if user E-Mail already exists (this has to be done before!)
	 * @param $user joomla user obj
	 * @param $postdata only cmc data
	 * @param int $plg which plugin triggerd the save method
	 */
	public static function saveTempUser($user, $postdata, $plg = _CPLG_JOOMLA)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$postdata['OPTINIP'] = $_SERVER['REMOTE_ADDR'];

		$query->insert("#__cmc_register")->columns("user_id, params, plg")
			->values(
				$db->quote($user->id) . ',' . $db->quote(json_encode($postdata))
				. ',' . $db->quote($plg)
			);

		$db->setQuery($query);
		$db->query();
	}

	/**
	 * Activates a temporary user after his user account is activated
	 * Checks if user is in the temporary table and if the user is already activated before
	 * sending the user to mailchimp
	 * @param $user
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

		if ($res == null) {
			return; // not in database
		}

		// Check if user is already activated

		$params = json_decode($res->params, true); // We want a assoc array here

		$chimp = new cmcHelperChimp();

		$userlists = $chimp->listsForEmail($user->email);
		$listId = $params['listid']; // hidden field

		if ($userlists && in_array($listId, $userlists)) {
			return; // Already in list, we don't update here, we update on form send
		}

		// Activate E-Mail in mailchimp
		if (isset($params['groups'])) {
			foreach ($params['groups'] as $key => $group) {
				$mergeVars[$key] = $group;
			}
		}

		if (isset($params['interests'])) {
			foreach ($params['interests'] as $key => $interest) {
				// take care of interests that contain a comma (,)
				array_walk($interest, create_function('&$val', '$val = str_replace(",","\,",$val);'));
				$mergeVars['GROUPINGS'][] = array('id' => $key, 'groups' => implode(',', $interest));
			}
		}

		$mergeVars['OPTINIP'] = $params['OPTINIP'];

		$chimp->listSubscribe($listId, $user->email, $mergeVars, 'html', false, true, true, false); // Double OPTIN false

		if (!$chimp->errorCode) {
			$query->update('#__cmc_users')->set('merges = ' . $db->quote(json_encode($mergeVars)))
				->where('email = ' . $db->quote($user->email) . ' AND list_id = ' . $db->quote($listId));
			$db->setQuery($query);
			$db->query();
		} else {
			echo "Error: " . $chimp->errorMessage;
		}

		return true;
	}

	/**
	 * Deletes users subscription, does check if the table exists before
	 * @param $user
	 */

	public static function deleteUser($user)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// check for email, more valid then id
		$query->select("*")->from("#__cmc_users")->where(
			"email = " . $db->quote($user->email)
		);
		$db->setQuery($query);

		$res = $db->loadObject();

		if ($res == null)
			return; // nothing to delete

		$chimp = new cmcHelperChimp();
		$userlists = $chimp->listsForEmail($user->email);

		if ($userlists && in_array($res->list_id, $userlists)) {
			$chimp->listUnsubscribe($res->list_id, $user->email, false, false, true);
		}

		return true;
	}


}