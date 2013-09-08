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
	 * Manupulates the registration form
	 *
	 * @param   object $data - registration form data
	 *
	 * @return mixed
	 */
	public function onUserRegisterFormDisplay(&$data)
	{
		libxml_use_internal_errors(true);
		$dom = new DOMDocument;
		$dom->loadHTML($data);

		// Find the before last li element
		$xp = new DOMXpath($dom);
		$nodes = $xp->query('//ul/li[last()-1]');


		$listid = $this->params->get('listid', "");
		$interests = $this->params->get('interests', '');
		$fields = $this->params->get('fields', '');

		$renderer = CmcHelperRegistrationrender::getInstance();
		$renderer->phoneFormat = $this->params->get("phoneFormat", "inter");
		$renderer->dateFormat = $this->params->get("dateFormat", "%Y-%m-%d");
		$renderer->address2 = $this->params->get("address2", 0);

		$form = new JForm('myform');
		$form->addFieldPath(JPATH_ADMINISTRATOR . '/components/com_cmc/models/fields');
		$ret = '';

		// Render Content
		$ret .= $renderer->renderForm(
			$fields, $interests
		);

		$form->load($ret);

		$fieldsets = $form->getFieldsets();

		foreach ($fieldsets as $key => $value)
		{
			echo JText::_($value->label);

			$fields = $form->getFieldset($key);

			foreach ($fields as $field)
			{
				echo $field->label;
				echo $field->input;
			}
		}
	}
}
