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
	 * Save the subscription
	 *
	 * @return void
	 */
	public function save()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$appl  = JFactory::getApplication();
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$chimp = new cmcHelperChimp;

		$input  = JFactory::getApplication()->input;
		$form   = $input->get('jform', '', 'array');
		$isAjax = $input->get('ajax');

		$mergeVars = CmcHelperList::mergeVars($form);

		$listId = $form['cmc']['listid'];
		$email  = $mergeVars['EMAIL'];

		$memberInfo = $chimp->listSubscribe($listId, $email, $mergeVars, $mergeVars['GROUPINGS'], 'html', true, true, true, false);

		if ($chimp->getLastError())
		{
			$response['html']  = $chimp->getLastError();
			$response['error'] = true;
		}
		else
		{
			// Get the member info from mailchimp
			$status     = 'applied';

			// User was found on list
			if (isset($memberInfo['status']))
			{
				$status = $memberInfo['status'];
			}

			// Check if the subscription is already present in the db
			if (CmcHelperUsers::getSubscription($email, $listId))
			{
				$query->update('#__cmc_users')
					->set(
						array(
							'list_id = ' . $db->quote($listId),
							'email = ' . $db->quote($email),
							'merges = ' . $db->quote(json_encode($mergeVars)),
							'status = ' . $db->q($status)
						)
					)
					->where('list_id = ' . $db->quote($listId))
					->where('email = ' . $db->quote($email));

				$html = 'updated';
			}
			else
			{
				$query->insert('#__cmc_users')->columns('list_id,email,merges,status')
					->values($db->quote($listId) . ',' . $db->quote($email) . ',' . $db->quote(json_encode($mergeVars)) . ',' . $db->q($status));
				$html = 'saved';
			}

			$db->setQuery($query);
			$db->execute();

			$response['html']  = $html;
			$response['error'] = false;
		}

		if ($isAjax)
		{
			echo json_encode($response);
			jexit();
		}

		$appl->enqueueMessage($response['html']);
		$appl->redirect($_SERVER['HTTP_REFERER']);
	}

	/**
	 * Checks if the current user exists in the mailchimp database
	 *
	 * @throws Exception
	 *
	 * @return void
	 */
	public function exist()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$url = '';
		$chimp = new cmcHelperChimp;

		$input = JFactory::getApplication()->input;
		$form  = $input->get('jform', '', 'array');

		$mergeVars = CmcHelperList::mergeVars($form);

		$email  = $mergeVars['EMAIL'];
		$listId = $form['cmc']['listid'];

		// Check if the user is in the list already
		$subscribed = $chimp->isSubscribed($listId, $email);

		if ($subscribed)
		{
			$url   = JRoute::_('index.php?option=com_cmc&task=subscription.update&email=' . $email . '&listid=' . $listId);
		}

		echo json_encode(array('exists' => $subscribed, 'url' => $url));
		jexit();
	}
}
