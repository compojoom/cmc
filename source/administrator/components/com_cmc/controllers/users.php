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
jimport('joomla.application.component.controlleradmin');

/**
 * Class CmcControllerUsers
 *
 * @since  1.0
 */
class CmcControllerUsers extends JControllerAdmin
{
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 */
	public function getModel($name = 'User', $prefix = 'CmcModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Delete users
	 *
	 * @throws Exception
	 *
	 * @return void
	 */
	public function delete()
	{
		$input = JFactory::getApplication()->input;
		$cid = $input->get('cid', array(), 'array');
		$params = JComponentHelper::getParams('com_cmc');
		$api_key = $params->get("api_key", '');
		$db = JFactory::getDBO();

		if (count($cid))
		{
			for ($i = 0; $i < count($cid); $i++)
			{
				$query = "SELECT * FROM #__cmc_users WHERE id = '" . $cid[$i] . "'";
				$db->setQuery($query);
				$member = $db->loadObject();

				try
				{
					CmcHelperBasic::unsubscribeList($member);
				}
				catch (Exception $e)
				{
					// Catching the case where the user is already unsubscribed from mailchimp
					if ($e->getCode() != 232)
					{
						throw $e;
					}
				}

			}

			$cids = implode(',', $cid);
			$query = "DELETE FROM #__cmc_users where id IN ( $cids )";
			$db->setQuery($query);

			try
			{
				$db->execute();
			}
			catch (Exception $e)
			{
				JFactory::getApplication()->enqueueMessage($e->getMessage());
			}
		}

		$this->setRedirect('index.php?option=com_cmc&view=users');
	}

	/**
	 * Exports users to CSV file for download
	 *
	 * @return void
	 */
	public function export()
	{
		$model = $this->getModel('Users');
		$users = $model->export();

		$output = fopen('php://output', 'w') or die("Can't open php://output");

		header('Content-Type:application/csv');
		header('Content-Disposition: attachment; filename="users.csv"');

		fputcsv($output, array('firstname', 'lastname', 'email', 'user_id', 'timestamp', 'list_id', 'status'), ',', '"');

		foreach ($users as $user)
		{
			fputcsv($output, $user, ',', '"');
		}

		fclose($output) or die("can't close php://output");

		jexit();
	}

	/**
	 * Adds the users from a group to the newsletter
	 *
	 * @return void
	 */
	public function addGroup()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$appl = JFactory::getApplication();
		$input = $appl->input;
		$chunks = 0;

		$list = $input->get('addtolist');
		$groups = $input->get('usergroups', array(), 'ARRAY');

		// Get the joomla users in the specific groups
		$query->select(array('id', 'name', 'username', 'email'))->from($db->qn('#__users') . ' AS u')
			->leftJoin('#__user_usergroup_map AS m ON u.id = m.user_id')
			->where('group_id IN (' . implode(',', $groups) . ')');

		$db->setQuery($query);
		$users = $db->loadObjectList('email');

		// Get mailchimp users in the specific list
		$query->clear();
		$query->select(array('email'))->from('#__cmc_users')->where('list_id = ' . $db->q($list));
		$db->setQuery($query);
		$musers = $db->loadObjectList();

		// Remove the users that are already in the list
		foreach ($musers as $value)
		{
			if (isset($users[$value->email]))
			{
				unset($users[$value->email]);
			}
		}

		if (count($users))
		{
			// Prepare the array for the mailchimp subscribe function
			foreach ($users as $user)
			{
				$names = explode(' ', $user->name);
				$u = array('EMAIL' => $user->email, 'FNAME' => $names[0]);

				if (isset($names[1]))
				{
					$u['LNAME'] = $names[1];
				}

				$batch[] = $u;
			}

			// Make sure that we process no more than 5000 records at a time
			if (count($batch) > 5000)
			{
				$chunks = array_chunk($batch, 5000);
			}

			if ($chunks)
			{
				foreach ($chunks as $chunk)
				{
					$this->batchSubscribe($list, $chunk);
				}
			}
			else
			{
				$this->batchSubscribe($list, $batch);
			}
		}
		else
		{
			$appl->enqueueMessage('COM_CMC_NO_NEW_USERS_IN_THE_GROUPS');
		}


		$appl->redirect('index.php?option=com_cmc&view=users');
	}

	/**
	 * Batch subscribe users
	 *
	 * @param   string  $list   - the list to subscribe to
	 * @param   array   $batch  - the batch with users
	 *
	 * @return void
	 */
	private function batchSubscribe($list, $batch)
	{
		$appl = JFactory::getApplication();
		$chimp = new cmcHelperChimp;
		$status = $chimp->listBatchSubscribe($list, $batch);

		if ($status['error_count'])
		{
			foreach ($status['errors'] as $error)
			{
				$appl->enqueueMessage($error['message']);
			}
		}
		else
		{
			$appl->enqueueMessage(JText::_('COM_CMC_ADD_GROUP_SUCCESS'));
		}
	}
}
