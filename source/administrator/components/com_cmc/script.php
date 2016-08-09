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
 * Script file of com_cmc component
 *
 * @since  1.0
 */
class Com_CmcInstallerScript
{
	/*
	  * The release value to be displayed and checked against throughout this file.
	  */
	public $release = '3.0';

	public $minimum_joomla_release = '2.5.10';

	public $extension = 'com_cmc';

	/**
	 * @var CompojoomInstaller
	 */
	private $installer;

	private $status;

	private $installationQueue = array(
		// Modules => { (folder) => { (module) => { (position), (published) } }* }*
		'modules' => array(
			'admin' => array(),
			'site' => array(
				'mod_cmc' => array('left', 0)
			)
		),
		'plugins' => array(
			'plg_community_cmc' => 0,
			'plg_system_ecom360' => 0,
			'plg_system_ecom360akeeba' => 0,
			'plg_system_ecom360hika' => 0,
			'plg_system_ecom360matukio' => 0,
			'plg_system_ecom360payplans' => 0,
			'plg_system_ecom360redshop' => 0,
			'plg_system_ecom360virtuemart' => 0,
			'plg_user_cmc' => '0'
		),
		'cbplugins' => array(
			'plug_cmc'
		),
		// Key is the name without the lib_ prefix, value if the library should be autopublished
		'libraries' => array(
			'compojoom' => 1
		)
	);

	/**
	 * Executed on install/update/discover
	 *
	 * @param   string                      $type    - the type of th einstallation
	 * @param   JInstallerAdapterComponent  $parent  - the parent JInstaller obeject
	 *
	 * @throws  Exception  - if library is not found
	 *
	 * @return boolean - true if everything is OK and we should continue with the installation
	 */
	public function preflight($type, $parent)
	{
		$path = $parent->getParent()->getPath('source') . '/libraries/compojoom/include.php';

		// Check if the file exists (on discover install it won't)
		if (JFile::exists($path))
		{
			require_once $path;
		}
		else
		{
			// Try fallback to installed one
			$path = JPATH_ROOT . '/libraries/compojoom/include.php';

			if (JFile::exists($path))
			{
				require_once $path;
			}
			else
			{
				throw new Exception("Compojoom library not found", 404);
			}
		}

		$this->installer = new CompojoomInstaller($type, $parent, 'com_cmc');

		if (!$this->installer->allowedInstall())
		{
			return false;
		}

		return true;
	}

	/**
	 * Method to uninstall the component
	 *
	 * @param   object  $parent  - the parent object
	 *
	 * @return void
	 */
	public function uninstall($parent)
	{
		require_once JPATH_LIBRARIES . '/compojoom/include.php';

		$this->installer = new CompojoomInstaller('uninstall', $parent, 'com_cmc');

		$this->status = new stdClass;

		// Let us install the modules & plugins
		$plugins = $this->installer->uninstallPlugins($this->installationQueue['plugins']);
		$modules = $this->installer->uninstallModules($this->installationQueue['modules']);

		$this->status->plugins = $plugins;
		$this->status->modules = $modules;

		echo $this->displayInfoUninstallation();
	}

	/**
	 * method to run after an install/update/discover method
	 *
	 * @param   string  $type    - the type
	 * @param   object  $parent  - the parent object
	 *
	 * @return void
	 */
	public function postflight($type, $parent)
	{
		$path = $parent->getParent()->getPath('source');
		$this->status = new stdClass;

		$dbInstaller = new CompojoomDatabaseInstaller(
			array(
				'dbinstaller_directory' => $path . '/administrator/components/com_cmc/sql/xml'
			)
		);
		$dbInstaller->updateSchema();

		// Let us install the modules
		$this->status->plugins = $this->installer->installPlugins($this->installationQueue['plugins']);
		$this->status->modules = $this->installer->installModules($this->installationQueue['modules']);
		$this->status->libraries = $this->installer->installLibraries($this->installationQueue['libraries']);

		$this->status->cb = false;

		foreach ($this->installationQueue['cbplugins'] as $plugin)
		{
			$this->status->cb = CompojoomInstallerCb::install($parent, $plugin);
		}

		echo $this->displayInfoInstallation();
	}

	/**
	 * Display installation info
	 *
	 * @return string
	 */
	private function displayInfoInstallation()
	{
		$html[] = '<div class="alert alert-info">' . JText::_('COM_CMC_INSTALLATION_SUCCESS') . '</div>';

		$html[] = CompojoomHtmlTemplates::renderSocialMediaInfo();

		if ($this->status->cb)
		{
			$html[] = '<p>' . JText::_('COM_CMC_CB_DETECTED_PLUGINS_INSTALLED') . '<br /></p>';
		}

		if ($this->status->libraries)
		{
			$html[] = $this->installer->renderLibraryInfoInstall($this->status->libraries);
		}

		if ($this->status->plugins)
		{
			$html[] = $this->installer->renderPluginInfoInstall($this->status->plugins);
		}

		if ($this->status->modules)
		{
			$html[] = $this->installer->renderModuleInfoInstall($this->status->modules);
		}

		return implode('', $html);
	}

	/**
	 * Displays uninstall info
	 *
	 * @return string
	 */
	public function displayInfoUninstallation()
	{
		$html[] = $this->installer->renderPluginInfoUninstall($this->status->plugins);
		$html[] = $this->installer->renderModuleInfoUninstall($this->status->modules);

		$html[] = CompojoomHtmlTemplates::renderSocialMediaInfo();

		return implode('', $html);
	}
}
