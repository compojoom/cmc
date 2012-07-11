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

require_once dirname(__FILE__).'/config.php';

class CompojoomControlCenter {

    public static $version = '1.0';

    /**
     * Loads the translation strings -- this is an internal function, called automatically
     */
    private static function loadLanguage()
    {
        // Load translations
        $basePath = dirname(__FILE__);
        $jlang = JFactory::getLanguage();
        $jlang->load('compojoomcontrolcenter', $basePath, 'en-GB', true); // Load English (British)
        $jlang->load('compojoomcontrolcenter', $basePath, $jlang->getDefault(), true); // Load the site's eventlist language
        $jlang->load('compojoomcontrolcenter', $basePath, null, true); // Load the currently selected language
    }

    /**
     * Handles requests to the "liveupdate" view which is used to display
     * update information and perform the live updates
     */
    public static function handleRequest($task = 'overview')
    {
        // Load language strings
        self::loadLanguage();

        if($task == 'overview'){
            // Load the controller and let it run the show
            require_once dirname(__FILE__).'/classes/controller.php';
            $controller = new ControlCenterController();
            $controller->execute(JRequest::getCmd('task','overview'));
            $controller->redirect();
        } else {
            JRequest::setVar('task', $task);
            // Load the controller and let it run the show
            require_once dirname(__FILE__).'/classes/controller.php';
            $controller = new ControlCenterController();
            $controller->execute($task);
            $controller->redirect();
        }

    }



}