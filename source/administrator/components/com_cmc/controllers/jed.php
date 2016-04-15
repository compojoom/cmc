<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * The updates provisioning Controller
 *
 * @since  4.0
 */
class CmcControllerJed extends CompojoomControllerJed
{
	protected $component = 'com_cmc';

	protected $isPro = 0;

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 *
	 * @since   12.2
	 */
	public function __construct($config = array())
	{
		$url = 'http://extensions.joomla.org/extensions/extension/marketing/mailing-a-newsletter-bridges/cmc';

		$this->data = array(
				'component' => $this->component,
				'title' => 'CMC - Mailchimp for Joomla!',
				'jed_url' => $url
		);

		parent::__construct();
	}
}
