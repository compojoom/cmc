<?php
/**
 * CmC
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 stable $
 **/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');


class CmcControllerWebhooks extends JController
{
    public function __construct($config = array()) {
        JLog::addLogger(
                array(
                    'text_file' => 'com_cmc.webhooks.php'
                )
            );
        parent::__construct();
    }

    public function request()
    {

        $secure_key = JComponentHelper::getParams('com_cmc')->get("webhooks_key", "");
        $input = JFactory::getApplication()->input;

        $key = $input->get('key','','string');

        if ($key != $secure_key) {
            $message = 'wrong key';
            JLog::add(json_encode($message));
            jexit();
        }

        $type = $input->get('type', '');

        // log the request to the log file
        $message = array(
            $input->get('type', ''),
            $input->get('data', '', 'array')
        );
        JLog::add(json_encode($message));

        switch ($type) {
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

    public function subscribe($data)
    {
        /**
         *  "type": "subscribe",
        "fired_at": "2009-03-26 21:35:57",
        "data[id]": "8a25ff1d98",
        "data[list_id]": "a6b5da1054",
        "data[email]": "api@mailchimp.com",
        "data[email_type]": "html",
        "data[merges][EMAIL]": "api@mailchimp.com",
        "data[merges][FNAME]": "MailChimp",
        "data[merges][LNAME]": "API",
        "data[merges][INTERESTS]": "Group1,Group2",
        "data[ip_opt]": "10.20.10.30",
        "data[ip_signup]": "10.20.10.30"
         */

        $item = array();
        $item['id'] = null;
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
        $item['created_time'] = JFactory::getDate()->toMySQL();
        $item['modified_user_id'] = 0;
        $item['modified_time'] = JFactory::getDate()->toMySQL();
        $item['access'] = 1;
        $item['query_data'] = json_encode($data);

        $row = JTable::getInstance('users', 'CmcTable');

        if (!$row->bind($item)) {
            return JError::raiseError(JText::_('COM_CMC_LIST_ERROR_SAVING') . " " . $row->getErrorMsg());
        }

        if (!$row->check()) {
            return JError::raiseError(JText::_('COM_CMC_LIST_ERROR_SAVING') . " " . $row->getErrorMsg());
        }

        if (!$row->store()) {
            return JError::raiseError(JText::_('COM_CMC_LIST_ERROR_SAVING') . " " . $row->getErrorMsg());
        }
    }

    /**
     * @param $data
     */
    public function unsubscribe($data)
    {
        /**
         *  "type": "unsubscribe",
        "fired_at": "2009-03-26 21:40:57",
        "data[action]": "unsub",
        "data[reason]": "manual",
        "data[id]": "8a25ff1d98",
        "data[list_id]": "a6b5da1054",
        "data[email]": "api+unsub@mailchimp.com",
        "data[email_type]": "html",
        "data[merges][EMAIL]": "api+unsub@mailchimp.com",
        "data[merges][FNAME]": "MailChimp",
        "data[merges][LNAME]": "API",
        "data[merges][INTERESTS]": "Group1,Group2",
        "data[ip_opt]": "10.20.10.30",
        "data[campaign_id]": "cb398d21d2",
        "data[reason]": "hard"
         */

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $email = $data['email'];

        if ($data['action'] == "delete") {

            // Droping the email from the list
            $query->delete('#__cmc_users')->where('email =' . $db->quote($email) . ' AND list_id = ' . $db->quote($data['list_id']));
            $db->setQuery($query);
            $db->query();
        } else {

            // TODO update the informations / reason too
            // Setting the email to unsubscribed
            $query->update('#__cmc_users')->set('status = '.$db->quote('unsubscribed'))
                ->where('email =' . $db->quote($email) . ' AND list_id = ' . $db->quote($data['list_id']));
            $db->setQuery($query);
            $db->query();
        }
    }

    public function cleaned($data)
    {
        // Hmm
    }

    /**
     * @param $data
     */
    public function upemail($data)
    {
        /**
         *  "type": "upemail",
        "fired_at": "2009-03-26\ 22:15:09",
        "data[list_id]": "a6b5da1054",
        "data[new_id]": "51da8c3259",
        "data[new_email]": "api+new@mailchimp.com",
        "data[old_email]": "api+old@mailchimp.com"
         */

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $oldmail = $data['old_email'];
        $newmail = $data['new_email'];

        $query->update('#__cmc_users')->set(array(
            'email = ' . $db->quote($newmail),
            'mc_id = ' . $db->quote($data['new_id']),
            'timestamp = '. $db->quote($data['fired_at']),
            'modified_time = ' . $db->quote($data['fired_at'])
            ))->where('email = ' . $db->quote($oldmail) . ' AND list_id = ' . $db->quote($data['list_id'] ));

        $db->setQuery($query);
        $db->query();
    }

    /**
     * @param $data
     */
    public function profile($data)
    {
        /**
         *  "type": "profile",
        "fired_at": "2009-03-26 21:31:21",
        "data[id]": "8a25ff1d98",
        "data[list_id]": "a6b5da1054",
        "data[email]": "api@mailchimp.com",
        "data[email_type]": "html",
        "data[merges][EMAIL]": "api@mailchimp.com",
        "data[merges][FNAME]": "MailChimp",
        "data[merges][LNAME]": "API",
        "data[merges][INTERESTS]": "Group1,Group2",
        "data[ip_opt]": "10.20.10.30"
         */

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $mc_id = $data['id'];
        $list_id = $data['list_id'];
        $email = $data['email'];
        $email_type = $data['email_type'];
        $ip_opt = $data['ip_opt'];

        // Will the E-Mail address been changed on profile updates?....

        $query->update('#__cmc_users')->set(array(
            'mc_id = ' . $db->quote($mc_id),
            'email_type = '. $db->quote($email_type),
            'ip_opt = '. $db->quote($ip_opt),
            'timestamp = '. $db->quote( $data['fired_at']),
            'modified_time = '. $db->quote($data['fired_at'])
        ))->where('email = ' . $db->quote($email) . ' AND list_id = ' . $db->quote($list_id));

        $db->setQuery($query);
        $db->query();
    }
}

