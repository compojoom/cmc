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

class plgSystemECom360_virtuemart extends JPlugin {

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

        $mc_cid = $session->get('mc_cid', '');
        $mc_eid = $session->get('mc_eid', '');

		$shop_name = $this->params->get("store_name", "Your shop");
		$shop_id = $this->params->get("store_id", 42);

        $products = array();


        foreach ($order['items'] as $item) {
            $products[] = array(
                "product_id" => $item->virtuemart_product_id, "sku" => $item->order_item_sku, "product_name" => $item->order_item_name,
                "category_id" => $item->virtuemart_category_id, "category_name" => "", "qty" => (double) $item->product_quantity,
                "cost" =>  $item->product_final_price
            );
        }

        CmcHelperEcom360::sendOrderInformations($mc_cid, $mc_eid, $shop_id, $shop_name, $order["details"]["BT"]->virtuemart_order_id, $order["details"]["BT"]->order_total,
            $order["details"]["BT"]->order_tax, $order["details"]["BT"]->order_shipment, $products
        );
    }
}