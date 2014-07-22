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
jimport('joomla.application.component.view');

/**
 * Class CmcViewUsers
 *
 * @since  1.0
 */
class CmcViewUsers extends JViewLegacy
{
	/**
	 * Displays the view
	 *
	 * @param   string  $tpl  - custom template
	 *
	 * @return mixed|void
	 */
	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->state = $this->get('state');
		$this->status = $this->get('status');
		$this->pagination = $this->get('Pagination');

		$lists = CmcHelperBasic::getLists();
		$options[] = array('value' => '', 'text' => JText::_('JALL'));

		foreach ($lists as $list)
		{
			$this->listNames[$list->mc_id] = $list->list_name;
			$options[] = array(
				'value' => $list->mc_id,
				'text' => $list->list_name
			);
		}

		$this->lists = JHtml::_('select.genericlist', $options, 'filter_list', 'onchange="this.form.submit()"', 'value', 'text', $this->state->get('filter.list'));
		array_shift($options);
		$this->addToList = JHtml::_('select.genericlist', $options, 'addtolist', '', 'value', 'text', $this->state->get('filter.list'));

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Ads the toolbar buttons
	 *
	 * @return void
	 */
	public function addToolbar()
	{
		// Set toolbar items for the page
		JToolBarHelper::addNew('user.add');
		JToolBarHelper::editList('user.edit');
		JToolBarHelper::deleteList(JText::_('COM_CMC_DO_YOU_REALLY_WANTO_TO_REMOVE_THIS_USERS'), 'users.delete');
		JToolBarHelper::custom('users.addGroup', 'plus', '', JText::_('COM_CMC_ADD_GROUP'), false);
		JToolBarHelper::custom('users.export', 'download', '', 'CSV', false);
	}
}
