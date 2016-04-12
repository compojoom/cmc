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
	 * Unsubscribes a user from the mailchimp list
	 *
	 * @param   object  $user  - the user object
	 *
	 * @throws Exception
	 *
	 * @return bool|string
	 */
	public static function unsubscribeList($user)
	{
		$api = new CmcHelperChimp;

		$api->listUnsubscribe($user->list_id, $user->email, true);

		if ($api->getLastError())
		{
			throw new Exception(JTEXT::_("COM_CMC_UNSUBSCRIBE_FAILED") . ": " . $api->getLastError(), 500);
		}

		return true;
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
