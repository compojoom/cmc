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
 * Class CmcTableShops
 *
 * @since  __DEPLOY_VERSION__
 */
class CmcTableShops extends JTable
{
	/**
	 * The constructor
	 *
	 * @param   string  &$db  - the db object
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__cmc_shops', 'id', $db);
	}
}
