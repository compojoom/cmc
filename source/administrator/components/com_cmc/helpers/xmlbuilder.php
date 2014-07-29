<?php
/**
 * @package    Cmc
 * @author     Yves Hoppe <yves@compojoom.com>
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CmcHelperRegistrationrender
 *
 * @since  1.4
 */
class CmcHelperXmlbuilder
{
	/**
	 * @var CmcHelperXmlbuilder
	 */
	private static $instance = null;

	/**
	 * The constructor
	 *
	 * @param   JRegistry  $options  - config object with everything we need
	 */
	public function __construct($options)
	{
		$this->newsletterCheckbox = $options->get('newsletterCheckbox', 1);
		$this->phoneFormat = $options->get("phoneFormat", "inter");
		$this->dateFormat = $options->get("dateFormat", "%Y-%m-%d");
		$this->address2 = $options->get("address2", 0);
		$this->listId = $options->get('listid', "");
		$this->interests = $options->get('interests', '');
		$this->fields = $options->get('fields', '');
		$this->introText = $options->get("intro-text");
		$this->outroText = $options->get("outro-text");
	}

	/**
	 * Gets a instance (SINGLETON) of this class
	 *
	 * @param   JRegistry  $config  - configration object
	 *
	 * @return CmcHelperXmlbuilder
	 */
	public static function getInstance($config)
	{
		$md5 = md5(serialize($config));

		if (!isset(self::$instance[$md5]))
		{
			self::$instance[$md5] = new CmcHelperXmlbuilder($config);
			$lang = JFactory::getLanguage();
			$lang->load('com_cmc', JPATH_ADMINISTRATOR);
		}

		return self::$instance[$md5];
	}

	/**
	 * Builds the necessary XML for JForm
	 *
	 * @return string
	 */
	public function build()
	{
		$html = '<?xml version="1.0" encoding="UTF-8" ?>';
		$html .= "<form>";
		$html .= '<fields name="cmc">';
		$html .= '<fieldset name="cmc" label="COM_CMC_NEWSLETTER">';

		// Intro-Text
		if (isset($this->introText))
		{
			$html .= '
						<field
							name="intro-text"
							type="spacer"
							class="text"
							label="' . htmlspecialchars(JText::_($this->introText)) . '"
						/>
						';
		}

		// Adding Newsletter Checkbox
		if ($this->newsletterCheckbox)
		{
			$html .= '
					<field
						name="newsletter"
						type="checkbox"
						id="newsletter"
						description="COM_CMC_NEWSLETTER_SUBSCRIBE"
						value="1"
						default="0"
						class="submitMerge inputbox cmc-checkboxes cmc-checkbox-subscribe"
						labelclass="form-label cmc-label"
						label="COM_CMC_NEWSLETTER"
					/>
					';
		}

		$html .= '<field type="hidden" name="listid" default="' . $this->listId . '" />';

		if (is_array($this->fields))
		{
			$html .= '</fieldset>';
			$html .= '</fields>';
			$html .= '<fields name="cmc_groups">';
			$html .= '<fieldset name="cmc_groups" label="COM_CMC_NEWSLETTER_DATA">';


			foreach ($this->fields as $f)
			{
				$field = explode(';', $f);
				$html .= $this->createXmlField($field);
			}
		}

		if (is_array($this->interests) )
		{
			$html .= '</fieldset>';
			$html .= '</fields>';
			$html .= '<fields name="cmc_interests">';
			$html .= '<fieldset name="cmc_interests" label="COM_CMC_NEWSLETTER_INTERESTS">';

			foreach ($this->interests as $i)
			{
				$interest = explode(';', $i);
				$groups = explode('####', $interest[3]);

				switch ($interest[1])
				{
					case 'checkboxes':
						$html .= '<field type="checkboxes" name="' . $interest[0] . '"
								class="submitMerge inputbox cmc-checkboxes"
								labelclass="form-label cmc-label"
								label="' . $interest[2] . '"
								id="' . $interest[0] . '" >';

						foreach ($groups as $g)
						{
							$o = explode('##', $g);
							$html .= '<option value="' . $o[0] . '">' . JText::_($o[1]) . '</option>';
						}

						$html .= '</field>';
						break;
					case 'radio':
						$html .= '<field
							name="' . $interest[0] . '"
							type="radio"
							default="0"
							label="' . $interest[2] . '"
							labelclass="form-label cmc-label">';

						foreach ($groups as $g)
						{
							$o = explode('##', $g);
							$html .= '<option value="' . $o[0] . '">' . JText::_($o[1]) . '</option>';
						}

						$html .= '</field>';
						break;
				}
			}
		}

		// Intro-Text
		if (isset($this->outroText))
		{
			$html .= '
						<field
							name="outro-text"
							type="spacer"
							class="text"
							label="' . htmlspecialchars(JText::_($this->outroText)) . '"
						/>
						';
		}

		$html .= '</fieldset>';
		$html .= '</fields>';
		$html .= '</form>';

		return $html;
	}

