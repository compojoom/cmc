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
class CmcModelSettings extends JModel
{
    var $_data  = null;
    var $_id	= null;

    function __construct() {
        parent::__construct();
    }

    function getData() {
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query);
        }
        return $this->_data;
    }

    function isCheckedOut( $uid=0 ) {
        if ($this->_loadData()) {
            if ($uid) {
                return ($this->_data->checked_out && $this->_data->checked_out != $uid);
            } else {
                return $this->_data->checked_out;
            }
        }
    }

    function checkin() {
        if ($this->_id) {
            $hotspots = & $this->getTable();
            if(! $hotspots->checkin($this->_id)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return false;
    }

    function checkout($uid = null) {
        if ($this->_id) {

            if (is_null($uid)) {
                $user	=& JFactory::getUser();
                $uid	= $user->get('id');
            }

            $hotspots = & $this->getTable();
            if(!$hotspots->checkout($uid, $this->_id)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            return true;
        }
        return false;
    }

    function store($dataArray)
    {
        $row =& $this->getTable('Settings', 'Table');

        if (!empty($dataArray)) {
            foreach ($dataArray as $key => $value) {
                $data['id'] 	= $key;
                $data['value']	= $value;

                if (!$row->bind($data)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }

                if (!$row->check()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }

                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
        }

        return true;
    }


    function _buildQuery() {

        $query = ' SELECT st.*'
            . ' FROM #__cmc_settings AS st'
            . ' ORDER BY st.id';
        return $query;
    }
}