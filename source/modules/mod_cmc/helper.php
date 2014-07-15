<?php
/**
 * @package    com_cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       15.07.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

JLoader::discover('cmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

/**
 * Class modCMCHelper
 *
 * @since  1.5
 */
class modCMCHelper {

	/**
	 * Creates a JForm
	 *
	 * @param   object  $params  - the module params
	 *
	 * @return object
	 */
	public static function getForm ($params)
	{
		$renderer = CmcHelperXmlbuilder::getInstance($params);

		// Generate the xml for the form
		$xml = $renderer->build();

		$mapping = self::getMapping($params->get('mapfields'));

		$form = JForm::getInstance('mod_cmc', $xml, array('control' => 'jform'));
		$form->bind($mapping);

		return $form;
	}

	/**
	 * Creates an array with the mapping to data
	 *
	 * @param   string  $raw  - the raw mapping definition as taken out of the params
	 *
	 * @return array
	 */
	public static function getMapping($raw)
	{
		$user = JFactory::getUser();

		// No need to go further. User is not logged in, so we can't use any info from his profile...
		if ($user->guest)
		{
			return array();
		}

		$lines = explode("\n", $raw);
		$groups = array();

		foreach ($lines as $line)
		{
			$map = explode('=', $line);

			if (strstr($map[1], ':'))
			{
				$parts = explode(':', $map[1]);
				$field = explode(' ', $user->get($parts[0]));

				$value = trim($field[(int) $parts[1]]);
			}
			else
			{
				$value = $user->get(trim($map[1]));
			}

			$groups[$map[0]] = $value;
		}

		return array('cmc_groups' => $groups);
	}

	public static function hasSignedForNewsletter($id)
	{
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__cmc_users')
			->where('(' . $db->qn('user_id') . '=' . $db->q($user->get('id')) . ' OR email = ' . $db->q($user->email) . ')')
			->where($db->qn('list_id') . '=' . $db->q($id));
		$db->setQuery($query);

		return $db->loadObject();
	}
}
