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

$view	= JRequest::getCmd('view');

$subMenus = array(
    'controlcenter' => 'COM_CMC_CONTROLCENTER',
    'lists' => 'COM_CMC_LISTS',
    'users' => 'COM_CMC_USERS',
    //'statistics' => 'COM_CMC_STATISTICS',
    'liveupdate' => 'COM_CMC_LIVEUPDATE'
);

foreach ($subMenus as $key => $name) {
    $active = ($view == $key);
    JSubMenuHelper::addEntry( JText::_($name) , 'index.php?option=com_cmc&view=' . $key , $active );
}