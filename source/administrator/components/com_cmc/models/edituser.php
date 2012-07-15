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

defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class CmcModelEditUser extends JModel {

    public function __construct() {
        parent::__construct();
        $array = JRequest::getVar('id', 0, '', 'array');
        $this->setId = (int) $array[0];
    }

    public function getUser() {
        $array2 = JRequest::getVar('id', 0, '', 'array');
        $this->setId2 = (int) $array2[0];
        $id = $this->setId2;

        if (empty($this->_data)) {
            $query = $this->_buildQuery($id);
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
        }
        return $this->_data;
    }

    private function _buildQuery($id) {
        $query = "SELECT * FROM #__cmc_users WHERE id = '" . $id . "'";
        return $query;
    }

}