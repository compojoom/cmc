<?php

/**
 * Tiles
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
jimport( 'joomla.html.pane' );
?>

<form action="index.php" method="post" name="adminForm">

<?php

$pane = JPane::getInstance( 'tabs',  array('startOffset'=>0));
echo $pane->startPane( 'pane' );
echo $pane->startPanel( JText::_( 'COM_CMC_BASIC' ), 'basic' );
?>
<div class="col60">
<fieldset class="adminform">
<legend><?php echo JText::_( 'COM_CMC_BASIC' ); ?></legend>

<table class="admintable">
<?php 
foreach ($this->items_basic as $value) {

        echo '<tr>';
        echo '<td class="key">';
        echo '<label for="'.$value->title.'" width="100" title="'.JText::_('COM_CMC_'.strtoupper($value->title). '_DESC').'">';
        echo JText::_('COM_CMC_'.strtoupper($value->title));
        echo '</label>';
        echo '</td>';

        echo '<td colspan="2">';

        switch ($value->type) {
                case 'textarea':
                        echo CmcSettingsHelper::getTextareaSettings($value->id, $value->title, $value->value);
                break;

                case 'select':
                        echo CmcSettingsHelper::getSelectSettings($value->id, $value->title, $value->value, $value->values);
                break;

                case 'text':
                default:
                        echo CmcSettingsHelper::getTextSettings($value->id, $value->title, $value->value);
                break;

        }
        echo '</td>';
        echo '</tr>';

}
?>
</table>
</fieldset>
</div>
<div class="clr"></div>
<?php

echo $pane->endPanel();

echo $pane->startPanel( JText::_( 'COM_CMC_ADVANCED' ), 'advanced' );
?>
<div class="col60">
<fieldset class="adminform">
<legend><?php echo JText::_( 'COM_CMC_ADVANCED' ); ?></legend>

<table class="admintable">
<?php
foreach ($this->items_advanced as $value) {

        echo '<tr>';
        echo '<td class="key">';
        echo '<label for="'.$value->title.'" width="100" title="'.JText::_('COM_CMC_'.strtoupper($value->title). '_DESC').'">';
        echo JText::_('COM_CMC_'.strtoupper($value->title));
        echo '</label>';
        echo '</td>';

        echo '<td colspan="2">';

        switch ($value->type) {
                case 'textarea':
                        echo CmcSettingsHelper::getTextareaSettings($value->id, $value->title, $value->value);
                break;

                case 'select':
                        echo CmcSettingsHelper::getSelectSettings($value->id, $value->title, $value->value, $value->values);
                break;

                case 'text':
                default:
                        echo CmcSettingsHelper::getTextSettings($value->id, $value->title, $value->value);
                break;

        }
        echo '</td>';
        echo '</tr>';
}
?>
</table>
</fieldset>
</div>
<div class="clr"></div>
<?php
echo $pane->endPanel();

echo $pane->endPane();
?>

<input type="hidden" name="option" value="com_cmc" />
<input type="hidden" name="view" value="settings" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="settings" />
    
<?php echo JHTML::_( 'form.token' ); ?>
</form>

