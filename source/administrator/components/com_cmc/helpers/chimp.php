<?php
/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 22.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * This class will work as a small abstraction over the MCAPI class.
 * I got too tired of typing the $key all the time :)
 */
class cmcHelperChimp extends MCAPI {

    public function __construct($key = '', $secure = '' ) {
        if(!$key) {
            $key = CmcSettingsHelper::getSettings('api_key', '');
        }

        if(!$secure) {
            $config = JFactory::getConfig();
            $secure = $config->get('force_ssl', 0);
        }

        parent::MCAPI($key, $secure);
    }
}