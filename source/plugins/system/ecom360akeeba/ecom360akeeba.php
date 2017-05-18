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

// get the cmcHelpers
JLoader::discover('CmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

/**
 * Class plgSystemECom360Akeeba
 *
 * @since  1.3
 */
class plgSystemECom360Akeeba extends JPlugin
{

	/**
	 * @param $row
	 * @param $info
	 *
	 * @return bool
	 */
	public function onAKSubscriptionChange($row, $info)
	{
		$app = JFactory::getApplication();

		// This plugin is only intended for the frontend
		if ($app->isAdmin())
		{
			return true;
		}

		if ($row->state == 'N' || $row->state == 'X')
		{
			return;
		}

		if (array_key_exists('state', (array) $info['modified']) && in_array($row->state, array('P', 'C')))
		{
			if ($row->enabled)
			{
				if (is_object($info['previous']) && $info['previous']->state == 'P')
				{
					// A pending subscription just got paid
					$this->notifyMC($row, $info);
				}
				else
				{
					// A new subscription just got paid; send new subscription notification
					$this->notifyMC($row, $info);
				}
			}
			elseif ($row->state == 'C')
			{
				if ($row->contact_flag <= 2)
				{
					// A new subscription which is for a renewal (will be active in a future date)
					$this->notifyMC($row, $info);
				}
			}
			else
			{
				// A new subscription which is pending payment by the processor
				$this->notifyMC($row, $info);
			}
		}

	}

	private function notifyMC($row, $info)
	{
		$session = JFactory::getSession();

		// Trigger plugin only if user comes from Mailchimp
		if (!$session->get('mc', '0'))
		{
			return;
		}

		// The shop data
		$shop = new stdClass;
		$shop->id = $this->params->get("store_id", 42);
		$shop->name = $this->params->get('store_name', 'Akeeba store');
		$shop->list_id = $this->params->get('list_id');
		$shop->currency_code = $this->params->get('currency_code', 'EUR');

		$akeebasubsLevel = FOFModel::getTmpInstance('Levels', 'AkeebasubsModel')->setId($row->akeebasubs_level_id)->getItem();

		$customer = $this->getCustomer($row->user_id);

		$products = array(
			0 => array(
				'id' => (string) $row->getId(),
				"product_id" => (string) $row->akeebasubs_level_id,
				'title' => $akeebasubsLevel->title,
				'product_variant_id' => (string)  $row->akeebasubs_level_id,
				'product_variant_title' => $akeebasubsLevel->title,
				'quantity' => 1,
				'price' => $row->gross_amount,
				'published_at_foreign' => $row->publish_up,
				'description' => $akeebasubsLevel->description,
				'type' => 'subscription'
			)
		);

		// The order data
		$order = new stdClass;
		$order->id = $row->getId();
		$order->currency_code = JComponentHelper::getParams('com_akeebasubs')->get('currency', 'EUR');
		$order->payment_tax = (double) $row->tax_amount;
		$order->order_total = (double) $row->gross_amount;
		$order->processed_at_foreign = $row->created_on;

		$chimp = new CmcHelperChimp;

		return $chimp->addEcomOrder(
			$session->get('mc_cid', '0'),
			$shop,
			$order,
			$products,
			$customer
		);
	}

	private function getCustomer($id)
	{
		$joomlaUser = JFactory::getUser($id);

		$user = new stdClass;
		$user->email_address = $joomlaUser->email;
		$name = explode(' ', $joomlaUser->name);
		$user->first_name = isset($name[0]) ? $name[0] : '';
		$user->last_name = isset($name[1]) ? $name[1] : '';

		$user->id = md5($joomlaUser->email);
		$user->opt_in_status = false;

		return $user;
	}
}
