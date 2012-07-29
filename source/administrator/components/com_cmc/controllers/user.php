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
jimport('joomla.application.component.controllerform');

class CmcControllerUser extends JControllerForm {


    public function save($key = null, $urlVar = null) {
        $row = JTable::getInstance('users', 'CmcTable');
        $params = JComponentHelper::getParams('com_cmc');
        $api_key = $params->get("api_key", '');
        $post = JRequest::get('post');
        $id = JRequest::getInt('id', 0);
        $post['id'] = $id;
        $list_id = JRequest::getVar('list_id', '');
        $email = JRequest::getVar('email', '');
        $firstname = JRequest::getVar('firstname', '');
        $lastname = JRequest::getVar('lastname', '');
        $email_type = JRequest::getVar('email_type', '');

        $user = JFactory::getUser();

        if (!$row->bind($post)) {
            echo "<script> alert('" . $row->getError() . "'); window.history.go (-1); </script>\n";
            exit();
        }

        // Updating it to mailchimp

//        if (!isset($row->published)) {
//            $row->published = 1;
//        }

        if(empty($id)){
            CmcHelperBasic::subscribeList($api_key, $list_id, $email, $firstname, $lastname, $user, null, $email_type, false);
        } else {
            // Updating to MC
            CmcHelperBasic::subscribeList($api_key, $list_id, $email, $firstname, $lastname, $user, null, $email_type, true);
        }

        if (!$row->store()) {
            echo "<script> alert('" . $row->getError() . "'); window.history.go (-1); </script>\n";
            exit();
        }

        switch ($this->task) {
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

    public function cancel() {
        $link = 'index.php?option=com_cmc&view=users';
        $this->setRedirect($link);
    }

}