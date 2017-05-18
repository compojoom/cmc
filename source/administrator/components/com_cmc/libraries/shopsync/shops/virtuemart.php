<?php

defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
require_once JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/vmmodel.php';

/**
 * Class CmcShopVirtuemart
 *
 * @since  __DEPLOY_VERSION__
 */
class CmcShopVirtuemart extends CmcShop
{
	const ROOT_ITEM = 9;

	/**
	 * Get the total count of products
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTotalProducts()
	{
		return $this->getTableCount('#__virtuemart_products', array('product_parent_id = ' . CmcShopVirtuemart::ROOT_ITEM));
	}

	/**
	 * Get the total orders of a product
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTotalOrders()
	{
		return $this->getTableCount('#__virtuemart_orders');
	}

	/**
	 * Get the total count of customers
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTotalCustomers()
	{
		return $this->getTableCount('#__virtuemart_vmusers');
	}

	/**
	 * Get the total count of product categories
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTotalProductCategories()
	{
		// TODO: Implement getTotalProductCategories() method.
		return 0;
	}

	/**
	 * Get the total count of checkouts
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTotalCheckouts()
	{
		return $this->getTableCount('#__virtuemart_carts');
	}


	/**
	 *
	 *
	 * @param   string  $table  Table to query
	 * @param   array   $where  Optional where query
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function getTableCount($table, $where = array())
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('count(*)')->from($db->quoteName($table));

		if (!empty($where))
		{
			$query->where(implode('AND ', $where));
		}

		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Get the products
	 *
	 * @param   int $offset Offset where to start
	 * @param   int $limit Limit
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getProducts($offset = 0, $limit = 100)
	{
		$app = JFactory::getApplication();

		$app->setUserState('com_virtuemart.virtuemartc-1.limit', $limit);
		$app->setUserState('com_virtuemart.virtuemartc-1.limitstart', $offset);
		$app->setUserState('com_virtuemart.virtuemart.limitstart', $offset);
		$app->input->set('limitstart', $offset);

		/** @var VirtueMartModelProduct $model */
		$model = VmModel::getModel('product');

		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		// We have to get the root items only, as we use the children otherwise
		$query->select('virtuemart_product_id')
			->from('#__virtuemart_products')
			->where('product_parent_id = ' . CmcShopVirtuemart::ROOT_ITEM)
			->where('published = 1')
			->order('virtuemart_product_id ASC');

		$db->setQuery($query, $offset, $limit);

		$vmProducts = $db->loadObjectList();

		$products = array();

		foreach ($vmProducts as $i => $row)
		{
			$vmProduct = $model->getProduct($row->virtuemart_product_id);

			$product = new CmcMailChimpProduct();

			$id = 'product_vm_' . $vmProduct->virtuemart_product_id;

			$product->id = $id;
			$product->title = $vmProduct->product_name;
			$product->description = $vmProduct->product_s_desc;
			$product->image_url = '';

			$variants = array();

			$model->setId($vmProduct->virtuemart_product_id);
			$uncatChildren = $model->getUncategorizedChildren(false);

			$variants[] = array(
				'id' => $id,
				'title' => $vmProduct->product_name,
				'price' => number_format($vmProduct->allPrices[0]['product_price'],2)
			);

			foreach ($uncatChildren as $child)
			{
				$vmChild = $model->getProduct($child);

				$variants[] = array(
					'id' => 'product_vm_' . $vmChild->virtuemart_product_id,
					'title' => $vmChild->product_name,
					'price' => number_format($vmChild->allPrices[0]['product_price'], 2)
				);
			}

			$product->variants = $variants;

			$products[] = $product;
		}

