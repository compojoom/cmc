<?php
/**
 * Tiles
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

class CmcViewSettings extends JView {

	public function display($tpl = null) {

		$uri =  JFactory::getURI();

		JToolBarHelper::title(JText::_('COM_CMC_SETTINGS'), 'config');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel('cancel', 'Close');
		JToolBarHelper::help('screen.cmc', true);

		$items = $this->get('Data');
		
		for ($i = 0; $i < count($items); $i++) {
			$item = $items[$i];

			if ($item->catdisp == "basic") {
				$items_basic[$item->id] = $item;
			}
			if ($item->catdisp == "advanced"){
				$items_advanced[$item->id] = $item;
			}
			if ($item->catdisp == "security"){
				$items_security[$item->id] = $item;
			}
		}

		$this->assignRef('items', $items);
		$this->assignRef('items_basic', $items_basic);
		$this->assignRef('items_advanced', $items_advanced);
		$this->assignRef('items_security', $items_security);

		parent::display($tpl);
	}

}