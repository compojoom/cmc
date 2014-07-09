<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
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
	public function postSaveHook($model, $validData)
	{
		$params = JComponentHelper::getParams('com_cmc');
		$api_key = $params->get("api_key", '');
		$user = JFactory::getUser();

		// Updating it to mailchimp
		if ($model->getState('user.new'))
		{
			CmcHelperBasic::subscribeList(
				$api_key,
				$validData['list_id'],
				$validData['email'],
				$validData['firstname'],
				$validData['lastname'],
				$user,
				null,
				$validData['email_type'],
				false
			);
		}
		else
		{
			// Updating to MC
			CmcHelperBasic::subscribeList(
				$api_key,
				$validData['list_id'],
				$validData['email'],
				$validData['firstname'],
				$validData['lastname'],
				$user,
				null,
				$validData['email_type'],
				true
			);
		}

	}
}
