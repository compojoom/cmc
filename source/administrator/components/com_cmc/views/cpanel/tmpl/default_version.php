<?php
/**
 * @package    CMC
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @copyright  Copyright (C) 2008 - 2014 Compojoom.com. All rights reserved.
 * @license    GNU GPL version 3 or later <http://www.gnu.org/licenses/gpl.html>
 */

defined('_JEXEC') or die;

// Changelog
JHtml::_('behavior.framework');
JHtml::_('behavior.modal');

$script = <<<JS
window.addEvent( 'domready' ,  function() {
    $('hs-btn-changelog').addEvent('click', showChangelog);
});

function showChangelog()
{
	var txChangelogElement = $('hs-changelog').clone();
	
    SqueezeBox.fromElement(
        txChangelogElement, {
            handler: 'adopt',
            size: {
                x: 550,
                y: 500
            }
        }
    );
}
JS;

$document = JFactory::getDocument();
$document->addScriptDeclaration($script, 'text/javascript');

?>
<table width="100%" class="table table-version table-bordered table-striped-offset1 table-condensed">
	<tr>
		<th colspan="2">
			<div class="cmc-logo">
				<a href="https://compojoom.com/joomla-extensions/cmc-mailchimp-for-joomla" target="_blank">
					<img src="<?php echo JUri::root(); ?>media/com_cmc/backend/images/cmc-logo.png" align="middle" alt="CMC logo"/>
				</a>
			</div>
		</th>
	</tr>
	<tr>
		<td width="120"><?php echo JText::_('LIB_COMPOJOOM_INSTALLED_VERSION'); ?></td>
		<td>
			<span id="hs-label-version" class="label"><?php echo $this->currentVersion ?></span>&nbsp;
			<a id="hs-btn-changelog" class="btn btn-default btn-sm"
			   title="<?php echo JText::_('LIB_COMPOJOOM_BTN_CHANGELOG'); ?>">
				<i class="fa fa-list"></i>
			</a>

			<a id="hs-btn-reloadupdate" href="index.php?option=com_cmc&task=update.force&<?php echo JFactory::getSession()->getFormToken(); ?>=1"
			   class="btn btn-default btn-sm" title="<?php echo JText::_('LIB_COMPOJOOM_BTN_RELOAD_UPDATE'); ?>">
				<i class="fa fa-repeat"></i>
			</a>

			<!-- CHANGELOG :: BEGIN -->
			<div style="display:none;">
				<div id="hs-changelog" class="compojoom-bootstrap-modal">
					<div class="changelog">
						<?php echo CompojoomChangelogColoriser::colorise(JPATH_COMPONENT_ADMINISTRATOR . '/CHANGELOG.php'); ?>
					</div>
				</div>
			</div>
			<!-- CHANGELOG :: END -->
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('LIB_COMPOJOOM_RELEASED'); ?></td>
		<td><?php echo CMC_DATE; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('LIB_COMPOJOOM_COPYRIGHT'); ?></td>
		<td>2008 - <?php echo date('Y'); ?> <a href="https://compojoom.com" target="_blank">Compojoom</a></td>
	</tr>
	<tr>
		<td><?php echo JText::_('LIB_COMPOJOOM_LICENSE'); ?></td>
		<td><a href="http://www.gnu.org/licenses/gpl.html" target="_blank">GNU GPLv3 or later</a> Paid</td>
	</tr>
</table>
