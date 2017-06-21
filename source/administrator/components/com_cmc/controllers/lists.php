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

	/**
	 * Create a mailchimp list
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function create()
	{
		$input = JFactory::getApplication()->input;

		$listDetails = $input->getArray($_POST);

		$listDetails['email_type_option'] = (bool) $listDetails['email_type_option'];


		$chimp = new CmcHelperChimp;

		$result = $chimp->createList($listDetails);

		if (!empty($result['status']))
		{
			return $this->setRedirect('index.php?option=com_cmc&view=lists', JText::_('COM_CMC_ERROR_CREATING_LIST') . ' ' . $result['detail'], 'error');
		}

		$table = JTable::getInstance('Lists', 'CmcTable');

		$listDetails['mc_id'] = $result['id'];
		$listDetails['web_id'] = $result['web_id'];
		$listDetails['list_name'] = $result['name'];
		$listDetails['email_type_option'] = $result['email_type_option'] ? 1 : 0;
		$listDetails['created_time'] = JFactory::getDate()->toSql();
		$listDetails['modified_time'] = JFactory::getDate()->toSql();
		$listDetails['default_from_name'] = $listDetails['campaign_defaults']['from_name'];
		$listDetails['default_from_email'] = $listDetails['campaign_defaults']['from_email'];

		$table->bind($listDetails);

		$table->store();

		$this->setRedirect('index.php?option=com_cmc&view=lists', JText::_('COM_CMC_LIST_CREATED'));
	}
}
