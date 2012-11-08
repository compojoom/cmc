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
jimport('joomla.application.component.view');

class CmcViewUsers extends JViewLegacy {

    public function display($tpl = null) {

        $this->items = $this->get('Items');
        $this->state = $this->get('state');
        $this->status = $this->get('status');
        $this->pagination = $this->get('Pagination');

        $lists = CmcHelperBasic::getLists();
        $options[] = array( 'value' => '', 'text' => JText::_('JALL'));
        foreach($lists as $list) {
            $options[] = array(
                'value' => $list->mc_id,
                'text' => $list->list_name
            );
        }
        $this->lists = JHtml::_('select.genericlist', $options, 'filter_list', 'onchange="this.form.submit()"', 'value', 'text', $this->state->get('filter.list'));
        array_shift($options);
        $this->addToList = JHtml::_('select.genericlist', $options, 'addtolist', '', 'value', 'text', $this->state->get('filter.list'));

        $this->assignRef('filter', $filter);

        $this->addToolbar();
        parent::display($tpl);
    }

    public function addToolbar() {
        // Set toolbar items for the page
        JToolBarHelper::title(JText::_('COM_CMC_USERS'), 'users');
        JToolBarHelper::deleteList(JText::_('COM_CMC_DO_YOU_REALLY_WANTO_TO_REMOVE_THIS_USERS'), 'users.delete');
        JToolBarHelper::editList('user.edit');
        JToolBarHelper::addNew('user.add');
        JToolBarHelper::custom('users.addGroup', 'group.png', '', JText::_('COM_CMC_ADD_GROUP'), false);
        JToolBarHelper::custom('users.export',  'csv.png', '', 'CSV', false);
    }
}