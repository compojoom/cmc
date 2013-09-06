<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

JLoader::discover('cmcHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

jimport('joomla.application.component.controllerlegacy');
$controller = JControllerLegacy::getInstance('Cmc');
$controller->execute(JFactory::getApplication()->input->getCmd('task', ''));
$controller->redirect();


