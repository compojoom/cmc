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

JLoader::discover('cmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

// Load Compojoom library
require_once JPATH_LIBRARIES . '/compojoom/include.php';

/**
 * Class PlgCommunityCmc
 *
 * @since  1.4
 */
class PlgCommunityCmc extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object  &$subject  - The object to observe
	 * @param   array   $config    - An optional associative array of configuration settings.
	 */
	public function __construct(&$subject, $config = array())
	{
		$jlang = JFactory::getLanguage();
		$jlang->load('com_cmc', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('com_cmc', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
		$jlang->load('com_cmc', JPATH_ADMINISTRATOR, null, true);
		$jlang->load('com_cmc.sys', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('com_cmc.sys', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
		$jlang->load('com_cmc.sys', JPATH_ADMINISTRATOR, null, true);

		parent::__construct($subject, $config);
	}

	/**
	 * Manupulates the registration form
	 *
	 * @param   string  &$data  - registration form data
	 *
	 * @return mixed
	 */
	public function onUserRegisterFormDisplay(&$data)
	{
		// Load the funky stuff
		CompojoomHtmlBehavior::jquery();
		JHtml::stylesheet('media/plg_community_cmc/css/style.css');
		JHtml::script('media/plg_community_cmc/js/cmc.js');

		$html = array();

		// Create the xml for JForm
		$builder = CmcHelperXmlbuilder::getInstance($this->params);
		$xml = $builder->build();

		$form = new JForm('myform');
		$form->addFieldPath(JPATH_ADMINISTRATOR . '/components/com_cmc/models/fields');
		$form->load($xml);

		$fieldsets = $form->getFieldsets();

		foreach ($fieldsets as $key => $value)
		{
			$fields = $form->getFieldset($key);

			foreach ($fields as $field)
			{
				$html[] = '<li class="cmc-newsletter">';
				$html[] = $field->label;
				$html[] = '<div class="form-field">' . $field->input . '</div>';
				$html[] = '</li>';
			}
		}

		$pos = strpos($data, '<li class="form-action has-seperator">');
		$data = substr($data, 0, $pos) . implode('', $html) . substr($data, $pos);
	}

	/**
	 * Saves a temporary subscription if necessary
	 *
	 * @param   array  $data  - post data
	 *
	 * @return bool
	 */
	public function onRegisterValidate($data)
	{
		// If newsletter was selected - save the user data!
		if (isset($data['cmc']) && ((int) $data['cmc']['newsletter'] === 1))
		{
			// Jomsocial doesn't create a user_id until the very last step
			// that's why we will save the user token for referrence later on
			$token = $this->getUserToken($data['authkey']);
			$user = new stdClass;
			$user->id = $token;
			$postData = array();

			$mappedData = $this->getMapping($this->params->get('mapfields'), $data);

			if (count($mappedData))
			{
				$mergedGroups = array_merge($mappedData, $data['cmc_groups']);
				$data = array_merge($data, array('cmc_groups' => $mergedGroups));
			}

			$postData['cmc']['listid'] = $data['cmc']['listid'];
			$postData['cmc_groups'] = $data['cmc_groups'];
			$postData['cmc_interests'] = $data['cmc_interests'];
			CmcHelperRegistration::saveTempUser(
				$user,
				$postData,
				_CPLG_JOMSOCIAL
			);
		}
	}

	/**
	 * Checks if we have a subscription and then does what is necessary - either activating it
	 * on the fly
	 *
	 * @param   array    $data    - the user data
	 * @param   boolean  $isNew   - true if the user is new
	 * @param   boolean  $result  - the result of the save
	 * @param   object   $error   - the error if any
	 *
	 * @return void
	 */
	public function onUserAfterSave($data, $isNew, $result, $error)
	{
		/**
		 * Jomsocial is calling the onUserAfterSave function around 3 times
		 * During the registration process. Because of that we end up sending 3
		 * Emails telling the user to subscribe. Since this is stupid, we'll mark
		 * if we've sent a mail and won't try to do it over and over again
		 */
		static $mailSent = false;

		// If we have a token, let us check if we have a subscription
		// And if we do, set the correct user_id
		if (isset($data['token']))
		{
			$subscription = $this->getSubscription($data['token']);

			if ($subscription)
			{
				$this->updateUserId($data['id'], $data['token']);
			}
		}

		// Now let us check if we have a subscription for the user id, this time using the user id
		$subscription = $this->getSubscription($data['id']);

		if ($subscription && !$mailSent)
		{
			if ($data["block"] == 0)
			{
				$json = json_decode($subscription->params, true);

				// Directly activate user
				CmcHelperRegistration::activateDirectUser(
					JFactory::getUser($data["id"]), $json, _CPLG_JOMSOCIAL
				);

				$mailSent = true;
			}
		}
	}

	/**
	 * Gets a user subscription
	 *
	 * @param   string  $token  - the user token
	 *
	 * @return mixed
	 */
	private function getSubscription($token)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')->from('#__cmc_register')
			->where($db->qn('user_id') . '=' . $db->q($token));

		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Updates the user id and changes the token to a real id
	 *
	 * @param   int     $id     - the user id
	 * @param   string  $token  - the user token
	 *
	 * @return void
	 */
	private function updateUserId($id, $token)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update($db->qn('#__cmc_register'))->set(
			$db->qn('user_id') . '=' . $db->q($id)
		)
			->where($db->qn('user_id') . '=' . $db->q($token));
		$db->setQuery($query);

		$db->execute();
	}

	/**
	 * Gets the user token by using the user auth_key
	 *
	 * @param   string  $key  - the key
	 *
	 * @return bool
	 */
	private function getUserToken($key)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('token'))->from($db->qn('#__community_register_auth_token'))
			->where($db->qn('auth_key') . '=' . $db->q($key));
		$db->setQuery($query);

		$result = $db->loadObject();

		return $result ? $result->token : false;
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
