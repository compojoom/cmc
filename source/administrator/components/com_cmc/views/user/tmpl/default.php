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

JHTML::_('behavior.tooltip');

JHTML::_('stylesheet', 'media/com_cmc/backend/css/cmc.css');
?>
<?php
echo CompojoomHtmlCtemplate::getHead(CmcHelperBasic::getMenu(), 'users', '', '');
?>
<div class="box-info full">
	<h2><?php echo JText::_('COM_CMC_EDIT_USER'); ?></h2>
    <div id="cmc" class="cmc">
        <form action="<?php echo JRoute::_('index.php?option=com_cmc&view=user&layout=edit&id=' . (int)$this->user->id); ?>"
              method="post" name="adminForm" id="adminForm" class="form" enctype="multipart/form-data">
            <fieldset class="adminform">
                <div id="cmc_gravatar">
                    <img src="http://www.gravatar.com/avatar/<?php echo md5($this->user->email); ?>?s=140"
                         alt="<?php echo $this->user->firstname . " " . $this->user->lastname; ?>"/>
                </div>

                <table width="80%">
                    <tr>
                        <td width="200" align="left" class="key">
	                        <?php echo $this->form->getLabel('firstname'); ?>
                        </td>
                        <td>
	                        <?php echo $this->form->getInput('firstname'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="200" align="left" class="key">
	                        <?php echo $this->form->getLabel('lastname'); ?>
                        </td>
                        <td>
	                        <?php echo $this->form->getInput('lastname'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="200" align="left" class="key">
	                        <?php echo $this->form->getLabel('email'); ?>
                        </td>
                        <td>
	                        <?php echo $this->form->getInput('email'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="200" align="left" class="key">
	                        <?php echo $this->form->getLabel('list_id'); ?>
                        </td>
                        <td>
                            <?php echo $this->form->getInput('list_id'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="200" align="left" class="key">
	                        <?php echo $this->form->getLabel('status'); ?>
                        </td>
                        <td>
	                        <?php echo $this->form->getInput('status'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="200" align="left" class="key">

	                        <?php echo $this->form->getLabel('email_type'); ?>
                        </td>
                        <td>

	                        <?php echo $this->form->getInput('email_type'); ?>
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


            <input type="hidden" name="task" value=""/>
            <?php echo JHTML::_('form.token'); ?>
        </form>
    </div>

</div>
<?php
// Show Footer
echo CompojoomHtmlCTemplate::getFooter(CmcHelperBasic::footer());
