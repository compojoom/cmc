<?php
/**
 * @package    Cmc
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

JLoader::discover('cmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

/**
 * Class PlgUserCmc
 *
 * @since  1.4
 */
class PlgUserCmc extends JPlugin
{
	/**
	 * Prepares the form
	 *
	 * @param   string  $form  - the form
	 * @param   object  $data  - the data object
	 *
	 * @return bool
	 */

	public function onContentPrepareForm($form, $data)
	{
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');

			return false;
		}

		// Check we are manipulating a valid form.
		$name = $form->getName();

		if (!in_array(
			$name, array('com_admin.profile', 'com_users.user',
				'com_users.profile', 'com_users.registration'
			)
		))
		{
			return true;
		}

		$lang = JFactory::getLanguage();
		$lang->load('plg_user_cmc', JPATH_ADMINISTRATOR);

		JHtml::_('behavior.framework');
		JHtml::script('media/plg_user_cmc/js/cmc.js');
		$renderer = CmcHelperXmlbuilder::getInstance($this->params);

		// Render Content
		$html = $renderer->build();

		// Inject fields into the form
		$form->load($html, false);

		return true;
	}


	/**
	 * Prepares the form
	 *
	 * @param   object   $data    - the users data
	 * @param   boolean  $isNew   - is the user new
	 * @param   object   $result  - the db result
	 * @param   string   $error   - the error message
	 *
	 * @return   boolean
	 */

	public function onUserAfterSave($data, $isNew, $result, $error)
	{
		$userId = JArrayHelper::getValue($data, 'id', 0, 'int');

		if ($userId && $result && isset($data['cmc']) && (count($data['cmc'])))
		{
			if ($data["cmc"]["newsletter"] != "1" && $isNew != false)
			{
				// Abort if Newsletter is not checked
				return true;
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
				else
				{
					// Directly activate user
					CmcHelperRegistration::activateDirectUser(
						JFactory::getUser($data["id"]), $data["cmc"], _CPLG_JOOMLA
					);
				}
			}
		}

		return true;
	}
}
