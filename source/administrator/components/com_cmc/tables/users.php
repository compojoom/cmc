<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
 * Class CmcTableUsers
 *
 * @since  1.0
 */
class CmcTableUsers extends JTable
{
	protected $_jsonEncode = array('merges');
	/**
	 * The constructor
	 *
	 * @param   string  &$db  - the db object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__cmc_users', 'id', $db);
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 * @throws  InvalidArgumentException
	 */
	public function bind($src, $ignore = array())
	{
		// If we have cmc_groups or cmc_intersts, than this would mean that we are coming from the form
		if (isset($src['cmc_groups']) || isset($src['cmc_interests']))
		{
			$src['merges'] = CmcHelperList::mergeVars($src, $src['list_id']);
		}

		return parent::bind($src, $ignore);
	}
}
