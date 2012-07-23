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

JLoader::register('CmcHelperSettings', JPATH_COMPONENT_ADMINISTRATOR . '/helper/settings.php');
JLoader::register('CmcHelperSynchronize', JPATH_COMPONENT_ADMINISTRATOR . '/helper/synchronizehelper.php');
JLoader::register('CmcHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helper/basichelper.php');
JLoader::register('CmcHelperChimp', JPATH_COMPONENT_ADMINISTRATOR . '/helper/chimp.php');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR .  '/tables');

jimport('joomla.application.component.controller');
$controller = JController::getInstance('Cmc');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();


