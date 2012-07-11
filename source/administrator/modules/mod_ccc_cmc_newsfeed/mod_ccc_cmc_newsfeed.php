<?php
/**
 * Compojoom Control Center
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

// No direct access.
defined('_JEXEC') or die;

// Include the mod_popular functions only once.
require_once dirname(__FILE__).'/helper.php';

$cacheDir = JPATH_CACHE;
if (!is_writable($cacheDir))
{
    echo '<div>';
    echo JText::_('MOD_FEED_ERR_CACHE');
    echo '</div>';
    return;
}

$rssurl	= $params->get('feedurl', '');

//check if feed URL has been set
if (empty ($rssurl))
{
    echo '<div>';
    echo JText::_('MOD_FEED_ERR_NO_URL');
    echo '</div>';
    return;
}

// Render the module
require JModuleHelper::getLayoutPath('mod_ccc_cmc_newsfeed', $params->get('layout', 'default'));