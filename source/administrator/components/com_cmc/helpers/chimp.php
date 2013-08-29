<?php
/**
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       28.08.13
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
JLoader::register('MCAPI', JPATH_ADMINISTRATOR . '/components/com_cmc/libraries/mailchimp/MCAPI.class.php');

/**
 * Class cmcHelperChimp
 *
 * This class will work as a small abstraction over the MCAPI class.
 * I got too tired of typing the $key all the time :)
 *
 * @since  1.0
 */
class CmcHelperChimp extends MCAPI
{
	/**
	 * The constructor
	 *
	 * @param   string  $key     - the mailchimp api key
	 * @param   string  $secure  - use secure connection
	 */
	public function __construct($key = '', $secure = '')
	{
		if (!$key)
		{
			$key = JComponentHelper::getParams('com_cmc')->get('api_key', '');
		}

		if (!$secure)
		{
			$config = JFactory::getConfig();
			$secure = $config->get('force_ssl', 0);
		}

		parent::MCAPI($key, $secure);
	}
}
