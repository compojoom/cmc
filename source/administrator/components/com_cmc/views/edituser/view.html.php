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

        $this->assignRef('user', $user);

        $this->addToolbar();
        parent::display($tpl);
    }

    public function addToolbar() {
        // Set toolbar items for the page
        JToolBarHelper::title(JText::_('COM_CMC_EDIT_USER'), 'user');
        JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::cancel();
        JToolBarHelper::help('screen.users', true);
    }

}
