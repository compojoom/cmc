<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */


defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');

/**
 * Class CmcControllerUser
 *
 * @since  1.0
 */
class CmcControllerUser extends JControllerForm
{
	/**
	 * Add/update the user in Mailchimp
	 *
	 * @param   JModelLegacy  $model      - the model object
	 * @param   array         $validData  - the valid data
	 *
	 * @return void
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		// Updating it to mailchimp
		if ($model->getState('user.new'))
		{
			CmcHelperList::subscribe(
				$validData['list_id'],
				$validData['email'],
				$validData['firstname'],
				$validData['lastname'],
				CmcHelperList::mergeVars($validData),
				$validData['email_type'],
				false
			);
		}
		else
		{
			// Updating to MC
			CmcHelperList::subscribe(
				$validData['list_id'],
				$validData['email'],
				$validData['firstname'],
				$validData['lastname'],
				CmcHelperList::mergeVars($validData),
				$validData['email_type'],
				true
			);
		}
	}

	/**
	 * Do some tricks here to hae the list_id in the url
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @throws Exception
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$input = JFactory::getApplication()->input;
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);

		if ($input->get('addtolist'))
		{
			return $append . '&filter_list=' . $input->get('addtolist');
		}

		// Get the form data
		$formData = new JInput($input->get('jform', '', 'array'));

		if ($formData->get('list_id'))
		{
			return $append . '&filter_list=' . $formData->get('list_id');
		}

		return $append;
	}
}
