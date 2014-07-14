<?php
/**
 * @author     Yves Hoppe <yves@compojoom.com>
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       09.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CmcHelperBasic
 *
 * @since  1.0
 */
class CmcHelperBasic
{
	/**
	 * The component list cache
	 *
	 * @var    array
	 * @since  1.1
	 */
	protected static $components = array();

	/**
	 * Checks if the required settings are set
	 *
	 * @static
	 * @return bool
	 */
	public static function checkRequiredSettings()
	{
		$params = JComponentHelper::getParams('com_cmc');
		$api_key = $params->get("api_key", '');
		$webhook = $params->get("webhooks_key", '');

		if (!empty($api_key) && !empty($webhook))
		{
			return true;
		}

		return false;
	}

	/**
	 * Gets the lists
	 *
	 * @return mixed
	 */
	public static function getLists()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__cmc_lists');
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * @static
	 *
	 * @param $api_key
	 * @param $list_id
	 * @param $email
	 *
	 * @return string
	 */
	public static function getUserDetailsMC($api_key, $list_id, $email, $id = null, $store = true)
	{
		$api = new MCAPI($api_key);
		$appl = JFactory::getApplication();

		$retval = $api->listMemberInfo($list_id, $email);

		if ($api->errorCode)
		{
			JFactory::getApplication()->enqueueMessage(JTEXT::_("COM_CMC_LOAD_USER_FAILED") . " " . $api->errorCode . " / " . $api->errorMessage, 'error');
			return false;
		}
		else
		{
//            echo "Success:".$retval['success']."\n";
//            echo "Errors:".sizeof($retval['error'])."\n";
//            //below is stupid code specific to what is returned
//            //Don't actually do something like this.
//            $i = 0;
//            foreach($retval['data'] as $k=>$v){
//                echo 'Member #'.(++$i)."\n";
//                if (is_array($v)){
//                    //handle the merges
//                    foreach($v as $l=>$w){
//                        if (is_array($w)){
//                            echo "\t$l:\n";
//                            foreach($w as $m=>$x){
//                                echo "\t\t$m = $x\n";
//                            }
//                        } else {
//                            echo "\t$l = $w\n";
//                        }
//                    }
//                } else {
//                    echo "$k = $v\n";
//                }
//            }
			/**
			 * @return array array of list members with their info in an array (see Returned Fields for details)
			 * @returnf int success the number of subscribers successfully found on the list
			 * @returnf int errors the number of subscribers who were not found on the list
			 * @returnf array data an array of arrays where each one has member info:
			string id The unique id for this email address on an account
			string email The email address associated with this record
			string email_type The type of emails this customer asked to get: html, text, or mobile
			array merges An associative array of all the merge tags and the data for those tags for this email address.
			 * <em>Note</em>: Interest Groups are returned as comma delimited strings - if a group name contains a comma,
			 *          it will be escaped with a backslash. ie, "," =&gt; "\,". Groupings will be returned with their "id" and "name"
			 *          as well as a "groups" field formatted just like Interest Groups
			string status The subscription status for this email address, either pending, subscribed, unsubscribed, or cleaned
			string ip_opt IP Address this address opted in from.
			string ip_signup IP Address this address signed up from.
			int member_rating the rating of the subscriber. This will be 1 - 5 as described <a href="http://eepurl.com/f-2P" target="_blank">here</a>
			string campaign_id If the user is unsubscribed and they unsubscribed from a specific campaign, that campaign_id will be listed, otherwise this is not returned.
			array lists An associative array of the other lists this member belongs to - the key is the list id and the value is their status in that list.
			date timestamp The time this email address was added to the list
			date info_changed The last time this record was changed. If the record is old enough, this may be blank.
			int web_id The Member id used in our web app, allows you to create a link directly to it
			array clients the various clients we've tracked the address as using - each included array includes client 'name' and 'icon_url'
			array static_segments the 'id', 'name', and date 'added' for any static segment this member is in
			 */

			$item = array();

			foreach ($retval['data'] as $user)
			{
				$item['id'] = $id;
				$item['mc_id'] = $user['id'];

				$item['list_id'] = $list_id;
				$item['email_type'] = $user['email_type'];
				$item['email'] = $user['email'];

				$item['merges'] = json_encode($user['merges']);

				$item['firstname'] = $user['merges']['FNAME'];
				$item['lastname'] = $user['merges']['LNAME'];

				//$item['interests'] = $user['merges']['INTERESTS'];

				$item['status'] = $user['status'];
				$item['ip_opt'] = $user['ip_opt'];
				$item['ip_signup'] = $user['ip_signup'];
				$item['language'] = $user['language'];

				$item['member_rating'] = $user['member_rating'];

				$item['timestamp'] = $user['timestamp'];
				$item['info_changed'] = $user['info_changed'];

				$item['web_id'] = $user['web_id'];
				$item['clients'] = json_encode($user['clients']);
				$item['static_segments'] = json_encode($user['static_segments']);

				$item['lists'] = json_encode($user['lists']);


				$item['query_data'] = json_encode($retval);

				if ($store)
				{
					$row = JTable::getInstance('users', 'CmcTable');

					try
					{
						$row->bind($item);
						$row->check();
						$row->store();
					}
					catch (Exception $e)
					{
						$appl->enqueueMessage(JText::_('COM_CMC_LIST_ERROR_SAVING') . $e->getMessage());

						return false;
					}
				}
			}

			return $row;
		}

	}

