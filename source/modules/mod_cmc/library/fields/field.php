<?php
/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 22.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

abstract class CmcField extends JFormField {

    public function __construct($form = null) {

        parent::__construct($form);

        $this->checkCmcInstall();
    }

    public function checkCmcInstall() {
        if(!JComponentHelper::getParams('com_cmc')->get('api_key', '')) {
            $appl = JFactory::getApplication();
            $appl->redirect('index.php?option=com_cmc','MOD_CMC_YOU_NEED_TO_PROVIDE_API_KEY');
        }
    }

    public function getSettings($key, $default = '') {
        return JComponentHelper::getParams('com_cmc')->get($key, $default);
    }
}