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

defined('_JEXEC') or die();
$lang = JFactory::getLanguage();
$lang->load('com_hotspots.sys',JPATH_ADMINISTRATOR);
$path = JURI::root() . '/media/com_cmc/backend/images/';
require_once( JPATH_COMPONENT_ADMINISTRATOR . '/liveupdate/liveupdate.php');
?>


    <div class="icon-wrapper">
        <div class="icon" >
            <a href="<?php echo JRoute::_('index.php?option=com_cmc&view=lists'); ?>" >
                <div>
                    <img src="<?php echo $path; ?>icon-48-lists.png" alt="" />
                </div>
                <span><?php echo JText::_('COM_CMC_LISTS'); ?></span>
            </a>
        </div>
        <div class="icon">
            <a href="<?php echo JRoute::_('index.php?option=com_cmc&view=users'); ?>">
                <div>
                    <img src="<?php echo $path; ?>icon-48-users.png" alt="" />
                </div>
                <span><?php echo JText::_('COM_CMC_USERS'); ?></span>
            </a>
        </div>
        <?php echo LiveUpdate::getIcon(); ?>
    </div>
