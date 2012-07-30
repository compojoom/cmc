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

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR .  '/tables');

require_once( JPATH_COMPONENT . '/controller.php' );
JLoader::register('MCAPI', JPATH_COMPONENT_ADMINISTRATOR . '/libraries/mailchimp/MCAPI.class.php');

JLoader::discover('cmcHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/');

// thank you for this black magic Nickolas :)
// Magic: merge the default translation with the current translation
$jlang =& JFactory::getLanguage();
$jlang->load('com_cmc', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_cmc', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_cmc', JPATH_ADMINISTRATOR, null, true);
$jlang->load('com_cmc.sys', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_cmc.sys', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_cmc.sys', JPATH_ADMINISTRATOR, null, true);

// Live updater
if(JRequest::getCmd('view','') == 'liveupdate') {
    JToolBarHelper::preferences( 'com_cmc' );
    require_once( JPATH_COMPONENT_ADMINISTRATOR . '/liveupdate/liveupdate.php');
    LiveUpdate::handleRequest();
    return;
}

/*
 * this part is a little crazy because of the redirects...
 * Show a warning only if we are in the controlcenter view
 * Redirect only if we are not in the controlcenter view
 */
if(!cmcHelperBasic::checkRequiredSettings()) {
    if(JRequest::getCmd('view','') == 'controlcenter') {
        JError::raiseWarning('NO_KEY', JText::_('COM_CMC_YOU_NEED_TO_PROVIDE_API_KEYS'));
    }
    if(JRequest::getCmd('view','') != 'controlcenter') {
        $appl = JFactory::getApplication();
        $appl->redirect('index.php?option=com_cmc&view=controlcenter');
    }
}

// Conrol Center
$view = JRequest::getCmd('view','');
if(( $view == '' && JRequest::getCmd('task') == '') || $view == 'controlcenter') {
    JToolBarHelper::preferences( 'com_cmc' );
    require_once( JPATH_COMPONENT_ADMINISTRATOR . '/controlcenter/controlcenter.php');
    CompojoomControlCenter::handleRequest();
    return;
}

$controller = JController::getInstance('Cmc');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();