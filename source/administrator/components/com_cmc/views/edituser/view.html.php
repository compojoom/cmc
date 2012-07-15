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

        $user = $model->getList();

        if (!$user) {
            // Create new empty list item
            $user = JTable::getInstance('users', 'CmcTable');
        }

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
