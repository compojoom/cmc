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
 * Class plgSystemECom360Virtuemart
 *
 * @since  1.3
 */
class plgSystemECom360Virtuemart extends JPlugin
{
	/**
	 * @param $cart
	 * @param $order
	 *
	 * @return bool
	 */
	public function plgVmConfirmedOrder($cart, $order)
	{
		$app = JFactory::getApplication();

		// This plugin is only intended for the frontend
		if ($app->isAdmin())
		{
			return true;
		}

		$session = JFactory::getSession();

		// Trigger plugin only if user comes from Mailchimp
		if (!$session->get('mc', '0'))
		{
			return;
		}

		$shop_name = $this->params->get("store_name", "Your shop");
		$shop_id = $this->params->get("store_id", 42);

		$products = array();


		foreach ($order['items'] as $item)
		{
			$products[] = array(
				"product_id" => $item->virtuemart_product_id, "sku" => $item->order_item_sku, "product_name" => $item->order_item_name,
				"category_id" => $item->virtuemart_category_id, "category_name" => "", "qty" => (double) $item->product_quantity,
				"cost" => $item->product_final_price
			);
		}

		return CmcHelperEcom360::sendOrderInformations(
			$shop_id, $shop_name, $order["details"]["BT"]->virtuemart_order_id, $order["details"]["BT"]->order_total,
			$order["details"]["BT"]->order_tax, $order["details"]["BT"]->order_shipment, $products
		);
	}
}
