<?php
/**
 * Cmc
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class CmcViewEditUser extends JView {

    function display($tpl = null) {

        $model = $this->getModel();
        $user = $model->getUser();

        if (!$user) {
            // Create new empty list item
            $user = JTable::getInstance('users', 'CmcTable');
        } else {
            // Update User from Mailchimp
            //     public static function getUserDetailsMC($api_key, $list_id, $email, $id = null, $store = true){
            // //$ret = CmcHelper::getUserDetailsMC(CmcSettingsHelper::getSettings("api_key", ''), "2c4bb4fad2", "hoppe.yves@gmail.com", 13, true);

            $user = CmcHelper::getUserDetailsMC(CmcSettingsHelper::getSettings("api_key", ''), $user->list_id, $user->email, $user->id, true);
        }
//
//        var_dump($user);
//        die("adsf");

        $lists = CmcHelper::getLists();

        $list_options = array();

        foreach($lists as $list) {
            $list_options[] = JHTML::_('select.option', $list->mc_id, $list->list_name );
        }

        $list_select = JHTML::_('select.genericlist', $list_options, 'list_id', null, 'value', 'text', $user->list_id);

        // html, text, or mobile
        $email_type_options = array(
            JHTML::_('select.option', 'html', JText::_('COM_CMC_HTML') ),
            JHTML::_('select.option', 'text', JText::_('COM_CMC_TEXT') ),
            JHTML::_('select.option', 'mobile', JText::_('COM_CMC_MOBILE') )
        );

        $email_type_select = JHTML::_('select.genericlist', $email_type_options, 'email_type', null, 'value', 'text', $user->email_type);

        $status_options = array(
            JHTML::_('select.option', 'subscribed', JText::_('COM_CMC_SUBSCRIBED') ),
            JHTML::_('select.option', 'unsubscribed', JText::_('COM_CMC_UNSUBSCRIBED') ),
            JHTML::_('select.option', 'cleaned', JText::_('COM_CMC_CLEANED') ),
            JHTML::_('select.option', 'pending', JText::_('COM_CMC_PENDING') )
        );

        $status_select = JHTML::_('select.genericlist', $status_options, 'status', null, 'value', 'text', $user->status);

        $this->assignRef('list_select', $list_select);
        $this->assignRef('lists', $lists);
        $this->assignRef('status_select', $status_select);
        $this->assignRef('email_type_select', $email_type_select);
        $this->assignRef('user', $user);

        $this->addToolbar();
        parent::display($tpl);
    }

    public function addToolbar() {
        // Set toolbar items for the page
        JToolBarHelper::title(JText::_('COM_CMC_EDIT_USER'), 'users');
        JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::cancel();
        JToolBarHelper::help('screen.users', true);
    }

}
