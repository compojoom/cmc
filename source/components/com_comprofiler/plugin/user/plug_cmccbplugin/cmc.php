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

if (!(defined('_VALID_CB') || defined('_JEXEC') || defined('_VALID_MOS')))
{
	die('Direct Access to this location is not allowed.');
}

// Check if CMC is installed
if (!@include_once JPATH_ADMINISTRATOR . "/components/com_cmc/helpers/xmlbuilder.php")
{
	return;
}

JLoader::register('CmcHelperChimp', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/chimp.php');
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
	 * Display our CMC fields at the registration
	 *
	 * @param   object  $tab       - The tab
	 * @param   JUser   $user      - The user
	 * @param   object  $ui        - The UI
	 * @param   object  $postdata  - The postdata
	 *
	 * @return string
	 */

	function getDisplayRegistration($tab, $user, $ui, $postdata)
	{
		JHtml::_('stylesheet', JURI::root() . 'media/mod_cmc/css/cmc.css');
		JHtml::_('behavior.framework', true);

		$listid = $this->params->get('listid', "");
		$interests = $this->params->get('interests', '');
		$fields = $this->params->get('fields', '');

		// Create the xml for JForm
		$builder = CmcHelperXmlbuilder::getInstance($this->params);

		// Load JS & Co
		JHtml::script(JURI::root() . '/media/plg_cb_cmc/js/cbcmc.js');

		JFactory::getDocument()->addScriptDeclaration("
			window.addEvent('domready', function(){
				document.id('cmc_check_newsletter').addEvent('click', function() {
					if(this.checked)
					{
						$$('input.cmc_req').addClass('required');
						$('cmc_newsletter').show();
					}
					else
					{
						$$('input.cmc_req').removeClass('required');
						$('cmc_newsletter').hide();
					}
				});

				$('cmc_newsletter').hide();
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

	function getDisplayTab($tab, $user, $ui)
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

	function saveRegistrationTab($tab, &$user, $ui, $postdata)
	{
		// Save User to temporary table- not active here
		if (!empty($postdata['cmc']['newsletter']))
		{
			// For the hidden field
			$listId = $postdata['cmc']['listid'];

			$updated = CmcHelperRegistration::isSubscribed($listId, $user->email);

			// Merge arrays to correct namespace
			$postdata['cmc']['groups'] = $postdata['cmc_groups'];
			$postdata['cmc']['interests'] = $postdata['cmc_interests'];

			if ($updated)
			{
				// Update users subscription with the new data
				CmcHelperRegistration::updateSubscription($user, $postdata['cmc']);
			}
			else
			{
				// Temporary save user in cmc database
				CmcHelperRegistration::saveTempUser($user, $postdata['cmc'], _CPLG_CB);
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

	function userDelete($user, $success)
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

	function userActivated($user, $success)
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

	function onBeforeUserBlocking($user, $block)
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

	function getEditTab($tab, $user, $ui)
	{
		return null;

		JHtml::_('stylesheet', JURI::root() . 'media/mod_cmc/css/cmc.css');
		JHtml::_('behavior.framework', true);

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

	function saveEditTab($tab, &$user, $ui, $postdata)
	{
		// Check if user is in CMC
	}


	/**
	 * Loads the list values for the plugin
	 *
	 * @return mixed
	 */
	function loadLists()
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

	function loadFields()
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

				$options[] = array($key => $field[$key] . ';' . $field['field_type'] . ';' . $field['name'] . ';' . $req . ';' . $choices, $val => $field[$val]);
			}
		}

		$attribs = 'multiple="multiple" size="8"';

		if ($options)
		{
			$content = "";
			//$content = "Fields: " . $this->params->get('fields', "");

			$content .= JHtml::_('select.genericlist', $options, 'params[fields][]', $attribs, $key, $val, explode("|*|", $this->params->get('fields', "")));


			$content .= '<script type="text/javascript">
				window.addEvent(\'domready\',function() {
				    $("jform_params_fields").addEvent( \'change\', function(){
					$("jform_params_fields").options[0].setProperty(\'selected\', \'selected\');

				    });
				});
				</script>';
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
	function loadInterests()
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
}
