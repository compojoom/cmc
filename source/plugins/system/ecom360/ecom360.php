<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

JLoader::discover('cmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

/**
 * Class plgSystemECom360
 *
 * @since  1.3
 */
class plgSystemECom360 extends JPlugin
{

	/**
	 * Sets the mc_cid & mc_eid session variables if the user is comming from mailchimp to the page
	 * @return bool
	 */
	public function onAfterDispatch()
	{
		$app = JFactory::getApplication();

		// This plugin is only intended for the frontend
		if ($app->isAdmin())
		{
			return true;
		}

		$doc = JFactory::getDocument();

		// This plugin is only for html, really?
		if ($doc->getType() != 'html')
		{
			return true;
		}

		$cid = JFactory::getApplication()->input->get('mc_cid', ''); // a string, no int!
		$eid = JFactory::getApplication()->input->get('mc_eid', '');

		// User comes from MC, cid is optional so just test for eid
		if (!empty($eid))
		{
			$session = JFactory::getSession();
			$session->set('mc', '1');
			$session->set('mc_cid', $cid);
			$session->set('mc_eid', $eid);
		}

		return true;
	}
}
