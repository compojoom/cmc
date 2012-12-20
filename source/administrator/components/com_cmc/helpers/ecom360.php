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

class CmcHelperEcom360
{
    private static $instance;

    public static function sendOrderInformations($api_key, $mc_cid, $mc_eid, $store_id, $store_name = "Store name", $order_id = 0, $total_amount = 0,
           $tax_amount = 0, $shipping_amount = 0,
           $products = array(0 => array("product_id" => 0, "sku" => "", "product_name" => "", "category_id" => 0, "category_name" => "", "qty" => 1.00, "cost" => 0.00))
    ){

           $order = array(
               "id" => $order_id,
               "email_id" => $mc_eid,
               //"email" => not needed - mc_eid
               "total" => (double) $total_amount,
               //"order_date" => $oeder_date,   // Should be always today, so not necessary
               "shipping" => (double) $shipping_amount,
               "tax" => (double) $tax_amount,
               "store_id" => $store_id,
               "store_name" => $store_name,
               "campaign_id" => $mc_cid, // Optional
               "items" => $products
           );

           $api = new MCAPI($api_key);

           $success = $api->ecommOrderAdd($api, $order);

            if ($api->errorCode){
                JError::raiseError(500, JTEXT::_("COM_CMC_UNSUBSCRIBE_FAILED")) . " " .$api->errorCode . " / " . $api->errorMessage;
            } else {
                return true;
            }
    }

}