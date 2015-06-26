<?php
/**
 * @package    Cmc
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;

// Load Compojoom library
require_once JPATH_LIBRARIES . '/compojoom/include.php';

JLoader::discover('cmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

/**
 * Class PlgUserCmc
 *
 * @since  1.4
 */
class PlgUserCmc extends JPlugin
{
	/**
	 * Prepares the form
	 *
	 * @param   string  $form  - the form
	 * @param   object  $data  - the data object
	 *
	 * @return bool
	 */

	public function onContentPrepareForm($form, $data)
	{
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');

			return false;
		}

		// Check we are manipulating a valid form.
		$name = $form->getName();

		if (in_array($name, array('com_users.user', 'com_users.profile')))
		{
			$this->edit($form, $data);

			return true;
		}

		if (!in_array($name, array('com_users.registration')))
		{
			return true;
		}

		$this->subscribe($form, $data);

		return true;
	}

	/**
	 * This function displays the newsletter form on the user profile
	 *
	 * @param   JForm  $form  - the user form
	 * @param   array  $data  - the user data
	 *
	 * @return bool
	 */
	public function edit($form, $data)
	{
		$appl = JFactory::getApplication();
		$subscriptionData = CmcHelperUsers::getSubscription($data->email, $this->params->get('listid'));

		$renderer = CmcHelperXmlbuilder::getInstance($this->params);

		// Render Content
		$html = $renderer->build();

		if ($appl->isSite())
		{
			CompojoomHtmlBehavior::jquery();
			JHtml::script('media/plg_user_cmc/js/cmc.js');
		}

		// Inject fields into the form
		$form->load($html, false);

		if ($subscriptionData)
		{
			$form->setFieldAttribute('newsletter', 'checked', 'checked', 'cmc');
			$form->bind(CmcHelperSubscription::convertMergesToFormData($subscriptionData->merges));
		}
	}

	/**
	 * Subscribe a user to our list
	 *
	 * @param   JForm  $form  - the user form
	 * @param   array  $data  - the user data
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
	public function subscribe($form, $data)
	{
		$needToValidate = true;

		$input = JFactory::getApplication()->input;
		$task = $input->get('task');

		if (in_array($task, array('register', 'apply', 'save')))
		{
			$requestData = JFactory::getApplication()->input->get('jform', array(), 'array');
			$needToValidate = isset($requestData['cmc']) && isset($requestData['cmc']['newsletter']);
		}

		if ($needToValidate)
		{
			$lang = JFactory::getLanguage();
			$lang->load('plg_user_cmc', JPATH_ADMINISTRATOR);

			CompojoomHtmlBehavior::jquery();
			JHtml::script('media/plg_user_cmc/js/cmc.js');
			$renderer = CmcHelperXmlbuilder::getInstance($this->params);

			// Render Content
			$html = $renderer->build();

			// Inject fields into the form
			$form->load($html, false);
		}

		return true;
	}


	/**
	 * Prepares the form
	 *
	 * @param   array    $data    - the users data
	 * @param   boolean  $isNew   - is the user new
	 * @param   object   $result  - the db result
	 * @param   string   $error   - the error message
	 *
	 * @return   boolean
	 */

	public function onUserAfterSave($data, $isNew, $result, $error)
	{
		$userId = ArrayHelper::getValue($data, 'id', 0, 'int');

		$input = JFactory::getApplication()->input;
		$task = $input->get('task');

		if (in_array($task, array('register','activate')))
		{
			if ($userId && $result && isset($data['cmc']) && (count($data['cmc'])))
			{
				if ($data["cmc"]["newsletter"] != "1" && $isNew != false)
				{
					// Abort if Newsletter is not checked
					return true;
				}

				$mappedData = $this->getMapping($this->params->get('mapfields'), $data);

				if (count($mappedData))
				{
					$mergedGroups = array_merge($mappedData, $data['cmc_groups']);
					$data = array_merge($data, array('cmc_groups' => $mergedGroups));
				}

				$user = JFactory::getUser($data["id"]);

				if ($data["block"] == 1)
				{
					// Temporary save user
					CmcHelperRegistration::saveTempUser($user, $data, _CPLG_JOOMLA);
				}
				else
				{
					if (!$isNew)
					{
						// Activate User to Mailchimp
						CmcHelperRegistration::activateTempUser($user);
					}
					else
					{
						// Directly activate user
						$activated = CmcHelperRegistration::activateDirectUser(
							$user, $data["cmc"], _CPLG_JOOMLA
						);

						if ($activated)
						{
							JFactory::getApplication()->enqueueMessage(JText::_('COM_CMC_YOU_VE_BEEN_SUBSCRIBED_BUT_CONFIRMATION_IS_NEEDED'));
						}
					}
				}
			}
			else
			{
				// We only do something if the user is unblocked
				if ($data["block"] == 0)
				{
					// Checking if user exists etc. is taken place in activate function
					CmcHelperRegistration::activateTempUser(JFactory::getUser($data["id"]));
				}
			}
		}

		if (in_array($task, array('apply', 'save')))
		{
			if ($userId && $result && isset($data['cmc']) && (count($data['cmc'])))
			{
				if ($data["cmc"]["newsletter"] != "1")
				{
					// Abort if Newsletter is not checked
					return true;
				}

				$mappedData = $this->getMapping($this->params->get('mapfields'), $data);

				if (count($mappedData))
				{
					$mergedGroups = array_merge($mappedData, $data['cmc_groups']);
					$data         = array_merge($data, array('cmc_groups' => $mergedGroups));
				}
			}

			$subscription = CmcHelperUsers::getSubscription($data['email'], $data['cmc']['listid']);

			// Updating it to mailchimp
			$update = $subscription ? true : false;

			CmcHelperList::subscribe(
				$data['cmc']['listid'],
				$data['email'],
				$data['cmc_groups']['FNAME'],
				$data['cmc_groups']['LNAME'],
				CmcHelperList::mergeVars($data),
				'html',
				$update,
				true
			);
		}

		return true;
	}

	/**
	 * Creates an array with the mapped data
	 *
	 * @param   string  $raw   - the raw mapping definition as taken out of the params
	 * @param   array   $user  - array with the user data
	 *
	 * @return array
	 */
	public static function getMapping($raw, $user)
	{
		if (!$raw)
		{
			return array();
		}

		$lines = explode("\n", trim($raw));
		$groups = array();

		foreach ($lines as $line)
		{
			$map = explode('=', $line);

			if (strstr($map[1], ':'))
			{
				$parts = explode(':', $map[1]);
				$field = explode(' ', $user[$parts[0]]);

				$value = trim($field[(int) $parts[1]]);
			}
			else
			{
				$value = $user[trim($map[1])];
			}

			$groups[$map[0]] = $value;
		}

		return $groups;
	}
}
