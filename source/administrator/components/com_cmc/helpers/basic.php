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

		$merge_vars = array_merge(array('FNAME' => $firstname, 'LNAME' => $lastname), $groupings);

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
		$api = new cmcHelperChimp;

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