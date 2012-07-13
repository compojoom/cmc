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

class CmcHelperSynchronize
{

    /**
     * Recreate / append the list and stores it in the database
     * @static
     * @param $apikey
     */
    public static function synchronizeList($apikey, $user, $append = false, $status="subscribed", $users = true)
    {
        $api = new MCAPI($apikey);
        $lists = $api->lists();

        $db =& JFactory::getDBO();

        if (!$append) {
            // Delete complete table and reset count to zero
            $query = "TRUNCATE #__cmc_lists";
            $db->setQuery($query);
            $db->query();
        }

        if ($api->errorCode) {
            JError::raiseError(500, JText::_("COM_CMC_API_ERROR") . " " . $api->errorMessage);
            return;
        }

        for ($i = 0; $i < count($lists['data']); $i++) {
            $list = $lists['data'][$i];
            $stats = $lists['data'][$i]['stats'];

//            var_dump($list);
//           var_dump($stats);
////
//            die("asdf");

            $list['list_name'] = $list['id'];

            $item = array();
            $item['id'] = null;
            $item['mc_id'] = $list['id'];
            $item['web_id'] = $list['web_id'];
            $item['list_name'] = $list['name'];
            $item['date_created'] = $list['date_created'];
            $item['email_type_option'] = $list['email_type_option'];
            $item['use_awesomebar'] = $list['use_awesomebar'];
            $item['default_from_name'] = $list['default_from_name'];
            $item['default_from_email'] = $list['default_from_email'];
            $item['default_subject'] = $list['default_subject'];
            $item['default_language'] = $list['default_language'];
            $item['list_rating'] = $list['list_rating'];
            $item['subscribe_url_short'] = $list['subscribe_url_short'];
            $item['subscribe_url_long'] = $list['subscribe_url_long'];
            $item['beamer_address'] = $list['beamer_address'];
            $item['visibility'] = $list['visibility'];
            $item['created_user_id'] = $user->id;
            $item['created_time'] = JFactory::getDate()->toMySQL();
            $item['modified_user_id'] = $user->id;
            $item['modified_time'] = JFactory::getDate()->toMySQL();
            $item['access'] = 1;
            $item['query_data'] = json_encode($list);

            $row = JTable::getInstance('lists', 'CmcTable');

            if (!$row->bind($item)) {
                return JError::raiseError(JText::_('COM_CMC_LIST_ERROR_SAVING') . " " . $row->getErrorMsg());
            }

            if (!$row->check()) {
                return JError::raiseError(JText::_('COM_CMC_LIST_ERROR_SAVING') . " " . $row->getErrorMsg());
            }

            if (!$row->store()) {
                return JError::raiseError(JText::_('COM_CMC_LIST_ERROR_SAVING') . " " . $row->getErrorMsg());
            }

            // Insert users into the new list

            if ($users) {

                // We can only get 15k users every query, so if we have more we need to do more queries
                if($stats['member_count'] < 15000) {
                    CmcHelperSynchronize::synchronizeUsers($apikey, $list['id'], $user);
                } else {
                    $cnt = $stats['member_count'] / 15000;

                    for($j = 0; $j < $cnt; $j++) {
                        $start = 15000 * $j;
                        $end = 15000 * ($j + 1);

                        CmcHelperSynchronize::synchronizeUsers($apikey, $list['id'], $user,
                            $status, $start, $end, $append);
                    }
                }
            }

            //die("asdf");
        }

        return true;
    }


    /**
     * Synchronize all lists and users to cmc
     * @static
     * @param $apikey
     * @param $user
     * @param null $listId
     * @param bool $append
     */
    public static function synchronize($apikey, $user, $append = false, $status = "subscribed")
    {
         CmcHelperSynchronize::synchronizeList($apikey, $user, $append, $status, true);
    }


    /**
     * @static
     * @param $apikey
     * @param $listId
     * @param $user
     * @param string $status
     * @param int $start
     * @param int $limit
     * @param bool $append
     * @return bool
     */
    public static function synchronizeUsers($apikey, $listId, $user,
                                            $status = "subscribed", $start = 0, $limit = 15000, $append = false)
    {
        $api = new MCAPI($apikey);

        $members = $api->listMembers($listId, $status, null, $start, $limit);

        if ($api->errorCode) {
            JError::raiseError(500, JText::_("COM_CMC_API_ERROR") . " " . $api->errorMessage);
            return;
        }

        $db =& JFactory::getDBO();

        if (!$append) {
            // We have to drop the items, because we could have more then one list
            $query = "DELETE FROM #__cmc_users WHERE list_id = '" . $listId . "'";
            $db->setQuery($query);
            $db->query();
        }

//        var_dump($members);
//        die("asdf");

        //  listMemberInfo(string apikey, string id, array email_address)


        foreach ($members['data'] as $member) {
            $item = array();
            $item['id'] = null;
            $item['list_id'] = $listId;

            $item['email'] = $member['email'];
            $item['timestamp'] = $member['timestamp'];

            $item['status'] = $status;

            $item['created_user_id'] = $user->id;
            $item['created_time'] = JFactory::getDate()->toMySQL();
            $item['modified_user_id'] = $user->id;
            $item['modified_time'] = JFactory::getDate()->toMySQL();
            $item['access'] = 1;
            $item['query_data'] = json_encode($member);

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

        return true;
    }

}