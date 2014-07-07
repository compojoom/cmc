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
jimport('joomla.application.component.controllerform');

/**
 * Class CmcControllerUser
 *
 * @since  1.0
 */
class CmcControllerUser extends JControllerForm
{
	/**
	 * Saves the user in the db
	 *
	 * @param   int     $key     - the key
	 * @param   object  $urlVar  - url vars
	 *
	 * @return bool|void
	 */
	public function save($key = null, $urlVar = null)
	{
		$row = JTable::getInstance('users', 'CmcTable');
		$input = JFactory::getApplication()->input;
		$params = JComponentHelper::getParams('com_cmc');
		$api_key = $params->get("api_key", '');
		$post = JRequest::get('post');
		$id = $input->getInt('id', 0);
		$post['id'] = $id;
		$list_id = $input->get('list_id', '');
		$email = $input->get('email', '');
		$firstname = $input->get('firstname', '');
		$lastname = $input->get('lastname', '');
		$email_type = $input->get('email_type', '');

		$user = JFactory::getUser();

		if (!$row->bind($post))
		{
			echo "<script> alert('" . $row->getError() . "'); window.history.go (-1); </script>\n";
			exit();
		}

		// Updating it to mailchimp

		if (empty($id))
		{
			CmcHelperBasic::subscribeList($api_key, $list_id, $email, $firstname, $lastname, $user, null, $email_type, false);
		}
		else
		{
			// Updating to MC
			CmcHelperBasic::subscribeList($api_key, $list_id, $email, $firstname, $lastname, $user, null, $email_type, true);
		}

		if (!$row->store())
		{
			echo "<script> alert('" . $row->getError() . "'); window.history.go (-1); </script>\n";
			exit();
		}

		switch ($this->task)
		{
			case 'apply':
				$msg = JText::_('COM_CMC_USER_APPLY');
				$link = 'index.php?option=com_cmc&view=user&layout=edit&id=' . $row->id;
				break;

			case 'save':
			default:
				$msg = JText::_('COM_CMC_USER_SAVE');
				$link = 'index.php?option=com_cmc&view=users';
				break;
		}

		$this->setRedirect($link, $msg);
	}

	/**
	 * Cancel and redirect
	 *
	 * @return bool|void
	 */
	public function cancel()
	{
		$link = 'index.php?option=com_cmc&view=users';
		$this->setRedirect($link);
	}
}
