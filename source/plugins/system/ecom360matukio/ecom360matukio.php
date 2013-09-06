<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JLoader::discover('CmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

/**
 * Class plgSystemECom360Matukio
 *
 * @since  1.3
 */
class plgSystemECom360Matukio extends JPlugin {


    /**
     *
     * ('onAfterBooking', $neu, $event)
     */

    public function onAfterBooking($neu, $event){
		$app = JFactory::getApplication();

		// This plugin is only intended for the frontend
		if ($app->isAdmin()) {
			return true;
		}

        $this->notifyMC($neu,$event);
    }

    private function notifyMC($row, $event) {
        $session = JFactory::getSession();
        // Trigger plugin only if user comes from Mailchimp
        if(!$session->get( 'mc', '0' )) {
            return;
        }

		$shop_name = $this->params->get("store_name", "Your shop");
		$shop_id = $this->params->get("store_id", 42);

		// get the cat information
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

        CmcHelperEcom360::sendOrderInformations(
												$shop_id,
												$shop_name,
												$row->id,
												$row->payment_brutto,
												$row->payment_tax,
												0.00,  // No shipping
												$products
        );
    }
}