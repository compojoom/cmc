<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The stats reporting Model
 *
 * @since  4.0
 */
class CmcModelStats extends CompojoomModelStats
{
	protected $extension = 'com_cmc';

	protected $exclude = array(
		'downloadid',
		'welcome_text',
		'api_key',
		'recaptcha_public_key',
		'recaptcha_private_key'
	);

	/**
	 * Here we set a custom extension name
	 *
	 * @return array
	 */
	public function getCustomExtensionData()
	{
		$data['extension'] = $this->extension . '.core';

		return $data;
	}
}
