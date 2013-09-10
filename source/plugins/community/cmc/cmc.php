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
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
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
	 * @param   string  &$data  - registration form data
	 *
	 * @return mixed
	 */
	public function onUserRegisterFormDisplay(&$data)
	{
		// Load the css file
		JHtml::stylesheet('media/plg_community_cmc/css/style.css');

		$html = array();
		$listid = $this->params->get('listid', "");
		$interests = $this->params->get('interests', '');
		$fields = $this->params->get('fields', '');

		$renderer = CmcHelperXmlbuilder::getInstance();
		$renderer->phoneFormat = $this->params->get("phoneFormat", "inter");
		$renderer->dateFormat = $this->params->get("dateFormat", "%Y-%m-%d");
		$renderer->address2 = $this->params->get("address2", 0);

		$form = new JForm('myform');
		$form->addFieldPath(JPATH_ADMINISTRATOR . '/components/com_cmc/models/fields');
		$ret = '';

		// Render Content
		$ret .= $renderer->renderForm($fields, $interests, $listid);

		$form->load($ret);

		$fieldsets = $form->getFieldsets();

		foreach ($fieldsets as $key => $value)
		{
			$fields = $form->getFieldset($key);

			foreach ($fields as $field)
			{
				$html[] = '<li>';
				$html[] = $field->label;
				$html[] = '<div class="form-field">' . $field->input . '</div>';
				$html[] = '</li>';
			}
		}

		$pos = strpos($data, '<li class="form-action has-seperator">');
		$data = substr($data, 0, $pos) . implode('', $html) . substr($data, $pos);
	}
}
