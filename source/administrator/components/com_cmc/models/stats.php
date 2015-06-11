<?php
/**
 * @package    CMC
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       11.06.15
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
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
