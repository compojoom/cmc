<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.modellist');

/**
 * Class CmcModelUsers
 *
 * @since  1.1
 */
class CmcModelUsers extends JModelList
{
	/**
	 * the constructor Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'u.id',
				'email', 'u.email',
				'timestamp', 'u.timestamp'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$status = $this->getUserStateFromRequest($this->context . '.filter.status', 'filter_status');
		$this->setState('filter.status', $status);

		$list = $this->getUserStateFromRequest($this->context . '.filter.list', 'filter_list');
		$this->setState('filter.list', $list);

		parent::populateState('u.email', 'asc');
	}

	/**
	 * Get the context
	 *
	 * @return null|string
	 */
	public function getContext()
	{
		return $this->context;
	}

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 */
	public function getListQuery()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery('true');

		$query->select('*')->from('#__cmc_users AS u');


		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('u.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('(u.email LIKE ' . $search . ')');
			}
		}

		$status = $this->getState('filter.status');

		if (!empty($status))
		{
			$query->where('u.status = ' . $db->Quote($status));
		}

		$list = $this->getState('filter.list');

		if (!empty($list))
		{
			$query->where($db->qn('u.list_id') . ' = ' . $db->q($list));
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Gets users for export
	 *
	 * @return mixed
	 */
	public function export()
	{
		$db = JFactory::getDbo();
		$this->populateState();
		$query = $this->getListQuery();

		$query->clear('select');

		$query->select(array('firstname', 'lastname', 'email', 'user_id', 'timestamp', 'list_id', 'status'));

		$db->setQuery($query);

		return $db->loadAssocList();
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return    JTable    A database object
	 */
	public function getTable($type = 'Users', $prefix = 'CmcTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return    JForm    A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_cmc.users', 'users', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}


		return $form;
	}


	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return    mixed    The data for the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_cmc.edit.user.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('users.id') == 0)
			{
				$app = JFactory::getApplication();
				$data->set('id', JFactory::getApplication()->input->getInt('id', $app->getUserState('com_cmc.users.filter.list_id')));
			}
		}

		return $data;
	}
}
