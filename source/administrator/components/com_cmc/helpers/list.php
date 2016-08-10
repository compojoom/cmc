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
		$data = self::getInterestsFieldsRaw($listId);
		$options = array();

		foreach($data as $key => $value)
		{
			$groups = array_map(function($mv) {
				return $mv['id'] . '##' . $mv['name'];
			}, $value['groups']);

			$options[] = array(
				'id' => $value['id'] . ';' . $value['type'] . ';' . $value['title'] . ';' . implode('####', $groups),
				'title' => $value['title']
			);
		}

		return $options;
	}

	public static function getInterestsFieldsRaw($listId)
	{
		$api = new cmcHelperChimp;
		$interests = $api->listInterestGroupings($listId);
		$fields = array();

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
						$groups[] =  array('id' => $ig['id'], 'name' => $ig['name']);
					}

					$fields[$interest['id']] = array(
						'id' => $interest['id'],
						'title' => $interest['title'],
						'type' => $interest['type'],
						'groups' => $groups
					);
				}
			}
		}

		return $fields;
	}

	/**
	 * Merge the post data
	 *
	 * @param   array  $form  - the newsletter form
	 *
	 * @return mixed
	 */
	public static function mergeVars($form, $listId)
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
			$mergeVars['GROUPINGS'] = self::createInterestsObject($form['cmc_interests'], $listId);
		}
		else
		{
			$mergeVars['GROUPINGS'] = array();
		}

		$mergeVars['OPTINIP'] = $_SERVER['REMOTE_ADDR'];

		return $mergeVars;
	}

	/**
	 * Create an interests Object that can be used with the mailchimp API
	 *
	 * @param   array  $subInterests - the $_POST array with the interests
	 *
	 * @return stdClass
	 */
	public static function createInterestsObject($subInterests, $listId)
	{
		$interestsConfig = self::getInterestsFieldsRaw($listId);

		$interests = new stdClass;
		foreach($interestsConfig as $key => $value)
		{
			foreach($value['groups'] as $group)
			{
				$id = $group['id'];
				// TODO: fix this for radio. Now on mailchimp if a user decides to change a radio option, mailchimp will still keep the old version
				// but if we don't do this - we can't show the selected option on our side
				if($value['type'] != 'radio')
				{
					$interests->$id = false;
				}
			}
		}

		foreach ($subInterests as $key => $interest)
		{
			// Each interest represents an object property
			if (is_array($interest))
			{
				foreach($interest as $value)
				{
					$interests->$value = true;
				}
			}
			else
			{
				$interests->$interest =  true;
			}
		}

		return $interests;
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
	public static function subscribe($listId, $email, $firstname, $lastname, 
	                                 $groupings = array(), $email_type = "html",
	                                 $update = false, $updateLocal = false)
	{
		$api = new CmcHelperChimp;

		$merge_vars = array_merge(array('FNAME' => $firstname, 'LNAME' => $lastname), $groupings);

		// By default this sends a confirmation email - you will not see new members
		// until the link contained in it is clicked!
		$api->listSubscribe($listId, $email, $merge_vars, $merge_vars['GROUPINGS'], $email_type, false, $update);

		if ($api->getLastError())
		{
			JFactory::getApplication()->enqueueMessage(
				JTEXT::_("COM_CMC_SUBSCRIBE_FAILED") . " " .
				$api->getLastError(), 'error'
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
