<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

JLoader::discover('cmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

/**
 * Class PlgCommunityCmc
 *
 * @since  1.4
 */
class PlgCommunityCmc extends JPlugin
{

	/**
	 * Constructor
	 *
	 * @param   object &$subject The object to observe
	 * @param   array  $config   An optional associative array of configuration settings.
	 */
	public function __construct(&$subject, $config = array())
	{
		$jlang = JFactory::getLanguage();
		$jlang->load('com_cmc', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('com_cmc', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
		$jlang->load('com_cmc', JPATH_ADMINISTRATOR, null, true);
		$jlang->load('com_cmc.sys', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('com_cmc.sys', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
		$jlang->load('com_cmc.sys', JPATH_ADMINISTRATOR, null, true);

		parent::__construct($subject, $config);
	}

	/**
	 * Manupulates the registration form
	 *
	 * @param   string &$data - registration form data
	 *
	 * @return mixed
	 */
	public function onUserRegisterFormDisplay(&$data)
	{
		// Load the funky stuff
		Jhtml::_('behavior.framework');
		JHtml::stylesheet('media/plg_community_cmc/css/style.css');
		JHtml::script('media/plg_community_cmc/js/cmc.js');

		$html = array();

		// Create the xml for JForm
		$builder = CmcHelperXmlbuilder::getInstance($this->params);
		$xml = $builder->build();

		$form = new JForm('myform');
		$form->addFieldPath(JPATH_ADMINISTRATOR . '/components/com_cmc/models/fields');
		$form->load($xml);

		$html[] = '<li>';
		$html[] = '<label class="form-label" for="cmc_newsletter">' . JText::_('COM_CMC_NEWSLETTER') . '</label>';
		$html[] = '<div class="form-field">';
		$html[] = '<input type="checkbox" name="cmc_newsletter" id="cmc_newsletter" value="1" style="float:left" />';
		$html[] = '<label for="cmc_newsletter" id="cmc_newsletter-lbl" style="margin-left: 20px">'
			. JText::_('COM_CMC_NEWSLETTER_SUBSCRIBE') . '</label>';
		$html[] = '</div>';
		$html[] = '</li>';

		$fieldsets = $form->getFieldsets();

		foreach ($fieldsets as $key => $value)
		{
			$fields = $form->getFieldset($key);

			foreach ($fields as $field)
			{
				$html[] = '<li class="cmc-newsletter" style="display: none">';
				$html[] = $field->label;
				$html[] = '<div class="form-field">' . $field->input . '</div>';
				$html[] = '</li>';
			}
		}

		$pos = strpos($data, '<li class="form-action has-seperator">');
		$data = substr($data, 0, $pos) . implode('', $html) . substr($data, $pos);
	}

	public function onRegisterValidate($params)
	{
		var_dump($params);
		die();
		$input = JFactory::getApplication()->input;
		if ($input->getInt("cmc_newsletter") != "1")
		{
			// Abort if Newsletter is not checked
			return true;
		}
		else
		{
			CmcHelperRegistration::saveTempUser($data["id"], $data["cmc"], _CPLG_JOMSOCIAL);
		}

		if ($data["block"] == 1)
		{
			// Temporary save user
			CmcHelperRegistration::saveTempUser($data["id"], $data["cmc"], _CPLG_JOOMLA);
		}
		else
		{
			if (!$isNew)
			{
				// Activate User to Mailchimp
				CmcHelperRegistration::activateTempUser(JFactory::getUser($data["id"]));
			}
			var_dump($params);
			die();
		}
	}

	function onUserAfterSave($data, $isNew, $isNew, $error)
	{
		var_dump($data);
		var_dump($isNew);
		var_dump($isNew);
		var_dump($error);
		die('onuseraftersave');
	}
}
