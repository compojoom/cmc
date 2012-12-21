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


class plgSystemECom360_redshop extends JPlugin {

    /**
     * @param $row
     * @param $info
     */

    public function afterOrderPlace($cart,$orderresult){

        $this->notifyMC($cart, $orderresult);
    }

    public function notifyMC($cart, $orderresult, $type = "new") {
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

        echo "MC_EID: " . $mc_eid . "<br />";
        echo "MC_CID: " . $mc_eid;

        /**
         * ["idx"]=> int(1) [0]=> array(17) {
         * ["hidden_attribute_cartimage"]=> string(0) "" ["product_price_excl_vat"]=> float(12)
         * ["subscription_id"]=> int(0) ["product_vat"]=> float(0) ["giftcard_id"]=> string(0) "" ["product_id"]=> string(1) "1"
         * ["discount_calc_output"]=> string(0) "" ["discount_calc"]=> array(0) { } ["product_price"]=> float(12) ["product_old_price"]=> float(12)
         * ["product_old_price_excl_vat"]=> int(12) ["cart_attribute"]=> array(0) { } ["cart_accessory"]=> array(0) { } ["quantity"]=> string(1) "2"
         * ["category_id"]=> string(1) "1" ["wrapper_id"]=> string(1) "0" ["wrapper_price"]=> int(0)
         * }
         *
         */
        foreach($cart as $prod) {

            // TODO Add query for product name
            $product_name = "";
            $category_name = "";

            $products = array( 0 => array(
                "product_id" => $prod['product_id'], "sku" => "", "product_name" => $product_name,
                "category_id" => $prod['category_id'], "category_name" => $category_name, "qty" => $prod['product_id'],         // No category id, qty always 1
                "cost" => ['product_price']
                )
            );
        }

        /**
         * ["notice_message"]=> string(0) ""
         * ["discount_type"]=> int(0) ["discount"]=> int(0) ["cart_discount"]=> int(0) ["user_shopper_group_id"]=> string(1) "1"
         * ["free_shipping"]=> int(0) ["product_subtotal"]=> float(24) ["product_subtotal_excl_vat"]=> float(24) ["voucher_discount"]=> int(0)
         * ["coupon_discount"]=> int(0) ["total"]=> float(24) ["subtotal"]=> float(24) ["subtotal_excl_vat"]=> float(24) ["tax"]=> float(0)
         * ["sub_total_vat"]=> float(0) ["discount_vat"]=> float(0) ["shipping_tax"]=> string(1) "0" ["discount_ex_vat"]=> float(0)
         * ["mod_cart_total"]=> float(24) ["user_id"]=> string(2) "59" ["shipping"]=> string(4) "0.00" ["shipping_vat"]=> string(1) "0"
         * ["payment_oprand"]=> string(1) "-" ["payment_amount"]=> int(0)
         */
        CmcHelperEcom360::sendOrderInformations($api_key, $mc_cid, $mc_eid, $shop_id, $shop_name, $orderresult['order_id'],
            $cart["total"], $cart["tax"], $cart["shipping"], $products // No shipping
        );
    }
}