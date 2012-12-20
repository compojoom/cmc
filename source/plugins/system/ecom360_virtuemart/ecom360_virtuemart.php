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

// import libaries
jimport('joomla.event.plugin');

JLoader::discover('CmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');   // Hmm not working?



class plgSystemECom360_virtuemart extends JPlugin {
    /**
     * array(4) {
         * ["details"]=> array(1) {
             * ["BT"]=> object(stdClass)#717 (53) {
                 * ["virtuemart_order_userinfo_id"]=> string(1) "3"
                 * ["virtuemart_order_id"]=> string(1) "3" ["virtuemart_user_id"]=> string(1) "0" ["address_type"]=> string(2) "BT" ["address_type_name"]=> NULL
                 * ["company"]=> string(4) "test" ["title"]=> string(2) "Mr" ["last_name"]=> string(6) "Tester" ["first_name"]=> string(6) "Markus"
                 * ["middle_name"]=> NULL ["phone_1"]=> string(12) "498932208134" ["phone_2"]=> string(12) "498932208134" ["fax"]=> NULL
                 * ["address_1"]=> string(11) "teststr. 23" ["address_2"]=> string(11) "teststr. 23" ["city"]=> string(10) "Testhausen"
                 * ["virtuemart_state_id"]=> string(1) "0" ["virtuemart_country_id"]=> string(3) "223" ["zip"]=> string(5) "18317"
                 * ["email"]=> string(14) "test@vicube.de" ["agreed"]=> string(1) "1" ["created_on"]=> string(19) "2012-12-20 10:48:37"
                 * ["created_by"]=> string(1) "0" ["modified_on"]=> string(19) "2012-12-20 10:48:37" ["modified_by"]=> string(1) "0"
                 * ["locked_on"]=> string(19) "0000-00-00 00:00:00" ["locked_by"]=> string(1) "0" ["virtuemart_vendor_id"]=> string(1) "1"
                 * ["order_number"]=> string(6) "00d405" ["order_pass"]=> string(7) "p_a3fe2" ["order_total"]=> string(9) "136.38000"
                 * ["order_salesPrice"]=> string(9) "136.38000" ["order_billTaxAmount"]=> string(8) "23.64000"
                 * ["order_billDiscountAmount"]=> string(7) "0.00000" ["order_discountAmount"]=> string(7) "0.00000"
                 * ["order_subtotal"]=> string(9) "112.74000" ["order_tax"]=> string(8) "23.64000" ["order_shipment"]=> string(4) "0.00"
                 * ["order_shipment_tax"]=> string(7) "0.00000" ["order_payment"]=> string(4) "0.00" ["order_payment_tax"]=> string(7) "0.00000"
                 * ["coupon_discount"]=> string(4) "0.00" ["coupon_code"]=> NULL ["order_discount"]=> string(4) "0.00" ["order_currency"]=> string(2) "47"
                 * ["order_status"]=> string(1) "P" ["user_currency_id"]=> string(2) "47" ["user_currency_rate"]=> string(7) "1.00000"
                 * ["virtuemart_paymentmethod_id"]=> string(1) "1" ["virtuemart_shipmentmethod_id"]=> string(1) "1" ["customer_note"]=> string(0) ""
                 * ["ip_address"]=> string(3) "::1" ["order_status_name"]=> string(7) "Pending"
             * }
         * }
     * ["history"]=> array(1)
         * { [0]=> object(stdClass)#718 (12)
             * { ["virtuemart_order_history_id"]=> string(1) "4" ["virtuemart_order_id"]=> string(1) "3" ["order_status_code"]=> string(1) "P"
             * ["customer_notified"]=> string(1) "0" ["comments"]=> string(0) "" ["published"]=> string(1) "1"
             * ["created_on"]=> string(19) "2012-12-20 10:48:37" ["created_by"]=> string(1) "0"
             * ["modified_on"]=> string(19) "2012-12-20 10:48:37" ["modified_by"]=> string(1) "0"
             * ["locked_on"]=> string(19) "0000-00-00 00:00:00" ["locked_by"]=> string(1) "0"
             * }
         * }
     * ["items"]=> array(1) {
         * [0]=> object(stdClass)#716 (15) {
             * ["virtuemart_order_item_id"]=> string(1) "3" [
             * "product_quantity"]=> string(1) "6" ["order_item_name"]=> string(8) "Nice Saw" ["order_item_sku"]=> string(3) "H01"
             * ["virtuemart_product_id"]=> string(1) "5" ["product_item_price"]=> string(8) "18.78665" ["product_final_price"]=> string(8)
             * "22.73000" ["product_basePriceWithTax"]=> string(8) "22.73000" ["product_subtotal_with_tax"]=> string(9) "136.38000"
             * ["product_subtotal_discount"]=> string(7) "0.00000" ["product_tax"]=> string(7) "3.94000" ["product_attribute"]=> NULL
             * ["order_status"]=> string(1) "P" ["intnotes"]=> string(0) "" ["virtuemart_category_id"]=> string(1) "1"
             * }
         * }
     * ["calc_rules"]=> array(3)
     * {
         * [0]=> object(stdClass)#715 (17) {
             * ["virtuemart_order_calc_rule_id"]=> string(1) "7" ["virtuemart_order_id"]=> string(1) "3"
             * ["virtuemart_vendor_id"]=> string(1) "1" ["virtuemart_order_item_id"]=> string(1) "3" ["calc_rule_name"]=> string(7) "Tax 21%"
             * ["calc_kind"]=> string(6) "VatTax" ["calc_mathop"]=> string(2) "+%" ["calc_amount"]=> string(7) "0.00000" ["calc_value"]=> string(8) "21.00000"
             * ["calc_currency"]=> string(2) "47" ["calc_params"]=> NULL ["created_on"]=> string(19) "2012-12-20 10:48:37" ["created_by"]=> string(1) "0"
             * ["modified_on"]=> string(19) "2012-12-20 10:48:37" ["modified_by"]=> string(1) "0" ["locked_on"]=> string(19) "0000-00-00 00:00:00"
             * ["locked_by"]=> string(1) "0"
         * }
         * [1]=> object(stdClass)#713 (17) {
             * ["virtuemart_order_calc_rule_id"]=> string(1) "8" ["virtuemart_order_id"]=> s
             * tring(1) "3" ["virtuemart_vendor_id"]=> string(1) "1" ["virtuemart_order_item_id"]=> NULL ["calc_rule_name"]=> string(0) "" ["calc_kind"]=> string(7)
             * "payment" ["calc_mathop"]=> string(0) "" ["calc_amount"]=> string(7) "0.00000" ["calc_value"]=> string(7) "0.00000" ["calc_currency"]=> string(1) "0"
             * ["calc_params"]=> string(0) "" ["created_on"]=> string(19) "2012-12-20 10:48:37" ["created_by"]=> string(1) "0"
             * ["modified_on"]=> string(19) "2012-12-20 10:48:37" ["modified_by"]=> string(1) "0" ["locked_on"]=> string(19) "0000-00-00 00:00:00"
                 * ["locked_by"]=> string(1) "0" } [2]=> object(stdClass)#711 (17) {
                     * ["virtuemart_order_calc_rule_id"]=> string(1) "9"
                     * ["virtuemart_order_id"]=> string(1) "3" ["virtuemart_vendor_id"]=> string(1) "1" ["virtuemart_order_item_id"]=> NULL
                     * ["calc_rule_name"]=> string(0) "" ["calc_kind"]=> string(8) "shipment" ["calc_mathop"]=> string(0) "" ["calc_amount"]=> string(7) "0.00000"
                     * ["calc_value"]=> string(7) "0.00000" ["calc_currency"]=> string(1) "0" ["calc_params"]=> string(0) "" ["created_on"]=> string(19)
                     * "2012-12-20 10:48:37" ["created_by"]=> string(1) "0" ["modified_on"]=> string(19) "2012-12-20 10:48:37" ["modified_by"]=> string(1) "0"
                     * ["locked_on"]=> string(19) "0000-00-00 00:00:00" ["locked_by"]=> string(1) "0"
                 * }
             * }
     * } asdf
     */

