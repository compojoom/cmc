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
jimport('joomla.application.component.controller');

class CmcControllerLists extends CmcController {

    public function synchronize(){
        $params = JComponentHelper::getParams('com_cmc');
        $api_key = $params->get("api_key", '');
        $user = JFactory::getUser();

        CmcHelperSynchronize::synchronize($api_key, $user);

        $this->setRedirect('index.php?option=com_cmc&view=lists', JText::_("COM_CMC_SYNCRONIZE_SUCCESSFUL"));
    }
}