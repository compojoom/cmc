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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class CmcViewCpanel extends JViewLegacy {

    public function display($tpl = null) {

        $this->addToolbar();
        parent::display($tpl);
    }

    public function addToolbar() {
        JToolBarHelper::title(JText::_('COM_CMC_CPANEL'), 'cpanel');
        JToolBarHelper::preferences( 'com_cmc' );
    }
}