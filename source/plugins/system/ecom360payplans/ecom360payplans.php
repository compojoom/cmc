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

JLoader::discover('CmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

/**
 * Class plgSystemECom360Payplans
 *
 * @since  1.3
 */
class plgSystemECom360Payplans extends JPlugin
{

	/**
	 * Notify Mailchimp only when the subscription has changed
	 *
	 * @param $prev
	 * @param $new
	 *
	 * @return bool
	 */
	public function onPayplansSubscriptionAfterSave($prev, $new)
	{

		$app = JFactory::getApplication();

		// This plugin is only intended for the frontend
		if ($app->isAdmin())
		{
			return true;
		}

		// no need to trigger if previous and current state is same
		if ($prev != null && $prev->getStatus() == $new->getStatus())
		{
			$this->notifyMC($new);
		}

		return true;
	}

	/**
	 *
	 * @param $data
	 */
	public function notifyMC($data)
	{
		$session = JFactory::getSession();

		// Trigger plugin only if user comes from Mailchimp
		if (!$session->get('mc', '0'))
		{
			return;
		}

		$shop_name = $this->params->get("store_name", "Your shop");
		$shop_id = $this->params->get("store_id", 42);

		// with each order you can subscribe to only 1 subscription. But there is no getPlan function
		$plans = $data->getPlans();

		$total = $data->getPrice();
		$tax = 0;

		// get the invoice information - otherwise we have no tax information for the purchase
		$invoice = $data->getOrder(true)->getInvoice();
		if ($invoice)
		{
			$total = $invoice->getTotal();
			$tax = $invoice->getTaxAmount();
		}

		$products = array(0 => array(
			"product_id" => $plans[0], "sku" => $plans[0], "product_name" => $data->getTitle(),
			"qty" => 1,
			"cost" => $data->getPrice()
		)
		);

		CmcHelperEcom360::sendOrderInformations(
			$shop_id,
			$shop_name, $data->getId(), $total, $tax, 0.00, $products // No shipping
		);
	}
}