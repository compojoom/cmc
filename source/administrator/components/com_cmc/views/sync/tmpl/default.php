<?php
/**
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       28.08.13
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.framework', 1);
jimport('joomla.filter.output');
JHTML::_('stylesheet', 'media/com_cmc/backend/css/cmc.css');
JHTML::_('script', 'media/com_cmc/backend/js/sync.js');
JHtml::_('script', 'system/progressbar.js', true, true);


$chimp = new CmcHelperChimp;
$lists = $chimp->lists();
?>
<script type="text/javascript">
	window.addEvent('domready', function() {
		new cmcSync();
	});
</script>

<div id="cmc-indexer-container">
	<h1 id="cmc-progress-header"><?php echo JText::_('COM_CMC_SYNCER_HEADER_INIT'); ?></h1>

	<p id="cmc-progress-message"><?php echo JText::_('COM_CMC_SYNCER_MESSAGE_INIT'); ?></p>

	<form id="cmc-progress-form"></form>

	<div id="cmc-progress-container"></div>

	<input id="cmc-indexer-token" type="hidden" name="<?php echo JFactory::getSession()->getFormToken(); ?>" value="1" />
</div>

<?php if($lists['total'] > 0) : ?>
	<br />
	<table class="table table-striped">
		<tr>
			<th>#</th>
			<th><?php echo JText::_('COM_CMC_LIST_ID'); ?></th>
			<th><?php echo JText::_('COM_CMC_LIST_NAME'); ?></th>
			<th><?php echo JText::_('COM_CMC_LIST_MEMBER_COUNT'); ?></th>
		</tr>
		<?php foreach($lists['data'] as $list) : ?>
			<tr>
				<td><input type="checkbox" name="<?php echo $list['id']; ?>" /></td>
				<td><?php echo $list['id']; ?></td>
				<td><?php echo $list['name']; ?></td>
				<td><?php echo $list['stats']['member_count']; ?></td>
			</tr>
		<?php endforeach; ?>
		<tr>
			<td colspan="3"></td>
			<td>
				<button class="btn" id="sync" class="disabled" disabled="disabled">
					<?php echo JText::_('COM_CMC_SYNCHRONIZE'); ?>
				</button>
			</td>
		</tr>
	</table>

<?php else : ?>
	<?php echo JText::_('COM_CMC_NO_LISTS_TO_SYNC'); ?>
<?php endif;?>
