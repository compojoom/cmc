<?php
/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 09.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_cmc')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
// thank you for this black magic Nickolas :)
// Magic: merge the default translation with the current translation
$jlang =& JFactory::getLanguage();
$jlang->load('com_cmc', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_cmc', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_cmc', JPATH_ADMINISTRATOR, null, true);

// Live updater
require_once( JPATH_COMPONENT_ADMINISTRATOR .  'liveupdate/liveupdate.php');

// Conrol Center
require_once( JPATH_COMPONENT_ADMINISTRATOR .  'controlcenter/controlcenter.php');
require_once( JPATH_COMPONENT . '/controller.php' );

// Mailchimp PHP Class
require_once( JPATH_COMPONENT_ADMINISTRATOR .  'helper/MCAPI.class.php');


if(JRequest::getCmd('view','') == 'liveupdate') {
    JToolBarHelper::preferences( 'com_cmc' );
    LiveUpdate::handleRequest();
    return;
}

if(JRequest::getCmd('view','') == 'controlcenter') {
    JToolBarHelper::preferences( 'com_cmc' );
    CompojoomControlCenter::handleRequest();
    return;
}

if(JRequest::getCmd('view','') == 'information') {
    JToolBarHelper::preferences( 'com_cmc' );
    CompojoomControlCenter::handleRequest('information');
    return;
}

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR .  '/tables');

// Get the view and controller from the request, or set to eventlist if they weren't set
JRequest::setVar('controller', JRequest::getCmd('view','events')); // Black magic: Get controller based on the selected view

// Require specific controller if requested
if ($controller = JRequest::getCmd('controller')) {
    $path = JPATH_COMPONENT_ADMINISTRATOR .  'controllers' .  $controller . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

if ($controller == '') {
    require_once(JPATH_COMPONENT_ADMINISTRATOR .  'controllers/lists.php');
    $controller = 'lists';
}

// Create the controller
$classname = 'CmcController' . $controller;
$controller = new $classname( );
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();