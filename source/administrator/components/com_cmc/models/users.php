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
jimport('joomla.application.component.modeladmin');

class CmcModelUsers extends JModelAdmin {

    var $_users = null;
    var $_total = null;
    var $_pagination = null;

    public function __construct() {
        parent::__construct();
        $appl = JFactory::getApplication();
        $context = 'com_cmc.users.list.';
        // Get the pagination request variables
        $limit = $appl->getUserStateFromRequest('global.list.limit', 'limit', $appl->getCfg('list_limit'), 'int');
        $limitstart = $appl->getUserStateFromRequest($context . 'limitstart', 'limitstart', 0, 'int');

        // In case limit has been changed, adjust limitstart accordingly
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    public function getList() {
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_data;
    }

    public function getTotal() {
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }

    public function getPagination() {
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_pagination;
    }

    private function _buildQuery() {
        $context = 'com_cmc.users.list.';
        // Get the WHERE and ORDER BY clauses for the query
        $where = $this->_buildContentWhere($context);
        $orderby = $this->_buildContentOrderBy($context, 'cc.email');

        $query = ' SELECT cc.* FROM #__cmc_users AS cc '
            . $where
            . $orderby
        ;

        return $query;
    }

    private function _buildContentOrderBy($context, $cc_or_a) {

        $appl = JFactory::getApplication();
        $filter_order = $appl->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'cc.email', 'cmd'); // Category tree works with id not with ordering
        $filter_order_dir = $appl->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');

        if ($filter_order == 'cc.email') {
            $orderby = ' ORDER BY cc.email ' . $filter_order_dir;
        } else {
            $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_dir;
        }
        return $orderby;
    }

    private function _buildContentWhere($context) {
        $appl = JFactory::getApplication();
        $filter_state = $appl->getUserStateFromRequest($context . 'filter_state', 'filter_state', '', 'word');
        $search = $appl->getUserStateFromRequest($context . 'search', 'search', '', 'string');
        $search = JString::strtolower($search);
        $where = array();

        if ($search) {
            $where[] = 'LOWER(cc.email) LIKE ' . $this->_db->Quote('%' . $search . '%');
        }
        if ($filter_state) {
            if ($filter_state == 'P') {
                $where[] = 'cc.published = 1';
            } else if ($filter_state == 'U') {
                $where[] = 'cc.published = 0';
            }
        }

        $where = ( count($where) ? ' WHERE ' . implode(' AND ', $where) : '' );

        return $where;
    }

    /**
     * returns a list with categories ordered by id
     * @return mixed
     */
    public function getLists() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        // TODO Fix that
        //echo 78687;
        $query->select('*')
            ->from('#__cmc_users');
        $db->setQuery($query);
        return $db->loadObjectList();
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
        $data = JFactory::getApplication()->getUserState('com_cmc.edit.list.data', array());

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