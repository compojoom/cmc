<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die();
jimport('joomla.application.component.modeladmin');

JLoader::discover('cmcTable', JPATH_ADMINISTRATOR . '/components/com_cmc/tables/');

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

		$userData = $this->getItem();

		// If the user data is stored in the session we are dealing with arrays, if we are loading from DB with object
		if (is_array($userData))
		{
			$old = $userData;
			$userData = new JRegistry;
			$userData->set('list_id', $old['list_id']);
			$userData->set('email', $old['cmc_groups']['email']);
		}
		elseif (is_object($userData) && !$userData->get('id'))
		{
			$userData = new JRegistry;
			$userData->set('list_id', isset($data['list_id']) ? $data['list_id'] : '');
			$userData->set('email', isset($data['cmc_groups']['EMAIL']) ? $data['cmc_groups']['EMAIL'] : '');
		}

		$listId = $userData->get('list_id', JFactory::getApplication()->input->get('filter_list'));

		// Get the merge fields and create a new form
		if ($listId)
		{
			$params = new JRegistry;
			$params->set('listid', $listId);

			$fields = array_map(
				function($value) {
					return $value['tag'];
				},
				CmcHelperList::getMergeFields($listId)
			);

			$interests = CmcHelperList::getInterestsFields($listId);

			if ($interests)
			{
				$interests = array_map(
					function($value) {
						return $value['id'];
					},
					$interests
				);
			}

			$params->set('fields', $fields);
			$params->set('interests', $interests);

			$renderer = CmcHelperXmlbuilder::getInstance($params);

			// Generate the xml for the form
			$xml = $renderer->build();


			$form->load($xml, true);

			$subscriptionData = CmcHelperUsers::getSubscription($userData->get('email'), $userData->get('list_id'));

			// Bind the data to the form
			if ($subscriptionData)
			{
				$form->bind(CmcHelperSubscription::convertMergesToFormData($subscriptionData->merges, $listId));
			}
		}

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

	/**
	 * Since we now have a dynamic form we have to go around the validation somehow
	 *
	 * @param   JForm   $form   The form to validate against.
	 * @param   array   $data   The data to validate.
	 * @param   string  $group  The name of the field group to validate.
	 *
	 * @return  mixed  Array of filtered data if valid, false otherwise.
	 */
	public function validate($form, $data, $group = null)
	{
		// The data that we are receiving should have a cmc_groups
		if (isset($data['cmc_groups']['EMAIL']))
		{
			$data['email'] = $data['cmc_groups']['EMAIL'];
			$data['firstname'] = $data['cmc_groups']['FNAME'];
			$data['lastname'] = $data['cmc_groups']['LNAME'];
		}
		else
		{
			JFactory::getApplication()->enqueueMessage('Your list doesn\'t have a EMAIL field or you are using a different field name for email(the field name should be EMAIL)');

			return false;
		}

		return parent::validate($form, $data);
	}
}
