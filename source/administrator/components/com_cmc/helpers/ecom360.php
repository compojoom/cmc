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

JLoader::register('cmcHelperChimp', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/chimp.php');


class CmcHelperEcom360
{
	private static $instance;

	/**
	 * Sends the tracking information to mailchimp if we have the tracking ids
	 * Logs errors
	 *
	 * @param        $store_id
	 * @param string $store_name
	 * @param int    $order_id
	 * @param int    $total_amount
	 * @param int    $tax_amount
	 * @param int    $shipping_amount
	 * @param array  $products
	 *
	 * @return bool
	 */
	public static function sendOrderInformations(
		$store_id,
		$store_name = "Store name",
		$order_id = 0,
		$total_amount = 0,
		$tax_amount = 0,
		$shipping_amount = 0,
		$products = array(
			0 => array(
				"product_id"    => 0,
				"sku"           => "",
				"product_name"  => "",
				"category_id"   => 0,
				"category_name" => "",
				"qty"           => 1.00,
				"cost"          => 0.00
			)
		),
		$currency = 'EUR',
		$customer = null
	)
	{
		// Log the errors to a file
		JLog::addLogger(array(
			'text_file' => 'com_cmc_ecom360.php'
		));

		$session = JFactory::getSession();
		$mc_cid  = $session->get('mc_cid', '');
		$mc_eid  = $session->get('mc_eid', '');

		if (!$mc_cid && !$mc_eid)
		{
			JLog::add('No cid and eid specified for the request', JLOG::ERROR);

			return false;
		}

		$order = array();

		$order["id"]            = $order_id;
		$order["customer"]      = $customer;
		$order["currency_code"] = $currency;
		$order["order_total"]   = (double) $total_amount;
		$order["tax_total"]     = (double) $tax_amount;
		$order["store_id"]      = $store_id;
		$order["campaign_id"]   = $mc_cid; // Optional
		$order["lines"]         = $products;

		$api = new CmcHelperChimp;
		$api->ecommOrderAdd($order);

		if ($api->errorCode)
		{
			JLog::add($api->errorMessage, JLOG::ERROR, $api->errorCode);

			return false;
		}

		return true;
	}
}
