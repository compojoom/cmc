<?php
/**
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       28.08.13
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

CompojoomHtmlBehavior::bootstrap31(true, true, true, false);
jimport('joomla.filter.output');
JHTML::_('stylesheet', 'media/com_cmc/backend/css/cmc.css');
JHTML::_('script', 'media/com_cmc/backend/js/sync.js');

// Load bootstrap


$chimp = new CmcHelperChimp;
$lists = $chimp->lists();
?>

<script type="text/javascript">
	jQuery(document).ready(function () {
		new cmcSync();
	});
</script>
<div class="compojoom-bootstrap" style="clear: both">
	<div class="box-info">

		<h2 id="cmc-progress-header"><?php echo JText::_('COM_CMC_SYNCER_HEADER_INIT'); ?></h2>

		<div class="additional-box">
			<button class="btn btn-primary" id="sync" class="disabled" disabled="disabled">
				<?php echo JText::_('COM_CMC_SYNCHRONIZE'); ?>
			</button>
			<button id="close" class="btn">Close</button>
		</div>
		<div id="cmc-indexer-container">

			<p id="cmc-progress-message"><?php echo JText::_('COM_CMC_SYNCER_MESSAGE_INIT'); ?></p>

			<form id="cmc-progress-form"></form>

			<div class="progress progress-striped active">
				<div id="cmc-progress-container" class="progress-bar progress-bar-success"></div>
			</div>

			<input id="cmc-indexer-token" type="hidden" name="<?php echo JFactory::getSession()->getFormToken(); ?>" value="1"/>
		</div>

		<?php if ($lists['total'] > 0) : ?>
			<br/>
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th><?php echo JText::_('COM_CMC_LIST_ID'); ?></th>
						<th><?php echo JText::_('COM_CMC_LIST_NAME'); ?></th>
						<th><?php echo JText::_('COM_CMC_LIST_MEMBER_COUNT'); ?></th>
					</tr>
				</thead>
				<?php foreach ($lists['data'] as $list) : ?>
					<tr>
						<td><input type="checkbox" name="<?php echo $list['id']; ?>"/></td>
						<td><?php echo $list['id']; ?></td>
						<td><?php echo $list['name']; ?></td>
						<td><?php echo $list['stats']['member_count']; ?></td>
					</tr>
				<?php endforeach; ?>
			</table>

		<?php else : ?>
			<?php echo JText::_('COM_CMC_NO_LISTS_TO_SYNC'); ?>
		<?php endif; ?>
	</div>
</div>
