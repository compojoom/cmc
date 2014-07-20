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
 * Class CmcControllerWebhooks
 *
 * @since  1.0
 */
class CmcControllerWebhooks extends JControllerLegacy
{
	/**
	 * The constructor
	 *
	 * @param   array  $config  - config array
	 */
	public function __construct($config = array())
	{
		JLog::addLogger(
			array(
				'text_file' => 'com_cmc.webhooks.php'
			)
		);
		parent::__construct();
	}

	/**
	 * Handles the request
	 *
	 * @return void
	 */
	public function request()
	{
		$secure_key = JComponentHelper::getParams('com_cmc')->get("webhooks_key", "");
		$input = JFactory::getApplication()->input;

		$key = $input->get('key', '', 'string');

		if ($key != $secure_key)
		{
			$message = 'wrong key';
			JLog::add(json_encode($message));
			jexit();
		}

		$type = $input->get('type', '');

		// Log the request to the log file
		$message = array(
			$input->get('type', ''),
			$input->get('data', '', 'array')
		);
		JLog::add(json_encode($message));

		switch ($type)
		{
			case "subscribe":
				$this->subscribe($input->get('data', '', 'array'));
				break;
			case "unsubscribe":
				$this->unsubscribe($input->get('data', '', 'array'));
				break;
			case "cleaned":
				$this->cleaned($input->get('data', '', 'array'));
				break;
			case "upemail":
				$this->upemail($input->get('data', '', 'array'));
				break;
			case "profile":
				$this->profile($input->get('data', '', 'array'));
				break;
			default:
				break;
		}

		jexit();
	}

	/**
	 * Subscribes the user
	 *
	 * @param   array  $data  - the user array
	 *
	 * @return mixed
	 */
	public function subscribe($data)
	{
		/**
		 *  "type": "subscribe",
		 * "fired_at": "2009-03-26 21:35:57",
		 * "data[id]": "8a25ff1d98",
		 * "data[list_id]": "a6b5da1054",
		 * "data[email]": "api@mailchimp.com",
		 * "data[email_type]": "html",
		 * "data[merges][EMAIL]": "api@mailchimp.com",
		 * "data[merges][FNAME]": "MailChimp",
		 * "data[merges][LNAME]": "API",
		 * "data[merges][INTERESTS]": "Group1,Group2",
		 * "data[ip_opt]": "10.20.10.30",
		 * "data[ip_signup]": "10.20.10.30"
		 */
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$item = array('id' => null);

		$query->select('id')->from('#__cmc_users')->where('email=' . $db->q($data['email']))
			->where('list_id=' . $db->q($data['list_id']));

		$db->setQuery($query);
		$update = $db->loadObject();

		if ($update)
		{
			$item['id'] = $update->id;
		}

		$item['mc_id'] = $data['id'];
		$item['list_id'] = $data['list_id'];

		$item['email'] = $data['email'];
		$item['timestamp'] = $data['fired_at'];

		$item['status'] = "subscribed";
		$item['email_type'] = $data['email_type'];
		$item['firstname'] = $data['merges']['FNAME'];
		$item['lastname'] = $data['merges']['LNAME'];

		$item['interests'] = $data['merges']['INTERESTS'];

		$item['merges'] = json_encode($data['merges']);

		$item['ip_opt'] = $data['ip_opt'];
		$item['ip_signup'] = $data['ip_signup'];

		$item['created_user_id'] = 0;
		$item['created_time'] = JFactory::getDate()->toSql();
		$item['modified_user_id'] = 0;
		$item['modified_time'] = JFactory::getDate()->toSql();
		$item['access'] = 1;
		$item['query_data'] = json_encode($data);

		$row = JTable::getInstance('users', 'CmcTable');

		try
		{
			$row->bind($item);
			$row->check();
			$row->store();
		}
		catch (Exception $e)
		{
			// Log the request to the log file
			$message = array(
				'save - subscribed error',
				$data
			);
			JLog::add(json_encode($message));
		}
	}

