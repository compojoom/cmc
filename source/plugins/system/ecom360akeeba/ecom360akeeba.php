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

// get the cmcHelpers
JLoader::discover('CmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

/**
 * Class plgSystemECom360Akeeba
 *
 * @since  1.3
 */
class plgSystemECom360Akeeba extends JPlugin
{

	/**
	 * @param $row
	 * @param $info
	 *
	 * @return bool
	 */
	public function onAKSubscriptionChange($row, $info)
	{
		$app = JFactory::getApplication();

		// This plugin is only intended for the frontend
		if ($app->isAdmin())
		{
			return true;
		}

		if ($row->state == 'N' || $row->state == 'X')
			return;

		if (array_key_exists('state', (array) $info['modified']) && in_array($row->state, array('P', 'C')))
		{
			if ($row->enabled)
			{
				if (is_object($info['previous']) && $info['previous']->state == 'P')
				{
					// A pending subscription just got paid
					$this->notifyMC($row, $info);
				}
				else
				{
					// A new subscription just got paid; send new subscription notification
					$this->notifyMC($row, $info);
				}
			}
			elseif ($row->state == 'C')
			{
				if ($row->contact_flag <= 2)
				{
					// A new subscription which is for a renewal (will be active in a future date)
					$this->notifyMC($row, $info);
				}
			}
			else
			{
				// A new subscription which is pending payment by the processor
				$this->notifyMC($row, $info);
			}
		}

	}

	private function notifyMC($row, $info)
	{
		$session = JFactory::getSession();

		// Trigger plugin only if user comes from Mailchimp
		if (!$session->get('mc', '0'))
		{
			return;
		}

		$shop_name = $this->params->get("store_name", "Your shop");
		$shop_id = $this->params->get("store_id", 42);

		$akeebasubsLevel = FOFModel::getTmpInstance('Levels', 'AkeebasubsModel')->setId($row->akeebasubs_level_id)->getItem();

		$akeeba_subscription_name = $akeebasubsLevel->title;

		$products = array(0 => array(
			"product_id" => $info['current']->akeebasubs_level_id, "sku" => "", "product_name" => $akeeba_subscription_name,
			"category_id" => 0, "category_name" => "", "qty" => 1.00, // No category id, qty always 1
			"cost" => $info['current']->gross_amount
		)
		);

		CmcHelperEcom360::sendOrderInformations(
			$shop_id,
			$shop_name,
			$info['current']->akeebasubs_subscription_id,
			$info['current']->gross_amount,
			$info['current']->tax_percent,
			0.00, // No shipping
			$products
		);
	}
}
