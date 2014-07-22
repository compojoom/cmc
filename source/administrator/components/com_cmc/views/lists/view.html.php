<?php
/**
 * @author     Yves Hoppe <yves@compojoom.com>
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       28.08.13
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

/**
 * Class CmcViewLists
 *
 * @since  1.0
 */
class CmcViewLists extends JViewLegacy
{
	/**
	 * Displays the view
	 *
	 * @param   string  $tpl  - the layout
	 *
	 * @return mixed|void
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Ads a toolbar to the page
	 *
	 * @return void
	 */
	public function addToolbar()
	{
		JToolBarHelper::custom('lists.sync', 'refresh', '', JText::_('COM_CMC_SYNC_HEADING'), false);
		JToolBarHelper::deleteList(JText::_('COM_CMC_DO_YOU_WANT_TO_REMOVE_LIST'), 'lists.delete');
	}
}
