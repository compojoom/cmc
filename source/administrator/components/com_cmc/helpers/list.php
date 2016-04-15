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

	/**
	 * Create an array with mailchimps merge fields
	 *
	 * @param   string  $listId  - the list id
	 *
	 * @return array|bool
	 */
	public static function getMergeFields($listId)
	{
		$api = new CmcHelperChimp;
		$fields = $api->listMergeVars($listId);

		$key = 'tag';
		$val = 'name';

		$options = false;

		if ($fields)
		{
			foreach ($fields as $field)
			{
				$choices = '';

				if (isset($field['options']['choices']))
				{
					foreach ($field['options']['choices'] as $c)
					{
						$choices .= $c . '##';
					}

					$choices = substr($choices, 0, -2);
				}

				$req = ($field['required']) ? 1 : 0;

				if ($req)
				{
					$options[] = array($key => $field[$key] . ';' . $field['type'] . ';' . $field['name']
						. ';' . $req . ';' . $choices, $val => $field[$val] . "*"
					);
				}
				else
				{
					$options[] = array($key => $field[$key] . ';' . $field['type'] . ';' . $field['name'] . ';' . $req . ';' . $choices, $val => $field[$val]);
				}
			}
		}

		return $options;
	}

	/**
	 * Create an array with mailchimps interest fields
	 *
	 * @param   string  $listId  - the list id
	 *
	 * @return array|bool
	 */
	public static function getInterestsFields($listId)
	{
		$api = new cmcHelperChimp;
		$interests = $api->listInterestGroupings($listId);
		$key = 'id';
		$val = 'title';
		$options = false;

		if ($interests)
		{
			foreach ($interests as $interest)
			{
				if ($interest['type'] != 'hidden')
				{
					$details = $api->listIntegerestGroupingsField($listId, $interest['id']);

					$groups = '';

					foreach ($details as $ig)
					{
						$groups .= $ig['name'] . '##' . $ig['name'] . '####';
					}

					$groups = substr($groups, 0, -4);

					$options[] = array($key => $interest[$key] . ';' . $interest['type'] . ';'
						. $interest['title'] . ';' . $groups, $val => $interest[$val]);
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

	/**
	 * Subscribe a user to mailchimp and if set create an entry for this user in our database
	 *
	 * @param   string  $listId       - the list id
	 * @param   string  $email        - the email of the user
	 * @param   string  $firstname    - the first name of the user
	 * @param   string  $lastname     - the last name of the user
	 * @param   array   $groupings    - any groupings (merge fields + interest fields)
	 * @param   string  $email_type   - the type of email the user wants to receive
	 * @param   bool    $update       - are we updating an existing user
	 * @param   bool    $updateLocal  - shall we create an entry for this user in our DB?
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
	public static function subscribe($listId, $email, $firstname, $lastname, $groupings = array(), $email_type = "html", $update = false, $updateLocal = false)
	{
		$api = new CmcHelperChimp;

		$merge_vars = array_merge(array('FNAME' => $firstname, 'LNAME' => $lastname), $groupings);


		// By default this sends a confirmation email - you will not see new members
		// until the link contained in it is clicked!
		$api->listSubscribe($listId, $email, $merge_vars, $email_type, false, $update);

		if ($api->errorCode)
		{
			JFactory::getApplication()->enqueueMessage(
				JTEXT::_("COM_CMC_SUBSCRIBE_FAILED") . " " .
				$api->errorCode . " / " . $api->errorMessage, 'error'
			);

			return false;
		}

		if ($updateLocal)
		{
			JLoader::discover('cmcModel', JPATH_ADMINISTRATOR . '/components/com_cmc/models/');
			$subscription = CmcHelperUsers::getSubscription($email, $listId);
			$memberInfo = $api->listMemberInfo($listId, $email);

			if ($memberInfo['success'])
			{
				$memberInfo = $memberInfo['data'][0];
			}

			$model = JModelLegacy::getInstance('User', 'CmcModel');
			$user = CmcHelperUsers::getJoomlaUsers(array($email));

			$saveData = CmcHelperUsers::bind($memberInfo, $user);

			if ($subscription)
			{
				$saveData['id'] = $subscription->id;
			}

			// Update in the local db
			$model->save($saveData);
		}

		return true;
	}
}
