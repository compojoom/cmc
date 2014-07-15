<?php
/**
 * @author     Daniel Dimitrov - compojoom.com
 * @date       : 09.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

$moduleId = $module->id;

JHtml::_('behavior.framework', true);
JHtml::_('behavior.formvalidation');
JHtml::script(JURI::root() . '/media/mod_cmc/js/cmc.js');
JHtml::_('stylesheet', JURI::root() . 'media/mod_cmc/css/cmc.css');

$document = JFactory::getDocument();
$script = 'window.addEvent("domready", function() {
	Locale.use("' . JFactory::getLanguage()->getTag() . '");
    var options = {
        language : {
            "updated" : ' . json_encode(JText::_($params->get('updateMsg'))) . ',
             "saved" : ' . json_encode(JText::_($params->get('thankyou'))) . '
        },
        spinner : "spinner-' . $moduleId . '"
    }
    new cmc("#cmc-signup-form-' . $moduleId . '", options);
});';

$document->addScriptDeclaration($script);
JText::script($params->get('thankyou'));
JText::script($params->get('updateMsg'));
?>

<div id="cmc-signup-<?php echo $moduleId; ?>"
     class="cmc-signup <?php echo $params->get('moduleclass_sfx', ''); ?>">
	<div class="cmc-updated" style="display:none">
		<?php echo JText::_($params->get('updateMsg')); ?>
	</div>
	<div class="cmc-saved" style="display:none">
		<?php echo JText::_($params->get('thankyou')); ?>
	</div>
	<form action="<?php echo JRoute::_('index.php?option=com_cmc&format=raw&task=subscription.save'); ?>" method="post"
	      id="cmc-signup-form-<?php echo $moduleId; ?>"
	      class="form-validate"
	      name="cmc<?php echo $moduleId; ?>">


		<div class="row-fluid">
			<?php $fieldsets = $form->getFieldsets('cmc'); ?>

			<?php foreach ($fieldsets as $key => $value): ?>
				<div class="span12">
					<?php $fields = $form->getFieldset($key); ?>

					<?php foreach ($fields as $field) : ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label; ?>
							</div>
							<div class="controls">
								<?php echo $field->input; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="row-fluid">
			<?php $fieldsets = $form->getFieldsets('cmc_groups'); ?>
			<?php foreach ($fieldsets as $key => $value) : ?>
				<div class="span12">

					<?php $fields = $form->getFieldset($key); ?>

					<?php foreach ($fields as $field) : ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label; ?>
							</div>
							<div class="controls">
								<?php echo $field->input; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="row-fluid">
			<?php $fieldsets = $form->getFieldsets('cmc_interests'); ?>
			<?php foreach ($fieldsets as $key => $value) : ?>
				<div class="span12">

					<?php $fields = $form->getFieldset($key); ?>

					<?php foreach ($fields as $field) : ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label; ?>
							</div>
							<div class="controls">
								<?php echo $field->input; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>



			<?php echo JHTML::_('form.token'); ?>
			<?php if ($params->get('outro-text-1')) : ?>
				<div id="outro1_<?php echo $moduleId; ?>" class="outro1">
					<p class="outro"><?php echo JText::_($params->get('outro-text-1')); ?></p>
				</div>
			<?php endif; ?>

			<button class="btn btn-primary">
				<?php echo JText::_('MOD_CMC_SUBSCRIBE'); ?>
				<img width="16" height="16" class="cmc-spinner" style="display: none;" src="<?php echo JURI::root(); ?>media/mod_cmc/images/loading-bubbles.svg">
			</button>

			<?php if ($params->get('outro-text-2')) : ?>
				<div id="outro2_<?php echo $moduleId; ?>" class="outro2">
					<p class="outro"><?php echo JText::_($params->get('outro-text-2')); ?></p>
				</div>
			<?php endif; ?>
	</form>
</div>
