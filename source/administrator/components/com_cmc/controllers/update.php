<?php
/**
 * @package    Cmc
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @copyright  Copyright (C) 2008 - 2014 Compojoom.com . All rights reserved.
 * @license    GNU GPL version 3 or later <http://www.gnu.org/licenses/gpl.html>
 */

// Protect from unauthorized access
defined('_JEXEC') or die;

/**
 * The updates provisioning Controller
 *
 * @since  1.5
 */
class CmcControllerUpdate extends JControllerLegacy
{
	/**
	 * Looks for an update to the extension
	 *
	 * @return string
	 */
	public function updateinfo()
	{
		$updateModel = JModelLegacy::getInstance('Updates', 'CmcModel');
		$updateInfo = (object) $updateModel->getUpdates(true);
		$extensionName = 'CMC';

		$result = '';

		if ($updateInfo->hasUpdate)
		{
			$strings = array(
				'header'  => JText::sprintf('LIB_COMPOJOOM_DASHBOARD_MSG_UPDATEFOUND', $extensionName, $updateInfo->version),
				'button'  => JText::sprintf('LIB_COMPOJOOM_DASHBOARD_MSG_UPDATENOW', $updateInfo->version),
				'infourl' => $updateInfo->infoURL,
				'infolbl' => JText::_('LIB_COMPOJOOM_DASHBOARD_MSG_MOREINFO'),
			);

			$result = <<<ENDRESULT
	<div class="alert alert-warning">
		<h3>
			<span class="fa fa-warning"></span>
			{$strings['header']}
		</h3>
		<p>
			<a href="index.php?option=com_installer&view=update" class="btn btn-primary">
				{$strings['button']}
			</a>
			<a href="{$strings['infourl']}" target="_blank" class="btn btn-small btn-info">
				{$strings['infolbl']}
			</a>
		</p>
	</div>
ENDRESULT;
		}

		echo '###' . $result . '###';

		// Cut the execution short
		JFactory::getApplication()->close();
	}

	/**
	 * Force joomla to check for updates
	 *
	 * @return void
	 */
	public function force()
	{
		JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		JModelLegacy::getInstance('Updates', 'CmcModel')->getUpdates(true);

		$url = 'index.php?option=' . JFactory::getApplication()->input->getCmd('option', '');
		$msg = JText::_('LIB_COMPOJOOM_UPDATE_INFORMATION_RELOADED');
		$this->setRedirect($url, $msg);
	}
}
