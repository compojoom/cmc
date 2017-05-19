<?php

defined('_JEXEC') or die('Restricted access');

/**
 * Class CmcMailChimpLine
 *
 * @since  __DEPLOY_VERSION__
 */
class CmcMailChimpLine
{
	/**
	 * A unique identifier for the order line item.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $id;

	/**
	 * The product id (e.g. product_q_123) - A unique identifier for the product associated with the order line item.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $product_id;

	/**
	 * The product variant id - A unique identifier for the product variant associated with the order line item
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $product_variant_id;

	/**
	 * Quantity
	 *
	 * @var    integer
	 * @since  __DEPLOY_VERSION__
	 */
	public $quantity;

	/**
	 * The price of an order line item.
	 *
	 * @var    double
	 * @since  __DEPLOY_VERSION__
	 */
	public $price;

	/**
	 * Optional discount
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $discount = '';

	/**
	 * Optional title
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $title = '';

	/**
	 * Optional product variant title
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $product_variant_title = '';
}
