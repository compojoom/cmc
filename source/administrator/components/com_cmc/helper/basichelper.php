<?php
/**
 * Cmc
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 stable $
 **/


defined('_JEXEC') or die('Restricted access');

class CmcHelper {

    /**
     * @static
     * @return bool
     */
    public static function checkRequiredSettings()
    {
        $api_key = CmcSettingsHelper::getSettings("api_key", '');
        $webhook = CmcSettingsHelper::getSettings("webhook_secret", '');

        if(!empty($api_key) && !empty($webhook)){
            return true;
        }

        return false;
    }

    /**
     * @static
     * @param $api_key
     * @param $list_id
     * @param $email
     * @param $firstname
     * @param $lastname
     * @param null $user
     * @param array $groupings
     */
    public static function subscribeList($api_key, $list_id, $email, $firstname, $lastname, $user = null, $groupings = array(null)){

        $api = new MCAPI($api_key);

        $merge_vars = array('FNAME'=>$firstname, 'LNAME'=>$lastname,
            $groupings
        );

        // By default this sends a confirmation email - you will not see new members
        // until the link contained in it is clicked!
        $retval = $api->listSubscribe( $list_id, $email, $merge_vars );

        if ($api->errorCode){
            return(JError::raiseError(JTEXT::_("COM_CMC_SUBSCRIBE_FAILED")) . " " .$api->errorCode . " / " . $api->errorMessage);
        } else {
            return true;
        }
    }

    /**
     * @static
     * @param $api_key
     * @param $list_id
     * @param $email
     * @param null $user
     * @return bool|string
     */
    public static function unsubscribeList($api_key, $list_id, $email, $user = null){
        $api = new MCAPI($api_key);

        $retval = $api->listUnsubscribe( $list_id, $email);
        if ($api->errorCode){
            return(JError::raiseError(JTEXT::_("COM_CMC_UNSUBSCRIBE_FAILED")) . " " .$api->errorCode . " / " . $api->errorMessage);
        } else {
            return true;
        }
    }


    /**
     * @static
     * @param $api_key
     * @param $list_id
     * @param $email
     * @param null $firstname
     * @param null $lastname
     * @param string $email_type
     * @param null $user
     * @return bool|string
     */
    public static function updateUser($api_key, $list_id, $email, $firstname=null, $lastname =null, $email_type="html", $user=null){
        $api = new MCAPI($api_key);

        $merge_vars = array("FNAME"=>$firstname, "LNAME"=>$lastname);

        $retval = $api->listUpdateMember($list_id, $email, $merge_vars, $email_type, false);

        if ($api->errorCode){
            return(JError::raiseError(JTEXT::_("COM_CMC_UNSUBSCRIBE_FAILED")) . " " .$api->errorCode . " / " . $api->errorMessage);
        } else {
            return true;
        }
    }

    /**
     * @static
     * @param $api_key
     * @param $list_id
     * @param bool $optin
     * @param bool $up_exist
     * @param bool $replace_int
     * @return string
     */
    public static function subscribeListBatch($api_key, $list_id, $batchlist, $optin = true, $up_exist=true, $replace_int = false){
        $api = new MCAPI($api_key);

//        $batch[] = array('EMAIL'=>$my_email, 'FNAME'=>'Joe');
//        $batch[] = array('EMAIL'=>$boss_man_email, 'FNAME'=>'Me', 'LNAME'=>'Chimp');

        // Todo check rights

        $optin = true; //yes, send optin emails
        $up_exist = true; // yes, update currently subscribed users
        $replace_int = false; // no, add interest, don't replace

        $vals = $api->listBatchSubscribe($list_id, $batch, $optin, $up_exist, $replace_int);

        if ($api->errorCode){
            return(JError::raiseError(JTEXT::_("COM_CMC_UNSUBSCRIBE_FAILED")) . " " .$api->errorCode . " / " . $api->errorMessage);
        } else {
            // Todo return this
            echo "added:   ".$vals['add_count']."\n";
            echo "updated: ".$vals['update_count']."\n";
            echo "errors:  ".$vals['error_count']."\n";
            foreach($vals['errors'] as $val){
                echo $val['email_address']. " failed\n";
                echo "code:".$val['code']."\n";
                echo "msg :".$val['message']."\n";
            }
        }

    }



}