	/**
	 * Handles the unsubscribe process
	 *
	 * @param   array  $data  - the user data
	 *
	 * @return mixed
	 */
	public function unsubscribe($data)
	{
		/**
		 *  "type": "unsubscribe",
		 * "fired_at": "2009-03-26 21:40:57",
		 * "data[action]": "unsub",
		 * "data[reason]": "manual",
		 * "data[id]": "8a25ff1d98",
		 * "data[list_id]": "a6b5da1054",
		 * "data[email]": "api+unsub@mailchimp.com",
		 * "data[email_type]": "html",
		 * "data[merges][EMAIL]": "api+unsub@mailchimp.com",
		 * "data[merges][FNAME]": "MailChimp",
		 * "data[merges][LNAME]": "API",
		 * "data[merges][INTERESTS]": "Group1,Group2",
		 * "data[ip_opt]": "10.20.10.30",
		 * "data[campaign_id]": "cb398d21d2",
		 * "data[reason]": "hard"
		 */

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$email = $data['email'];

		if ($data['action'] == "delete")
		{
			// Droping the email from the list
			$query->delete('#__cmc_users')->where('email =' . $db->quote($email) . ' AND list_id = ' . $db->quote($data['list_id']));
			$db->setQuery($query);
			$db->execute();
		}
		else
		{
			// TODO update the informations / reason too
			// Setting the email to unsubscribed
			$query->update('#__cmc_users')->set('status = ' . $db->quote('unsubscribed'))
				->where('email =' . $db->quote($email) . ' AND list_id = ' . $db->quote($data['list_id']));
			$db->setQuery($query);
			$db->execute();
		}
	}

	/**
	 * Cleaned user
	 *
	 * @param   array  $data  - the user data
	 *
	 * @return void
	 */
	public function cleaned($data)
	{
		// Hmm
	}

	/**
	 * Updates the user email
	 *
	 * @param   array  $data  - the user data
	 *
	 * @return void
	 */
	public function upemail($data)
	{
		/**
		 *  "type": "upemail",
		 * "fired_at": "2009-03-26\ 22:15:09",
		 * "data[list_id]": "a6b5da1054",
		 * "data[new_id]": "51da8c3259",
		 * "data[new_email]": "api+new@mailchimp.com",
		 * "data[old_email]": "api+old@mailchimp.com"
		 */

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$oldmail = $data['old_email'];
		$newmail = $data['new_email'];

		$query->update('#__cmc_users')->set(
			array(
				'email = ' . $db->quote($newmail),
				'mc_id = ' . $db->quote($data['new_id']),
				'timestamp = ' . $db->quote($data['fired_at']),
				'modified_time = ' . $db->quote($data['fired_at'])
			)
		)->where('email = ' . $db->quote($oldmail) . ' AND list_id = ' . $db->quote($data['list_id']));

		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Updates the user profile
	 *
	 * @param   array  $data  - the user object
	 *
	 * @return void
	 */
	public function profile($data)
	{
		/**
		 *  "type": "profile",
		 * "fired_at": "2009-03-26 21:31:21",
		 * "data[id]": "8a25ff1d98",
		 * "data[list_id]": "a6b5da1054",
		 * "data[email]": "api@mailchimp.com",
		 * "data[email_type]": "html",
		 * "data[merges][EMAIL]": "api@mailchimp.com",
		 * "data[merges][FNAME]": "MailChimp",
		 * "data[merges][LNAME]": "API",
		 * "data[merges][INTERESTS]": "Group1,Group2",
		 * "data[ip_opt]": "10.20.10.30"
		 */

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$mc_id = $data['id'];
		$list_id = $data['list_id'];
		$email = $data['email'];
		$email_type = $data['email_type'];
		$ip_opt = $data['ip_opt'];

		// Will the E-Mail address been changed on profile updates?....

		$query->update('#__cmc_users')->set(
			array(
				'mc_id = ' . $db->quote($mc_id),
				'email_type = ' . $db->quote($email_type),
				'ip_opt = ' . $db->quote($ip_opt),
				'timestamp = ' . $db->quote($data['fired_at']),
				'modified_time = ' . $db->quote($data['fired_at'])
			)
		)->where('email = ' . $db->quote($email) . ' AND list_id = ' . $db->quote($list_id));

		$db->setQuery($query);
		$db->execute();
	}
}
