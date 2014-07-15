<?php
/**
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       09.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE . '/modules/mod_cmc/helper.php';

$user = JFactory::getUser();
$form = modCMCHelper::getForm($params);

$layout = 'default';

if (!$user->guest)
{
	if (modCMCHelper::hasSignedForNewsletter($params->get('listid')))
	{
		$layout = 'signed';
	}
}

require JModuleHelper::getLayoutPath('mod_cmc', $layout);
