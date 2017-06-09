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
JLoader::discover('CmcMailChimp', JPATH_ADMINISTRATOR . '/components/com_cmc/libraries/shopsync/items/');

require_once JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
require_once JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/vmmodel.php';

/**administrator/components/com_cmc/libraries/shopsync/shops/virtuemart.php
 * Class plgSystemECom360Virtuemart
 *
 * @since  1.3
 */
class plgSystemECom360Virtuemart extends JPlugin
{
	/**
	 * The shop object
	 *
	 * @var    object
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	private $shop;

	/**
	 * Chimp API
	 *
	 * @var    CmcHelperChimp
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	private $chimp;

	/**
	 * plgSystemECom360Virtuemart constructor.
	 *
	 * @param   object  $subject  Subject
	 * @param   array   $config   Config
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function __construct($subject, array $config = array())
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Load the shop
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function loadShop()
	{
		$shopId      = $this->params->get('store_id', 1);
		$this->shop  = CmcHelperShop::getShop($shopId);
		$this->chimp = new CmcHelperChimp;
	}

	/**
	 * Add Order to MailChimp
	 *
	 * @param   object  $cart   The cart object
	 * @param   object  $order  The order
	 *
	 * @return  bool
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function plgVmConfirmedOrder($cart, $order)
	{
		$this->loadShop();

		$session = JFactory::getSession();

		$customerId = $cart['BT']->customer_number;

		if (!empty($order->virtuemart_user_id))
		{
			$customerId = $order->virtuemart_user_id;
		}

		$customer = CmcHelperShop::getCustomerObject(
			$cart->BT['email'],
			$customerId,
			$cart->BT['company'],
			$cart->BT['email'],
			$cart->BT['last_name']
		);

		$lines = array();

		foreach ($order['items'] as $item)
		{
			$line = new CmcMailChimpLine;

			$line->id                    = CmcHelperShop::PREFIX_ORDER . $item->virtuemart_order_item_id;
			$line->title                 = $item->order_item_name;

			$parentProductId = CmcHelperShop::getVmParentProductId($item->virtuemart_product_id);

			$line->product_id            = CmcHelperShop::PREFIX_PRODUCT . $parentProductId;
			$line->product_variant_id    = CmcHelperShop::PREFIX_PRODUCT . $item->virtuemart_product_id;
			$line->product_variant_title = $item->order_item_name;
			$line->quantity              = (int) $item->product_quantity;
			$line->price                 = (double) $item->product_final_price;

			$lines[] = $line;
		}

		// The order data
		$mOrder           = new CmcMailChimpOrder;
		$mOrder->id       = CmcHelperShop::PREFIX_ORDER . $order["details"]["BT"]->virtuemart_order_id;
		$mOrder->customer = $customer;

		// Currency
		/** @var VirtueMartModelCurrency $curModel */
		$curModel = VmModel::getModel('currency');

		$currency = $curModel->getCurrency($cart->BT['order_currency']);
		$currencyCode = !empty($currency->currency_code_2) ? $currency->currency_code_2 : $currency->currency_code_3;

		$mOrder->currency_code        = $currencyCode;
		$mOrder->payment_tax          = (double) $order["details"]["BT"]->order_tax;
		$mOrder->order_total          = (double) $order["details"]["BT"]->order_total;
		$mOrder->processed_at_foreign = JFactory::getDate($order->order_created)->toSql();

		$mOrder->lines       = $lines;
		$mOrder->campaign_id = $session->get('mc_cid', '');

		if (empty($session->get('mc_cid', '')))
		{
			// MailChimp does not accept empty|null value here
			unset($mOrder->campaign_id);
		}

