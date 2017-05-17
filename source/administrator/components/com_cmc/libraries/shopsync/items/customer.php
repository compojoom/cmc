<?php

defined('_JEXEC') or die('Restricted access');

/**
 * Class CmcMailChimpCustomer
 *
 * @since  __DEPLOY_VERSION__
 */
class CmcMailChimpCustomer
{
	/**
	 * The customer id (e.g. customer_shop_123)
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $id;

	/**
	 * The email address
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $email_address;

	/**
	 * First name
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $first_name;

	/**
	 * Last name
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	public $last_name;


	/**
	 * Company
	 *
	 * @var     string
	 * @since  __DEPLOY_VERSION__
	 */
	public $company = '';

	/**
	 * Opt-in to newsletter
	 *
	 * @var    boolean
	 * @since  __DEPLOY_VERSION__
	 */
	public $opt_in_status;
}
