<?php
/**
 * Compojoom Community-Builder Plugin
 * @package Joomla!
 * @Copyright (C) 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/

if (!(defined( '_VALID_CB')||defined('_JEXEC')||defined('_VALID_MOS'))) {
    die('Direct Access to this location is not allowed.');
}

// Check if CMC is installed
if (!@include_once(JPATH_ADMINISTRATOR . "/components/com_cmc/helpers/registration.php")) {
    return;
}

global $_PLUGINS;

$_PLUGINS->registerFunction('onUserActive', 'userActivated', 'getCmcTab');
$_PLUGINS->registerFunction( 'onAfterDeleteUser', 'userDelete','getCmcTab' );
$_PLUGINS->registerFunction( 'onBeforeUserBlocking', 'onBeforeUserBlocking','getCmcTab' );

/**
 * Class CBCmc
 * @author Yves Hoppe
*/

class CBCmc extends cbTabHandler
{

    /**
     * Gets the handler
     */

    function getCmcTab()
    {
        // TODO insert a installation check

        $this->cbTabHandler();
    }

    /**
     * @param $tab
     * @param $user
     * @param $ui
     */

    function getDisplayRegistration($tab, $user, $ui)
    {
        $ret = array();


        $htmlValue = JHTML::_('select.booleanlist', "acymailing[user][html]" ,'',1,JText::_('HTML'),JText::_('JOOMEXT_TEXT').'&nbsp;&nbsp;');
        $ret[] = cbTabs::_createPseudoField( $tab, JText::_('RECEIVE'), $htmlValue, '', 'acymailingReceiveField', false );
        die("asdf");

        return $ret;
    }

    /**
     * Deletes the CMC Subscription, triggered on user deletion
     * @param $user
     * @param $success
     */

    function userDelete($user, $success)
    {

    }

    /**
     * Activates the CMC Subcription, triggered on user activation
     * @param $user
     * @param $success
     */

    function userActivated($user, $success)
    {
        if (!$success) {
            return;
        }

        // Activate CMC Registration

        return;
    }

    /**
     * @param $user
     * @param $block
     */

    function onBeforeUserBlocking($user,$block)
    {

    }

}
