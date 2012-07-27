<?php
/**
 * Tiles
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

defined('_JEXEC') or die('Restricted access');

$editor = JFactory::getEditor();

JHTML::_('behavior.mootools');
JHTML::_('behavior.tooltip');
JHTML::_('stylesheet', 'cmc.css', 'media/com_cmc/backend/css/');

?>

<div id="cmc" class="cmc">
    <form action="<?php echo JRoute::_('index.php?option=com_cmc&view=user&layout=edit&id='.(int)$this->user->id); ?>" method="post" name="adminForm" id="adminForm" class="form" enctype="multipart/form-data">
        <fieldset class="adminform">
            <div id="cmc_gravatar">
                <img src="http://www.gravatar.com/avatar/<?php echo md5($this->user->email); ?>?s=140" alt="<?php echo $this->user->firstname . " " . $this->user->lastname; ?>" />
            </div>
            <legend><?php echo JText::_('COM_CMC_EDIT_USER'); ?></legend>
            <table width="80%">
                <!--
                <tr>
                    <td width="200" align="left" class="key">
                        <?php // echo JText::_('COM_CMC_ID'); ?>:
                    </td>
                    <td>
                        <?php //echo $this->user->id; ?> / Mailchimp Id: <?php //echo $this->user->mc_id; ?>
                    </td>
                </tr>
                -->
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_FIRSTNAME'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="firstname" id="firstname" size="50" maxlength="250" value="<?php echo $this->user->firstname; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_LASTNAME'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="lastname" id="lastname" size="50" maxlength="250" value="<?php echo $this->user->lastname; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_EMAIL'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="email" id="email" size="50" maxlength="250" value="<?php echo $this->user->email; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_LIST'); ?>:
                    </td>
                    <td>
                        <?php echo $this->list_select; ?>
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_STATUS'); ?>:
                    </td>
                    <td>
                        <?php echo $this->status_select; ?>
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_EMAIL_TYPE'); ?>:
                    </td>
                    <td>
                        <?php echo $this->email_type_select; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>

                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_MAILCHIMP_ID'); ?>:
                    </td>
                    <td>
                        <?php echo $this->user->mc_id; ?>
                    </td>
                </tr>

                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_WEB_ID'); ?>:
                    </td>
                    <td>
                        <?php echo $this->user->web_id; ?>
                    </td>
                </tr>

                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_CUSTOM_FIELDS'); ?>:
                    </td>
                    <td>
                        <?php echo CmcHelperBasic::array_implode(" = ", ", ", json_decode($this->user->merges, true)); ?>
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_CLIENTS'); ?>:
                    </td>
                    <td>
                        <?php echo CmcHelperBasic::array_implode(" = ", ", ", json_decode($this->user->clients, true)); ?>
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_LANGUAGE'); ?>:
                    </td>
                    <td>
                        <?php echo $this->user->language; ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>

                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_MEMBER_SINCE'); ?>:
                    </td>
                    <td>
                        <?php echo $this->user->timestamp; ?>
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_LAST_CHANGE'); ?>:
                    </td>
                    <td>
                        <?php echo $this->user->info_changed; ?>
                    </td>
                </tr>

            </table>


        </fieldset>


        <input type="hidden" name="task" value="" />
        <?php echo JHTML::_('form.token'); ?>
    </form>
</div>