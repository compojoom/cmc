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
class ModCMCHelper
{
	/**
	 * Creates a JForm
	 *
	 * @param   int     $id      - the module id. We use it to create unique jform instance
	 * @param   object  $params  - the module params
	 *
	 * @return object
	 */
	public static function getForm ($id, $params)
	{
		$renderer = CmcHelperXmlbuilder::getInstance($params);

		// Generate the xml for the form
		$xml = $renderer->build();

		$mapping = self::getMapping($params->get('mapfields'));

		try
		{
			$form = JForm::getInstance('mod_cmc_' . $id, $xml, array('control' => 'jform'));
			$form->bind($mapping);
		}
		catch (Exception $e)
		{
			return false;
		}


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

	/**
	 * Checks the subscribe status of the logged in user
	 *
	 * @param   int  $id  - the list_id
	 *
	 * @return mixed
	 */
	public static function getNewsletterStatus($id)
	{
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('status')->from('#__cmc_users')
			->where('(' . $db->qn('user_id') . '=' . $db->q($user->get('id')) . ' OR email = ' . $db->q($user->email) . ')')
			->where($db->qn('list_id') . '=' . $db->q($id));
		$db->setQuery($query);

		return $db->loadObject();
	}
}
