<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

/**
 * Class CmcHelperSettings
 *
 * @since  1.0.0
 */
class CmcHelperSettings
{
	/**
	 * @var    \Joomla\Registry\Registry
	 */
	private static $instance;

	/**
	 * Gets a setting with the given title, returns default if not available
	 *
	 * @param   string  $title    - The key / title of the setting
	 * @param   string  $default  - The default value (if setting not found)
	 *
	 * @return  mixed
	 */
	public static function _($title = '', $default = '')
	{
		return self::getSettings($title, $default);
	}

	/**
	 * Gets a setting with the given title, returns default if not available
	 *
	 * @param   string  $title    - The key / title of the setting
	 * @param   string  $default  - The default value (if setting not found)
	 *
	 * @return  mixed
	 */
	public static function getSettings($title = '', $default = '')
	{
		if (!isset(self::$instance))
		{
			self::$instance = self::_loadSettings();
		}

		return self::$instance->get($title, $default);
	}

	/**
	 * Returns a singleton with all settings
	 *
	 * @return JObject - loads a singleton object with all settings
	 */
	private static function _loadSettings()
	{
		$params = JComponentHelper::getParams('com_cmc');

		// Grab the settings from the menu and merge them in the object
		$app = JFactory::getApplication();
		$menu = $app->getMenu();

		if (is_object($menu))
		{
			$item = $menu->getActive();

			if ($item)
			{
				$menuParams = $menu->getParams($item->id);

				foreach ($menuParams->toArray() as $key => $value)
				{
					if ($key == 'show_page_heading')
					{
						$key = 'show_page_title';
					}

					// If there is no value in the menu for styled map, just skip it
					if ($key == 'styled_maps')
					{
						if (trim($value) == '')
						{
							continue;
						}
					}

					$params->set($key, $value);
				}

				// Handle the settings override
				$override = $item->params->get('settings_override', '');

				if ($override)
				{
					$overrideSettings = explode("\n", $override);

					foreach ($overrideSettings as $value)
					{
						$setting      = explode('=', $value);
						$settingValue = trim($setting[1]);

						if (is_numeric($settingValue))
						{
							$params->set($setting[0], (int) $settingValue);
						}
						else
						{
							$params->set($setting[0], $settingValue);
						}
					}
				}
			}
		}

		return $params;
	}
}
