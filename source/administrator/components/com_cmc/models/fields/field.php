<?php
/**
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       22.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CmcField
 *
 * @since  1.0
 */
abstract class CmcField extends JFormField
{
	/**
	 * The constructor
	 *
	 * @param   object  $form  The form to attach to the form field object.
	 */
	public function __construct($form = null)
	{
		parent::__construct($form);

		$this->checkCmcInstall();
	}

	/**
	 * Checks if com_cmc is installed
	 *
	 * @return void
	 */
	public function checkCmcInstall()
	{
		if (!JComponentHelper::getParams('com_cmc')->get('api_key', ''))
		{
			$appl = JFactory::getApplication();
			$appl->redirect('index.php?option=com_cmc', JText::_('MOD_CMC_YOU_NEED_TO_PROVIDE_API_KEY'));
		}
	}

	/**
	 * Gets component settings
	 *
	 * @param   string  $key      - setting name
	 * @param   string  $default  - default value
	 *
	 * @return mixed
	 */
	public function getSettings($key, $default = '')
	{
		return JComponentHelper::getParams('com_cmc')->get($key, $default);
	}
}
