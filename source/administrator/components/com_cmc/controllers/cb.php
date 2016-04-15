<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

class CmcControllerCb extends CmcController
{
	/**
	 * Helper task for installing CB plugin later
	 *
	 * @throws  Exception - if CB not found
	 *
	 * @return  void
	 */
	public function installPlugin()
	{
		JLoader::import("joomla.filesystem.file");
		JLoader::import("joomla.filesystem.folder");

		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/library/cb/cb.installer.php'))
		{
			global $_CB_framework;
			require_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php';
			require_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.class.php';
			require_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/comprofiler.class.php';

			require_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/library/cb/cb.installer.php';

			$plugin = "plug_cmc";

			$cbInstaller = new cbInstallerPlugin;

			if ($cbInstaller->install(JPATH_ROOT . '/components/com_comprofiler/plugin/user/' . $plugin . '/'))
			{
				$langPath = JPATH_ROOT . '/components/com_comprofiler/plugin/user/' . $plugin . '/administrator/language';

				$cbNames = explode('_', $plugin);

				if (JFolder::exists($langPath))
				{
					$languages = JFolder::folders($langPath);

					foreach ($languages as $language)
					{
						if (JFolder::exists(JPATH_ROOT . '/administrator/language/' . $language))
						{
							JFile::copy(
								$langPath . '/' . $language . '/' . $language . '.plg_' . $cbNames[1] . '.ini',
								JPATH_ROOT . '/administrator/language/' . $language . '/' . $language . '.plg_' . $cbNames[1] . '.ini'
							);
						}
					}
				}
			}
			else
			{
				throw new Exception("CB plugin installation failed");
			}
		}
		else
		{
			throw new Exception("CB Framework not found", 404);
		}

		$msg = JText::_('COM_CMC_CB_PLUGIN_INSTALLED_SUCCESSFULLY');

		$this->setRedirect('index.php?option=com_cmc&view=lists', $msg);
	}
}