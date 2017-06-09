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
	 * CmcControllerEcommerce constructor.
	 *
	 * @param   array  $config  Optional config params
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Add logging
		JLog::addLogger(
			array(
				'text_file' => 'com_cmc.errors.php'
			),
			JLog::ERROR,
			array('com_cmc')
		);
	}

	/**
	 * Sync task to be called by JavaScript
	 * index.php?option=com_cmc&task=ecommerce.sync&type=1&action=customers&offset=0&limit=100
	 *
	 * @return  void
	 * 
	 * @since   __DEPLOY_VERSION__
	 */
	public function sync()
	{
		$input = JFactory::getApplication()->input;

		$this->loadShop();

		$shopType = $input->getInt('type');
		$shopId   = $input->getInt('shopId');
		$action   = $input->getCmd('action');
		$offset   = $input->getInt('offset', 0);
		$limit    = $input->getInt('limit', 10);

		// TODO switch by type
		$syncer = new CmcShopVirtuemart();

		$method = 'get' . ucfirst($action);

		$results = $syncer->$method($offset, $limit);

		if (empty($results))
		{
			echo json_encode(array('success' => true, 'result' => $results));

			jexit();
		}

		// Sync it to mailChimp
		$chimp = new CmcHelperChimp;
		$shop  = CmcHelperShop::getShop($shopId);

		$errors = array();

		$map = array(
			'products'   => 'product',
			'customers'  => 'customer',
			'orders'     => 'order',
			'categories' => 'category',
			'checkouts'  => 'cart'
		);

		// Add them
		foreach ($results as $result)
		{
			$method = 'add' . ucfirst($map[$action]);

			$ret = $chimp->$method($shop->shop_id, $result);

			if (!empty($ret['status']) && substr($ret['status'], 0,1) === '4')
			{
				JLog::add('Couldn\'t sync for ' . $shop->shop_id, Jlog::ERROR, 'com_cmc');

				$errors[] = array('item' => $result, 'result' => $ret);
			}
		}

		if (!empty($errors))
		{
			echo json_encode(array('success' => false, 'errors' => $errors));

			jexit();
		}

		echo json_encode(array('success' => true, 'results' => $results));
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

		$result = $chimp->createShop($mcShop);

		echo json_encode(array('shopId' => $shop->shop_id, 'result' => $result));
		jexit();
	}

	/**
	 * Set the shop sync to done
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function finalizeShop()
	{
		$input = JFactory::getApplication()->input;

		$this->loadShop();

		$shopId = $input->getInt('id');

		$shop = CmcHelperShop::getShop($shopId);

		if (empty($shop))
		{
			echo json_encode(array('shopId' => $shop->shop_id, 'success' => false));
			jexit();
		}

		// Create a shop in Mailchimp
		$chimp = new CmcHelperChimp;

		$mcShop = new stdClass;

		$mcShop->id       = $shop->shop_id;
		$mcShop->list_id  = $shop->list_id;
		$mcShop->name     = $shop->name;

		// TODO
		$mcShop->platform = 'VirtueMart';

		$mcShop->is_syncing    = false;

		$mcShop->domain = JUri::root();

		$result = $chimp->updateShop($mcShop);

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

	/**
	 * Delete a shop
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function deleteShop()
	{
		$input = JFactory::getApplication()->input;

		$ids = $input->get('cid', array(), 'array');

		$link = JRoute::_('index.php?option=com_cmc&view=ecommerce');

		if (empty($ids))
		{
			$this->setRedirect($link, 'No shops selected to delete');
		}

		$this->loadShop();

		$db = JFactory::getDbo();

		$chimp = new CmcHelperChimp;

		foreach ($ids as $id)
		{
			$shop = CmcHelperShop::getShop($id);

			if (!$shop)
			{
				throw new Exception('Could not load shop with id ' . $id . ' for deletion!', 500);
			}

			$query = $db->getQuery(true);

			$query->delete('#__cmc_shops')->where('id = ' . (int) $id);

			$db->setQuery($query);
			$db->execute();

			$result = $chimp->deleteShop($shop->shop_id);

			if (!empty($result['status']))
			{
				$this->setRedirect($link, 'Error deleting shop ' . $id . ' Message: ' . $result['title'] , 'error');
			}
		}

		$this->setRedirect($link, JText::_('COM_CMC_SHOP_DELETED_SUCCESSFULLY'), 'info');
	}
}
