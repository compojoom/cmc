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

// Check if CMC is installed
if (!@include_once JPATH_ADMINISTRATOR . "/components/com_cmc/helpers/xmlbuilder.php")
{
	return;
}

// Load Compojoom library
require_once JPATH_LIBRARIES . '/compojoom/include.php';

JLoader::register('CmcHelperChimp', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/chimp.php');
JLoader::register('CmcHelperRegistration', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/registration.php');
JLoader::register('CmcHelperRegistrationrender', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/registrationrender.php');

global $_PLUGINS;
$_PLUGINS->registerFunction('onUserActive', 'userActivated', 'getCmcTab');
$_PLUGINS->registerFunction('onAfterDeleteUser', 'userDelete', 'getCmcTab');
$_PLUGINS->registerFunction('onBeforeUserBlocking', 'onBeforeUserBlocking', 'getCmcTab');

$language = JFactory::getLanguage();

// Load language
$language->load('plg_cmccb', JPATH_ADMINISTRATOR, 'en-GB', true);
$language->load('plg_cmccb', JPATH_ADMINISTRATOR, $language->getDefault(), true);
$language->load('plg_cmccb', JPATH_ADMINISTRATOR, null, true);
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

		$listid = $this->params->get('listid', "");
		$interests = $this->params->get('interests', '');
		$fields = $this->params->get('fields', '');

		// Create the xml for JForm
		$builder = CmcHelperXmlbuilder::getInstance($this->params);

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
			$fields = explode("|*|", $this->params->get('fields', ''));
			$builder->fields = $fields;
		}

		if (!empty($interests))
		{
			$interests = explode("|*|", $this->params->get('interests', ''));
			$builder->interests = $interests;
		}

		$xml = $builder->build();
		$form = new JForm('myform');
		$form->addFieldPath(JPATH_ADMINISTRATOR . '/components/com_cmc/models/fields');
		$form->load($xml);

		$fieldsets = $form->getFieldsets();

		$ret = "\t<tr>\n";
		$ret .= "\t\t<td class='titleCell'>" . JText::_('PLG_CMCCB_SUBSCRIPTION') . ":</td>\n";
		$ret .= "\t\t<td class='fieldCell'>";

		// Display
		$ret .= '<input type="checkbox" name="cmc[newsletter]" id="cmc_check_newsletter" value="1" />';
		$ret .= '<label for="cmc_check_newsletter" id="cmc_newsletter_lbl">' . JText::_('PLG_CMCCB_NEWSLETTER') . '</label>';
		$ret .= "</td>\n";
		$ret .= "</tr>\n";
		$ret .= "\t<tr>\n";
		$ret .= "<td colspan='2' id='cmc_td_newsletter' style=''>\n";
		$ret .= "<div id=\"cmc_newsletter\" style=\"display: block;\">\n";

		// Render Content
		foreach ($fieldsets as $key => $value)
		{
			if ($key != "cmc")
			{
				$ret .= '<div class="ctitle"><h3>' . JText::_($value->label) . '</h3></div>';
				$fields = $form->getFieldset($key);
				$ret .= "<table class=\"contentpane " . $key . "\" style=\"width: 100%\">";

				foreach ($fields as $field)
				{
					$ret .= '<tr>';
					$ret .= '<td class="titleCell">';
					$ret .= $field->label;
					$ret .= '</td>';
					$ret .= '<td class="fieldCell">';
					$ret .= '<div class="form-field">' . $field->input . '</div>';
					$ret .= '</td>';
					$ret .= '</tr>';
				}

				$ret .= "</table>";
			}
		}

		$ret .= '<input type="hidden" name="cmc[listid]" id="cmc_listid" value="' . $listid . '" />';

		// End open tables / divs
		$ret .= "</div>\n";
		$ret .= "</td>\n";
		$ret .= "</tr>\n";
		$ret .= "\t</tr>\n";

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
		// Show the CMC Subscription options
		// return $this->getEditTab($tab, $user, $ui);
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

	public function saveRegistrationTab($tab, &$user, $ui, $postdata)
	{
		// Save User to temporary table- not active here
		if (!empty($postdata['cmc']['newsletter']))
		{
			// For the hidden field
			$listId = $postdata['cmc']['listid'];

			$mappedData = $this->getMapping($this->params->get('mapfields'), $postdata);

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

		$listId = $this->params->get('listid', "");

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
	 * Loads the list values for the plugin
	 *
	 * @return mixed
	 */
	public function loadLists()
	{
		$api = new cmcHelperChimp;
		$lists = $api->lists();

		$key = 'id';
		$val = 'name';
		$options[] = array($key => '', $val => '-- ' . JText::_('Please select') . ' --');

		foreach ($lists['data'] as $list)
		{
			$options[] = array($key => $list[$key], $val => $list[$val]);
		}

		$attribs = "onchange='submitbutton(\"applyPlugin\")'";

		if ($options)
		{
			$content = JHtml::_(
				'select.genericlist', $options, 'params[listid]', $attribs, $key,
				$val, $this->params->get('listid', "")
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
		$listid = $this->params->get('listid', "");

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

			$content .= JHtml::_('select.genericlist', $options, 'params[fields][]', $attribs, $key, $val, explode("|*|", $this->params->get('fields', "")));

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
		$listid = $this->params->get('listid', "");

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
			$content = JHtml::_('select.genericlist', $options, 'params[interests][]', $attribs, $key, $val, explode("|*|", $this->params->get('interests', "")));
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
}
