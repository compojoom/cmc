<?php
/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 22.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

class JFormFieldWebhook extends JFormFieldText {

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
            var url = document.id('webhook-url').get('value');
            document.id('jform_webhooks_key').addEvent('keyup', function(){
                var key = {key : this.get('value')};
                document.id('webhook-url').set('value', url.substitute(key));
            });

            if(document.id('jform_webhooks_key').get('value') != '') {
                document.id('webhook-url').set('value', url.substitute({key: document.id('jform_webhooks_key').get('value')}));
            }

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

        return '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
            . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . '/>
            <input type="text" size="60" readonly="readonly" id="webhook-url" value="'.JURI::root().'index.php?option=com_cmc&format=raw&task=webhooks.request&key={key}" />';
    }
}