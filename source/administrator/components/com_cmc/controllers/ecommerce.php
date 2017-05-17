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
 * @since  __DEPLOY_VERSION
 */
class CmcControllerEcommerce extends CmcController
{
	/**
	 * Sync task to be called by JavaScript
	 *
	 * @return  boolean
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

		var_dump($result);

		die('wtf');
	}

	/**
	 * Load shop dependencies
	 *
	 * @return  void
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
