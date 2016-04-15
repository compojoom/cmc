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
 * Class CmcViewUser
 *
 * @since  1.0
 */
class CmcViewUser extends CmcViewBackend
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
		$this->user = $this->get('Item');

		if (!$this->user->get('id'))
		{
			// Create new empty list item
			$this->user = JTable::getInstance('users', 'CmcTable');
		}

		$this->form = $this->get('Form');

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
		$this->setCTitle(JText::_('COM_CMC_EDIT_USER'), JText::_(''), 'users');

		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_CMC_EDIT_USER'), 'users');
		JToolBarHelper::save('user.save');
		JToolBarHelper::apply('user.apply');
		JToolBarHelper::cancel('user.cancel');
		JToolBarHelper::help('screen.users', true);
	}
}
