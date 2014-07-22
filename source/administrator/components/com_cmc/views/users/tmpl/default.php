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
if (JVERSION > 3)
{
	JHtml::_('formbehavior.chosen', 'select');
}
jimport('joomla.filter.output');
JHTML::_('stylesheet', 'media/com_cmc/backend/css/cmc.css');
JHTML::_('script', 'media/com_cmc/backend/js/users.js', true);

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$filterStatus = $this->escape($this->state->get('filter.status'));

?>
<script type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        if (pressbutton == 'users.addGroup') {
            new cmcUsers();
        } else {
            Joomla.submitform(pressbutton);
        }
    }
</script>
<?php
echo CompojoomHtmlCtemplate::getHead(CmcHelperBasic::getMenu(), 'users', 'COM_CMC_USERS', '');
?>
	<div id="groups" style="display: none;" class="box-info">
		<h2>
			<?php echo JText::_('COM_CMC_SELECT_LIST'); ?>
		</h2>
		<div class="additional-box">
			<button id="close" class="btn">Close</button>
		</div>

		<div class="alert alert-warning fltlft">
			<?php echo JText::_('COM_CMC_ADD_USERS_FROM_GROUP_INFO'); ?>
		</div>

		<form id="addGroup" name="addGroup" action="<?php echo JRoute::_('index.php?option=com_cmc&view=users'); ?>"
		      method="post">
			<?php echo $this->addToList; ?>
			<br/>
			<br/>
			<?php echo JText::_('COM_CMC_SELECT_JOOMLA_USERGROUPS'); ?> <br/>
			<?php echo JHtml::_('access.usergroups', 'usergroups', ''); ?>
			<input type="hidden" name="task" value="users.addGroup"/>

			<button class="btn btn-primary"><?php echo JText::_('COM_CMC_ADD_USERS_NOW'); ?></button>

			<?php echo JHTML::_('form.token'); ?>
		</form>
	</div>
<div class="box-info full">

    <form action="<?php echo JRoute::_('index.php?option=com_cmc&view=users'); ?>" method="post" name="adminForm"
          id="adminForm">
        <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search fltlft btn-group pull-left">
                <label class="filter-search-lbl element-invisible"
                       for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
                <input type="text" name="filter_search" id="filter_search"
                       value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                       title="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"
                       placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"/>

            </div>
            <div class="btn-group pull-left hidden-phone">
                <?php if (JVERSION > 2.5) : ?>
                <button class="btn" type="submit"><i class="icon-search"></i></button>
                <button class="btn" type="button"
                        onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i>
                </button>
                <?php else : ?>
                <button class="btn" type="submit"
                        style="margin:0"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                <button class="btn" type="button" style="margin:0"
                        onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>

                <?php endif; ?>
            </div>
            <div class="filter-select fltrt pull-right">
                <select name="filter_status" class="inputbox chzn-single" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('COM_CMC_STATUS');?></option>
                    <?php
                    $subs = $subu = $subup = $subc = "";

                    if ($filterStatus == "subscribed") {
                        $subs = ' selected="selected" ';
                    } else if ($filterStatus == "unsubscribed") {
                        $subu = ' selected="selected "';
                    } else if ($filterStatus == "updated") {
                        $subup = ' selected="selected "';
                    } else if ($filterStatus == "cleaned") {
                        $subc = ' selected="selected" ';
                    }
                    ?>
                    <option value="subscribed" <?php echo $subs; ?>><?php echo JText::_('COM_CMC_SUBSCRIBED');?></option>
                    <option value="unsubscribed" <?php echo $subu; ?>><?php echo JText::_('COM_CMC_UNSUBSCRIBED');?></option>
                    <option value="updated" <?php echo $subup; ?>><?php echo JText::_('COM_CMC_CLEANED');?></option>
                    <option value="cleaned" <?php echo $subc; ?>><?php echo JText::_('COM_CMC_UPDATED');?></option>
                </select>

                <?php echo $this->lists; ?>
            </div>
        </div>
        <div class="clr"></div>

	    <div class="table-responsive">
		    <table class="table table-hover table-striped">
	            <thead>
	            <tr>
	                <th width="5">#</th>
	                <th width="5">
	                    <input type="checkbox" name="checkall-toggle" value=""
	                           title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
	                </th>
	                <th width="7%"><?php echo JText::_('COM_CMC_GRAVATAR'); ?></th>
	                <th class="title">
	                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_EMAIL', 'u.email', $listDirn, $listOrder); ?>
	                </th>
	                <th width="10%">
	                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'u.id', $listDirn, $listOrder); ?>
	                </th>
	                <th width="10%"><?php echo JText::_('COM_CMC_LIST'); ?></th>
	                <th width="20%">
	                    <?php echo JHtml::_('grid.sort', 'COM_CMC_TIMESTAMP', 'u.timestamp', $listDirn, $listOrder); ?>
	                </th>
	                <th width="15%"><?php echo JText::_('COM_CMC_STATUS'); ?></th>
	            </tr>
	            </thead>
	            <tfoot>
	            <tr>
	                <td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
	            </tr>
	            </tfoot>
	            <tbody>
	            <?php foreach ($this->items as $i => $item) : ?>
	            <tr class="<?php echo "row" . $i % 2; ?>">
	                <td><?php echo $this->pagination->getRowOffset($i); ?></td>
	                <td>
	                    <?php echo JHTML::_('grid.id', $i, $item->id); ?>
	                </td>
	                <td align="center">
	                    <img src="http://www.gravatar.com/avatar/<?php echo md5($item->email); ?>?s=20"
	                         alt="<?php echo $item->firstname . " " . $item->lastname; ?>"/>
	                </td>
	                <td>
	                    <a href="<?php echo JRoute::_('index.php?option=com_cmc&task=user.edit&id=' . $item->id);; ?>">
	                        <?php echo $item->email; ?>
	                    </a>
	                </td>
	                <td>
	                    <?php echo $item->id; ?>
	                </td>
	                <td>
	                    <?php echo $this->listNames[$item->list_id]; ?>
	                </td>
	                <td>
	                    <?php echo $item->timestamp; ?>
	                </td>
	                <td>
	                    <?php echo $item->status; ?>
	                </td>
	            </tr>
	                <?php endforeach; ?>
	            </tbody>
	        </table>
	    </div>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" name="filter_order" value="<?php echo $listOrder ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>"/>

        <?php echo JHTML::_('form.token'); ?>
    </form>
</div>
<?php
// Show Footer
echo CompojoomHtmlCTemplate::getFooter(CmcHelperBasic::footer());
