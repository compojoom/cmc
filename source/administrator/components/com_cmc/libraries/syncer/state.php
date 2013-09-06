<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       29.08.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CmcHelperSyncer
 *
 * @since  1.2
 */
class CmcSyncerState
{
	/**
	 * The syncer state object.
	 *
	 * @var    object
	 * @since  2.5
	 */
	public static $state;

	/**
	 * Method to get the syncer state.
	 *
	 * @return  object  The indexer state object.
	 *
	 * @since   1.3
	 */
	public static function getState()
	{
		// First, try to load from the internal state.
		if (!empty(self::$state))
		{
			return self::$state;
		}

		// If we couldn't load from the internal state, try the session.
		$session = JFactory::getSession();
		$data = $session->get('_cmc.state', null);

		// If the state is empty, load the values for the first time.
		if (empty($data))
		{
			$data = new JObject;

			// Set the current time as the start time.
			$data->startTime = JFactory::getDate()->toSQL();

			// Set the remaining default values.
			$data->batchSize = 1000;
		}

		// Set the state.
		self::$state = $data;

		return self::$state;
	}

	/**
	 * Method to set the indexer state.
	 *
	 * @param   object  $data  A new syncer state object.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   1.3
	 */
	public static function setState($data)
	{
		// Check the state object.
		if (empty($data) || !$data instanceof JObject)
		{
			return false;
		}

		// Set the new internal state.
		self::$state = $data;

		// Set the new session state.
		$session = JFactory::getSession();
		$session->set('_cmc.state', $data);

		return true;
	}

	/**
	 * Method to reset the syncer state.
	 *
	 * @return  void
	 *
	 * @since   1.3
	 */
	public static function resetState()
	{
		// Reset the internal state to null.
		self::$state = null;

		// Reset the session state to null.
		$session = JFactory::getSession();
		$session->set('_cmc.state', null);
	}
}
