<?php
/**
 * Compojoom ControlCenter
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

defined('_JEXEC') or die();

// Loading css and js
JHTML::_('behavior.tooltip');
JHTML::_('stylesheet', 'ccc.css', 'media/compojoomcc/css/');
JHTML::_('script', 'ccc.js', 'media/compojoomcc/js/');


$modules = JModuleHelper::getModules('ccc_'. $this->config->extensionPosition . '_information');

?>

<div id="ccc_information">
    <div id="ccc_information_inner">
        <h2><?php echo JText::_('COMPOJOOM_CONTROLCENTER_VERSION'); ?></h2>
        <p>
            <?php echo $this->config->version; ?>
        </p>
        <h2><?php echo JText::_('COMPOJOOM_CONTROLCENTER_COPYRIGHT'); ?></h2>
        <p>
            <?php echo $this->config->copyright; ?>
        </p>
        <h2><?php echo JText::_('COMPOJOOM_CONTROLCENTER_LICENSE'); ?></h2>
        <p>
            <?php echo $this->config->license; ?>
        </p>
        <h2><?php echo JText::_('COMPOJOOM_CONTROLCENTER_TRANLATION'); ?></h2>
        <p>
            <?php echo $this->config->translation; ?>
        </p>
        <!-- Description -->
        <p>
            <?php echo JText::_($this->config->description); ?>
        </p>
        <h2>Thank you</h2>
        <p>
            This software would not have been possible without the help of those listed here.
            THANK YOU for your continuous help, support and inspiration!
        </p>
        <ul>
            <?php echo JText::_($this->config->thankyou); ?>
        </ul>

    </div>
    <div id="ccc_information_modules">
        <?php
        foreach ($modules as $modules) {
            $output = JModuleHelper::renderModule($module);
            echo $output;
        }
        ?>
    </div>
</div>