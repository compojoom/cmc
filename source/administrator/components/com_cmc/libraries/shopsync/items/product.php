<?php

defined('_JEXEC') or die('Restricted access');

/**
 * Class CmcMailChimpProduct
 *
 * @since  __DEPLOY_VERSION__
 */
class CmcMailChimpProduct
{
	/**
	 * The item id (e.g. product_q_123)
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $id;

	/**
	 * The item name
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $title;

	/**
	 * The optional description
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $description = '';

	/**
	 * The optional image url
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $image_url = '';

	/**
	 * Variants of the product or the product only (same id)
	 *
	 * @var    array
	 * @since  __DEPLOY_VERSION__
	 */
	public $variants;
}
