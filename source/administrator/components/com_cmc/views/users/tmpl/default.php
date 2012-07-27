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
jimport('joomla.filter.output');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$filterStatus = $this->escape($this->state->get('filter_status'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_cmc&view=users'); ?>" method="post" name="adminForm">
    <div class="filter-search fltlft">
        <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
        <input type="text" name="filter_search" id="filter_search"
               value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
               title="<?php echo JText::_('COM_CMC_SEARCH_IN_EMAIL'); ?>"/>
        <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
        <button type="button"
                onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
        <?php
            echo JHTML::_('grid.state', $this->status, 'subscribed', 'unsubscribed');
        ?>

    </div>
    <div class="clr"></div>

    <table class="adminlist">
        <thead>
        <tr>
            <th width="5"><?php echo JText::_('JGRID_HEADING_ROW_NUMBER'); ?></th>
            <th width="5">
                <input type="checkbox" name="checkall-toggle" value=""
                       title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
            </th>
            <th width="7%"><?php echo JText::_('COM_CMC_GRAVATAR'); ?></th>
            <th class="title">
                <?php echo JHtml::_('grid.sort', 'JGLOBAL_EMAIL', 'u.email', $listDirn, $listOrder); ?>
            </th>
            <th width="10%"><?php echo JText::_('JGRID_HEADING_ID'); ?></th>
            <th width="10%"><?php echo JText::_('COM_CMC_LIST'); ?></th>
            <th width="20%"><?php echo JText::_('COM_CMC_TIMESTAMP'); ?></th>
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
                <?php echo JHTML::_('grid.id', $i, $item->id);; ?>
            </td>
            <td align="center">
                <img src="http://www.gravatar.com/avatar/<?php echo md5($item->email); ?>?s=20" alt="<?php echo $item->firstname . " " . $item->lastname; ?>" />
            </td>
            <td>
                <a href="<?php echo JRoute::_('index.php?option=com_cmc&task=user.edit&id=' . $item->id);; ?>">
                    <?php echo $item->email; ?>
                </a>
            </td>
            <td align="center">
                <?php echo $item->id; ?>
            </td>
            <td align="center">
                <?php echo CmcHelperBasic::getListName($item->list_id); ?>
            </td>
            <td align="center">
                <?php echo $item->timestamp; ?>
            </td>
            <td align="center">
                <?php echo $item->status; ?>
            </td>
        </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>"/>
    <input type="hidden" name="filter_status" value="<?php echo $filterStatus; ?>"/>


    <?php echo JHTML::_('form.token'); ?>
</form>
