<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
 * Class CmcTableLists
 *
 * @since  1.2
 */
class CmcTableLists extends JTable
{
	/**
	 * The constructor
	 *
	 * @param   string  &$db  - the db object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__cmc_lists', 'id', $db);
	}
}
