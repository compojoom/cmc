<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

/**
 * Class CmcControllerLists
 *
 * @since  1.2
 */
class CmcControllerLists extends CmcController
{
	/**
	 * Delete list and users (only from the database, not from Mailchimp)
	 *
	 * @return void
	 */
	public function delete()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');

		// Let us quote the values
		foreach ($cid as $key => $value)
		{
			$cid[$key] = $db->quote($value);
		}

		$query->delete('#__cmc_lists')->where('mc_id IN (' . implode(',', $cid) . ')');

		$db->setQuery($query);
		$db->execute();

		$query->clear();
		$query->delete('#__cmc_users')->where('list_id IN (' . implode(',', $cid) . ')');

		$db->setQuery($query);
		$db->execute();

		$this->setRedirect('index.php?option=com_cmc&view=lists');
	}
}
