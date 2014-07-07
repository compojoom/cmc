<?php
/**
 * @author     Daniel Dimitrov - compojoom.com
 * @date       : 09.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
JLoader::discover('cmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

$renderer = CmcHelperXmlbuilder::getInstance($params);

// Render Content
$html = $renderer->build();
$form = JForm::getInstance('mod_cmc', $html, array('control' => 'jform'));
$moduleId = $module->id;

JHtml::_('behavior.framework', true);
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
    new cmc("cmc-signup-form-' . $moduleId . '", options);
});';

$document->addScriptDeclaration($script);
JText::script($params->get('thankyou'));
JText::script($params->get('updateMsg'));
?>

<div id="cmc-signup-<?php echo $moduleId; ?>"
     class="cmc-signup <?php echo $params->get('moduleclass_sfx', ''); ?>">
	<form action="<?php echo JRoute::_('index.php?option=com_cmc&format=raw&task=subscription.save'); ?>" method="post"
	      id="cmc-signup-form-<?php echo $moduleId; ?>"
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

				<input type="submit" class="button btn btn-primary" value="<?php echo JText::_('MOD_CMC_SUBSCRIBE'); ?>"
				       id="cmc-signup-submit-<?php echo $moduleId; ?>"/>

			<?php if ($params->get('outro-text-2')) : ?>
				<div id="outro2_<?php echo $moduleId; ?>" class="outro2">
					<p class="outro"><?php echo JText::_($params->get('outro-text-2')); ?></p>
				</div>
			<?php endif; ?>

			<div id="spinner-<?php echo $moduleId; ?>" style="text-align:center;display:none;"><img
							src="<?php echo JURI::root(); ?>media/mod_cmc/images/ajax-loader.gif" alt="Please wait"/>
			</div>
	</form>
</div>
