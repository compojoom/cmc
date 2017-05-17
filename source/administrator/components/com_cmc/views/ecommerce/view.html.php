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
 * Class CmcViewEcommerce
 *
 * @since  1.0
 */
class CmcViewEcommerce extends CmcViewBackend
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
		$this->setCTitle(JText::_('COM_CMC_E_COMMERCE_SYNC'), JText::_(''), 'ecommerce');

		JToolBarHelper::custom('ecommerce.sync', 'refresh', '', JText::_('COM_CMC_SYNC_HEADING'), false);
	}
}
