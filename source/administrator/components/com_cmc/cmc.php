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

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_cmc'))
{
	JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');

	return false;
}

// Load Compojoom library
require_once JPATH_LIBRARIES . '/compojoom/include.php';

// In J3.0 the toolbar is not loaded automatically, so let us load it ourselves.
require_once 'toolbar.cmc.php';

$input = JFactory::getApplication()->input;
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

require_once JPATH_COMPONENT . '/controller.php';

JLoader::register('MCAPI', JPATH_COMPONENT_ADMINISTRATOR . '/libraries/mailchimp/MCAPI.class.php');
JLoader::discover('cmcHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/');

// Thank you for this black magic Nickolas :)
// Magic: merge the default translation with the current translation
$jlang = JFactory::getLanguage();
$jlang->load('com_cmc', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_cmc', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_cmc', JPATH_ADMINISTRATOR, null, true);
$jlang->load('com_cmc.sys', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_cmc.sys', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_cmc.sys', JPATH_ADMINISTRATOR, null, true);

/*
 * this part is a little crazy because of the redirects...
 * Show a warning only if we are in the cpanel view
 * Redirect only if we are not in the cpanel view
 */
if (!cmcHelperBasic::checkRequiredSettings())
{
	if ($input->getCmd('view', '') == 'cpanel')
	{
		JFactory::getApplication()->enqueueMessage(JText::_('COM_CMC_YOU_NEED_TO_PROVIDE_API_KEYS'), 'error');
	}

	if ($input->getCmd('view', '') != 'cpanel')
	{
		$appl = JFactory::getApplication();
		$appl->redirect('index.php?option=com_cmc&view=cpanel');
	}
}

$controller = JControllerLegacy::getInstance('Cmc');
$controller->execute($input->getCmd('task', ''));
$controller->redirect();
