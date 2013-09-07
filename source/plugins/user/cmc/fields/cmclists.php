<?php
/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 22.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
JLoader::register('CmcField', JPATH_ROOT . '/modules/mod_cmc/library/fields/field.php');
JLoader::register('cmcHelperChimp', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/chimp.php');


class JFormFieldCmclists extends CmcField {

    public function getInput() {
        $api = new cmcHelperChimp();
        $lists = $api->lists();

        $key = 'id';
        $val = 'name';
        $options[] = array( $key => '', $val => '-- '.JText::_('MOD_CMC_PLEASE_SELECT_A_LIST').' --');

        foreach ($lists['data'] as $list){
            $options[]=array($key=>$list[$key],$val=>$list[$val]);
        }

        $attribs = "onchange='submitbutton(\"plugin.apply\")'";
        if($options){
            $content =  JHtml::_('select.genericlist',$options, 'jform[params][listid]', $attribs, $key, $val, $this->value, $this->id);
        }

        return $content;
    }
}