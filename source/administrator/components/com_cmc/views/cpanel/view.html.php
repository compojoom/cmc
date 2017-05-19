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
jimport('joomla.application.component.view');

/**
 * Class CmcViewCpanel
 *
 * @since  1.3
 */
class CmcViewCpanel extends CmcViewBackend
{
	/**
	 * Displays the view
	 *
	 * @param   string  $tpl  - custom template
	 *
	 * @return mixed|void
	 */
	public function display($tpl = null)
	{
		/** @var CmcModelUpdates $updateModel */
		$updateModel = JModelLegacy::getInstance('Updates', 'CmcModel');

		/** @var CmcModelStats $statsModel */
        $statsModel = JModelLegacy::getInstance('Stats', 'CmcModel');

		// Run the automatic database check
		$updateModel->checkAndFixDatabase();

		$this->currentVersion = $updateModel->getVersion();
        $this->updateStats = $statsModel->needsUpdate();

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Ads the toolbar buttons
	 *
	 * @return void
	 */
	public function addToolbar()
	{
		$this->setCTitle(JText::_('COM_CMC_CPANEL'), JText::_(''), 'cpanel');

		JToolBarHelper::title(JText::_('COM_CMC_CPANEL'), 'cpanel');
		JToolBarHelper::preferences('com_cmc');
	}

	/**
	 * Gets the account details from mailchimp
	 *
	 * @return mixed
	 */
	public function getAccountDetails()
	{
		$cache = JFactory::getCache('mod_ccc_cmc_mailchimp', 'output');
		$cache->setCaching(true);
		$details = null; // $cache->get('details');

		if (!$details)
		{
			$chimp = new CmcHelperChimp;
			$data = $chimp->get('/');

			$details = serialize($data);
			$cache->store(($details), 'details');
		};

		return unserialize($details);
	}
}
