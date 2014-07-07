<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       28.08.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

JLoader::discover('CmcSyncer', JPATH_COMPONENT_ADMINISTRATOR . '/libraries/syncer');

/**
 * Class CmcControllerSync
 *
 * @since  1.2
 */
class CmcControllerSync extends CmcController
{
	/**
	 * Initialize the sync process
	 *
	 * @return void
	 */
	public function start()
	{
		// Check for a valid token. If invalid, send a 403 with the error message.
		JSession::checkToken('request') or $this->sendResponse(new Exception(JText::_('JINVALID_TOKEN'), 403));

		// Put in a buffer to silence noise.
		ob_start();

		// Initiate an empty state
		CmcSyncerState::resetState();
		$state = CmcSyncerState::getState();

		$input = JFactory::getApplication()->input;
		$lists = $input->getString('lists');

		$chimp = new CmcHelperChimp;
		$listStats = $chimp->lists(array('list_id' => $lists));
		$names = array();

		foreach ($listStats['data'] as $key => $list)
		{
			$state->lists[$key] = array();
			$state->lists[$key]['mc_id'] = $list['id'];
			$state->lists[$key]['name'] = $list['name'];
			$state->lists[$key]['toSync'] = $list['stats']['member_count'];

			$names[] = $list['name'];

			// Delete the old list info
			CmcHelperList::delete($list['id']);

			// Add the new list info
			$listModel = $this->getModel('List', 'cmcModel');
			$listModel->save($list);

			// Add the joomla list id
			$state->lists[$key]['id'] = $listModel->getState('list.id');

			// Delete users in that list
			CmcHelperUsers::delete($list['id']);
		}


		$state->header = JText::sprintf('COM_CMC_LISTS_TO_SYNC', count($state->lists));
		$state->message = JText::sprintf('COM_CMC_LISTS_TO_SYNC_DESC', '"' . implode('", "', $names) . '"', $state->lists[0]['name']);

		$state->offset = 0;

		CmcSyncerState::setState($state);

		$this->sendResponse($state);
	}

	/**
	 * Syncs the users of the list
	 *
	 * @return void
	 */
	public function batch()
	{
		// Check for a valid token. If invalid, send a 403 with the error message.
		JSession::checkToken('request') or $this->sendResponse(new Exception(JText::_('JINVALID_TOKEN'), 403));

		// Put in a buffer to silence noise.
		ob_start();

		// Remove the script time limit.
		@set_time_limit(0);

		$state = CmcSyncerState::getState();

		$chimp = new CmcHelperChimp;

		$members = $chimp->listMembers($state->lists[0]['mc_id'], 'subscribed', null, $state->offset, $state->batchSize);

		CmcHelperUsers::save($members['data'], $state->lists[0]['id'], $state->lists[0]['mc_id']);

		$pages = $state->lists[0]['toSync'] / $state->batchSize;

		if ($state->offset < $pages)
		{
			$state->offset = $state->offset + 1;
			$state->header = JText::sprintf('COM_CMC_BATCH_SYNC_IN_LIST', $state->lists[0]['name']);
			$state->message = JText::sprintf('COM_CMC_BATCH_SYNC_PROGRESS', $state->offset * $state->batchSize, $state->batchSize);
		}

		if ($state->offset >= $pages)
		{
			// First list in the array was synced, lets remove it
			$oldList = array_shift($state->lists);

			// If we still have lists, then let us reset the offset
			if (count($state->lists))
			{
				$state->header = JText::sprintf('COM_CMC_BATCH_SYNC_IN_OLD_LIST_COMPLETE', $oldList['name']);
				$state->message = JText::sprintf('COM_CMC_BATCH_SYNC_IN_OLD_LIST_COMPLETE_DESC', $oldList['toSync'], $oldList['name'], $state->lists[0]['name']);

				$state->offset = 0;
			}
			else
			{
				$state->header = JText::_('COM_CMC_SYNC_COMPLETE');
				$state->message = '<div class="alert alert-info">' . JText::_('COM_CMC_SYNC_COMPLETE_DESC') . '</div>';

			}
		}

		CmcSyncerState::setState($state);

		$this->sendResponse($state);
	}

	/**
	 * Method to handle a send a JSON response. The body parameter
	 * can be a Exception object for when an error has occurred or
	 * a JObject for a good response.
	 *
	 * @param   mixed  $data  JObject on success, Exception on error. [optional]
	 *
	 * @return  void
	 *
	 * @since   2.51.2
	 */
	public static function sendResponse($data = null)
	{
		// Send the assigned error code if we are catching an exception.
		if ($data instanceof Exception)
		{
			JLog::add($data->getMessage(), JLog::ERROR);
			JFactory::getApplication()->setHeader('status', $data->getCode());
			JFactory::getApplication()->sendHeaders();
		}

		// Create the response object.
		$response = new CmcSyncerResponse($data);

		// Add the buffer.
		$response->buffer = JDEBUG ? ob_get_contents() : ob_end_clean();

		// Send the JSON response.
		echo json_encode($response);

		// Close the application.
		JFactory::getApplication()->close();
	}
}
