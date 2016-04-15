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
 * Class CmcViewUsers
 *
 * @since  1.0
 */
class CmcViewUsers extends CmcViewBackend
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
		$this->setCTitle(JText::_('COM_CMC_USERS'), JText::_(''), 'users');

		// Set toolbar items for the page
		JToolBarHelper::addNew('user.add');
		JToolBarHelper::editList('user.edit');
		JToolBarHelper::deleteList(JText::_('COM_CMC_DO_YOU_REALLY_WANTO_TO_REMOVE_THIS_USERS'), 'users.delete');
		JToolBarHelper::custom('users.addGroup', 'plus', '', JText::_('COM_CMC_ADD_GROUP'), false);
		JToolBarHelper::custom('users.export', 'download', '', 'CSV', false);
	}
}
