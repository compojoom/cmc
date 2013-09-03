<?php
/**
 * Compojoom Community-Builder Plugin
 * @package Joomla!
 * @Copyright (C) 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/

defined('_JEXEC') or die('Restricted access');

/**
 * Helper class for Registration plugins
 * Class CmcHelperRegistration
 */

define('_CPLG_JOOMLA', 0);
define('_CPLG_CB', 1);
define('_CPLG_JOMSOCIAL', 2);

class CmcHelperRegistration
{
    private static $instance;

    /**
     * Temporary saves the user merge_vars after the registration, no processing
     * Does not check if user E-Mail already exists (this has to be done before!)
     * @param $user joomla user obj
     * @param $postdata only cmc data
     * @param int $plg which plugin triggerd the save method
     */
    public static function saveTempUser($user, $postdata, $plg = _CPLG_JOOMLA)
    {
        $db = & JFactory::getDBO();
        $query = $db->getQuery(true);

        $postdata['OPTINIP'] = $_SERVER['REMOTE_ADDR'];

        $query->insert("#__cmc_register")->columns("user_id, params, plg")
            ->values($db->quote($user->id), $db->quote(json_encode($postdata)), $db->quote($plg));

        $db->setQuery($query);
        $db->query();
    }

    /**
     * @param $user
     */
    public static function activateTempUser($user)
    {

    }



}