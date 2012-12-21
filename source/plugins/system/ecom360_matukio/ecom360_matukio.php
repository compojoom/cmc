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


class plgSystemECom360_matukio extends JPlugin {





    /**
     *
     * ('onAfterBooking', $neu, $event)
     */

    public function onAfterBooking($neu, $event){
        $this->notifyMC($neu,$event);
    }

    function notifyMC($row, $event) {
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
         * ($api_key, $mc_cid, $mc_eid, $store_id, $store_name = "Store name", $order_id = 0, $total_amount = 0,
         * $tax_amount = 0, $shipping_amount = 0,
         * $products = array(0 => array("product_id" => 0, "sku" => "", "product_name" => "", "category_id" => 0, "category_name" => "", "qty" => 1.00, "cost" => 0.00))
         */

        $db = JFactory::getDbo();
        $sql = "SELECT * FROM #__categories WHERE id = " . $event->catid;

        $db->setQuery($sql);
        $cat = $db->loadObject();

        $products = array( 0 => array(
            "product_id" => $event->id, "sku" => $event->semnum, "product_name" => $event->title,
            "category_id" => $event->catid, "category_name" => $cat->title, "qty" => $row->nrbooked,
            "cost" =>  $event->fee
            )
        );

        CmcHelperEcom360::sendOrderInformations($api_key, $mc_cid, $mc_eid, $shop_id, $shop_name, $row->id, $row->payment_brutto,
            $row->payment_tax, 0.00, $products // No shipping
        );
    }
}