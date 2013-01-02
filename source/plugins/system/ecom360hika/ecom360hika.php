<?php
/**
 * Compojoom System Plugin
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JLoader::discover('CmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');


class plgSystemECom360Hika extends JPlugin {


	/**
	 * @param $order
	 * @param $send_email
	 */
	public function onAfterOrderCreate($order,$send_email){
        $this->notifyMC($order);
    }

	/**
	 *
	 * @param $order
	 * @return void
	 * @internal param $data
	 */
    function notifyMC($order) {
        $session = JFactory::getSession();
        $mc = $session->get( 'mc', '0' );

        // Trigger plugin only if user comes from Mailchimp
        if(!$mc) {
            return;
        }

        $mc_cid = $session->get('mc_cid', '');
        $mc_eid = $session->get('mc_eid', '');

		$shop_name = $this->params->get("store_name", "Your shop");
		$shop_id = $this->params->get("store_id", 42);


        foreach ($order->cart->products as $product) {
            $category = ""; // Todo query for it

            $products[] = array(
                "product_id" => $product->product_id, "sku" => "", "product_name" => $product->order_product_code,
                "category_id" => 0, "category_name" => $category, "qty" => $product->order_product_quantity,
                "cost" =>($product->order_product_price + $product->order_product_tax)
            );
        }

        $shipping = 0;

        if($order->order_shipping_price != null)
            $shipping = $order->order_shipping_price;

        CmcHelperEcom360::sendOrderInformations($mc_cid, $mc_eid, $shop_id, $shop_name, $order->order_id, $order->cart->full_total->prices[0]->price_value_with_tax,
            ($order->cart->full_total->prices[0]->price_value_with_tax - $order->cart->full_total->prices[0]->price_value), $shipping, $products
        );
    }
}