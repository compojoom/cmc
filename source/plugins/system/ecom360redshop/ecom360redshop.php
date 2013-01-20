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


class plgSystemECom360Redshop extends JPlugin {

	/**
	 * @param $cart
	 * @param $orderresult
	 * @return void
	 * @internal param $row
	 * @internal param $info
	 */
    public function afterOrderPlace($cart,$orderresult){

		$app = JFactory::getApplication();

		// This plugin is only intended for the frontend
		if ($app->isAdmin()) {
			return true;
		}

        $this->notifyMC($cart, $orderresult);
    }

    /*
      array(25) {
            [0]=> array(17) {
                     ["hidden_attribute_cartimage"]=> string(0) "" ["product_price_excl_vat"]=> float(12) ["subscription_id"]=> int(0)
                    ["product_vat"]=> float(0) ["giftcard_id"]=> string(0) "" ["product_id"]=> string(1) "1" ["discount_calc_output"]=> string(0) ""
                    ["discount_calc"]=> array(0) { } ["product_price"]=> float(12) ["product_old_price"]=> float(12) ["product_old_price_excl_vat"]=> int(12)
                    ["cart_attribute"]=> array(0) { } ["cart_accessory"]=> array(0) { } ["quantity"]=> string(1) "2" ["category_id"]=> string(1) "1"
                    ["wrapper_id"]=> string(1) "0" ["wrapper_price"]=> int(0)
                }

                ["notice_message"]=> string(0) "" ["discount_type"]=> int(0)
                ["discount"]=> int(0) ["cart_discount"]=> int(0) ["user_shopper_group_id"]=> string(1) "1" ["free_shipping"]=> int(0)
                ["product_subtotal"]=> float(24) ["product_subtotal_excl_vat"]=> float(24) ["voucher_discount"]=> int(0) ["coupon_discount"]=> int(0)
                ["total"]=> float(24) ["subtotal"]=> float(24) ["subtotal_excl_vat"]=> float(24) ["tax"]=> float(0) ["sub_total_vat"]=> float(0)
                ["discount_vat"]=> float(0) ["shipping_tax"]=> string(1) "0" ["discount_ex_vat"]=> float(0) ["mod_cart_total"]=> float(24)
                ["user_id"]=> string(2) "58" ["shipping"]=> string(4) "0.00" ["shipping_vat"]=> string(1) "0" ["payment_oprand"]=> string(1) "-"
                ["payment_amount"]=> int(0)
            }
     */

    /**
     * @param $cart
     * @param $orderresult
     * @param string $type
     * @return mixed
     */


    public function notifyMC($cart, $orderresult, $type = "new") {
        $session = JFactory::getSession();

        // Trigger plugin only if user comes from Mailchimp
        if(!$session->get( 'mc', '0' )) {
            return false;
        }

		$shop_name = $this->params->get("store_name", "Your shop");
		$shop_id = $this->params->get("store_id", 42);

        $products = array();


        for($i = 0; $i < $cart["idx"]; $i++) {
            $prod = $cart[$i];
//
//            var_dump($prod);
//
//            echo "PROD: " . $prod["product_id"];
//            die();

            // TODO Add query for product name
            $product_name = "redshop_product";
            $category_name = "";

            $products[] = array(
                "product_id" => $prod['product_id'], "sku" => "", "product_name" => $product_name,
                "category_id" => $prod['category_id'], "category_name" => $category_name, "qty" => $prod['quantity'],         // No category id, qty always 1
                "cost" => $prod['product_price']
            );
        }



        return CmcHelperEcom360::sendOrderInformations($shop_id, $shop_name, $orderresult->order_id,
            $cart['total'], $cart['tax'], $cart['shipping'], $products // No shipping
        );
    }
}