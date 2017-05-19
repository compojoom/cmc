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

JLoader::register('JFormFieldList', JPATH_LIBRARIES . '/joomla/form/fields/list.php');

/**
 * Class JFormFieldCmcshops
 *
 * @since  __DEPLOY_VERSION__
 */
class JFormFieldCmcshops extends JFormFieldList
{
	public function __construct($form = null)
	{
		parent::__construct($form);

		// Load Compojoom library
		require_once JPATH_LIBRARIES . '/compojoom/include.php';

		CompojoomLanguage::load('com_cmc', JPATH_SITE);
		CompojoomLanguage::load('com_cmc', JPATH_ADMINISTRATOR);
		CompojoomLanguage::load('com_cmc.sys', JPATH_ADMINISTRATOR);
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getOptions()
	{
		$options = array();

		$options[] = JHtml::_('select.option', '0', '-- ' . JText::_('COM_CMC_PLEASE_SELECT_A_SHOP') . ' --');

		$shops = $this->getShops();

		foreach ($shops as $shop)
		{
			$options[] = JHtml::_('select.option', $shop->value, $shop->text);
		}

		return $options;
	}

	/**
	 * Get the available shops from the db
	 *
	 * return   array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getShops()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select('id as value, name as text')->from('#__cmc_shops');

		$db->setQuery($query);

		return $db->loadObjectList();
	}
}
