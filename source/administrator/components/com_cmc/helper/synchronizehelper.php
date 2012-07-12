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

public class CmcHelperSynchronize {

    /**
     * @static
     * @param $apikey
     */
    public static function synchronizeList($apikey, $append = false){
        $api = new MCAPI($apikey);
        $lists = $api->lists();

        $db =& JFactory::getDBO();

        if(!$append) {
            // Delete complete table and reset count to zero
            $query = "TRUNCATE #__cmc_lists";
            $db->setQuery($query);
            $db->query();
        }


        if ($api->errorCode){
            JError::raiseError(500, JText::_("COM_CMC_API_ERROR") . " " . $api->errorMessage);
            return;
        }

        foreach ($lists['data'] as $list){

        }

    }
    
}