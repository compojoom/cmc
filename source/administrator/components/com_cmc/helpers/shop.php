<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . '/components/com_cmc/libraries/shopsync/items/customer.php';

/**
 * Class CmcHelperShop
 *
 * @since  __DEPLOY_VERSION__
 */
class CmcHelperShop
{
	/**
	 * Product prefix
	 */
	const PREFIX_PRODUCT = 'product_vm_';

	/**
	 * Order prefix
	 */
	const PREFIX_ORDER   = 'order_vm_';

	/**
	 * Order line prefix
	 */
	const PREFIX_ORDER_LINE = 'order_vm_line_';

	/**
	 * Cart prefix
	 */
	const PREFIX_CART = 'cart_vm_';

	/**
	 * Customer prefix
	 */
	const PREFIX_CUSTOMER = 'customer_vm_';

	/**
	 * Get the shop
	 *
	 * @param   int  $id  Id of the shop
	 *
	 * @return  object|null
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getShop($id)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*')->from('#__cmc_shops')->where('id = ' . (int) $id);

		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Create a customer
	 *
	 * @param   string  $emailAddress  Email address
	 * @param   string  $id            Id (without customer_vm)
	 * @param   string  $company       Company
	 * @param   string  $firstName     First name
	 * @param   string  $lastName      Last name
	 *
	 * @return  CmcMailChimpCustomer
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getCustomerObject($emailAddress, $id = '', $company = '', $firstName = '', $lastName = '')
	{
		$customer = new CmcMailChimpCustomer;

		$customer->id = self::PREFIX_CUSTOMER . ((empty($id)) ? preg_replace("/[^a-zA-Z0-9]+/", '', $emailAddress) : $id);
		$customer->email_address = $emailAddress;

		$customer->company       = $company ?: '';
		$customer->first_name    = $firstName ?: '';
		$customer->last_name     = $lastName ?: '';

		$customer->opt_in_status = false;

		return $customer;
	}
}