	/**
	 * Returns an xml formatted form field
	 *
	 * @param   array  $field  - the field array
	 *
	 * @return  string
	 */
	public function createXmlField($field)
	{
		$fieldtype = $field[1];

		// We need to return a xml formatted object for the joomla form

		if ($fieldtype == "text")
		{
			return $this->xmltext($field);
		}
		elseif ($fieldtype == "dropdown")
		{
			return $this->dropdown($field);
		}
		elseif ($fieldtype == "radio")
		{
			return $this->radio($field);
		}
		elseif ($fieldtype == "date")
		{
			return $this->date($field);
		}
		elseif ($fieldtype == "birthday")
		{
			return $this->birthday($field);
		}
		elseif ($fieldtype == "phone")
		{
			return $this->phone($field);
		}
		elseif ($fieldtype == "address")
		{
			return $this->address($field);
		}
		else
		{
			// Fallback, maybe should be a 404 not supported
			return $this->xmltext($field);
		}
	}

	/**
	 * Function that double encodes the entities in a text - removing any html tags from text
	 *
	 * @param   string  $text  - the text
	 *
	 * @return string
	 */
	private function noEntities($text)
	{
		return htmlspecialchars(htmlspecialchars($text));
	}

	/**
	 * Returns an xml formatted form field
	 *
	 * @param   array  $field   - the field array
	 * @param   array  $config  - the field type
	 *
	 * @return string
	 */
	public function xmltext($field, $config = array())
	{
		// Structure: EMAIL;email;Email Address;1;
		$validate = array(
			'email' => 'validate-email',
			'number' => 'validate-digits',
			'url' => 'validate-url',
			'phone' => 'validate-digits'
		);

		$class = array();
		$type = isset($config['type']) ? $config['type'] : 'text';

		if (isset($config['class']))
		{
			$class[] = $config['class'];
		}

		if (isset($validate[$field[1]]))
		{
			$class[] = $validate[$field[1]];
		}

		// Double escape as we don't allow html in the label
		$title = $this->noEntities(JText::_($field[2]));

		$req = ($field[3]) ? ' cmc_req' : '';

		$x = "<field\n";
		$x .= "name=\"" . $field[0] . "\"\n";
		$x .= "type=\"" . $type . "\"\n";
		$x .= "id=\"" . $field[0] . "\"\n";

		// Do we want a description here?
		$x .= "description=\"\"\n";
		$x .= "filter=\"string\"\n";
		$x .= 'class="inputbox input-medium' . $req . ' ' . implode(' ', $class) . '" ';
		$x .= 'labelclass="form-label cmc-label" ';
		$x .= 'hint="' . $title . ' ' . ($req ? '*' : '') . '" ';
		$x .= "label=\"" . $title . "\"\n";

		if ($field[3])
		{
			$x .= ' required="required"';
		}

		$x .= "/>\n";

		return $x;
	}

	/**
	 * Returns a drop-down input box element
	 *
	 * @param   array  $params  - Example FNAME;text;First Name;0;""
	 *
	 * @return string
	 */
	public function dropdown($params)
	{
		$choices = explode('##', $params[4]);
		$req = ($params[3]) ? ' cmc_req' : '';

		// Double escape as we don't allow html in the label
		$title = $this->noEntities(JText::_($params[2]));

		$select = '<field
			id="' . $params[0] . '"
			name="' . $params[0] . '"
			type="list"
			label="' . $title . '"
			labelclass="form-label cmc-label"'
			. ($params[3] ? ' required="required" ' : ' ') .
			'default="0"
			class="inputbox input-medium' . $req . '">';

		if (!$params[3])
		{
			$select .= '<option value=""></option>';
		}

		foreach ($choices as $ch)
		{
			$select .= '<option value="' . $ch . '">' . $ch . '</option>';
		}

		$select .= '</field>';

		return $select;
	}

	/**
	 * Returns a radio input box element
	 *
	 * @param   array  $params  - Example FNAME;text;First Name;0;""
	 *
	 * @return  string
	 */
	public function radio($params)
	{
		$choices = explode('##', $params[4]);
		$req = ($params[3]) ? 'cmcreq' : '';
		$title = $this->noEntities(JText::_($params[2]));

		$radio = '<field
			name="' . $params[0] . '"
			type="radio"
			default="0"
			class="inputbox ' . $req . '"'
			. ($params[3] ? ' required="required" ' : ' ') .
			'labelclass="form-label cmc-label"
			label="' . $title . '">';

		foreach ($choices as $ch)
		{
			$radio .= '<option value="' . $ch . '">' . $ch . '</option>';
		}

		$radio .= '</field>';

		return $radio;
	}

	/**
	 * Returns date input box element
	 *
	 * @param   array  $params  - Example FNAME;text;First Name;0;""
	 *
	 * @return  string
	 */
	public function date($params)
	{
		$title = $this->noEntities(JText::_($params[2]));
		$req = ($params[3]) ? ' cmc_req' : '';

		return '<field
			name="' . $params[0] . '"
			type="calendar"
			class="inputbox input-small' . $req . '"
			labelclass="form-label cmc-label"
			label="' . $title . '" '
			. ($params[3] ? ' required="required" ' : ' ') .
			'format="' . $this->dateFormat . '"
			maxlength="10"
		/>';
	}

