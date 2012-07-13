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

//wh_log('==================[ Incoming Request ]==================');
//
//wh_log("Full _REQUEST dump:\n".print_r($_REQUEST,true)); 

$key = JRequest::getVar('key', '');
if (empty($key)){
    wh_log('No security key specified, ignoring request');

} else if ($key != $secure_key) {
        wh_log('Security key specified, but not correct:');
        wh_log("\t".'Wanted: "'.$my_key.'", but received "'.$_GET['key'].'"');
} else {
    //process the request
    wh_log('Processing a "'.$_POST['type'].'" request...');
    switch($_POST['type']){
        case 'subscribe'  : subscribe($_POST['data']);   break;
        case 'unsubscribe': unsubscribe($_POST['data']); break;
        case 'cleaned'    : cleaned($_POST['data']);     break;
        case 'upemail'    : upemail($_POST['data']);     break;
        case 'profile'    : profile($_POST['data']);     break;
        default:
            wh_log('Request type "'.$_POST['type'].'" unknown, ignoring.');
    }
}
wh_log('Finished processing request.');

/***********************************************
    Helper Functions
 ***********************************************/
function wh_log($msg){
    $logfile = 'webhook.log';
    file_put_contents($logfile,date("Y-m-d H:i:s")." | ".$msg."\n",FILE_APPEND);
}

function subscribe($data){
    wh_log($data['email'] . ' just subscribed!');
}
function unsubscribe($data){
    wh_log($data['email'] . ' just unsubscribed!');
}
function cleaned($data){
    wh_log($data['email'] . ' was cleaned from your list!');
}
function upemail($data){
    wh_log($data['old_email'] . ' changed their email address to '. $data['old_email']. '!');
}
function profile($data){
    wh_log($data['email'] . ' updated their profile!');
}

