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
 * Class plgSystemECom360Hika
 *
 * @since  1.3
 */
class plgSystemECom360Hika extends JPlugin
{


	/**
	 * @param $order
	 * @param $send_email
	 *
	 * @return bool
	 */
	public function onAfterOrderCreate($order, $send_email)
	{
		$app = JFactory::getApplication();

		// This plugin is only intended for the frontend
		if ($app->isAdmin())
		{
			return true;
		}

		$this->notifyMC($order);
	}

	/**
	 *
	 * @param $order
	 *
	 * @return void
	 * @internal param $data
	 */
	private function notifyMC($order)
	{
		$session = JFactory::getSession();
		// Trigger plugin only if user comes from Mailchimp
		if (!$session->get('mc', '0'))
		{
			return;
		}

		$customer = $this->getCustomer($order->order_user_id);

		// No point in going further as we couldn't fetch the user data
		if(!$customer)
		{
			return;
		}

		// The shop data
		$shop = new stdClass;
		$shop->id = $this->params->get("store_id", 42);;
		$shop->name = $this->params->get('store_name', 'Hika store');
		$shop->list_id = $this->params->get('list_id');
		$shop->currency_code = $this->params->get('currency_code', 'EUR');

		$currencyInfo = unserialize($order->order_currency_info);
		$taxInfo = array_pop($order->order_tax_info);

		// The order data
		$mOrder = new stdClass;
		$mOrder->id = (string) $order->order_id;
		$mOrder->currency_code = $currencyInfo->currency_code;
		$mOrder->payment_tax = (double) $taxInfo->tax_amount;
		$mOrder->order_total = (double) $order->cart->full_total->prices[0]->price_value_with_tax;
		$mOrder->processed_at_foreign = JFactory::getDate($order->order_created)->toSql();

		// Products
		foreach ($order->cart->products as $product)
		{
			$products[] = array(
				'id' => (string) $product->order_id,
				"product_id" => (string) $product->product_id,
				'title' => $product->order_product_name,
				'product_variant_id' => (string)  $product->product_id,
				'product_variant_title' => $product->order_product_name,
				"quantity" => (int) $product->order_product_quantity,
				"price" => (double) ($product->order_product_price + $product->order_product_tax)
			);
		}

		$chimp = new CmcHelperChimp;

		// Now send all this to Mailchimp
		return $chimp->addEcomOrder(
			$session->get('mc_cid', '0'),
			$shop,
			$mOrder,
			$products,
			$customer
		);
	}

	private function getCustomer($id)
	{
		$user = new stdClass;
		$user->email_address = '';
		$user->first_name = '';
		$user->last_name = '';
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__hikashop_user')->where('user_id = ' . $db->q($id));

		$db->setQuery($query);

		$hikaUser = $db->loadObject();

		if (!$hikaUser)
		{
			return false;
		}

		$user->email_address = $hikaUser->user_email;
		if($hikaUser->user_cms_id)
		{
			$joomlaUser = JFactory::getUser($hikaUser->user_cms_id);

			$name = explode(' ', $joomlaUser->name);
			$user->first_name = isset($name[0]) ? $name[0] : '';
			$user->last_name = isset($name[1]) ? $name[1] : '';
		}

		$user->id = md5($user->email_address);
		$user->opt_in_status = false;

		return $user;
	}
}