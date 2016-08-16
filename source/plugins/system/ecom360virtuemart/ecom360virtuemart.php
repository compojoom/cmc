<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
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

		$customer = $this->getCustomer($cart->BT);

		// The shop data
		$shop = new stdClass;
		$shop->id = $this->params->get("store_id", 42);;
		$shop->name = $this->params->get('store_name', 'Virtuemart store');
		$shop->list_id = $this->params->get('list_id');
		$shop->currency_code = $this->params->get('currency_code', 'EUR');

		$products = array();

		foreach ($order['items'] as $item)
		{
			$products[] = array(
				'id' => (string) $item->virtuemart_order_item_id,
				"product_id" => (string) $item->virtuemart_product_id,
				'title' => $item->order_item_name,
				'product_variant_id' => (string)  $item->virtuemart_product_id,
				'product_variant_title' => $item->order_item_name,
				"quantity" => (int) $item->quantity,
				"price" => (double) $item->product_subtotal_with_tax
			);
		}

		// The order data
		$mOrder = new stdClass;
		$mOrder->id = (string) $order["details"]["BT"]->virtuemart_order_id;
		$mOrder->currency_code = $this->params->get('currency_code', 'EUR');
		$mOrder->payment_tax = (double) $order["details"]["BT"]->order_tax;
		$mOrder->order_total = (double) $order["details"]["BT"]->order_total;
		$mOrder->processed_at_foreign = JFactory::getDate($order->order_created)->toSql();


		$chimp = new CmcHelperChimp;

		return $chimp->addEcomOrder(
			$session->get('mc_cid', '0'),
			$shop,
			$mOrder,
			$products,
			$customer
		);
	}

	public function getCustomer($cartUser)
	{
		$user = new stdClass;
		$user->id = md5($cartUser['email']);
		$user->email_address = $cartUser['email'];
		$user->first_name = $cartUser['first_name'];
		$user->last_name = $cartUser['last_name'];
		$user->opt_in_status = false;

		return $user;
	}
}