	/**
	 * @static
	 *
	 * @param       $api_key
	 * @param       $list_id
	 * @param       $email
	 * @param       $firstname
	 * @param       $lastname
	 * @param null  $user
	 * @param array $groupings
	 */
	public static function subscribeList($api_key, $list_id, $email, $firstname, $lastname, $user = null, $groupings = array(null), $email_type = "html", $update = false)
	{

		$api = new MCAPI($api_key);

		$merge_vars = array('FNAME' => $firstname, 'LNAME' => $lastname,
			$groupings
		);

		// By default this sends a confirmation email - you will not see new members
		// until the link contained in it is clicked!
		$retval = $api->listSubscribe($list_id, $email, $merge_vars, $email_type, false, $update);

		if ($api->errorCode)
		{
			JFactory::getApplication()->enqueueMessage(JTEXT::_("COM_CMC_SUBSCRIBE_FAILED") . " " . $api->errorCode . " / " . $api->errorMessage, 'error');
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Unsubscribes a user from the mailchimp list
	 *
	 * @param $user
	 *
	 * @throws Exception
	 * @return bool|string
	 */
	public static function unsubscribeList($user)
	{
		$api = new cmcHelperChimp();

		$api->listUnsubscribe($user->list_id, $user->email, true);
		if ($api->errorCode)
		{
			throw new Exception(JTEXT::_("COM_CMC_UNSUBSCRIBE_FAILED") . ": " . $api->errorMessage, $api->errorCode);
		}

		return true;
	}

	/**
	 * @static
	 *
	 * @param        $api_key
	 * @param        $list_id
	 * @param        $email
	 * @param null   $firstname
	 * @param null   $lastname
	 * @param string $email_type
	 * @param null   $user
	 *
	 * @return bool|string
	 */
	public static function updateUser($api_key, $list_id, $email, $firstname = null, $lastname = null, $email_type = "html", $user = null)
	{
		$api = new MCAPI($api_key);

		$merge_vars = array("FNAME" => $firstname, "LNAME" => $lastname);

		$retval = $api->listUpdateMember($list_id, $email, $merge_vars, $email_type, false);

		if ($api->errorCode)
		{
			JFactory::getApplication()->enqueueMessage(JTEXT::_("COM_CMC_UNSUBSCRIBE_FAILED"). " " . $api->errorCode . " / " . $api->errorMessage, 'error');
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * @static
	 *
	 * @param $glue
	 * @param $separator
	 * @param $array
	 *
	 * @return string
	 */
	public static function array_implode($glue, $separator, $array)
	{
		if (!is_array($array))
		{
			return $array;
		}
		$string = array();
		foreach ($array as $key => $val)
		{
			$newval = "";
			if (is_array($val))
			{
				foreach ($val as $v)
				{
					$newval .= implode(',', array_values($v));
				}
			}
			else
			{
				$newval = $val;
			}
			$string[] = "{$key}{$glue}{$newval}";
		}
		return implode($separator, $string);
	}

	/**
	 * @static
	 *
	 * @param      $api_key
	 * @param      $list_id
	 * @param bool $optin
	 * @param bool $up_exist
	 * @param bool $replace_int
	 *
	 * @return string
	 */
	public static function subscribeListBatch($api_key, $list_id, $batchlist, $optin = true, $up_exist = true, $replace_int = false)
	{
		$api = new MCAPI($api_key);

//        $batch[] = array('EMAIL'=>$my_email, 'FNAME'=>'Joe');
//        $batch[] = array('EMAIL'=>$boss_man_email, 'FNAME'=>'Me', 'LNAME'=>'Chimp');

		// Todo check rights

		$optin = true; //yes, send optin emails
		$up_exist = true; // yes, update currently subscribed users
		$replace_int = false; // no, add interest, don't replace

		$vals = $api->listBatchSubscribe($list_id, $batchlist, $optin, $up_exist, $replace_int);

		if ($api->errorCode)
		{
			JFactory::getApplication()->enqueueMessage(
				JText::_("COM_CMC_UNSUBSCRIBE_FAILED") . " " . $api->errorCode . " / " . $api->errorMessage,
				'error'
			);
			return false;
		}
		else
		{
			// Todo return this
			echo "added:   " . $vals['add_count'] . "\n";
			echo "updated: " . $vals['update_count'] . "\n";
			echo "errors:  " . $vals['error_count'] . "\n";
			foreach ($vals['errors'] as $val)
			{
				echo $val['email_address'] . " failed\n";
				echo "code:" . $val['code'] . "\n";
				echo "msg :" . $val['message'] . "\n";
			}
		}

	}

	/**
	 * @param string $option
	 *
	 * @return bool || object
	 */
	public static function getComponent($option = 'com_cmc')
	{
		if (!isset(self::$components[$option]))
		{
			if (self::_load($option))
			{
				$result = self::$components[$option];
			}
			else
			{
				$result = false;
			}
		}
		else
		{
			$result = self::$components[$option];
		}

		return $result;


	}

	/**
	 *
	 */
	public static function footer()
	{
		$footer = '<p style="text-align: center; margin-top: 15px;" class="copyright"> ';
		$footer .= 'CMC - <a href="https://mailchimp.com/?pid=compojoom&source=website" target="_blank">Mailchimp</a>® integration for <a href="http://joomla.org" target="_blank">Joomla!™</a>';
		$footer .= ' by <a href="https://compojoom.com">compojoom.com</a>';
		$footer .= '</p>';
		return $footer;
	}

	private static function _load($option)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery('true');
		$query->select('*')->from('#__extensions');
		$query->where($query->qn('type') . ' = ' . $db->quote('component'));
		$query->where($query->qn('element') . ' = ' . $db->quote($option));
		$db->setQuery($query, 0, 1);

		self::$components[$option] = $db->loadObject();

		// Convert the params to an object.
		if (is_string(self::$components[$option]->params))
		{
			$temp = new JRegistry;
			$temp->loadString(self::$components[$option]->params);
			self::$components[$option]->params = $temp;
		}

		if (is_string(self::$components[$option]->manifest_cache))
		{
			$temp = new JRegistry;
			$temp->loadString(self::$components[$option]->manifest_cache);
			self::$components[$option]->manifest_cache = $temp;
		}

		return $db->loadObject();
	}

	/**
	 * Generates the menu
	 *
	 * @return  array
	 */
	public static function getMenu()
	{
		$menu = array(
			'cpanel' => array(
				'link' => 'index.php?option=com_cmc&view=cpanel',
				'title' => 'COM_CMC_CPANEL',
				'icon' => 'fa-dashboard',
				'anchor' => '',
				'children' => array(),
				'label' => '',
				'keywords' => 'dashboard home overview cpanel'
			),
			'lists' => array(
				'link' => 'index.php?option=com_cmc&view=lists',
				'title' => 'COM_CMC_LISTS',
				'icon' => 'fa-list-alt',
				'anchor' => '',
				'children' => array(),
				'label' => '',
				'keywords' => 'lists'
			),
			'users' => array(
				'link' => 'index.php?option=com_cmc&view=users',
				'title' => 'COM_CMC_USERS',
				'icon' => 'fa-users',
				'anchor' => '',
				'children' => array(),
				'label' => '',
				'keywords' => 'users'
			)
		);

		return $menu;
	}


}