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

jimport('joomla.application.component.controllerlegacy');

/**
 * Class CmcControllerSubscription
 *
 * @since  1.0
 */
class CmcControllerSubscription extends JControllerLegacy
{
	/**
	 * Sends an email with information how to update the form
	 *
	 * @return bool
	 */
	public function update()
	{
		$appl = JFactory::getApplication();
		$input = $appl->input;
		$listId = $input->getString('listid');
		$email = $input->getString('email');
		$mailer = JFactory::getMailer();
		$chimp = new cmcHelperChimp;

		if (!$listId && !$email)
		{
			$appl->enqueueMessage(JText::_('COM_CMC_INVALID_LIST_OR_EMAIL'));
			$appl->redirect($_SERVER['HTTP_REFERER']);

			return false;
		}

		$dc = "us1";

		if (strstr($chimp->api_key, "-"))
		{
			list($key, $dc) = explode("-", $chimp->api_key, 2);

			if (!$dc)
			{
				$dc = "us1";
			}
		}

		$account = $chimp->getAccountDetails();
		$memberInfo = $chimp->listMemberInfo($listId, $email);
		$listInfo = $chimp->lists($listId);

		$url = 'https://' . $account['username'] . '.' . $dc . '.list-manage.com/profile?u='
			. $account['account_id'] . '&id=' . $listId . '&e=' . $memberInfo['unique_email_id'];


		$subject = JText::sprintf('COM_CMC_CHANGE_YOUR_SUBSCRIPTION_PREFERENCES_EMAIL_TITLE', $listInfo['data'][0]['name']);
		$text = JText::sprintf('COM_CMC_CHANGE_YOUR_SUBSCRIPTION_PREFERENCES_EMAIL_CONTENT', $listInfo['data'][0]['name'], $url);

		$config = JFactory::getConfig();

		if ($mailer->sendMail($config->get('mailfrom'), $config->get('fromname'), $email, $subject, $text, true))
		{
			$appl->enqueueMessage(JText::sprintf('COM_CMC_EMAIL_WITH_FURTHER_INSTRUCTIONS_UPDATE', $email));
			$appl->redirect($_SERVER['HTTP_REFERER']);

			return true;
		}

		$appl->enqueueMessage(JText::_('COM_CMC_SOMETHING_WENT_WRONG'));
		$appl->redirect($_SERVER['HTTP_REFERER']);

		return false;
	}

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
		$db = JFactory::getDbo();
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
			$chimp = new CmcHelperChimp;

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
