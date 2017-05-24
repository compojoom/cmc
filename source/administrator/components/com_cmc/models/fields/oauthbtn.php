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
JLoader::register('JFormFieldText', JPATH_LIBRARIES . '/joomla/form/fields/text.php');

/**
 * Class JFormFieldOauthbtn
 *
 * Button to authenticate with MC
 *
 * @since  __DEPLOY_VERSION__
 */
class JFormFieldOauthbtn extends JFormFieldText
{
	/**
	 * Type of the Form field
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $type = 'oauthbtn';

	/**
	 * Get the input
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getInput()
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_cmc/libraries/oauth/MC_OAuth2Client.php';

		$client = new MC_OAuth2Client();
		$url    = $client->getLoginUri();

		return parent::getInput() . ' <a href="' . $url . '" target="_blank" class="btn btn-primary">' . JText::_('COM_CMC_OAUTH2_AUTHENTIFICATION') . '</a>';
	}
}
