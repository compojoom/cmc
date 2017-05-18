<?php

defined('_JEXEC') or die('Restricted access');

/**
 * Interface CmcShopInterface
 *
 * @since  __DEPLOY_VERSION__
 */
interface CmcShopInterface
{
	/**
	 * Get the total count of products
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTotalProducts();

	/**
	 * Get the total orders of a product
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTotalOrders();

	/**
	 * Get the total count of customers
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTotalCustomers();

	/**
	 * Get the total count of product categories
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTotalProductCategories();

	/**
	 * Get the total count of checkouts
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTotalCheckouts();

	/**
	 * Get the products
	 *
	 * @param   int  $offset  Offset where to start
	 * @param   int  $limit   Limit
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getProducts($offset = 0, $limit = 100);

	/**
	 * Get the orders
	 *
	 * @param   int  $offset  Offset where to start
	 * @param   int  $limit   Limit
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getOrders($offset = 0, $limit = 100);

	/**
	 * Get the customers
	 *
	 * @param   int  $offset  Offset where to start
	 * @param   int  $limit   Limit
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getCustomers($offset = 0, $limit = 100);

	/**
	 * Get the product categories (Optional)
	 *
	 * @param   int  $offset  Offset where to start
	 * @param   int  $limit   Limit
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getProductCategories($offset = 0, $limit = 100);

	/**
	 * Get the checkouts
	 *
	 * @param   int  $offset  Offset where to start
	 * @param   int  $limit   Limit
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getCheckouts($offset = 0, $limit = 100);
}
