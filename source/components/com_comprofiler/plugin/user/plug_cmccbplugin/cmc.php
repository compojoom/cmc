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

if (!(defined('_VALID_CB') || defined('_JEXEC') || defined('_VALID_MOS'))) {
    die('Direct Access to this location is not allowed.');
}

// Check if CMC is installed
if (!@include_once(JPATH_ADMINISTRATOR . "/components/com_cmc/helpers/registration.php")) {
    return;
}

JLoader::register('CmcHelperChimp', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/chimp.php');
JLoader::register('CmcHelperRegistrationrender', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/registrationrender.php');

global $_PLUGINS;
$_PLUGINS->registerFunction('onUserActive', 'userActivated', 'getCmcTab');
$_PLUGINS->registerFunction('onAfterDeleteUser', 'userDelete', 'getCmcTab');
$_PLUGINS->registerFunction('onBeforeUserBlocking', 'onBeforeUserBlocking', 'getCmcTab');

$language = JFactory::getLanguage();
$language->load('plg_cmccb', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('plg_cmccb', JPATH_ADMINISTRATOR, $language->getDefault(), true);
$language->load('plg_cmccb', JPATH_ADMINISTRATOR, null, true);

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
     * @param $postdata
     * @return string
     */

    function getDisplayRegistration($tab, $user, $ui, $postdata)
    {
        JHtml::_('stylesheet', JURI::root() . 'media/mod_cmc/css/cmc.css');
        JHtml::_('behavior.framework', true);

        $listid = $this->params->get('listid', "");
        $interests = explode("|*|", $this->params->get('interests', ''));
        $fields = explode("|*|", $this->params->get('fields', ''));

        $ret = "\t<tr>\n";
        $ret .= "\t\t<td class='titleCell'>" . JText::_('PLG_CMCCB_SUBSCRIPTION') . ":</td>\n";
        $ret .= "\t\t<td class='fieldCell'>";

        // Display
        $ret .= '<input type="checkbox" name="cmc[newsletter]" id="cmc[newsletter]" value="1" />';
        $ret .= '<label for="cmc[newsletter]" id="cmc[newsletter]-lbl">' . JText::_('PLG_CMCCB_NEWSLETTER') . '</label>';
        $ret .= "</td>\n";
        $ret .= "</tr>\n";
        $ret .= "\t<tr>\n";
        $ret .= "<td colspan='2' id='cmc_td_newsletter' style=''>\n";
        $ret .= "<div id=\"cmc_newsletter\" style=\"display: none;\">\n";

        $renderer = CmcHelperRegistrationrender::getInstance();
        $renderer->phoneFormat = $this->params->get("phoneFormat", "inter");
        $renderer->dateFormat = $this->params->get("dateFormat", "%Y-%m-%d");
        $renderer->address2 = $this->params->get("address2", 0);

        // Render Content
        $ret .= $renderer->renderForm($this->params->get('intro-text', ""),
            $this->params->get('outro-text-1', ""), $this->params->get('outro-text-2', ""),
            $fields, $interests, $listid, _CPLG_CB
        );

        //$ret .= '<input type="hidden" name="cmc[listid]" value="' . $listid . '" />';
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

    function getDisplayTab($tab, $user, $ui)
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

            //var_dump($postdata['cmc']);

            // Check if user email already registered
            $chimp = new cmcHelperChimp();

            $userlists = $chimp->listsForEmail($user->email);
            $listId = $postdata['cmc']['listid']; // hidden field

            if ($userlists && in_array($listId, $userlists)) {
                $updated = true;
            } else {
                $updated = false;
            }

            if ($updated) {
                // Update user data


            } else {
                // Temporary save user in cmc databse
                CmcHelperRegistration::saveTempUser($user, $postdata['cmc'], _CPLG_CB);
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

        // Activates the user (after checking if he exists etc)
        CmcHelperRegistration::activateTempUser($user);

        return;
    }

    /**
     * @param $user
     * @param $block
     */

    function onBeforeUserBlocking($user, $block)
    {

    }

    /**
     * @param $tab
     * @param $user
     * @param $ui
     * @return string
     */

    function getEditTab($tab, $user, $ui)
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


    /**
     * @return mixed
     */
    function loadLists()
    {
        $api = new cmcHelperChimp();
        $lists = $api->lists();

        $key = 'id';
        $val = 'name';
        $options[] = array( $key => '', $val => '-- '.JText::_('Please select').' --');

        foreach ($lists['data'] as $list){
            $options[]=array($key=>$list[$key],$val=>$list[$val]);
        }

        $attribs = "onchange='submitbutton(\"applyPlugin\")'";

        //$attribs = null;
        if($options){
            //$content = "listid: " . $this->params->get('listid', "");
            $content =  JHtml::_('select.genericlist', $options, 'params[listid]', $attribs, $key,
                $val, $this->params->get('listid', ""));
        }

        return $content;
    }


    /**
     * @return string
     */
    function loadFields()
    {
        $listid = $this->params->get('listid', "");

        if (empty($listid)) {
           $content = '<div style="float:left;">' . JText::_('PLG_CMCCB_NO_FIELDS') . '</div>';
           return $content;
        }

        $api = new cmcHelperChimp();
        $fields = $api->listMergeVars($listid);
        $key = 'tag';
        $val = 'name';
        $options = false;
        if ($fields) {
            foreach ($fields as $field) {
                $choices = '';
                if (isset($field['choices'])) {
                    foreach ($field['choices'] as $c) {
                        $choices .= $c . '##';
                    }
                    $choices = substr($choices, 0, -2);
                }
                $req = ($field['req']) ? 1 : 0;
                if ($field[$key] == 'EMAIL') {
                    if (!is_array($this->value)) {
                        $oldValue = $this->value;
                        $this->value = array();
                        $this->value[] = $oldValue;
                    }
                    $this->value[] = $field[$key] . ';' . $field['field_type'] . ';' . $field['name'] . ';' . $req . ';' . $choices;
                }
                $options[] = array($key => $field[$key] . ';' . $field['field_type'] . ';' . $field['name'] . ';' . $req . ';' . $choices, $val => $field[$val]);
            }
        }

        $attribs = 'multiple="multiple" size="8"';

        if ($options) {

            $content = "";
            $content = "Fields: " . $this->params->get('fields', "");

            $content .= JHtml::_('select.genericlist', $options, 'params[fields][]', $attribs, $key, $val, explode("|*|", $this->params->get('fields', "")));


            $content .= '<script type="text/javascript">
				window.addEvent(\'domready\',function() {
				    $("jform_params_fields").addEvent( \'change\', function(){
					$("jform_params_fields").options[0].setProperty(\'selected\', \'selected\');

				    });
				});
				</script>';
        } else {
            $content = '<div style="float:left;">' . JText::_('PLG_CMCCB_NO_FIELDS') . '</div>';
        }

        return $content;
    }

    /**
     * @return mixed|string
     */
    function loadInterests()
    {
        $listid = $this->params->get('listid', "");

        if (empty($listid)) {
            $content = '<div style="float:left;">' . JText::_('PLG_CMCCB_NO_INTEREST_GROUPS') . '</div>';
            return $content;
        }

        $api = new cmcHelperChimp();
        $interests = $api->listInterestGroupings($listid);
        $key = 'id';
        $val = 'name';
        $options = false;
        if ($interests) {
            foreach ($interests as $interest) {
                if ($interest['form_field'] != 'hidden') {
                    $groups = '';
                    foreach ($interest['groups'] as $ig) {
                        $groups .= $ig['name'] . '##' . $ig['name'] . '####';
                    }
                    $groups = substr($groups, 0, -4);
                    $options[] = array($key => $interest[$key] . ';' . $interest['form_field'] . ';' . $interest['name'] . ';' . $groups, $val => $interest[$val]);
                }
            }
        }

        $attribs = 'multiple="multiple" size="8"';
        if ($options) {
            $content = JHtml::_('select.genericlist', $options, 'params[interests][]', $attribs, $key, $val, explode("|*|", $this->params->get('interests', "")));
        } else {
            $content = '<div style="float:left;">' . JText::_('PLG_CMCCB_NO_INTEREST_GROUPS') . '</div>';
        }

        return $content;
    }


}
