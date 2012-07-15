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

class CmcViewEditList extends JView {

    function display($tpl = null) {

        $model = $this->getModel();

        $list = $model->getList();

        if (!$list) {
            // Create new empty list item
            $list = JTable::getInstance('lists', 'CmcTable');
        }

        $this->assignRef('list', $list);

        $this->addToolbar();
        parent::display($tpl);
    }

    public function addToolbar() {
        // Set toolbar items for the page
        JToolBarHelper::title(JText::_('COM_CMC_EDIT_LIST'), 'lists');
        JToolBarHelper::save();
        JToolBarHelper::apply();
        JToolBarHelper::cancel();
        JToolBarHelper::help('screen.lists', true);
    }

}
