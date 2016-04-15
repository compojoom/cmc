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