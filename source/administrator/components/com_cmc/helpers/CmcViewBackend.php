<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

/**
 * View class for CMC Backend
 *
 * @since  3.0.0
 */
class CmcViewBackend extends CmcView
{
	/**
	 * The title
	 *
	 * @var    string
	 */
	protected $_ctitle = "";

	/**
	 * Slogan
	 *
	 * @var    string
	 */
	protected $_cslogan = "";

	/**
	 * Menu entry
	 *
	 * @var    string
	 */
	protected $_cmenuEntry = "";

	/**
	 * Set title
	 *
	 * @param   String  $title      - The title
	 * @param   String  $slogan     - The slogan
	 * @param   String  $menuEntry  - The menu entry
	 *
	 * @return void
	 */
	public function setCTitle($title, $slogan, $menuEntry)
	{
		$this->_ctitle = $title;
		$this->_cslogan = $slogan;
		$this->_cmenuEntry = $menuEntry;
	}

	/**
	 * Execute and display a Matukio template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 *
	 * @see     JViewLegacy::loadTemplate()
	 * @since   12.2
	 */
	public function display($tpl = null)
	{
		JHtml::_('formbehavior.chosen', 'select');

		try
		{
			$result = $this->loadTemplate($tpl);
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
			$result = '<div>' . $e->getMessage() . '</div>';
		}


		if ($result instanceof Exception)
		{
			return $result;
		}

		echo CompojoomHtmlCtemplate::getHead(
			CmcHelperBasic::getMenu(),
			$this->_cmenuEntry,
			$this->_ctitle,
			$this->_cslogan
		);

		echo '<!-- Start CMC by compojoom.com -->';
		echo '<div id="cmc_holder">';

		// Content from the template
		echo $result;

		// Copyright
		echo CompojoomHtmlCTemplate::getFooter(CmcHelperBasic::footer());
		echo '</div>';
		echo '<!-- End CMC by compojoom.com -->';

		// Minify css & js
		CompojoomHtml::external(
			CompojoomHtml::getScriptQueue('cmc'),
			CompojoomHtml::getCSSQueue('cmc'),
			'media/com_cmc/cache', CmcHelperSettings::_('js_minify', '1'),
			CmcHelperSettings::_('css_minify', '1')
		);
	}
}
