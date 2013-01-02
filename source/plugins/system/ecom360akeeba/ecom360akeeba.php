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

// get the cmcHelpers
JLoader::discover('CmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');


class plgSystemECom360Akeeba extends JPlugin {

    /**
     * @param $row
     * @param $info
     */
    public function onAKSubscriptionChange($row, $info){

        if($row->state == 'N' || $row->state == 'X')
            return;

        if(array_key_exists('state', (array)$info['modified']) && in_array($row->state, array('P','C'))) {
            if($row->enabled) {
                if(is_object($info['previous']) && $info['previous']->state == 'P') {
                    // A pending subscription just got paid
                    $this->notifyMC($row, $info);
                } else {
                    // A new subscription just got paid; send new subscription notification
                    $this->notifyMC($row, $info);
                }
            } elseif($row->state == 'C') {
                if($row->contact_flag <= 2) {
                    // A new subscription which is for a renewal (will be active in a future date)
                    $this->notifyMC($row, $info);
                }
            } else {
                // A new subscription which is pending payment by the processor
                $this->notifyMC($row, $info);
            }
        }

    }

    private function notifyMC($row, $info) {
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

        $akeebasubsLevel = FOFModel::getTmpInstance('Levels','AkeebasubsModel')->setId($row->akeebasubs_level_id)->getItem();

        $akeeba_subscription_name = $akeebasubsLevel->title;

        $products = array( 0 => array(
            "product_id" => $info['current']->akeebasubs_level_id, "sku" => "", "product_name" => $akeeba_subscription_name,
            "category_id" => 0, "category_name" => "", "qty" => 1.00,         // No category id, qty always 1
            "cost" =>  $info['current']->gross_amount
            )
        );

        CmcHelperEcom360::sendOrderInformations($mc_cid, $mc_eid, $shop_id, $shop_name, $info['current']->akeebasubs_subscription_id, $info['current']->gross_amount,
            $info['current']->tax_percent, 0.00, $products // No shipping
        );
    }
}