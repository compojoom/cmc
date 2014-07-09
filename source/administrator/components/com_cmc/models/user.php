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
jimport('joomla.application.component.modeladmin');

/**
 * Class CmcModelUser
 *
 * @since  1.1
 */
class CmcModelUser extends JModelAdmin
{
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $name     The table type to instantiate
	 * @param   string  $prefix   A prefix for the table class name. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return    JTable    A database object
	 */
	public function getTable($name = 'Users', $prefix = 'CmcTable', $options = array())
	{
		return JTable::getInstance($name, $prefix, $options);
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
		$form = $this->loadForm('com_cmc.user', 'user', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return    mixed    The data for the form
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_cmc.edit.user.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}
}
