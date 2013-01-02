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

class plgSystemECom360_payplans extends JPlugin {

	/**
	 * Notify Mailchimp only when the subscription has changed
	 * @param $prev
	 * @param $new
	 * @return bool
	 */
	public function onPayplansSubscriptionAfterSave($prev, $new){
		// no need to trigger if previous and current state is same
		if($prev != null && $prev->getStatus() == $new->getStatus()){
			$this->notifyMC($new);
		}

		return true;
    }

    /**
     *
     * @param $data
     */
    public function notifyMC($data) {
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

		// with each order you can subscribe to only 1 subscription. But there is no getPlan function
		$plans = $data->getPlans();
		// get the invoice information - otherwise we have no tax information for the purchase
		$invoice = $data->getOrder(true)->getInvoice();

        $products = array( 0 => array(
            "product_id" => $plans[0], "sku" => $plans[0], "product_name" => $data->getTitle(),
            "qty" => 1,
            "cost" =>  $data->getPrice()
            )
        );

        CmcHelperEcom360::sendOrderInformations($mc_cid, $mc_eid, $shop_id,
			$shop_name, $data->getId(), $invoice->getTotal(),
            $invoice->getTaxAmount(), 0.00, $products // No shipping
        );
    }
}