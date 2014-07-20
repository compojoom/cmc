<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       06.09.13
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
	 * Save the subscription
	 *
	 * @return void
	 */
	public function save()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$appl = JFactory::getApplication();
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$chimp = new cmcHelperChimp;

		$input = JFactory::getApplication()->input;
		$form = $input->get('jform', '', 'array');
		$isAjax = $input->get('ajax');

		$mergeVars = $this->mergeVars($form);

		$listId = $form['cmc']['listid'];
		$email = $mergeVars['EMAIL'];

		$chimp->listSubscribe($listId, $email, $mergeVars, 'html', true, true, false, false);

		if ($chimp->errorCode)
		{
			$response['html'] = $chimp->errorMessage;
			$response['error'] = true;
		}
		else
		{
			$query->insert('#__cmc_users')->columns('list_id,email,merges,status')
				->values($db->quote($listId) . ',' . $db->quote($email) . ',' . $db->quote(json_encode($mergeVars)). ','.$db->q('applied'));

			$db->setQuery($query);
			$db->execute();

			$response['html'] = 'saved';
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

	private function mergeVars($form)
	{
		if (isset($form['cmc_groups']))
		{
			foreach ($form['cmc_groups'] as $key => $group)
			{
				$mergeVars[$key] = $group;
			}
		}

		if (isset($form['cmc_interests']))
		{
			foreach ($form['cmc_interests'] as $key => $interest)
			{
				// Take care of interests that contain a comma (,)
				if (is_array($interest))
				{
					array_walk($interest, create_function('&$val', '$val = str_replace(",","\,",$val);'));
					$mergeVars['GROUPINGS'][] = array('id' => $key, 'groups' => implode(',', $interest));
				}
				else
				{
					$mergeVars['GROUPINGS'][] = array('id' => $key, 'groups' => $interest);
				}
			}
		}

		$mergeVars['OPTINIP'] = $_SERVER['REMOTE_ADDR'];

		return $mergeVars;
	}

	public function exist() {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$chimp = new cmcHelperChimp;

		$input = JFactory::getApplication()->input;
		$form = $input->get('jform', '', 'array');

		$mergeVars = $this->mergeVars($form);

		$email = $mergeVars['EMAIL'];
		$listId = $form['cmc']['listid'];

		// Check if the user is in the list already
		$userlists = $chimp->listsForEmail($email);

		if ($userlists && in_array($listId, $userlists))
		{
			$exist = true;
			$url = JRoute::_('index.php?option=com_cmc&task=subscription.update&email=' . $email . '&listid=' . $listId);
		}
		else
		{
			$exist = false;
			$url = '';
		}

		echo json_encode(array('exists' => $exist, 'url' => $url));
		jexit();

	}
}
