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


class plgSystemECom360Redshop extends JPlugin
{
	/**
	 * @param $cart
	 * @param $orderresult
	 *
	 * @return void
	 * @internal param $row
	 * @internal param $info
	 */
	public function afterOrderPlace($cart, $orderresult)
	{

		$app = JFactory::getApplication();

		// This plugin is only intended for the frontend
		if ($app->isAdmin())
		{
			return true;
		}

		$this->notifyMC($cart, $orderresult);
	}

	/**
	 * @param        $cart
	 * @param        $orderresult
	 * @param string $type
	 *
	 * @return mixed
	 */
	public function notifyMC($cart, $orderresult, $type = "new")
	{
		$session = JFactory::getSession();

		// Trigger plugin only if user comes from Mailchimp
		if (!$session->get('mc', '0'))
		{
			return false;
		}

		$customer = $this->getCustomer($cart['user_id']);

		// The shop data
		$shop = new stdClass;
		$shop->id = $this->params->get("store_id", 42);;
		$shop->name = $this->params->get('store_name', 'Redshop store');
		$shop->list_id = $this->params->get('list_id');
		$shop->currency_code = $this->params->get('currency_code', 'EUR');

		$products = array();

		for ($i = 0; $i < $cart["idx"]; $i++)
		{
			$prod = $cart[$i];

			$prodInfo = $this->getProductInfo($prod['product_id']);

			$products[] = array(
				'id' => (string) $orderresult->order_id,
				"product_id" => (string) $prod['product_id'],
				'title' => $prodInfo->product_name,
				'product_variant_id' => (string)  $prod['product_id'],
				'product_variant_title' => $prodInfo->product_name,
				'price' => $prod['product_price'],
				'quantity' => (int) $prod['quantity'],
				'type' =>  $prodInfo->category_name
			);
		}

		// The order data
		$order = new stdClass;
		$order->id = $orderresult->order_id;
		$order->currency_code = $this->params->get('currency_code', 'EUR');
		$order->payment_tax = (double) $orderresult->order_tax;
		$order->order_total = (double) $orderresult->order_total;
		$order->processed_at_foreign = JFactory::getDate($orderresult->cdate)->toSql();

		$chimp = new CmcHelperChimp;

		return $chimp->addEcomOrder(
			$session->get('mc_cid', '0'),
			$shop,
			$order,
			$products,
			$customer
		);
	}

	/**
	 * the cart object doesn't have all the necessary info about the product, that is
	 * why we need to grab it ourselves
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	private function getProductInfo($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('p.product_name, c.category_name')->from('#__redshop_product AS p')
			->leftJoin('#__redshop_product_category_xref AS xref ON p.product_id = xref.product_id')
			->leftJoin('#__redshop_category as c ON c.category_id = xref.category_id')
			->where('p.product_id = ' . $db->q($id));

		$db->setQuery($query, 0, 1);

		return $db->loadObject();
	}

	/**
	 * Get the customer object
	 *
	 * @param   int  $id  - the joomla user object
	 *
	 * @return stdClass
	 */
	private function getCustomer($id)
	{
		$joomlaUser = JFactory::getUser($id);

		$user = new stdClass;
		$user->email_address = $joomlaUser->email;
		$name = explode(' ', $joomlaUser->name);
		$user->first_name = isset($name[0]) ? $name[0] : '';
		$user->last_name = isset($name[1]) ? $name[1] : '';

		$user->id = md5($joomlaUser->email);
		$user->opt_in_status = false;

		return $user;
	}
}
