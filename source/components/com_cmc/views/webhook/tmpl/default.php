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

$secure_key = CmcSettingsHelper::getSettings("secure_key", "");

if(empty($secure_key)){
    JError::raiseError("403", JText::("COM_CMC_SET_YOUT_SECURE_KEY_FIRST"));
    return;
}
//wh_log('==================[ Incoming Request ]==================');
//
//wh_log("Full _REQUEST dump:\n".print_r($_REQUEST,true)); 


// http://apidocs.mailchimp.com/webhooks/

$key = JRequest::getVar('key', '');
if (empty($key)) {
    //wh_log('No security key specified, ignoring request');
} else if ($key != $secure_key) {
   // Wrong key received
} else {
    $type = JRequest::getVar('type', '');

    switch($type) {
        case "subscribe": subscribe(JRequest::get('data')); break;
        case "unsubscribe": unsubcribe(JRequest::get('data')); break;
        case "cleaned": cleaned(JRequest::get('data')); break;
        case "upemail": upemail(JRequest::get('data')); break;
        case "profile": profile(JRequest::get('data')); break;
        default: break;
    }
}

function subscribe($data){
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

    $db =& JFactory::getDBO();

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
function unsubscribe($data){
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

    $db =& JFactory::getDBO();

    if ($data['action'] == "delete") {
        $email = $data['email'];
        // Droping the email from the list
        $query = "DELETE FROM #__cmc_users WHERE email = '" . $email . "' AND list_id = '" . $data['list_id'] . "'";
        $db->setQuery($query);
        $db->query();
    } else {
        $email = $data['email'];
        // TODO update the informations / reason too
        // Setting the email to unsubscribed
        $query = "UPDATE #__cmc_users SET status = 'unsubscribed' WHERE email = '" . $email
                . "' AND list_id = '" . $data['list_id'] . "'";
        $db->setQuery($query);
        $db->query();
    }
}

function cleaned($data){
    // Hmm
}

/**
 * @param $data
 */
function upemail($data){
    /**
     *  "type": "upemail",
        "fired_at": "2009-03-26\ 22:15:09",
        "data[list_id]": "a6b5da1054",
        "data[new_id]": "51da8c3259",
        "data[new_email]": "api+new@mailchimp.com",
        "data[old_email]": "api+old@mailchimp.com"
     */

   $db =& JFactory::getDBO();
   $oldmail = $data['old_email'];
   $newmail = $data['new_email'];

   $query = "UPDATE #__cmc_users SET email = '" . $newmail . "', mc_id = '" . $data['new_id'] . "', timestamp = '"
        . $data['fired_at'] . "', modified_time = '" . $data['fired_at'] . "'   WHERE email = '" . $oldmail
        . "' AND list_id = '" . $data['list_id'] . "'";
   $db->setQuery($query);
   $db->query();
}

/**
 * @param $data
 */
function profile($data){
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

   $db =& JFactory::getDBO();

   $mc_id = $data['id'];
   $list_id = $data['list_id'];
   $email = $data['email'];
   $email_type = $data['email_type'];
   $ip_opt = $data['ip_opt'];

    // Will the E-Mail address been changed on profile updates?....

    $query = "UPDATE #__cmc_users SET mc_id = '" . $mc_id . "', email_type = '"
            . $email_type . "', ip_opt = '" . $ip_opt . "', timestamp = '" . $data['fired_at']
            . "', modified_time = '"  . $data['fired_at'] . "' WHERE email = '" . $email
            . "' AND list_id = '" . $list_id . "'";
   $db->setQuery($query);
   $db->query();
}
