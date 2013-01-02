<?php
/**
 * Cmc - Helper
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/

defined('_JEXEC') or die('Restricted access');

JLoader::register('cmcHelperChimp', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/chimp.php');


class CmcHelperEcom360
{
    private static $instance;

	/**
	 * Sends the tracking information to mailchimp if we have the tracking ids
	 * Logs errors
	 * @param $store_id
	 * @param string $store_name
	 * @param int $order_id
	 * @param int $total_amount
	 * @param int $tax_amount
	 * @param int $shipping_amount
	 * @param array $products
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
							"product_id" => 0,
							"sku" => "",
							"product_name" => "",
							"category_id" => 0,
							"category_name" => "",
							"qty" => 1.00,
							"cost" => 0.00
						)
					)
	){

		// log the errors to a file
		JLog::addLogger(array(
			'text_file' => 'com_cmc_ecom360.php'
		));
		$session = JFactory::getSession();
		$mc_cid = $session->get('mc_cid', '');
		$mc_eid = $session->get('mc_eid', '');

		if(!$mc_cid && !$mc_eid) {
			JLog::add('No cid and eid specified for the request', JLOG::ERROR);
			return false;
		}

        $order = array();

        $order["id"] = $order_id;
        $order["email_id"] = $mc_eid;
        $order["total"] = (double)$total_amount;
        $order["shipping"] = (double)$shipping_amount;
        $order["tax"] = (double)$tax_amount;
        $order["store_id"] = $store_id;
        $order["store_name"] = $store_name;
        $order["campaign_id"] = $mc_cid; // Optional
        $order["items"] = $products;

        $api = new cmcHelperChimp();
        $api->ecommOrderAdd($order);

        if ($api->errorCode) {
			JLog::add($api->errorMessage, JLOG::ERROR, $api->errorCode);
			return false;
        }

		return true;
    }

}