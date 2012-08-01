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

class CmcControllerUsers extends JControllerAdmin {

    /**
     * Proxy for getModel.
     * @since	1.6
     */
    public function getModel($name = 'User', $prefix = 'CmcModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }


    public function delete(){
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $params = JComponentHelper::getParams('com_cmc');
        $api_key = $params->get("api_key", '');
        $db = JFactory::getDBO();

        if (count($cid)) {
            for($i = 0; $i < $cid; $i++){
                $query = "SELECT * FROM #__cmc_users WHERE id = '" . $cid[$i] . "'";
                $db->setQuery($query);
                $member = $db->loadObject();
                CmcHelperBasic::unsubscribeList($api_key, $member->list_id, $member->email);
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
}