		return $this->chimp->addOrder($this->shop->shop_id, $mOrder);
	}

	/**
	 * Delete a product
	 *
	 * @param   object  $id  Id of the product
	 *
	 * @return  array|false
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function plgVmOnDeleteProduct($id, $ok)
	{
		$this->loadShop();

		return $this->chimp->deleteProduct($this->shop->shop_id, CmcHelperShop::PREFIX_PRODUCT . $id);
	}

	/**
	 * Add or update a product to MailChimp
	 *
	 * @param   object  $data  Data for the product
	 *
	 * @return  array|false
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function plgVmAfterStoreProduct($data, $productData)
	{
		$this->loadShop();

		/** @var VirtueMartModelProduct $model */
		$model = VmModel::getModel('product');

		$product = new CmcMailChimpProduct;

		$id = CmcHelperShop::PREFIX_PRODUCT . $data['virtuemart_product_id'];

		$product->id          = $id;
		$product->title       = $data['product_name'];
		$product->description = $data['product_s_desc'];
		$product->image_url   = '';

		$variants = array();

		$model->setId($data['virtuemart_product_id']);
		$uncatChildren = $model->getUncategorizedChildren(false);

		$variants[] = array(
			'id'    => $id,
			'title' => $data['product_name'],
			'price' => number_format((float) $data['mprices']['product_price'][0],2)
		);

		foreach ($uncatChildren as $child)
		{
			$vmChild = $model->getProduct($child);

			$variants[] = array(
				'id'    => CmcHelperShop::PREFIX_PRODUCT . $vmChild->virtuemart_product_id,
				'title' => $vmChild->product_name,
				'price' => number_format((float) $vmChild->allPrices[0]['product_price'], 2)
			);
		}

		$product->variants = $variants;

		return $this->chimp->addProduct($this->shop->shop_id, $product);
	}

	/**
	 * Save or Update a user
	 *
	 * @param   object  $user  User
	 *
	 * @return  array|false
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function plgVmOnUserStore($user)
	{
		$this->loadShop();

		$customer = CmcHelperShop::getCustomerObject(
			$user['email'],
			$user['virtuemart_user_id'],
			$user['company'],
			$user['first_name'],
			$user['last_name']
		);

		return $this->chimp->addCustomer($this->shop->shop_id, $customer);;
	}

	/**
	 * Sent the cart to MailChimp
	 *
	 * @param   array  $data  Crap
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function plgVmOnUpdateCart($data)
	{
		$this->loadShop();

		$vmCart = VirtueMartCart::getCart();

		if (empty($vmCart->user->virtuemart_user_id))
		{
			// We can't send a card to MailChimp without a user email
			return true;
		}

		$session = JFactory::getSession();

		/** @var VirtueMartModelCurrency $curModel */
		$curModel = VmModel::getModel('currency');

		/** @var VirtueMartModelProduct $model */
		$prodModel = VmModel::getModel('product');

		/** @var VirtueMartModelUser $model */
		$userModel = VmModel::getModel('user');

		$cart = new CmcMailChimpCart;

		$cart->id = CmcHelperShop::PREFIX_CART . $vmCart->virtuemart_cart_id;

		// Customer
		$userAddress = $userModel->getUserAddressList($vmCart->user->virtuemart_user_id, 'BT');
		$userModel->setId($vmCart->user->virtuemart_user_id);
		$user = $userModel->getUser($userAddress[0]->virtuemart_userinfo_id);

		$customer = CmcHelperShop::getCustomerObject(
			$user->JUser->email,
			$vmCart->user->virtuemart_user_id,
			$userAddress[0]->company,
			$userAddress[0]->first_name,
			$userAddress[0]->last_name
		);

		// Cart information
		$cart->customer = $customer;

		$currency     = $curModel->getCurrency($vmCart->pricesCurrency);
		$currencyCode = !empty($currency->currency_code_2) ?: $currency->currency_code_3;

		$cart->currency_code = $currencyCode;

		$lines      = array();
		$total      = 0;
		$totalTax   = 0;

		foreach ($vmCart->cartProductsData as $i => $item)
		{
			$product = $prodModel->getProduct($item['virtuemart_product_id']);

			$line = new CmcMailChimpLine;

			$line->id = CmcHelperShop::PREFIX_ORDER_LINE . $vmCart->virtuemart_cart_id . '_' . $i;

			$parentProductId = CmcHelperShop::getVmParentProductId($item['virtuemart_product_id']);

			$line->product_id         = CmcHelperShop::PREFIX_PRODUCT . $parentProductId;
			$line->product_variant_id = CmcHelperShop::PREFIX_PRODUCT . $item['virtuemart_product_id'];
			$line->quantity           = $item['quantity'];

			$itemPrice = empty($product->allPrices[0]['salesPrice']) ? $product->allPrices[0]['product_price'] : $product->allPrices[0]['salesPrice'];
			$taxAmount = empty($product->allPrices[0]['taxAmount']) ? 0 : $product->allPrices[0]['taxAmount'];

			$price = $itemPrice * $item['quantity'];
			$tax   = $taxAmount * $item['quantity'];

			$total    += $price;
			$totalTax += $tax;

			$line->price = $price;

			$lines[] = $line;
		}

		$cart->lines = $lines;

		$cart->order_total = $total;
		$cart->tax_total   = $totalTax;

		$cart->campaign_id = $session->get('mc_cid', '');

		if (empty($session->get('mc_cid', '')))
		{
			// MailChimp does not accept empty|null value here
			unset($cart->campaign_id);
		}

		// Send result to MailChimp
		$result = $this->chimp->addCart($this->shop->shop_id, $cart);

		return true;
	}
}
