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
jimport('joomla.application.component.controller');

/**
 * Class CmcControllerEcommerce
 *
 * @since  __DEPLOY_VERSION__
 */
class CmcControllerEcommerce extends CmcController
{
	/**
	 * Sync task to be called by JavaScript
	 * index.php?option=com_cmc&task=ecommerce.sync&type=1&action=customers&offset=0&limit=100
	 *
	 * @return  boolean
	 * 
	 * @since   __DEPLOY_VERSION__
	 */
	public function sync()
	{
		$input = JFactory::getApplication()->input;

		$this->loadShop();

		$shopType = $input->getInt('type');
		$action   = $input->getCmd('action');
		$offset   = $input->getInt('offset', 0);
		$limit    = $input->getInt('limit', 100);

		// TODO switch by type
		$syncer = new CmcShopVirtuemart();

		$method = 'get' . ucfirst($action);

		$result = $syncer->$method($offset, $limit);

		if (empty($result))
		{
			return json_encode(array('success' => true, 'result' => $result));
		}

		// Sync it to mailChimp
		$chimp = new CmcHelperChimp;
		$shop  = CmcHelperShop::getShop();


		echo json_encode($result);
		jexit();
	}

	/**
	 * Sync task to be called by JavaScript
	 *
	 * @return  void
	 * 
	 * @since   __DEPLOY_VERSION__
	 */
	public function getSyncTotalCount()
	{
		$input = JFactory::getApplication()->input;

		$this->loadShop();

		$shopType = $input->getInt('type');

		// TODO switch by type or plugin
		$syncer = new CmcShopVirtuemart;

		$result = new stdClass;

		$result->productsCount  = $syncer->getTotalProducts();
		$result->ordersCount    = $syncer->getTotalOrders();
		$result->customersCount = $syncer->getTotalCustomers();
		$result->categoriesCount = $syncer->getTotalProductCategories();
		$result->checkoutsCount = $syncer->getTotalCheckouts();

		echo json_encode($result);
		jexit();
	}

	/**
	 * Create a new shop
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function createShop()
	{
		$input = JFactory::getApplication()->input;

		$this->loadShop();

		$shopType = $input->getInt('type');
		$list     = $input->getCmd('list');
		$title    = $input->getString('title', '');
		$currency = $input->getString('currency', '');
		$email    = $input->getString('email', '');

		// The shop data
		$shop = new stdClass;

		$shop->name    = $title;
		$shop->list_id = $list;
		$shop->type    = $shopType;
		$shop->synced  = 0;
		$shop->created = JFactory::getDate()->toSql();

		$table = JTable::getInstance('Shops', 'CmcTable');

		$table->save($shop);

		$table->checkIn();

		// TODO type
		$shop->shop_id = 'vm_' . $table->id;

		$table->save($shop);

		// Create a shop in Mailchimp
		$chimp = new CmcHelperChimp;

		$mcShop = new stdClass;

		$mcShop->id       = $shop->shop_id;
		$mcShop->list_id  = $shop->list_id;
		$mcShop->name     = $shop->name;

		// TODO
		$mcShop->platform = 'VirtueMart';

		$mcShop->is_syncing    = true;
		$mcShop->email_address = $email;
		$mcShop->currency_code = $currency;

		$mcShop->domain = JUri::root();

		// $result = $chimp->createShop($mcShop);
		$result = 'tmp';

		echo json_encode(array('shopId' => $shop->shop_id, 'result' => $result));
		jexit();
	}

	/**
	 * Load shop dependencies
	 *
	 * @return  void
	 * 
	 * @since   __DEPLOY_VERSION__
	 */
	private function loadShop()
	{
		// TODO Move to autoloader
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/libraries/shopsync/shopinterface.php';
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/libraries/shopsync/items/product.php';
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/libraries/shopsync/items/customer.php';
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/libraries/shopsync/items/line.php';
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/libraries/shopsync/items/order.php';
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/libraries/shopsync/items/cart.php';
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/libraries/shopsync/shop.php';
		require_once JPATH_COMPONENT_ADMINISTRATOR . '/libraries/shopsync/shops/virtuemart.php';
	}
}
