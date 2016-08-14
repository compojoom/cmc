<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

JLoader::discover('CmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

/**
 * Class plgSystemECom360Matukio
 *
 * @since  1.3
 */
class plgSystemECom360Matukio extends JPlugin
{
	/**
	 *
	 * ('onAfterBooking', $neu, $event)
	 */
	public function onAfterBookingSave($context, $neu, $event)
	{
		if($context != 'com_matukio.book')
		{
			return;
		}

		$app = JFactory::getApplication();

		// This plugin is only intended for the frontend
		if ($app->isAdmin())
		{
			return true;
		}

		$this->notifyMC($neu, $event);
	}

	/**
	 * Track the booking with Mailchimp
	 *
	 * @param   object  $row   - the booking object
	 * @param   object  $event - the event object
	 *
	 * @return array|false|void
	 */
	private function notifyMC($row, $event)
	{
		$session = JFactory::getSession();

		// Trigger plugin only if user comes from Mailchimp
		if (!$session->get('mc', '0'))
		{
			return;
		}

		$chimp = new CmcHelperChimp;
		$price = (float) $row->payment_brutto;

		$customerNames = explode(' ', $row->name);

		// Array with producs
		$products = array(
			0 => array(
				'id' => (string) $row->id,
				'product_id'  => $event->id,
				'title' => $event->title,
				'product_variant_id' => (string)  $event->id,
				'product_variant_title' => $event->title,
				'quantity' => (int) $row->nrbooked,
				'price'        => (float) $price,
				'published_at_foreign' => $event->publishdate,
				'description' => $event->description,
				'type' => 'event'
			)
		);

		// The shop data
		$shop = new stdClass;
		$shop->id = $this->params->get("store_id", 42);
		$shop->name = $this->params->get('store_name', 'Matukio store');
		$shop->list_id = $this->params->get('list_id');
		$shop->currency_code = $this->params->get('currency_code', 'EUR');

		// The customer data
		$customer = new stdClass();
		$customer->id = md5($row->email);
		$customer->email_address = $row->email;
		$customer->opt_in_status = false;
		$customer->first_name = isset($customerNames[0]) ? $customerNames[0] : '';
		$customer->last_name = isset($customerNames[1]) ? $customerNames[1] : '';

		// The order data
		$order = new stdClass;
		$order->id = $row->id;
		$order->currency_code = $event->payment_code;
		$order->payment_tax = (double) $row->payment_tax;
		$order->order_total = (double) $price;
		$order->processed_at_foreign = $row->bookingdate;

		// Now send all this to Mailchimp
		return $chimp->addEcomOrder(
			$session->get('mc_cid', '0'),
			$shop,
			$order,
			$products,
			$customer
		);
	}
}
