<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

JLoader::discover('cmcHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

jimport('joomla.application.component.controllerlegacy');
$controller = JControllerLegacy::getInstance('Cmc');
$controller->execute(JFactory::getApplication()->input->getCmd('task', ''));
$controller->redirect();


