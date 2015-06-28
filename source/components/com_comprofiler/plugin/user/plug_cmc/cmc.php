<?php
/**
 * Compojoom Community-Builder Plugin
 * @Copyright (C) 2013 - Yves Hoppe <yves@compojoom.com>
 * @Copyright (C) 2013 - Daniel Dimitrov <daniel@compojoom.com>
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/

if (!(defined('_VALID_CB') || defined('_JEXEC') || defined('_VALID_MOS')))
{
	die('Direct Access to this location is not allowed.');
}

use CBLib\Input\Get;
use CBLib\Language\CBTxt;
use CBLib\Registry\GetterInterface;
use CBLib\Registry\RegistryInterface;
use CB\Database\Table\PluginTable;
use CB\Database\Table\TabTable;
use CB\Database\Table\UserTable;




// Check if CMC is installed
if (!@include_once JPATH_ADMINISTRATOR . "/components/com_cmc/helpers/xmlbuilder.php")
{
	return;
}

// Load Compojoom library
require_once JPATH_LIBRARIES . '/compojoom/include.php';

JLoader::discover('cmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

global $_PLUGINS;
$_PLUGINS->registerFunction('onUserActive', 'userActivated', 'getCmcTab');
$_PLUGINS->registerFunction('onAfterDeleteUser', 'userDelete', 'getCmcTab');
$_PLUGINS->registerFunction('onBeforeUserBlocking', 'onBeforeUserBlocking', 'getCmcTab');
$_PLUGINS->registerFunction('onAfterUserProfileEditDisplay', 'onAfterUserProfileEditDisplay', 'getCmcTab');

$language = JFactory::getLanguage();

// Load language
$language->load('plg_plug_cmc', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('plg_plug_cmc', JPATH_ADMINISTRATOR, $language->getDefault(), true);
$language->load('plg_plug_cmc', JPATH_ADMINISTRATOR, null, true);
$language->load('com_cmc', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('com_cmc', JPATH_ADMINISTRATOR, $language->getDefault(), true);
$language->load('com_cmc', JPATH_ADMINISTRATOR, null, true);
$language->load('com_cmc.sys', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('com_cmc.sys', JPATH_ADMINISTRATOR, $language->getDefault(), true);
$language->load('com_cmc.sys', JPATH_ADMINISTRATOR, null, true);

/**
 * Class getCmcTab
 *
 * @since  1.4
 */
class GetCmcTab extends cbTabHandler
{
	public $installed = true;
	public $errormsg = "This plugin can't work without the CMC Component";

	/**
	 * Constructor
	 */
	public function __construct( )
	{
		parent::__construct();
	}

	/**
	 * Gets the handler
	 */

	public function getCmcTab()
	{
		$this->cbTabHandler();
	}

	/**
	 * Display our CMC fields at the registration
	 *
	 * @param   object  $tab       - The tab
	 * @param   JUser   $user      - The user
	 * @param   object  $ui        - The UI
	 * @param   object  $postdata  - The postdata
	 *
	 * @return string
	 */

