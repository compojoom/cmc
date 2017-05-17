<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

?>

<div class="shops-to-sync" class="box-info full">
    <?php echo JText::_('COM_CMC_INITIAL_SHOP_SYNC'); ?>

    <div class="control-group">
        <select name="shop" id="shop">
            <option value="virtuemart">VirtueMart</option>
        </select>
    </div>

    <button id="btnAddShop" class="btn btn-primary"><?php echo JText::_('COM_CMC_START_INITIAL_SYNC'); ?></button>
</div>

<h3><?php echo JText::_('COM_CMC_SHOPS'); ?></h3>


<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th></th>
                <th><?php echo JText::_('COM_CMC_SHOP_NAME'); ?></th>
                <th><?php echo JText::_('COM_CMC_SHOP_TYPE'); ?></th>
                <th><?php echo JText::_('COM_CMC_SHOP_ID'); ?></th>
            </tr>
        </thead>
    </table>
</div>
