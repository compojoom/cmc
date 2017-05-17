<?php

defined('_JEXEC') or die('Restricted access');

/**
 * Class CmcMailChimpOrder
 *
 * @since  __DEPLOY_VERSION__
 */
class CmcMailChimpOrder
{
	/**
	 * The order id (e.g. order_shop_123)
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $id;

	/**
	 * The customer
	 *
	 * @var     CmcMailChimpCustomer
	 * @since  __DEPLOY_VERSION__
	 */
	public $customer;

	/**
	 * Campaign Id (optional)
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $campaign_id = '';

	/**
	 * Currency code
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $currency_code;

	/**
	 * Total value
	 *
	 * @var    double
	 * @since  __DEPLOY_VERSION__
	 */
	public $order_total;

	/**
	 * The order items
	 *
	 * @var    array
	 * @since  __DEPLOY_VERSION__
	 */
	public $lines;

	/**
	 * Tracking code (optional)
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $tracking_code = '';

	/**
	 * Tax value (optional)
	 *
	 * @var    double
	 * @since  __DEPLOY_VERSION__
	 */
	public $tax_total = 0.00;

	/**
	 * Shipping value (optional)
	 *
	 * @var    double
	 * @since  __DEPLOY_VERSION__
	 */
	public $shipping_total = 0.00;

	/**
	 * Shipping address (optional)
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $shipping_address = '';

	/**
	 * Billing address (optional)
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $billing_address = '';
}