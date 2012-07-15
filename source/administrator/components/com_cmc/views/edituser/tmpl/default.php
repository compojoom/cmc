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
?>
<div id="cmc" class="cmc">
    <form action="index.php" method="post" name="adminForm" id="adminForm" class="form" enctype="multipart/form-data">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_CMC_EDIT_USER'); ?></legend>
            <table>
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
                        <?php echo CmcHelper::array_implode(" = ", ", ", json_decode($this->user->merges, true)); ?>
                    </td>
                </tr>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_CMC_CLIENTS'); ?>:
                    </td>
                    <td>
                        <?php echo CmcHelper::array_implode(" = ", ", ", json_decode($this->user->clients, true)); ?>
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
        <input type="hidden" name="id" value="<?php echo $this->user->id; ?>" />
        <input type="hidden" name="mail_old" value="<?php echo $this->user->email; ?>" />
        <input type="hidden" name="option" value="COM_CMC" />
        <input type="hidden" name="controller" value="users" />
        <input type="hidden" name="view" value="edituser" />
        <input type="hidden" name="model" value="edituser" />
        <input type="hidden" name="task" value="edituser" />
    </form>
</div>