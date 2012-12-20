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

JLoader::discover('cmcHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR .  '/tables');

jimport('joomla.application.component.controllerlegacy');
$controller = JControllerLegacy::getInstance('Cmc');
$controller->execute(JFactory::getApplication()->input->getCmd('task', ''));
$controller->redirect();


