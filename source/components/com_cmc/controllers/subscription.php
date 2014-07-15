<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       15.07.14
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerlegacy');

/**
 * Class CmcControllerSubscription
 *
 * @since  1.0
 */
class CmcControllerSubscription extends JControllerLegacy
{
	/**
	 * Delete the user subscription
	 *
	 * @return void
	 */
	public function delete()
	{
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$user = JFactory::getUser();
		$appl = JFactory::getApplication();
		$input = $appl->input;
		$listId = $input->getString('listid');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		if ($user->guest)
		{
			$appl->enqueueMessage(JText::_('COM_CMC_YOU_NEED_TO_BE_LOGGED_IN_TO_UNSUBSCRIBE'));
		}

		$query->select('*')->from('#__cmc_users')
			->where('(' . $db->qn('user_id') . '=' . $db->q($user->get('id')) . ' OR email = ' . $db->q($user->email) . ')')
			->where($db->qn('list_id') . '=' . $db->q($listId));
		$db->setQuery($query);

		$subscription = $db->loadObject();

		if ($subscription)
		{
			$chimp = new cmcHelperChimp;

			if ($chimp->listUnsubscribe($listId, $subscription->email))
			{
				$appl->enqueueMessage(JText::_('COM_CMC_YOU_WERE_SUCCESSFULLY_UNSUBSCRIBED'));
			}

			$query->clear('select');
			$query->clear('from');
			$query->delete('#__cmc_users');
			$db->setQuery($query);
			$db->execute();
		}

		$appl->redirect($_SERVER['HTTP_REFERER']);
	}
}
