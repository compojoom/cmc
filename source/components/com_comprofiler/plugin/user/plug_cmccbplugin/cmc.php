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
        JHtml::_('stylesheet', JURI::root() . 'media/mod_cmc/css/cmc.css');

        $listid = $this->params->get('listid', "");


        $ret = "\t<tr>\n";
        $ret .= "\t\t<td class='titleCell'>". JText::_('SUBSCRIPTION') .":</td>\n";
        $ret .= "\t\t<td class='fieldCell'>";

        // Display
        $ret .= '<input type="checkbox" name="cmc[newsletter]" id="cmc[newsletter]" value="1" />';
        $ret .= '<label for="cmc[newsletter]" id="cmc[newsletter]-lbl">' . JText::_('NEWSLETTER') . '</label>';
        $ret .= "</td>\n";
        $ret .= "</tr>\n";
        $ret .= "\t<tr>\n";
        $ret .= "<td colspan='2' id='cmc_td_newsletter' style=''>\n";
        $ret .= "<div id=\"cmc_newsletter\" style=\"display: none;\">\n";

        // Render Content
        $ret .= "test: <input type=\"text\" name=\"cmc[merge_var1]\" id=\"cmc[merge_var1]\" />";

        $ret .= '<input type="hidden" name="cmc[listid]" value="' . $listid . '" />';
        $ret .= "</div>\n";
        $ret .= "</td>\n";
        $ret .= "</tr>\n";
        $ret .= "\t</tr>\n";

        // TODO move to document.ready in separate file
        $ret .= "<script type=\"text/javascript\">";
        $ret .= 'document.id("cmc[newsletter]").addEvent("click", function() {';
        $ret .= 'document.id("cmc_newsletter").setStyle("display", "block");';
        $ret .= "});";
        $ret .= "</script>";


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
        // Save User to temporary table- not active here


        if (!empty($postdata['cmc']['newsletter'])) {

            var_dump($postdata['cmc']);

            // Check if user email already registered
            $chimp = new cmcHelperChimp();

            $userlists = $chimp->listsForEmail($user->email);
            $listId = $postdata['cmc']['listid']; // hidden field

            if ($userlists && in_array($listId, $userlists)) {
                $updated = true;
            } else {
                $updated = false;
            }

            if($updated) {
                // Update user data


            } else {
                // Temporary save user in cmc databse


            }

        }

        echo $user->id;
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
