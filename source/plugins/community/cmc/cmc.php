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
	 * @param   object  $data  - registration form data
	 *
	 * @return mixed
	 */
	public function onUserRegisterFormDisplay(&$data)
	{
//		var_dump($this->params);
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
		$form->addFieldPath(JPATH_ADMINISTRATOR .'/components/com_cmc/models/fields');
		$ret = '';

		$ret = "<form>\n";
		$ret .= "<fields name=\"cmc\">\n";
		$ret .= "<fieldset name=\"cmc\" label=\"PLG_USER_CMC_CMC_LABEL\">\n";

//		$ret = '<li>';
//		$ret .= '<input type="checkbox" name="cmc[newsletter]" id="cmc[newsletter]" value="1" />';
//		$ret .= '<label for="cmc[newsletter]" id="cmc[newsletter]-lbl">' . JText::_('PLG_CMCCB_NEWSLETTER') . '</label>';
//		$ret .= "<div id=\"cmc_newsletter\" style=\"display: none;\">\n";
//		var_dump($this->params->get("dateFormat"));
		// Render Content
		$ret .= $renderer->renderForm(
			$this->params->get('intro-text', ""),
			$this->params->get('outro-text-1', ""), $this->params->get('outro-text-2', ""),
			$fields, $interests, $listid, _CPLG_JOOMLA
		);

		$ret .= "</fieldset>\n";
		$ret .= "</fields>\n";
		$ret .= "</form>";

//		$ret .= '</div>';
//
//		// TODO move to document.ready in separate file
//		$ret .= "<script type=\"text/javascript\">";
//
//		$ret .= 'document.id("cmc[newsletter]").addEvent("click", function() {';
//		$ret .= 'document.id("cmc_newsletter").setStyle("display", "block");';
//		$ret .= "});";
//		$ret .= "</script>";
//		$ret .= '</li>';
		var_dump($ret);

		var_dump($form->load($ret));

		$fields = $form->getFieldset('cmc');

		foreach($fields as $field) {
			echo $field->label;
			echo $field->input;
		}
//		$fragment = $dom->createDocumentFragment();
//		$fragment->appendXML($ret);
//		$nodes->item(0)->appendChild($fragment);
//
//		$data = $dom->saveHTML();
//		var_dump($ret);

	}
}
