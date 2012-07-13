<?php
/**
 * CmC
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 stable $
 **/

defined('_JEXEC') or die('Restricted access');

require_once( JPATH_COMPONENT_ADMINISTRATOR .  '/helper/defines.php');

JLoader::register('CmcHelperSettings', JPATH_COMPONENT_ADMINISTRATOR . '/helper/settings.php');
JLoader::register('CmcHelperSynchronize', JPATH_COMPONENT_ADMINISTRATOR . '/helper/synchronizehelper.php');
JLoader::register('CmcHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helper/basichelper.php');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR .  '/tables');

// Get the view and controller from the request, or set to eventlist if they weren't set
JRequest::setVar('controller', JRequest::getCmd('view','webhook')); // Black magic: Get controller based on the selected view

// Require specific controller if requested
if ($controller = JRequest::getCmd('controller')) {
    $path = JPATH_COMPONENT . '/controllers/' .  $controller . '.php';

    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

if ($controller == '') {
    require_once(JPATH_COMPONENT .'/controllers/webhook.php');
    $controller = 'webhook';
}

// Create the controller
$classname = 'CmcController' . $controller;
$controller = new $classname( );
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();


