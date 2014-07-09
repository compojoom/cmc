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

$language = JFactory::getLanguage();
$language->load('com_cmc.sys', JPATH_ADMINISTRATOR, null, true);

$view = JFactory::getApplication()->input->getCmd('view');

$subMenus = array(
	'cpanel' => 'COM_CMC_CPANEL',
	'lists' => 'COM_CMC_LISTS',
	'users' => 'COM_CMC_USERS'
);

foreach ($subMenus as $key => $name)
{
	$active = ($view == $key);
	JHtmlSidebar::addEntry(JText::_($name), 'index.php?option=com_cmc&view=' . $key, $active);
}
