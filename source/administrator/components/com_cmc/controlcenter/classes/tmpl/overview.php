<?php
/**
 * ControlCenter
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

defined('_JEXEC') or die;

// Module Helper for our Positions
jimport( 'joomla.application.module.helper' );

$modules_left = JModuleHelper::getModules('ccc_'. $this->config->extensionPosition . '_left');
$modules_slider = JModuleHelper::getModules('ccc_'. $this->config->extensionPosition . '_slider');
$modules_promotion = JModuleHelper::getModules('ccc_'. $this->config->extensionPosition . '_promotion');

JHTML::_('behavior.tooltip');
JHTML::_('stylesheet', 'ccc.css', 'media/com_cmc/ccc/css/');
JHTML::_('script', 'ccc.js', 'media/com_cmc/ccc/js/');

JHTML::_('stylesheet', 'cmc.css', 'media/com_cmc/backend/css/');

?>

<div id="ccc_left">
    <div id="ccc_left_inner">
        <?php
            foreach ($modules_left as $module) {
                $output = JModuleHelper::renderModule($module);
                echo $output;
            }
        ?>
    </div>
    <div id="ccc_promotion">
        <?php
        foreach ($modules_promotion as $module) {
            $output = JModuleHelper::renderModule($module);
            echo $output;
        }
        ?>
    </div>
</div>
<div id="ccc_right">
    <div id="ccc_right_inner">
        <?php
            echo JHtml::_('sliders.start', 'panel-sliders', array('useCookie'=>'1'));

            foreach ($modules_slider as $module) {
                $output = JModuleHelper::renderModule($module);
                $params = new JRegistry;
                $params->loadString($module->params);
                if ($params->get('automatic_title', '0')=='0') {
                    echo JHtml::_('sliders.panel', JText::_($module->title), 'cpanel-panel-'.$module->name);
                }
                elseif (method_exists('mod'.$module->name.'Helper', 'getTitle')) {
                    echo JHtml::_('sliders.panel', call_user_func_array(array('mod'.$module->name.'Helper', 'getTitle'),
                        array($params)), 'cpanel-panel-'.$module->name);
                }
                else {
                    echo JHtml::_('sliders.panel', JText::_('MOD_'.$module->name.'_TITLE'), 'cpanel-panel-'.$module->name);
                }
                echo $output;
            }

            echo JHtml::_('sliders.end');
        ?>
        <div id="ccc_right_footer">

        </div>
    </div>
</div>
<div class="clr"></div>
<p style="font-size: small">
    <strong>
        CMC <?php echo CmcHelperBasic::getComponent('com_cmc')->manifest_cache->get('version'); ?></strong>
    <br />
	<span style="font-size: x-small">
		Copyright 2008&ndash;2012 &copy;compojoom.com
	</span>
    <br />

    <strong>
        If you use CMC, please post a rating and a review at the
        <a href="http://extensions.joomla.org/extensions/content-sharing/mailing-a-newsletter-bridges/21710" target="_blank">Joomla! Extensions Directory</a>.
    </strong>
    <br />

	<span style="font-size: x-small">
		CMC is Free software released under the
		<a href="www.gnu.org/licenses/gpl.html">GNU General Public License,</a>
		version 2 of the license or &ndash;at your option&ndash; any later version
		published by the Free Software Foundation.
	</span>
    <br />
    <span>
        <a href="https://mailchimp.com/?pid=compojoom&source=website" target="_blank">MailChimp</a>® is a registered trademark of The Rocket Science Group
    </span>
</p>