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
jimport('joomla.application.component.view');

/**
 * Class CmcViewLists
 *
 * @since  1.0
 */
class CmcViewLists extends CmcViewBackend
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
		$this->setCTitle(JText::_('COM_CMC_LISTS'), JText::_(''), 'lists');

		JToolBarHelper::custom('lists.sync', 'refresh', '', JText::_('COM_CMC_SYNC_HEADING'), false);
		JToolBarHelper::deleteList(JText::_('COM_CMC_DO_YOU_WANT_TO_REMOVE_LIST'), 'lists.delete');
	}
}
