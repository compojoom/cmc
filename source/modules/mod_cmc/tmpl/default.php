<?php
/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 09.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ROOT . '/modules/mod_cmc/library/form/form.php');
$moduleId = $module->id;
$interests = $params->get('interests');
$fields = $params->get('fields');

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


$form = new cmcForm($params);

?>
<div id="cmc-signup-<?php echo $moduleId; ?>" class="cmc-signup <?php echo $params->get('moduleclass_sfx', ''); ?>">
	<form action="<?php echo JRoute::_('index.php?option=com_cmc&format=raw&task=subscription.save'); ?>" method="post"
	      id="cmc-signup-form-<?php echo $moduleId; ?>" name="cmc<?php echo $moduleId; ?>">
		<div id="intro<?php echo $moduleId; ?>">
			<?php if ($params->get('intro-text')) : ?>
				<p class="intro"><?php echo JText::_($params->get('intro-text')); ?></p>
			<?php endif; ?>
		</div>
		<?php
		if (is_array($fields)) {
			foreach ($fields as $f) {
				$field = explode(';', $f);
				echo '<div>';
				echo $form->$field[1]($field);
				echo '</div>';
			}
		}

		if (is_array($interests)) {
			foreach ($interests as $i) {

				$interest = explode(';', $i);
				$groups = explode('####', $interest[3]);

				echo '<div class="signup-title">' . JText::_($interest[2]) . '</div>';
				switch ($interest[1]) {
					case 'checkboxes':
						foreach ($groups as $g) {
							$o = explode('##', $g);
							echo '<label for="' . $interest[0] . '_' . $o[0] . '" class="checkbox"><input type="checkbox" name="jform[interests][' . $interest[0] . '][]" id="' . $interest[0] . '_' . str_replace(' ', '_', $o[0]) . '" class="submitMerge inputbox" value="' . $o[0] . '" />' . JText::_($o[1]) . '</label>';
						}
						break;
					case 'radio':
						foreach ($groups as $g) {
							$o = explode('##', $g);
							echo '<label for="' . $interest[0] . '_' . $o[0] . '" class="radio"><input type="radio" name="jform[interests][' . $interest[0] . ']" id="' . $interest[0] . '_' . str_replace(' ', '_', $o[0]) . '" class="submitMerge inputbox" value="' . $o[0] . '" />' . JText::_($o[1]) . '</label>';
						}
						break;
					case 'dropdown':
						echo '<select name="jform[interests][' . $interest[0] . ']" id="' . $interest[0] . '" class="submitMerge inputbox">';
						echo '<option value=""></option>';
						foreach ($groups as $g) {
							$o = explode('##', $g);
							echo '<option value="' . $o[0] . '">' . JText::_($o[1]) . '</option>';
						}
						echo '</select><br />';
						break;
				}
			}
		}
		?>

		<input type="hidden" name="jform[listid]" value="<?php echo $params->get('listid'); ?>"/>
		<?php echo JHTML::_('form.token'); ?>
		<?php if ($params->get('outro-text-1')) : ?>
			<div id="outro1_<?php echo $moduleId; ?>" class="outro1">
				<p class="outro"><?php echo JText::_($params->get('outro-text-1')); ?></p>
			</div>
		<?php endif; ?>
		<div>
			<input type="submit" class="button btn btn-primary" value="<?php echo JText::_('MOD_CMC_SUBSCRIBE'); ?>"
			       id="cmc-signup-submit-<?php echo $moduleId; ?>"/>
		</div>
		<?php if ($params->get('outro-text-2')) : ?>
			<div id="outro2_<?php echo $moduleId; ?>" class="outro2">
				<p class="outro"><?php echo JText::_($params->get('outro-text-2')); ?></p>
			</div>
		<?php endif; ?>
	</form>
	<div id="spinner-<?php echo $moduleId; ?>" style="text-align:center;display:none;"><img
			src="<?php echo JURI::root(); ?>media/mod_cmc/images/ajax-loader.gif" alt="Please wait"/></div>

</div>