	public function getDisplayRegistration($tab, $user, $ui, $postdata)
	{
		JHtml::_('stylesheet', JURI::root() . 'media/mod_cmc/css/cmc.css');
		CompojoomHtmlBehavior::jquery();
		$plugin = GetCmcTab::getPlugin();


		$listid = $plugin->params->get('listid', "");
		$interests = $plugin->params->get('interests', '');
		$fields = $plugin->params->get('fields', '');

		// Create the xml for JForm
		$builder = CmcHelperXmlbuilder::getInstance($plugin->params);

		// Load JS & Co
		JFactory::getDocument()->addScriptDeclaration("
			jQuery(document).ready(function(){
				var $ = jQuery;

				$('#cmc_check_newsletter').on('click', function() {
					if($(this).prop('checked'))
					{
						$('input.cmc_req').addClass('required');
						$('#cmc_newsletter').show();
					}
					else
					{
						$('input.cmc_req').removeClass('required');
						$('#cmc_newsletter').hide();
					}
				});

				$('#cmc_newsletter').hide();
			});
		");

		// We have to set the fields / interests manually for cb because they are no array! See explode
		if (!empty($fields))
		{
			$fields = explode("|*|", $plugin->params->get('fields', ''));
			$builder->fields = $fields;
		}

		if (!empty($interests))
		{
			$interests = explode("|*|", $plugin->params->get('interests', ''));
			$builder->interests = $interests;
		}

		$xml = $builder->build();
		$form = new JForm('myform');
		$form->addFieldPath(JPATH_ADMINISTRATOR . '/components/com_cmc/models/fields');
		$form->load($xml);

		$fieldsets = $form->getFieldsets();

		$ret = '<div class="cbFieldsContentsTab">';
		$ret .= '<div class="sectiontableentry1 cbft_predefined cbtt_input form-group cb_form_line clearfix">';

		$ret .= '<label for="name" id="cblabname" class="control-label col-sm-3">' . JText::_('PLG_CMCCB_SUBSCRIPTION') . '</label>';

		$ret .= '<div class="cb_field col-sm-9">';

		$ret .= '<input type="checkbox" name="cmc[newsletter]" id="cmc_check_newsletter" value="1" /> ';
		$ret .= '<label for="cmc_check_newsletter" id="cmc_newsletter_lbl">' . JText::_('PLG_CMCCB_NEWSLETTER') . '</label>';
		$ret .= "</div>\n";

		$ret .= "</div>";

		$ret .= "<div id='cmc_td_newsletter' style='' class=\"cbFieldsContentsTab\">\n";
		$ret .= "<div id=\"cmc_newsletter\" style=\"display: block;\">\n";

		// Render Content
		foreach ($fieldsets as $key => $value)
		{
			if ($key != "cmc")
			{
				$ret .= '<div class="sectiontableentry1 cbft_predefined cbtt_input form-group cb_form_line clearfix">';
				$ret .= '<label class="col-sm-12">' . JText::_($value->label) . '</label>';
				$ret .= '</div>';
				$fields = $form->getFieldset($key);

				foreach ($fields as $field)
				{
					$ret .= '<div class="sectiontableentry1 cbft_predefined cbtt_input form-group cb_form_line clearfix">';
					$ret .= '<div class="control-label col-sm-3">' . $field->label . '</div>';

					$ret .= '<div class="cb_field col-sm-9">';
					$ret .=  $field->input;
					$ret .= '</div>';
					$ret .= '</div>';
				}

			}
		}

		$ret .= '<input type="hidden" name="cmc[listid]" id="cmc_listid" value="' . $listid . '" />';

		// End open tables / divs
		$ret .= "</div>\n";
		$ret .= "</div>\n";

		return $ret;
	}

	/**
	 * User Profile tab
	 *
	 * @param   object  $tab   - The tab
	 * @param   JUser   $user  - The joomla user
	 * @param   object  $ui    - The ui
	 *
	 * @return  void
	 */

	public function getDisplayTab($tab, $user, $ui)
	{
		global $_CB_framework, $_CB_database, $ueConfig;
		$loggedUser = JFactory::getUser();
		$params = $this->getParams();

		if ($loggedUser->get('id') === $user->get('id'))
		{
			$module = clone JModuleHelper::getModule('mod_cmc');

			$module->id     = 'cb' . $module->id;
			$module->params = $params;

			return JModuleHelper::renderModule($module);
		}

		return '';
	}

	public function &getParams()
	{
		$params = $this->params;

		// We have to set the fields / interests manually for cb because they are no array! See explode
		$fields = $params->get('fields', '');

		if (!empty($fields))
		{
			$params->set('fields', explode("|*|", $fields));
		}

		$interests = $params->get('interests', '');

		if (!empty( $interests))
		{
			$params->set('interests', explode("|*|", $interests));
		}

		return $params;
	}

	/**
	 * Saves the registration information
	 *
	 * @param   object  $tab       - The tab
	 * @param   JUser   &$user     - The JUser
	 * @param   object  $ui        - The UI
	 * @param   object  $postdata  - The postdata
	 *
	 * @return  void
	 */

	function saveRegistrationTab($tab, &$user, $ui, $postdata)
	{
		// Save User to temporary table- not active here
		if (!empty($postdata['cmc']['newsletter']))
		{
			// For the hidden field
			$listId = $postdata['cmc']['listid'];
			$plugin = GetCmcTab::getPlugin();

			$mappedData = $this->getMapping($plugin->params->get('mapfields'), $postdata);

			if (count($mappedData))
			{
				$mergedGroups = array_merge($mappedData, $postdata['cmc_groups']);
				$postdata = array_merge($postdata, array('cmc_groups' => $mergedGroups));
			}

			$updated = CmcHelperRegistration::isSubscribed($listId, $user->email);

			if ($updated)
			{
				// Update users subscription with the new data
				CmcHelperRegistration::updateSubscription($user, $postdata);
			}
			else
			{
				// Temporary save user in cmc database
				CmcHelperRegistration::saveTempUser($user, $postdata, _CPLG_CB);
			}
		}
	}


	/**
	 * Deletes the CMC Subscription, triggered on user deletion
	 *
	 * @param   JUser   $user     - The JUser Obj
	 * @param   string  $success  - Success string
	 *
	 * @return  void
	 */

	public function userDelete($user, $success)
	{
		if (!$success)
		{
			return;
		}

		CmcHelperRegistration::deleteUser($user);

		return;
	}

	/**
	 * Activates the CMC Subcription, triggered on user activation
	 *
	 * @param   JUser   $user     - The JUser Obj
	 * @param   string  $success  - Success string
	 *
	 * @return  void
	 */

	public function userActivated($user, $success)
	{
		if (!$success)
		{
			return;
		}

		// Activates the user (after checking if he exists etc)
		CmcHelperRegistration::activateTempUser($user);

		return;
	}

	/**
	 * Unsubscribes the user from the list when user gets blocked / unblocked (Not implemented yet)
	 *
	 * @param   JUser  $user   - The JUser Obj
	 * @param   int    $block  - Is the user blocked or unblocked
	 *
	 * @return  void
	 */

	public function onBeforeUserBlocking($user, $block)
	{
		// May follow in a later release
	}

	/**
	 * Shows the Edit tab
	 *
	 * @param   object  $tab   - The tab
	 * @param   JUser   $user  - The JUser Obj
	 * @param   object  $ui    - The UI
	 *
	 * @return  string
	 */

	public function getEditTab($tab, $user, $ui)
	{
		return null;

		JHtml::_('stylesheet', JURI::root() . 'media/mod_cmc/css/cmc.css');
		$plugin = GetCmcTab::getPlugin();

		$listId = $plugin->params->get('listid', "");

		if (empty($listId))
		{
			return  JText::_("COM_CMC_LIST_NOT_SET");
		}

		$chimp = new cmcHelperChimp;

		$userlists = $chimp->listsForEmail($user->email);

		$html = '';

		if ($userlists && in_array($listId, $userlists))
		{
			// User is in list
			$html .= "<table><tr><td>" . JText::_("COM_CMC_SUBSCRIBED") . "</td></tr></table>";
		}
		else
		{
			// User has no subscription
			$html .= "<table><tr><td>" . JText::_("COM_CMC_NO_SUBSCRIPTION") . "</td></tr></table>";
		}


		return $html;
	}


	/**
	 * Saves the edited tab
	 *
	 * @param   object  $tab       - The tab
	 * @param   JUser   &$user     - The user
	 * @param   object  $ui        - The ui
	 * @param   object  $postdata  - The postdata
	 *
	 * @return  void
	 */

	public function saveEditTab($tab, &$user, $ui, $postdata)
	{
		// Check if user is in CMC
	}

	/**
	 * Gets the plugin
	 *
	 * @todo   Do not change system objects, nor extend attributes to it.
	 *
	 * @return PluginTable
	 */
	static public function getPlugin( )
	{
		global $_PLUGINS;

		static $plugin					=	null;

		if ( ! isset( $plugin ) ) {
			$plugin						=	$_PLUGINS->getLoadedPlugin( 'user', 'cmc' );

			if ( $plugin !== null ) {
				$plugin->relPath		=	$_PLUGINS->getPluginRelPath( $plugin );
				$plugin->livePath		=	$_PLUGINS->getPluginLivePath( $plugin );
				$plugin->absPath		=	$_PLUGINS->getPluginPath( $plugin );
				$plugin->xml			=	$_PLUGINS->getPluginXmlPath( $plugin );
				$plugin->params			=	$_PLUGINS->getPluginParams( $plugin );
			}
		}

		return $plugin;
	}

	/**
	 * Loads the list values for the plugin
	 *
	 * @return mixed
	 */
	public function loadLists()
	{
		$api = new cmcHelperChimp;
		$lists = $api->lists();

		// Get the plugin (No this->params) any longer
		$plugin = GetCmcTab::getPlugin();

		$key = 'id';
		$val = 'name';
		$options[] = array($key => '', $val => '-- ' . JText::_('Please select') . ' --');

		foreach ($lists['data'] as $list)
		{
			$options[] = array($key => $list[$key], $val => $list[$val]);
		}

		$attribs = "onchange='submitbutton(\"act=apply\")'";

		if ($options)
		{
			$content = JHtml::_(
				'select.genericlist', $options, 'params[listid]', $attribs, $key,
				$val, $plugin->params->get('listid', "")
			);
		}

		return $content;
	}


	/**
	 * Loads the possible lists
	 *
	 * @return  mixed
	 */

	public function loadFields()
	{
		$plugin = GetCmcTab::getPlugin();


		$listid = $plugin->params->get('listid', "");

		if (empty($listid))
		{
			$content = '<div style="float:left;">' . JText::_('PLG_CMCCB_NO_FIELDS') . '</div>';

			return $content;
		}

		$api = new cmcHelperChimp;
		$fields = $api->listMergeVars($listid);
		$key = 'tag';
		$val = 'name';
		$options = false;

		if ($fields)
		{
			foreach ($fields as $field)
			{
				$choices = '';

				if (isset($field['choices']))
				{
					foreach ($field['choices'] as $c)
					{
						$choices .= $c . '##';
					}

					$choices = substr($choices, 0, -2);
				}

				$req = ($field['req']) ? 1 : 0;

				if ($field[$key] == 'EMAIL')
				{
					if (isset($this->value) && !is_array($this->value))
					{
						$oldValue = $this->value;
						$this->value = array();
						$this->value[] = $oldValue;
					}

					$this->value[] = $field[$key] . ';' . $field['field_type'] . ';' . $field['name'] . ';' . $req . ';' . $choices;
				}

				if ($req)
				{
					$options[] = array($key => $field[$key] . ';' . $field['field_type'] . ';' . $field['name']
						. ';' . $req . ';' . $choices, $val => $field[$val] . "*"
					);
				}
				else
				{
					$options[] = array($key => $field[$key] . ';' . $field['field_type'] . ';' . $field['name'] . ';' . $req . ';' . $choices, $val => $field[$val]);
				}
			}
		}

		$attribs = 'multiple="multiple" size="8"';

		if ($options)
		{
			$content = "";

			$content .= JHtml::_('select.genericlist', $options, 'params[fields][]', $attribs, $key, $val, explode("|*|", $plugin->params->get('fields', "")));

		}
		else
		{
			$content = '<div style="float:left;">' . JText::_('PLG_CMCCB_NO_FIELDS') . '</div>';
		}

		return $content;
	}

	/**
	 * Loads the interests
	 *
	 * @return  mixed|string
	 */
	public function loadInterests()
	{
		$plugin = GetCmcTab::getPlugin();
		$listid = $plugin->params->get('listid', "");

		if (empty($listid))
		{
			$content = '<div style="float:left;">' . JText::_('PLG_CMCCB_NO_INTEREST_GROUPS') . '</div>';

			return $content;
		}

		$api = new cmcHelperChimp;
		$interests = $api->listInterestGroupings($listid);
		$key = 'id';
		$val = 'name';
		$options = false;

		if ($interests)
		{
			foreach ($interests as $interest)
			{
				if ($interest['form_field'] != 'hidden')
				{
					$groups = '';

					foreach ($interest['groups'] as $ig)
					{
						$groups .= $ig['name'] . '##' . $ig['name'] . '####';
					}

					$groups = substr($groups, 0, -4);
					$options[] = array($key => $interest[$key] . ';' . $interest['form_field'] . ';' . $interest['name'] . ';' . $groups, $val => $interest[$val]);
				}
			}
		}

		$attribs = 'multiple="multiple" size="8"';

		if ($options)
		{
			$content = JHtml::_('select.genericlist', $options, 'params[interests][]', $attribs, $key, $val, explode("|*|", $plugin->params->get('interests', "")));
		}
		else
		{
			$content = '<div style="float:left;">' . JText::_('PLG_CMCCB_NO_INTEREST_GROUPS') . '</div>';
		}

		return $content;
	}

	/**
	 * Creates an array with the mapped data
	 *
	 * @param   string  $raw   - the raw mapping definition as taken out of the params
	 * @param   array   $user  - array with the user data
	 *
	 * @return array
	 */
	public static function getMapping($raw, $user)
	{
		if (!$raw)
		{
			return array();
		}

		$lines = explode("\n", trim($raw));
		$groups = array();

		foreach ($lines as $line)
		{
			$map = explode('=', $line);

			if (strstr($map[1], ':'))
			{
				$parts = explode(':', $map[1]);
				$field = explode(' ', $user[$parts[0]]);

				$value = trim($field[(int) $parts[1]]);
			}
			else
			{
				$value = $user[trim($map[1])];
			}

			$groups[$map[0]] = $value;
		}

		return $groups;
	}

	public function onBeforeUserProfileEditDisplay($user, $tabContent)
	{
		$tabContent = 'balbla';//		die();

		die('labladasfd');
	}
}