		return $products;
	}

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
	public function getOrders($offset = 0, $limit = 100)
	{
		$app = JFactory::getApplication();

		$app->setUserState('com_virtuemart.orders.limit', $limit);
		$app->setUserState('com_virtuemart.virtuemartc-1.limitstart', $offset);
		$app->setUserState('com_virtuemart.orders.limitstart', $offset);
		$app->input->set('limitstart', $offset);

		/** @var VirtueMartModelOrders $model */
		$model = VmModel::getModel('orders');

		/** @var VirtueMartModelCurrency $curModel */
		$curModel = VmModel::getModel('currency');

		// Overview of orders
		$vmOrders = $model->getOrdersList();

		$orders = array();

		$currency = null;
		$currencyCode = '';

		foreach ($vmOrders as $i => $vmOrder)
		{
			$completeOrder = $model->getOrder($vmOrder->virtuemart_order_id);

			$order     = new CmcMailChimpOrder();
			$customer  = new CmcMailChimpCustomer();

			$customerId = 'customer_vm_' . $completeOrder['details']['BT']->customer_number;

			// Non-Guest booking, we actually have a user
			if (!empty($vmOrder->virtuemart_user_id))
			{
				$customerId ='customer_vm_' . $vmOrder->virtuemart_user_id;
			}

			$customer->id = $customerId;
			$customer->company = $completeOrder['details']['BT']->company;
			$customer->email_address = $completeOrder['details']['BT']->email;
			$customer->first_name = $completeOrder['details']['BT']->first_name;
			$customer->last_name = $completeOrder['details']['BT']->last_name;
			$customer->opt_in_status = true;

			// Order
			$order->id = 'order_vm_' . $completeOrder['details']['BT']->virtuemart_order_id;
			$order->customer = $customer;
			$order->order_total = $completeOrder['details']['BT']->order_total;

			// Currency load
			if (!$currency)
			{
				$currency = $curModel->getCurrency($completeOrder['details']['BT']->order_currency);
				$currencyCode = !empty($currency->currency_code_2) ?: $currency->currency_code_3;
			}

			$order->currency_code = $currencyCode;

			// Lines
			$lines = array();

			foreach ($completeOrder['items'] as $i => $item)
			{
				$line = new CmcMailChimpLine();

				$line->id = 'order_vm_line_' . $item->virtuemart_order_item_id;
				$line->product_id = 'product_vm_' . $item->virtuemart_product_id;
				$line->product_variant_id = 'product_vm_' . $item->virtuemart_product_id;
				$line->quantity = $item->product_quantity;
				$line->price = $item->product_final_price;

				$lines[] = $line;
			}

			$order->lines = $lines;

			// Optional infos
			$order->tax_total = $completeOrder['details']['BT']->order_tax;
			$order->shipping_total = $completeOrder['details']['BT']->order_shipment;

			// TODO add more like shipping and billing address

			$orders[] = $order;
		}

		return $orders;
	}

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
	public function getCustomers($offset = 0, $limit = 100)
	{
		$app = JFactory::getApplication();

		$app->setUserState('com_virtuemart.vmusers.limit', $limit);
		$app->setUserState('com_virtuemart.virtuemartc-1.limitstart', $offset);
		$app->setUserState('com_virtuemart.vmusers.limitstart', $offset);
		$app->input->set('limitstart', $offset);

		/** @var VirtueMartModelUser $model */
		$model = VmModel::getModel('user');

		$vmUsers = $model->getUserList();

		$customers = array();

		foreach ($vmUsers as $vmUser)
		{
			$addressList = $model->getUserAddressList($vmUser->id, 'BT');

			$customer = new CmcMailChimpCustomer();

			$customer->id = 'customer_vm_' . $vmUser->id;

			$customer->email_address = $vmUser->email;

			// TODO how to handle?
			$customer->opt_in_status = true;

			if (!empty($addressList))
			{
				// We take the first one
				$customer->company    = $addressList[0]->company;
				$customer->last_name  = $addressList[0]->last_name;
				$customer->first_name = $addressList[0]->first_name;
			}
			else
			{
				// Take it from user with split on space
				list ($firstname, $lastname) = explode(' ', $vmUser->name);

				$customer->first_name = $firstname ?: '';
				$customer->last_name  = $lastname ?: '';
			}

			$customers[] = $customer;
		}

		return $customers;
	}

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
	public function getProductCategories($offset = 0, $limit = 100)
	{
		return array();
	}

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
	public function getCheckouts($offset = 0, $limit = 100)
	{
		// Get the cart from virtuemart carts table
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select('*')->from('#__virtuemart_carts');

		$db->setQuery($query, $offset, $limit);

		$result = $db->loadObjectList();

		if (empty($result))
		{
			return array();
		}

		$carts = array();

		/** @var VirtueMartModelCurrency $curModel */
		$curModel = VmModel::getModel('currency');

		/** @var VirtueMartModelProduct $model */
		$prodModel = VmModel::getModel('product');

		/** @var VirtueMartModelUser $model */
		$userModel = VmModel::getModel('user');

		$currency = null;
		$currencyCode = '';

		foreach ($result as $vmCart)
		{
			$cart     = new CmcMailChimpCart();
			$customer = new CmcMailChimpCustomer();

			$cart->id = 'cart_vm_' . $vmCart->virtuemart_cart_id;

			// Customer
			$customerId = 'customer_vm_' . uniqid();

			$customer->id = $customerId;

			// Non-Guest booking, we actually have a user
			if (!empty($vmCart->virtuemart_user_id))
			{
				$customer->id ='customer_vm_' . $vmCart->virtuemart_user_id;

				// Load Customer
				$userAddress = $userModel->getUserAddressList($vmCart->virtuemart_user_id, 'BT');

				$userModel->setId($vmCart->virtuemart_user_id);

				$user = $userModel->getUser($userAddress[0]->virtuemart_userinfo_id);

				$customer->email_address = $user->JUser->email;

				$customer->company       = $userAddress[0]->company;
				$customer->first_name    = $userAddress[0]->first_name;
				$customer->last_name     = $userAddress[0]->last_name;
				$customer->opt_in_status = true;
			}

			// Cart information
			$cart->customer = $customer;

			$cartData = json_decode($vmCart->cartData);

			// Currency load
			if (!$currency)
			{
				$currency = $curModel->getCurrency($cartData->pricesCurrency);
				$currencyCode = !empty($currency->currency_code_2) ?: $currency->currency_code_3;
			}

			$cart->currency_code = $currencyCode;

			$lines = array();
			$total = 0;
			$totalTax   = 0;

			foreach ($cartData->cartProductsData as $i => $item)
			{
				$product = $prodModel->getProduct($item->virtuemart_product_id);

				$line = new CmcMailChimpLine();

				$line->id = 'order_vm_line_' . $vmCart->virtuemart_cart_id . '_' . $i;

				$line->product_id = 'product_vm_' . $item->virtuemart_product_id;
				$line->product_variant_id = 'product_vm_' . $item->virtuemart_product_id;
				$line->quantity = $item->quantity;

				$price = $product->allPrices[0]['salesPrice'] * $item->quantity;
				$tax   = $product->allPrices[0]['taxAmount'] * $item->quantity;

				$total    += $price;
				$totalTax += $tax;

				$line->price = $price;

				$lines[] = $line;
			}

			$cart->lines = $lines;

			$cart->order_total = $total;
			$cart->tax_total   = $totalTax;

			$carts[] = $cart;
		}

		return $carts;
	}
}
