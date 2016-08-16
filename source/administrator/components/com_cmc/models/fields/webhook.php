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

/**
 * Class JFormFieldWebhook
 *
 * @since  1.0
 */
class JFormFieldWebhook extends JFormFieldText
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'Webhook';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$script = "window.addEvent('domready', function() {
            document.id('webhook-url').addEvent('click', function() {
                this.select();
            });
        });";
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);

		// Initialize some field attributes.
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';


		if (!$this->value)
		{
			$this->value = md5(JFactory::getUser()->id . JFactory::getUser()->name . microtime());
		}

		return '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
		. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . '/>
            <input type="text" size="60" readonly="readonly" id="webhook-url" value="' . JURI::root()
				. 'index.php?option=com_cmc&format=raw&task=webhooks.request&key=' . $this->value . '" />';
	}
}
