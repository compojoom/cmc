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
 * Class CmcModelLists
 *
 * @since  1.2
 */
class CmcModelList extends JModelAdmin
{
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return    JTable    A database object
	 */
	public function getTable($type = 'Lists', $prefix = 'CmcTable', $config = array())
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
		$form = $this->loadForm('com_cmc.lists', 'lists', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * The save function
	 *
	 * @param   array  $list  - data to save
	 *
	 * @return bool
	 */
	public function save($list)
	{
		$user = JFactory::getUser();
		$item = array();
		$item['id'] = null;
		$item['mc_id'] = $list['id'];
		$item['web_id'] = $list['web_id'];
		$item['list_name'] = $list['name'];
		$item['date_created'] = $list['date_created'];
		$item['email_type_option'] = $list['email_type_option'];
		$item['use_awesomebar'] = $list['use_awesomebar'];
		$item['default_from_name'] = $list['default_from_name'];
		$item['default_from_email'] = $list['default_from_email'];
		$item['default_subject'] = $list['default_subject'];
		$item['default_language'] = $list['default_language'];
		$item['list_rating'] = $list['list_rating'];
		$item['subscribe_url_short'] = $list['subscribe_url_short'];
		$item['subscribe_url_long'] = $list['subscribe_url_long'];
		$item['beamer_address'] = $list['beamer_address'];
		$item['visibility'] = $list['visibility'];
		$item['created_user_id'] = $user->id;
		$item['created_time'] = JFactory::getDate()->toSql();
		$item['modified_user_id'] = $user->id;
		$item['modified_time'] = JFactory::getDate()->toSql();
		$item['access'] = 1;
		$item['query_data'] = json_encode($list);

		return parent::save($item);
	}
}
