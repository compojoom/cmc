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
	 * @return bool
	 */
	public function onAfterOrderCreate($order,$send_email){
		$app = JFactory::getApplication();

		// This plugin is only intended for the frontend
		if ($app->isAdmin()) {
			return true;
		}

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
        // Trigger plugin only if user comes from Mailchimp
        if(!$session->get( 'mc', '0' )) {
            return;
        }

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

        CmcHelperEcom360::sendOrderInformations(
			$shop_id,
			$shop_name,
			$order->order_id,
			$order->cart->full_total->prices[0]->price_value_with_tax,
            ($order->cart->full_total->prices[0]->price_value_with_tax - $order->cart->full_total->prices[0]->price_value),
			$shipping,
			$products
        );
    }
}