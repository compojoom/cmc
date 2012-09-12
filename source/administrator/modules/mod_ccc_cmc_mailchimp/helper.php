<?php
/**
 * Compojoom Control Center
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

// No direct access.
defined('_JEXEC') or die;

abstract class modCCCMailchimpHelper
{
    public static function getAccountDetails()
    {
        $cache = JFactory::getCache('mod_ccc_cmc_mailchimp', 'output');
        $cache->setCaching(true);
        $details = $cache->get('details');

        if(!$details) {
            $chimp = new cmcHelperChimp();
            $data = $chimp->getAccountDetails();
            $details = serialize($data);
            $cache->store(($details), 'details');
        };

        return unserialize($details);
    }
}