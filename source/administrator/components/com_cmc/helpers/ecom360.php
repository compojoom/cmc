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

JLoader::register('MCAPI', JPATH_ADMINISTRATOR . '/components/com_cmc/libraries/mailchimp/MCAPI.class.php');


class CmcHelperEcom360
{
    private static $instance;

    public static function sendOrderInformations($api_key, $mc_cid, $mc_eid, $store_id, $store_name = "Store name", $order_id = 0, $total_amount = 0,
                                                 $tax_amount = 0, $shipping_amount = 0,
                                                 $products = array(0 => array("product_id" => 0, "sku" => "", "product_name" => "", "category_id" => 0, "category_name" => "", "qty" => 1.00, "cost" => 0.00)))
    {

        $order = array();

        $order["id"] = $order_id;
        $order["email_id"] = $mc_eid;
        //$order["email"] =  not needed - mc_eid
        $order["total"] = (double)$total_amount;
        //$order["order_date"] =  $order_date;   // Should be always today; so not necessary
        $order["shipping"] = (double)$shipping_amount;
        $order["tax"] = (double)$tax_amount;
        $order["store_id"] = $store_id;
        $order["store_name"] = $store_name;
        $order["campaign_id"] = $mc_cid; // Optional
        $order["items"] = $products;

//        echo "<br><br>";
//        var_dump($order);
//        echo "<br><br>";

        $api = new MCAPI($api_key);

        $success = $api->ecommOrderAdd($order);

        if ($api->errorCode) {
            var_dump($api);
            die();
            JError::raiseError(500, JTEXT::_("COM_CMC_TRACKING_FAILED")) . " " . $api->errorCode . " / " . $api->errorMessage;
        } else {
            return true;
        }

    }

}