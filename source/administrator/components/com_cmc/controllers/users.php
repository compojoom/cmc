<?php
/**
 * Tiles
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controlleradmin');

class CmcControllerUsers extends JControllerAdmin
{

    /**
     * Proxy for getModel.
     * @since    1.6
     */
    public function getModel($name = 'User', $prefix = 'CmcModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }


    public function delete()
    {
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $params = JComponentHelper::getParams('com_cmc');
        $api_key = $params->get("api_key", '');
        $db = JFactory::getDBO();

        if (count($cid)) {
            for ($i = 0; $i < $cid; $i++) {
                $query = "SELECT * FROM #__cmc_users WHERE id = '" . $cid[$i] . "'";
                $db->setQuery($query);
                $member = $db->loadObject();
                CmcHelperBasic::unsubscribeList($member);
            }

            $cids = implode(',', $cid);
            $query = "DELETE FROM #__cmc_users where id IN ( $cids )";
            $db->setQuery($query);
            if (!$db->query()) {
                echo "<script> alert('" . $db->getErrorMsg() . "'); window.history.go (-1); </script>\n";
            }
        }
        $this->setRedirect('index.php?option=com_cmc&view=users');
    }

    /**
     * Exports users to CSV file for download
     *
     */
    public function export()
    {
        $model = $this->getModel('Users');
        $users = $model->export();

        $output = fopen('php://output', 'w') or die("Can't open php://output");

        header('Content-Type:application/csv');
        header('Content-Disposition: attachment; filename="users.csv"');

        fputcsv($output, array('firstname', 'lastname', 'email', 'user_id', 'timestamp', 'list_id', 'status'), ',', '"');

        foreach ($users as $user) {
            fputcsv($output, $user, ',', '"');
        }

        fclose($output) or die("can't close php://output");

        jexit();
    }

    public function addGroup()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $appl = JFactory::getApplication();
        $input = $appl->input;
        $chunks = 0;

        $list = $input->get('filter_list');
        $groups = $input->get('usergroups', array(), 'ARRAY');

        // get the joomla users in the specific groups
        $query->select(array('id', 'name', 'username', 'email'))->from($db->qn('#__users') . ' AS u')
            ->leftJoin('#__user_usergroup_map AS m ON u.id = m.user_id')
            ->where('group_id IN (' . implode(',', $groups) . ')');

        $db->setQuery($query);
        $users = $db->loadObjectList('email');

        // get mailchimp users in the specific list
        $query->clear();
        $query->select(array('email'))->from('#__cmc_users')->where('list_id = ' . $db->q($list));
        $db->setQuery($query);
        $musers = $db->loadObjectList();

        //remove the users that are already in the list
        foreach ($musers as $value) {
            if (isset($users[$value->email])) {
                unset($users[$value->email]);
            }
        }

        if (count($users)) {
            // prepare the array for the mailchimp subscribe function
            foreach ($users as $user) {
                $names = explode(' ', $user->name);
                $u = array('EMAIL' => $user->email, 'FNAME' => $names[0]);
                if (isset($names[1])) {
                    $u['LNAME'] = $names[1];
                }
                $batch[] = $u;
            }

            // make sure that we process no more than 5000 records at a time
            if (count($batch) > 5000) {
                $chunks = array_chunk($batch, 5000);
            }

            if ($chunks) {
                foreach ($chunks as $chunk) {
                    $this->batchSubscribe($list, $chunk);
                }
            } else {
                $this->batchSubscribe($list, $batch);
            }
        } else {
            $appl->enqueueMessage('COM_CMC_NO_NEW_USERS_IN_THE_GROUPS');
        }


        $appl->redirect('index.php?option=com_cmc&view=users');
    }

    private function batchSubscribe($list, $batch)
    {
        $appl = JFactory::getApplication();
        $chimp = new cmcHelperChimp;
        $status = $chimp->listBatchSubscribe($list, $batch);

        if ($status['error_count']) {
            foreach ($status['errors'] as $error) {
                $appl->enqueueMessage($error['message']);
            }
        } else {
            $appl->enqueueMessage(JText::_('COM_CMC_ADD_GROUP_SUCCESS'));
        }
    }
}