<?php
/**
 * @package    Com_Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       09.07.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
JLoader::register('JFormFieldList', JPATH_LIBRARIES . '/joomla/form/fields/list.php');

/**
 * Class JFormFieldCmclistid
 *
 * Creates a list with the mailchimp lists that are already synced with the site
 *
 * @since  1.5
 */
class JFormFieldCmclistid extends JFormFieldList
{
	/**
	 * Get the options
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		$lists = CmcHelperBasic::getLists();

		$list_options = array();

		foreach ($lists as $list)
		{
			$list_options[] = JHTML::_('select.option', $list->mc_id, $list->list_name);
		}

		return $list_options;
	}
}
