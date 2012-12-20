<?php
/**
 * Compojoom System Plugin
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined('COMPOJOOM_PLUGIN')) {
    define('COMPOJOOM_PLUGIN', '1.0.0');
}

if (!defined('CDEBUG')) {
    define('CDEBUG', false);
}

// import libaries
jimport('joomla.event.plugin');

JLoader::discover('cmcHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/');

class plgSystemECom360 extends JPlugin {

    /**
     * mc_cid - this is the campaign's unique_id
     *  mc_eid - this is the member email's unique_id
     * @var array
     */

    protected $_getvars = array("mc_cid", "mc_eid");

    public function onAfterDispatch() {

        $app = JFactory::getApplication();

        // This plugin is only intended for the frontend
        if ($app->isAdmin()) {
            return true;
        }

        $doc = JFactory::getDocument();

        // This plugin is only for html, really?
        if ($doc->getType() != 'html')
        {
            return true;
        }

        $cid = JFactory::getApplication()->input->get('mc_cid', '');
        $eid = JFactory::getApplication()->input->get('mc_eid', '');

        // User comes from MC, cid is optional so just test for eid
        if(!empty($eid)) {
            $session = JFactory::getSession();
            $session->set( 'mc', '1' );
            $session->set( 'mc_cid', $cid);
            $session->set( 'mc_eid', $eid);
        }
    }

}