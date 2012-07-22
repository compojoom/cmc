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

defined('_JEXEC') or die();
jimport('joomla.application.component.modellist');

class CmcModelUsers extends JModelList {

    protected function populateState($ordering = null, $direction = null)
    {
        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        parent::populateState('u.email', 'asc');
    }

    public function getListQuery(){
        $db = JFactory::getDbo();
        $query = $db->getQuery('true');

        $query->select('*')->from('#__cmc_users AS u');


        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('u.id = '.(int) substr($search, 3));
            } else {
                $search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('(u.email LIKE '.$search .')');
            }
        }

        // Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');

        $query->order($db->escape($orderCol.' '.$orderDirn));

        return $query;
    }

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	1.6
     */
    public function getTable($type = 'Users', $prefix = 'CmcTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param	array	$data		An optional array of data for the form to interogate.
     * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
     * @return	JForm	A JForm object on success, false on failure
     * @since	1.6
     */
    public function getForm($data = array(), $loadData = true)
    {

        // Initialise variables.
        $app	= JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_cmc.users', 'users', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }

        // Determine correct permissions to check.
        //        if ($this->getState('categorie.id')) {
        //            // Existing record. Can only edit in selected categories.
        //            $form->setFieldAttribute('catid', 'action', 'core.edit');
        //        } else {
        //            // New record. Can only create in selected categories.
        //            $form->setFieldAttribute('catid', 'action', 'core.create');
        //        }

        return $form;
    }


    /**
     * Method to get the data that should be injected in the form.
     * TODO Fix that
     * @return	mixed	The data for the form.
     * @since	1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_cmc.edit.user.data', array());

        if (empty($data)) {
            $data = $this->getItem();

            // Prime some default values.
            if ($this->getState('users.id') == 0) {
                $app = JFactory::getApplication();
                $data->set('id', JRequest::getInt('id', $app->getUserState('com_cmc.users.filter.list_id')));
            }
        }

        return $data;
    }

}