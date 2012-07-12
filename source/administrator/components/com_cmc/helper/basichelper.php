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

}