	/**
	 * Returns a birthday input box element
	 *
	 * @param   array  $params  - Example FNAME;text;First Name;0;""
	 *
	 * @return  string
	 */
	public function birthday($params)
	{
		$req = ($params[3]) ? ' cmc_req' : '';
		$title = $this->noEntities(JText::_($params[2]));

		$address = '<field type="birthday"
					id="' . $params[0] . '_month"
					name="birthday"
					class="inputbox input-small cmc-birthday' . $req . '"
					labelclass="form-label cmc-label"'
					. ($params[3] ? ' required="required" ' : ' ') .
					'label="' . $title . '" />';

		return $address;
	}

	/**
	 * Returns phone input box element
	 *
	 * @param   array  $params  - Example FNAME;text;First Name;0;""
	 *
	 * @return  string
	 */
	public function phone($params)
	{
		$req = ($params[3]) ? ' cmc_req' : '';
		$title = $this->noEntities(JText::_($params[2]));
		$inter = '';

		if ($this->phoneFormat == 'inter')
		{
			$inter = 'inter';
		}

		$phone = '
		<field name="' . $params[0] . '"
		type="phone"
		id="cmc-phone-' . $params[0] . '"
		class="phone input-medium validate-digits ' . $inter . $req . '"
		labelclass="form-label cmc-label"'
		. ($params[3] ? ' required="required" ' : ' ') .
		'size="40" ' .
		'hint="' . $title . ' ' . ($req ? '*' : '') . '" ' .
		'label="' . $title . '"
		/>';

		return $phone;
	}

	/**
	 * Returns address input box element
	 *
	 * @param   array  $params  - Example FNAME;text;First Name;0;""
	 *
	 * @return  string
	 */
	public function address($params)
	{
		$req = ($params[3]) ? ' cmc_req' : '';
		$title = $this->noEntities(JText::_($params[2]));

		$address = '<field type="spacer" name="addr" label="' . $title . '" />';
		$address .= '<field
                name="' . $params[0] . '][addr1"
                type="text" default=""
                label="' . JText::_('CMC_STREET_ADDRESS') . '"
                class="inputbox input-medium' . $req . '"
                ' . ($params[3] ? ' required="required" ' : ' ') . '
                labelclass="form-label cmc-label"
                />';

		if ($this->address2)
		{
			$address .= '<field
	                name="' . $params[0] . '][addr2"
	                type="text" default=""
	                label="' . JText::_('CMC_STREET_ADDRESS2') . '"
	                class="inputbox input-medium' . $req . '"
	                ' . ($params[3] ? ' required="required" ' : ' ') . '
	                labelclass="form-label cmc-label"
	                />';
		}

		$address .= '<field
                name="' . $params[0] . '][city"
                type="text" default=""
                label="' . JText::_('CMC_CITY') . '"
                class="inputbox input-medium' . $req . '"
                ' . ($params[3] ? ' required="required" ' : ' ') . '
                labelclass="form-label cmc-label"
                 />';
		$address .= '<field
                name="' . $params[0] . '][state"
                type="text" default=""
                label="' . JText::_('CMC_STATE') . '"
                class="inputbox input-medium' . $req . '"
                ' . ($params[3] ? ' required="required" ' : ' ') . '
                labelclass="form-label cmc-label"
                />';
		$address .= '<field
                name="' . $params[0] . '][zip"
                type="text" default=""
                label="' . JText::_('CMC_ZIP') . '"
                class="inputbox input-medium' . $req . '"
                ' . ($params[3] ? ' required="required" ' : ' ') . '
                labelclass="form-label cmc-label"
                />';

		$address .= $this->getCountryDropdown($params[0], $params[0], JText::_('CMC_COUNTRY'), $req) . '<br />';

		return $address;
	}

	/**
	 * Returns date input box element
	 *
	 * @param   string   $name   - Name of the select
	 * @param   int      $id     - The date format for this field
	 * @param   string   $title  - The field name prefix
	 * @param   boolean  $req    - Is the field required?
	 *
	 * @return string
	 */
	private function getCountryDropdown($name, $id, $title, $req)
	{
		$options = CmcHelperCountries::getCountries();
		$select = '<field
			id="' . $id . '"
			name="' . $name . '][country"
			type="list"
			label="' . $this->noEntities($title) . '"
			default="0"
			class="inputbox input-medium"
			' . ($req ? ' required="required" ' : ' ') . '
			labelclass="form-label cmc-label"
			>';

		$select .= '<option value=""></option>';

		foreach ($options as $k => $v)
		{
			$select .= '<option value="' . $k . '">' . ucwords(strtolower($v)) . '</option>';
		}

		$select .= '</field>';

		return $select;
	}
}
