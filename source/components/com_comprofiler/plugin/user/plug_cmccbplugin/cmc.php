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

class getCmcTab extends cbTabHandler
{

    var $installed = true;
    var $errormsg = "This plugin can't work without the CMC Component";

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

        $ret = "\t<tr>\n";
        $ret .= "\t\t<td class='titleCell'>"."Newsletter Signup:"."</td>\n";
        $ret .= "\t\t<td class='fieldCell'>";
        $ret .= "ParameterText: ";
        $ret .= "<p>List of CMC Newsletters? Or like the module?!</p>";
        $ret .= "</td>";
        $ret .= "\t</tr>\n";

        return $ret;
    }

    /**
     * @param $tab
     * @param $user
     * @param $ui
     */

    function getDisplayTab( $tab, $user, $ui)
    {

    }

    /**
     * @param $tab
     * @param $user
     * @param $ui
     * @param $postdata
     */

    function saveRegistrationTab($tab, &$user, $ui, $postdata)
    {
        // Save User
        // NOt active


        var_dump($user);

        $juser = JFactory::getUser($user->_cmsUser->id);

        $juser->setParam("newsletter", 1);
        $juser->save();

        if (!empty($postdata['cmc']['newsletter'])) {

            // Check if user email already registered
            // Query cmc_users table mit email

            // Update subscription


            // Save user in CMC database




        }

        echo $juser->id;
        die();



    }



    /**
     * Deletes the CMC Subscription, triggered on user deletion
     * @param $user
     * @param $success
     */

    function userDelete($user, $success)
    {
        if (!$success) {
            return;
        }


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

        // Query cmc database


        // Activate CMC Registration

        // Mailchimp



        return;
    }

    /**
     * @param $user
     * @param $block
     */

    function onBeforeUserBlocking($user,$block)
    {

    }

    /**
     * @param $tab
     * @param $user
     * @param $ui
     * @return string
     */

    function getEditTab( $tab, $user, $ui)
    {
        $return = '';

        $return .= "<table><tr><td>I love cmc</td></tr>";

        return $return;
    }


    /**
     * @param $tab
     * @param $user
     * @param $ui
     * @param $postdata
     */

    function saveEditTab($tab, &$user, $ui, $postdata)
    {

    }

}