    /**
     * @param $cart
     * @param $order
     */
    public function plgVmConfirmedOrder($cart, $order){

        $session = JFactory::getSession();
        $mc = $session->get( 'mc', '0' );

        // Trigger plugin only if user comes from Mailchimp
        if(!$mc) {
            return;
        }

        echo JPATH_ADMINISTRATOR . 'components/com_cmc/helpers/';

        $mc_cid = $session->get('mc_cid', '');
        $mc_eid = $session->get('mc_eid', '');

        $params = JComponentHelper::getParams('com_cmc');
        $api_key = $params->get("api_key", '');
        $shop_name = $params->get("shop_name", "Your shop");
        $shop_id = $params->get("shop_id", 42);

        /**
         * ($api_key, $mc_cid, $mc_eid, $store_id, $store_name = "Store name", $order_id = 0, $total_amount = 0,
         * $tax_amount = 0, $shipping_amount = 0,
         * $products = array(0 => array("product_id" => 0, "sku" => "", "product_name" => "", "category_id" => 0, "category_name" => "", "qty" => 1.00, "cost" => 0.00))
         */

        $products = array();

        /**
         * ["items"]=> array(1) {
         * [0]=> object(stdClass)#716 (15) {
         * ["virtuemart_order_item_id"]=> string(1) "3" [
         * "product_quantity"]=> string(1) "6" ["order_item_name"]=> string(8) "Nice Saw" ["order_item_sku"]=> string(3) "H01"
         * ["virtuemart_product_id"]=> string(1) "5" ["product_item_price"]=> string(8) "18.78665" ["product_final_price"]=> string(8)
         * "22.73000" ["product_basePriceWithTax"]=> string(8) "22.73000" ["product_subtotal_with_tax"]=> string(9) "136.38000"
         * ["product_subtotal_discount"]=> string(7) "0.00000" ["product_tax"]=> string(7) "3.94000" ["product_attribute"]=> NULL
         * ["order_status"]=> string(1) "P" ["intnotes"]=> string(0) "" ["virtuemart_category_id"]=> string(1) "1"
         * }
         * }
         */
        foreach ($order['items'] as $item) {
            $products[] = array(
                "product_id" => $item->virtuemart_product_id, "sku" => $item->order_item_sku, "product_name" => $item->order_item_name,
                "category_id" => $item->virtuemart_category_id, "category_name" => "", "qty" => (double) $item->product_quantity,
                "cost" =>  $item->product_final_price
            );
        }

        CmcHelperEcom360::sendOrderInformations($api_key, $mc_cid, $mc_eid, $shop_id, $shop_name, $order["details"]["BT"]->virtuemart_order_id, $order["details"]["BT"]->order_total,
            $order["details"]["BT"]->order_tax, $order["details"]["BT"]->order_shipment, $products
        );
    }
}