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


class plgSystemECom360_redshop extends JPlugin {

	/**
	 * @param $cart
	 * @param $orderresult
	 * @return void
	 * @internal param $row
	 * @internal param $info
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

        $mc_cid = $session->get('mc_cid', '');
        $mc_eid = $session->get('mc_eid', '');

		$shop_name = $this->params->get("store_name", "Your shop");
		$shop_id = $this->params->get("store_id", 42);

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

        CmcHelperEcom360::sendOrderInformations($mc_cid, $mc_eid, $shop_id, $shop_name, $orderresult['order_id'],
            $cart["total"], $cart["tax"], $cart["shipping"], $products // No shipping
        );
    }
}