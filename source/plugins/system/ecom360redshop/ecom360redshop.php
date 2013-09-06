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

		$shop_name = $this->params->get("store_name", "Your shop");
		$shop_id = $this->params->get("store_id", 42);

		$products = array();


		for ($i = 0; $i < $cart["idx"]; $i++)
		{
			$prod = $cart[$i];

			$prodInfo = $this->getProductInfo($prod['product_id']);
			$product_name = $prodInfo->product_name;
			$category_name = $prodInfo->category_name;

			$products[] = array(
				"product_id" => $prod['product_id'], "sku" => "", "product_name" => $product_name,
				"category_id" => $prod['category_id'], "category_name" => $category_name, "qty" => $prod['quantity'], // No category id, qty always 1
				"cost" => $prod['product_price']
			);
		}


		return CmcHelperEcom360::sendOrderInformations(
			$shop_id, $shop_name, $orderresult->order_id,
			$cart['total'], $cart['tax'], $cart['shipping'], $products // No shipping
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
}
