<?php
/**
 * CMC - Adminstrator
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - http://compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0 stable
 **/

defined('_JEXEC') or die('Restricted access');

$language = JFactory::getLanguage();
$language->load('com_cmc.sys', JPATH_ADMINISTRATOR, null, true);


$view = JRequest::getCmd('task');

$active2 = ($view == 'controlcenter');
JSubMenuHelper::addEntry(JText::_('COM_CMC_CONTROLCENTER'), 'index.php?option=com_cmc&view=controlcenter', $active2);

$subMenus = array(
    'lists' => 'COM_CMC_LISTS',
    'users' => 'COM_CMC_USERS',
    'settings' => 'COM_CMC_CONFIGURATION',
    'statistics' => 'COM_CMC_STATISTICS',
);

foreach ($subMenus as $key => $name) {
    $active = ($view == $key);
    if ($key == 'settings') {
        JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_cmc&view=settings', $active);
    } else if($key == 'lists') {
        JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_cmc&view=lists', $active);
    } else if($key == 'users') {
        JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_cmc&view=users', $active);
    } else if($key == 'statistics') {
        JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_cmc&view=statistics', $active);
    } else {
        JSubMenuHelper::addEntry(JText::_($name), 'index.php?option=com_cmc&view=' . $key, $active);
    }
}
$active = ($view == 'liveupdate');
JSubMenuHelper::addEntry(JText::_('COM_CMC_LIVEUPDATE'), 'index.php?option=com_cmc&view=liveupdate', $active);
