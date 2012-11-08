<?php

defined('_JEXEC') or die;

// Module Helper for our Positions
jimport('joomla.application.module.helper');

$mailchimpModule = JModuleHelper::getModule('mod_ccc_cmc_mailchimp');
$iconsModule = JModuleHelper::getModule('mod_ccc_cmc_icons');
$newsfeedModule = JModuleHelper::getModule('mod_ccc_cmc_newsfeed');
$updateModule = JModuleHelper::getModule('mod_ccc_cmc_update');

$mailchimpOutput = JModuleHelper::renderModule($mailchimpModule);
$iconsOutput = JModuleHelper::renderModule($iconsModule);
$newsfeedOutput = JModuleHelper::renderModule($newsfeedModule);
$updateOutput = JModuleHelper::renderModule($updateModule);

JHTML::_('behavior.tooltip');
CmcHelperBasic::bootstrap();
JHTML::_('stylesheet', 'media/com_cmc/css/strapper.css');
?>

<div class="compojoom-bootstrap" xmlns="http://www.w3.org/1999/html">
    <div class="cpanel">
        <div class="row-fluid compojoom-margin">
            <div class="span6">
                <?php
                echo $iconsOutput;
                ?>
            </div>
            <div class="span4">
                <?php
                echo $mailchimpOutput;
                ?>
            </div>
        </div>


        <div class="row-fluid compojoom-margin">

            <div class="span10 form-horizontal">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#newsfeed" data-toggle="tab">
                            <?php
                            echo JText::_($newsfeedModule->title);
                            ?>
                        </a>
                    <li>
                        <a href="#update" data-toggle="tab">
                            <?php

                            echo JText::_($updateModule->title);
                            ?>
                        </a>
                    </li>

                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="newsfeed">
                        <?php echo $newsfeedOutput; ?>
                    </div>

                    <div class="tab-pane" id="update">
                        <?php echo $updateOutput; ?>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row-fluid compojoom-margin">

            <p>
                <strong>
                    CMC <?php echo CmcHelperBasic::getComponent('com_cmc')->manifest_cache->get('version'); ?></strong>
                <br/>
                <small>
                    Copyright 2008&ndash;2012 &copy;compojoom.com
                </small>

                <br/>

                <strong>
                    If you use CMC, please post a rating and a review at the
                    <a href="http://extensions.joomla.org/extensions/content-sharing/mailing-a-newsletter-bridges/21710"
                       target="_blank">Joomla! Extensions Directory</a>.
                </strong>
            </p>

            <p>
                <small>
                    CMC is Free software released under the
                    <a href="www.gnu.org/licenses/gpl.html">GNU General Public License,</a>
                    version 2 of the license or &ndash;at your option&ndash; any later version
                    published by the Free Software Foundation.
                </small>
            </p>
            <p>
        <span>
            <a href="https://mailchimp.com/?pid=compojoom&source=website" target="_blank">MailChimp</a>® is a registered trademark of The Rocket Science Group
        </span>
            </p>
            <p>
                You don't speak english? <a href="https://compojoom.com/downloads/languages-cool-geil?view=project&id=5" target="_blank">Go here and download a translation</a>.
            </p>
        </div>
    </div>
</div>