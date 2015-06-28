<?php
/**
 * @package    Com_Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       28.06.2015
 *
 * @copyright  Copyright (C) 2008 - 2015 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

$form = $displayData->form;

$fieldsets = $form->getFieldsets();

foreach ($fieldsets as $key => $value) :
	$fields = $form->getFieldset($key);

	foreach ($fields as $field) : ?>
		<div class="joms-form__group cmc-newsletter">
			<span>
				<?php echo $field->label; ?>
			</span>
			<?php
			if (strtolower($field->type) == 'list')
			{
				$field->class = $field->class . ' joms-select';
			} else {
				$field->class = $field->class . ' joms-input';
			}
			?>
			<?php echo $field->input; ?>

		</div>
	<?php endforeach; ?>
<?php endforeach; ?>