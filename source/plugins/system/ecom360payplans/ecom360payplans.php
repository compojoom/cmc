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
 * Class plgSystemECom360Payplans
 *
 * @since  1.3
 */
class plgSystemECom360Payplans extends JPlugin
{

	/**
	 * Notify Mailchimp only when the subscription has changed
	 *
	 * @param   object   $prev  Previous object
	 * @param   string   $new   The new object
	 *
	 * @return  bool
	 *
	 * @since   1.3.0
	 */
	public function onPayplansPaymentAfterSave($prev, $new)
	{
		$app = JFactory::getApplication();

		// This plugin is only intended for the frontend
		if ($app->isAdmin())
		{
			return true;
		}

		$this->notifyMC($new);

		return true;
	}

	/**
	 * Notify MailChimp API
	 *
	 * @param   object  $data  Te payment data
	 *
	 * @return  boolean  true on success
	 *
	 * @since   1.3.0
	 */
	public function notifyMC($data)
	{
		$session = JFactory::getSession();

		// Trigger plugin only if user comes from Mailchimp
		if (!$session->get('mc', '0'))
		{
			return;
		}

		// $chimp = new CmcHelperChimp;
		$price = (float) $data->amount;

		$user = JFactory::getUser($data->user_id);

		$customerNames = explode(' ', $user->name);

		$plan = $this->get($data->app_id);

		// Array with producs
		$products = array(
			0 => array(
				'id' => (string) $data->payment_id,
				'product_id'  => $data->app_id,
				'title' => $plan->title,
				'product_variant_id' => (string)  $data->app_id,
				'product_variant_title' => $plan->title,
				'quantity' => (int) 1,
				'price'        => (float) $price,
				'type' => 'subscription'
			)
		);

		// The shop data
		$shop = new stdClass;
		$shop->id = $this->params->get('store_id', 42);
		$shop->name = $this->params->get('store_name', 'PayPlans store');
		$shop->list_id = $this->params->get('list_id');
		$shop->currency_code = $data->currency;

		// The customer data
		$customer = new stdClass();
		$customer->id = md5($user->email);
		$customer->email_address = $user->email;
		$customer->opt_in_status = false;
		$customer->first_name = isset($customerNames[0]) ? $customerNames[0] : '';
		$customer->last_name = isset($customerNames[1]) ? $customerNames[1] : '';

		// The order data
		$order = new stdClass;
		$order->id = $data->payment_id;
		$order->currency_code = $data->currency;
		$order->payment_tax = (double) 0;
		$order->order_total = (double) $price;
		$order->processed_at_foreign = JFactory::getDate($data->created_data->date)->toSql();

		$chimp = new CmcHelperChimp;

		// Now send all this to Mailchimp
		return $chimp->addEcomOrder(
			$session->get('mc_cid', '0'),
			$shop,
			$order,
			$products,
			$customer
		);
	}

	/**
	 * Get the payplan
	 *
	 * @param   int  $id  The id
	 *
	 * @return  mixed
	 *
	 * @since   3.0.0
	 */
	protected function getPayplan($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('*')
			->from('#__payplans_app')
			->where('app_id = ' . $db->q('id'));

		$db->setQuery($query);

		return $db->loadObject();
	}
}
