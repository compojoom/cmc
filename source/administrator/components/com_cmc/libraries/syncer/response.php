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

/**
 * Cmc Syncer JSON Response Class
 *
 * @since  2.5
 */
class CmcSyncerResponse
{
	/**
	 * Class Constructor
	 *
	 * @param   mixed  $state  The processing state for the indexer
	 *
	 * @since   2.5
	 */
	public function __construct($state)
	{
		// The old token is invalid so send a new one.
		$this->token = JFactory::getSession()->getFormToken();

		// Check if we are dealing with an error.
		if ($state instanceof Exception)
		{
			// Log the error
			JLog::add($state->getMessage(), JLog::ERROR);

			// Prepare the error response.
			$this->error = true;
			$this->header = JText::_('COM_CMC_SYNCER_HEADER_ERROR');
			$this->message = $state->getMessage();
		}
		else
		{
			// Prepare the response data.
			$this->batchSize = (int) $state->batchSize;

			$this->offset = $state->offset;
			$this->lists = $state->lists;
			$this->startTime = $state->startTime;
			$this->endTime = JFactory::getDate()->toSQL();
			$this->header = $state->header;
			$this->message = $state->message;
		}
	}
}
