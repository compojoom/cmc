<?php
/**
 * @package    Cmc
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       06.09.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__) . "/registration.php");

/**
 * Class CmcHelperRegistrationrender
 *
 * @since  1.4
 */
class CmcHelperRegistrationrender
{

	public $countries = array(
		'AF' => 'AFGHANISTAN',
		'AX' => 'ÅLAND ISLANDS',
		'AL' => 'ALBANIA',
		'DZ' => 'ALGERIA',
		'AS' => 'AMERICAN SAMOA',
		'AD' => 'ANDORRA',
		'AO' => 'ANGOLA',
		'AI' => 'ANGUILLA',
		'AQ' => 'ANTARCTICA',
		'AG' => 'ANTIGUA AND BARBUDA',
		'AR' => 'ARGENTINA',
		'AM' => 'ARMENIA',
		'AW' => 'ARUBA',
		'AU' => 'AUSTRALIA',
		'AT' => 'AUSTRIA',
		'AZ' => 'AZERBAIJAN',
		'BS' => 'BAHAMAS',
		'BH' => 'BAHRAIN',
		'BD' => 'BANGLADESH',
		'BB' => 'BARBADOS',
		'BY' => 'BELARUS',
		'BE' => 'BELGIUM',
		'BZ' => 'BELIZE',
		'BJ' => 'BENIN',
		'BM' => 'BERMUDA',
		'BT' => 'BHUTAN',
		'BO' => 'BOLIVIA, PLURINATIONAL STATE OF',
		'BA' => 'BOSNIA AND HERZEGOVINA',
		'BW' => 'BOTSWANA',
		'BV' => 'BOUVET ISLAND',
		'BR' => 'BRAZIL',
		'IO' => 'BRITISH INDIAN OCEAN TERRITORY',
		'BN' => 'BRUNEI DARUSSALAM',
		'BG' => 'BULGARIA',
		'BF' => 'BURKINA FASO',
		'BI' => 'BURUNDI',
		'KH' => 'CAMBODIA',
		'CM' => 'CAMEROON',
		'CA' => 'CANADA',
		'CV' => 'CAPE VERDE',
		'KY' => 'CAYMAN ISLANDS',
		'CF' => 'CENTRAL AFRICAN REPUBLIC',
		'TD' => 'CHAD',
		'CL' => 'CHILE',
		'CN' => 'CHINA',
		'CX' => 'CHRISTMAS ISLAND',
		'CC' => 'COCOS (KEELING) ISLANDS',
		'CO' => 'COLOMBIA',
		'KM' => 'COMOROS',
		'CG' => 'CONGO',
		'CD' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE',
		'CK' => 'COOK ISLANDS',
		'CR' => 'COSTA RICA',
		'CI' => 'CÔTE D\'IVOIRE',
		'HR' => 'CROATIA',
		'CU' => 'CUBA',
		'CY' => 'CYPRUS',
		'CZ' => 'CZECH REPUBLIC',
		'DK' => 'DENMARK',
		'DJ' => 'DJIBOUTI',
		'DM' => 'DOMINICA',
		'DO' => 'DOMINICAN REPUBLIC',
		'EC' => 'ECUADOR',
		'EG' => 'EGYPT',
		'SV' => 'EL SALVADOR',
		'GQ' => 'EQUATORIAL GUINEA',
		'ER' => 'ERITREA',
		'EE' => 'ESTONIA',
		'ET' => 'ETHIOPIA',
		'FK' => 'FALKLAND ISLANDS (MALVINAS)',
		'FO' => 'FAROE ISLANDS',
		'FJ' => 'FIJI',
		'FI' => 'FINLAND',
		'FR' => 'FRANCE',
		'GF' => 'FRENCH GUIANA',
		'PF' => 'FRENCH POLYNESIA',
		'TF' => 'FRENCH SOUTHERN TERRITORIES',
		'GA' => 'GABON',
		'GM' => 'GAMBIA',
		'GE' => 'GEORGIA',
		'DE' => 'GERMANY',
		'GH' => 'GHANA',
		'GI' => 'GIBRALTAR',
		'GR' => 'GREECE',
		'GL' => 'GREENLAND',
		'GD' => 'GRENADA',
		'GP' => 'GUADELOUPE',
		'GU' => 'GUAM',
		'GT' => 'GUATEMALA',
		'GG' => 'GUERNSEY',
		'GN' => 'GUINEA',
		'GW' => 'GUINEA-BISSAU',
		'GY' => 'GUYANA',
		'HT' => 'HAITI',
		'HM' => 'HEARD ISLAND AND MCDONALD ISLANDS',
		'VA' => 'HOLY SEE (VATICAN CITY STATE)',
		'HN' => 'HONDURAS',
		'HK' => 'HONG KONG',
		'HU' => 'HUNGARY',
		'IS' => 'ICELAND',
		'IN' => 'INDIA',
		'ID' => 'INDONESIA',
		'IR' => 'IRAN, ISLAMIC REPUBLIC OF',
		'IQ' => 'IRAQ',
		'IE' => 'IRELAND',
		'IM' => 'ISLE OF MAN',
		'IL' => 'ISRAEL',
		'IT' => 'ITALY',
		'JM' => 'JAMAICA',
		'JP' => 'JAPAN',
		'JE' => 'JERSEY',
		'JO' => 'JORDAN',
		'KZ' => 'KAZAKHSTAN',
		'KE' => 'KENYA',
		'KI' => 'KIRIBATI',
		'KP' => 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF',
		'KR' => 'KOREA, REPUBLIC OF',
		'KW' => 'KUWAIT',
		'KG' => 'KYRGYZSTAN',
		'LA' => 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC',
		'LV' => 'LATVIA',
		'LB' => 'LEBANON',
		'LS' => 'LESOTHO',
		'LR' => 'LIBERIA',
		'LY' => 'LIBYAN ARAB JAMAHIRIYA',
		'LI' => 'LIECHTENSTEIN',
		'LT' => 'LITHUANIA',
		'LU' => 'LUXEMBOURG',
		'MO' => 'MACAO',
		'MK' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF',
		'MG' => 'MADAGASCAR',
		'MW' => 'MALAWI',
		'MY' => 'MALAYSIA',
		'MV' => 'MALDIVES',
		'ML' => 'MALI',
		'MT' => 'MALTA',
		'MH' => 'MARSHALL ISLANDS',
		'MQ' => 'MARTINIQUE',
		'MR' => 'MAURITANIA',
		'MU' => 'MAURITIUS',
		'YT' => 'MAYOTTE',
		'MX' => 'MEXICO',
		'FM' => 'MICRONESIA, FEDERATED STATES OF',
		'MD' => 'MOLDOVA, REPUBLIC OF',
		'MC' => 'MONACO',
		'MN' => 'MONGOLIA',
		'ME' => 'MONTENEGRO',
		'MS' => 'MONTSERRAT',
		'MA' => 'MOROCCO',
		'MZ' => 'MOZAMBIQUE',
		'MM' => 'MYANMAR',
		'NA' => 'NAMIBIA',
		'NR' => 'NAURU',
		'NP' => 'NEPAL',
		'NL' => 'NETHERLANDS',
		'AN' => 'NETHERLANDS ANTILLES',
		'NC' => 'NEW CALEDONIA',
		'NZ' => 'NEW ZEALAND',
		'NI' => 'NICARAGUA',
		'NE' => 'NIGER',
		'NG' => 'NIGERIA',
		'NU' => 'NIUE',
		'NF' => 'NORFOLK ISLAND',
		'MP' => 'NORTHERN MARIANA ISLANDS',
		'NO' => 'NORWAY',
		'OM' => 'OMAN',
		'PK' => 'PAKISTAN',
		'PW' => 'PALAU',
		'PS' => 'PALESTINIAN TERRITORY, OCCUPIED',
		'PA' => 'PANAMA',
		'PG' => 'PAPUA NEW GUINEA',
		'PY' => 'PARAGUAY',
		'PE' => 'PERU',
		'PH' => 'PHILIPPINES',
		'PN' => 'PITCAIRN',
		'PL' => 'POLAND',
		'PT' => 'PORTUGAL',
		'PR' => 'PUERTO RICO',
		'QA' => 'QATAR',
		'RE' => 'RÉUNION',
		'RO' => 'ROMANIA',
		'RU' => 'RUSSIAN FEDERATION',
		'RW' => 'RWANDA',
		'BL' => 'SAINT BARTHÉLEMY',
		'SH' => 'SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA',
		'KN' => 'SAINT KITTS AND NEVIS',
		'LC' => 'SAINT LUCIA',
		'MF' => 'SAINT MARTIN',
		'PM' => 'SAINT PIERRE AND MIQUELON',
		'VC' => 'SAINT VINCENT AND THE GRENADINES',
		'WS' => 'SAMOA',
		'SM' => 'SAN MARINO',
		'ST' => 'SAO TOME AND PRINCIPE',
		'SA' => 'SAUDI ARABIA',
		'SN' => 'SENEGAL',
		'RS' => 'SERBIA',
		'SC' => 'SEYCHELLES',
		'SL' => 'SIERRA LEONE',
		'SG' => 'SINGAPORE',
		'SK' => 'SLOVAKIA',
		'SI' => 'SLOVENIA',
		'SB' => 'SOLOMON ISLANDS',
		'SO' => 'SOMALIA',
		'ZA' => 'SOUTH AFRICA',
		'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
		'ES' => 'SPAIN',
		'LK' => 'SRI LANKA',
		'SD' => 'SUDAN',
		'SR' => 'SURINAME',
		'SJ' => 'SVALBARD AND JAN MAYEN',
		'SZ' => 'SWAZILAND',
		'SE' => 'SWEDEN',
		'CH' => 'SWITZERLAND',
		'SY' => 'SYRIAN ARAB REPUBLIC',
		'TW' => 'TAIWAN, PROVINCE OF CHINA',
		'TJ' => 'TAJIKISTAN',
		'TZ' => 'TANZANIA, UNITED REPUBLIC OF',
		'TH' => 'THAILAND',
		'TL' => 'TIMOR-LESTE',
		'TG' => 'TOGO',
		'TK' => 'TOKELAU',
		'TO' => 'TONGA',
		'TT' => 'TRINIDAD AND TOBAGO',
		'TN' => 'TUNISIA',
		'TR' => 'TURKEY',
		'TM' => 'TURKMENISTAN',
		'TC' => 'TURKS AND CAICOS ISLANDS',
		'TV' => 'TUVALU',
		'UG' => 'UGANDA',
		'UA' => 'UKRAINE',
		'AE' => 'UNITED ARAB EMIRATES',
		'GB' => 'UNITED KINGDOM',
		'US' => 'UNITED STATES',
		'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
		'UY' => 'URUGUAY',
		'UZ' => 'UZBEKISTAN',
		'VU' => 'VANUATU',
		'VA' => 'VATICAN CITY STATE',
		'VE' => 'VENEZUELA, BOLIVARIAN REPUBLIC OF',
		'VN' => 'VIET NAM',
		'VG' => 'VIRGIN ISLANDS, BRITISH',
		'VI' => 'VIRGIN ISLANDS, U.S.',
		'WF' => 'WALLIS AND FUTUNA',
		'EH' => 'WESTERN SAHARA',
		'YE' => 'YEMEN',
		'ZM' => 'ZAMBIA',
		'ZW' => 'ZIMBABWE'
	);

	public $dateFormat, $phoneFormat, $address2;

	private static $instance = null;

	/**
	 * Gets a instance (SINGLETON) of this class
	 *
	 * @return object
	 */
	public static function getInstance()
	{
		if (null === self::$instance)
		{
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * @param int $plugin
	 */
	public function renderForm(
		$introtext, $outrotext, $outrotext2, $fields,
		$interests, $listid, $plugin = _CPLG_JOOMLA
		)
	{
		$html = "";

		if($plugin != _CPLG_JOOMLA)
		{
			$html .= '<div id="intro_text">';
		}

		if ($introtext && $plugin != _CPLG_JOOMLA)
		{
			$html .= "<p class=\"intro\">";
			$html .= $introtext;
			$html .= "</p>";
		}

		if ($plugin == _CPLG_CB)
		{
			$html .= "<table class=\"content_pane\" style=\"width: 100%; border: 0 !important;\">";
		}

		if (is_array($fields))
		{
			foreach ($fields as $f)
			{
				$field = explode(';', $f);

				// Render field
				if ($plugin == _CPLG_JOOMLA)
				{
					$html .= $this->renderJoomlaField($field);
				}
				elseif ($plugin == _CPLG_CB)
				{
					$html .= $this->renderCBField($field);
				}
				elseif ($plugin == _CPLG_JOMSOCIAL)
				{
					$html .= $this->renderField($field);
				}
			}
		}

		if (is_array($interests) && $plugin != _CPLG_JOOMLA)
		{
			foreach ($interests as $i)
			{
				$interest = explode(';', $i);
				$groups = explode('####', $interest[3]);

				$html .= '<div class="signup-title">' . JText::_($interest[2]) . '</div>';

				switch ($interest[1])
				{
					case 'checkboxes':
						foreach ($groups as $g)
						{
							$o = explode('##', $g);
							$html .= '<label for="' . $interest[0] . '_' . $o[0]
								. '" class="checkbox"><input type="checkbox" name="cmc[interests]['
								. $interest[0] . '][]" id="' . $interest[0] . '_' . str_replace(' ', '_', $o[0])
								. '" class="submitMerge inputbox" value="' . $o[0] . '" />' . JText::_($o[1]) . '</label>';
						}
						break;
					case 'radio':
						foreach ($groups as $g)
						{
							$o = explode('##', $g);
							$html .= '<label for="' . $interest[0] . '_' . $o[0]
								. '" class="radio"><input type="radio" name="cmc[interests]['
								. $interest[0] . ']" id="' . $interest[0] . '_' . str_replace(' ', '_', $o[0])
								. '" class="submitMerge inputbox" value="' . $o[0] . '" />' . JText::_($o[1]) . '</label>';
						}
						break;
					case 'dropdown':
						$html .= '<select name="cmc[interests][' . $interest[0] . ']" id="'
							. $interest[0] . '" class="submitMerge inputbox">';
						$html .= '<option value=""></option>';

						foreach ($groups as $g)
						{
							$o = explode('##', $g);
							$html .= '<option value="' . $o[0] . '">' . JText::_($o[1]) . '</option>';
						}

						$html .= '</select><br />';
						break;
				}
			}
		}

		if ($plugin == _CPLG_CB)
		{
			$html .= "</table>";
			$html .= '<input type="hidden" name="cmc[listid]" value="' . $listid . '" />';
		}

		if ($outrotext && $plugin != _CPLG_JOOMLA)
		{
			$html .= '<div class="outro1">';
			$html .= '<p class="outro">' . $outrotext . '</p>';
			$html .= '</div>';

		}

		if ($outrotext2 && $plugin != _CPLG_JOOMLA)
		{
			$html .= '<div class="outro2">';
			$html .= '<p class="outro">' . $outrotext2 . '</p>';
			$html .= '</div>';

		}

		return $html;
	}


	/**
	 *
	 * @param $field FNAME;text;First Name;0;""
	 */
	public function renderField($field, $prefix = "cmc")
	{
		$fieldname = $field[0];
		$fieldtype = $field[1];
		$label = $field[2];
		$val = $field[3];
		// 'text', 'email', 'imageurl', 'number', 'zip', 'url'
		// Not using exec here
		if ($fieldtype == "text")
		{
			return $this->text($field, $prefix);
		}
		elseif ($fieldtype == "dropdown")
		{
			return $this->dropdown($field, $prefix);
		}
		elseif ($fieldtype == "radio")
		{
			return $this->radio($field, $prefix);
		}
		elseif ($fieldtype == "date")
		{
			return $this->date($field, $this->dateFormat, $prefix);
		}
		elseif ($fieldtype == "birthday")
		{
			return $this->birthday($field, $prefix);
		}
		elseif ($fieldtype == "phone")
		{
			return $this->phone($field, $this->phoneFormat, $prefix);
		}
		elseif ($fieldtype == "address")
		{
			return $this->address($field, $this->address2, $prefix);
		}
		else
		{
			// Fallback, maybe should be a 404 not supported
			return $this->text($field);
		}
	}

	/**
	 * Returns an xml formatted form field
	 *
	 * @param   object  $field  - the field array
	 *
	 * @return object
	 */
	public function renderJoomlaField($field)
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
			//return $this->date($field);
		}
		elseif ($fieldtype == "birthday")
		{
			return $this->birthday($field);
		}
		elseif ($fieldtype == "phone")
		{
			//return $this->phone($field);
		}
		elseif ($fieldtype == "address")
		{
			//return $this->address($field);
		}
		else
		{
			// Fallback, maybe should be a 404 not supported
			return $this->xmltext($field);
		}
	}

	/**
	 * @param $field
	 * @return string
	 */
	private function renderCBField($field)
	{
		$inputfield = $this->renderField($field);

		$h = "<tr class=\"sectiontableentry1 cbft_predefined\">\n";
		$h .= "<td class=\"titleCell\">";
		// TODO Make label
		$h .= $field[2] . ":";
		$h .= "</td>\n";
		$h .= "<td class=\"fieldCell\">";
		$h .= $inputfield;
		$h .= "</td>\n";
		$h .= "</tr>\n";

		return $h;
	}

	/**
	 * @param $params
	 * @param string $prefix
	 * @return string
	 */
	public function text($params, $prefix = "cmc")
	{
		$class = $params[3] ? array('required', 'inputbox', 'input-medium') : array('inputbox', 'input-medium');
		$validate = array(
			'email' => 'validate-email',
			'number' => 'validate-digits',
			'url' => 'validate-url',
			'phone' => 'validate-digits'
		);

		if (isset($validate[$params[1]]))
		{
			$class[] = $validate[$params[1]];
		}

		$title = JText::_($params[2]);

		if ($params[3])
		{
			$title = $title . ' *';
		}

		return $this->input(
			$prefix . '[groups][' . $params[0] . ']', $params[0],
			'class="' . implode(' ', $class) . '"', $title
		);
	}

	/**
	 * Returns an xml formatted form field
	 *
	 * @param   object  $field  - the field array
	 *
	 * @return string
	 */
	public function xmltext($field)
	{
		// Structure: EMAIL;email;Email Address;1;
		$class = $field[3] ? array('required', 'inputbox', 'input-medium') : array('inputbox', 'input-medium');
		$validate = array(
			'email' => 'validate-email',
			'number' => 'validate-digits',
			'url' => 'validate-url',
			'phone' => 'validate-digits'
		);

		if (isset($validate[$field[1]]))
		{
			$class[] = $validate[$field[1]];
		}

		$title = JText::_($field[2]);

		$x = "<field\n";
		$x .= "name=\"" . $field[0] . "\"\n";
		$x .= "type=\"text\"\n";
		$x .= "id=\"" . $field[0] . "\"\n";

		// Do we want a description here?
		$x .= "description=\"\"\n";
		$x .= "filter=\"string\"\n";
		$x .= "class=\"" . implode(" ", $class) . "\"\n";
		$x .= "label=\"" . $title . "\"\n";
		$x .= "size=\"30\"\n";
		$x .= "/>\n";

		return $x;
	}

	/**
	 * @param $params
	 * @param string $prefix
	 * @return string
	 */
	public function dropdown($params, $prefix = "cmc")
	{
		$choices = explode('##', $params[4]);
		$req = ($params[3]) ? 'class="required inputbox"' : 'class="inputbox"';
		$title = JText::_($params[2]);
		if ($params[3]) {
			$title = JText::_($params[2]) . ' *';
		}
		echo '<div class="mcsignupTitle">' . $title . '</div>';
		$select = '<select name="' . $prefix . '[groups][' . $params[0] . ']" id="' . $params[0] . '" ' . $req . '>';
		if (!$params[3]) {
			$select .= '<option value=""></option>';
		}
		foreach ($choices as $ch) {
			$select .= '<option value="' . $ch . '">' . $ch . '</option>';
		}
		$select .= '</select><br />';

		return $select;
	}

	/**
	 * @param $params
	 * @param string $prefix
	 * @return string
	 */
	public function radio($params, $prefix = "cmc")
	{
		$choices = explode('##', $params[4]);
		$req = ($params[3]) ? 'class="required inputbox"' : 'class="inputbox"';
		$title = JText::_($params[2]);
		if ($params[3]) {
			$title = JText::_($params[2]) . ' *';
		}
		$radio = '<div class="mcsignupTitle">' . $title . '</div>';
		foreach ($choices as $ch) {
			$radio .= '<label class="radio" for="' . $params[0] . '_' . str_replace(' ', '_', $ch)
				. '"><input type="radio" name="' . $prefix . '[groups][' . $params[0] . ']" id="'
				. $params[0] . '_' . str_replace(' ', '_', $ch) . '" ' . $req . ' value="'
				. $ch . '" title="' . JText::_($title) . '" />' . JText::_($ch) . '</label>';
		}

		return $radio;
	}

	/**
	 * @param $params
	 * @param string $prefix
	 * @return string
	 */
	public function date($params, $dateformat, $prefix = "cmc")
	{
		JHTML::_('behavior.calendar');
		$title = JText::_($params[2]);
		if ($params[3]) {
			$title = $params[2] . ' *';
		}
		$attributes = array('maxlength' => '10', 'title' => $title);
		if ($params[3]) {
			$attributes['class'] = 'required inputbox input-small';
		} else {
			$attributes['class'] = 'inputbox input-small';
		}
		return JHTML::calendar(
			$title, $prefix . '[groups][' . $params[0] . ']',
			$params[0], $dateformat, $attributes
		);
	}

	/**
	 * @param $params
	 * @param string $prefix
	 * @return string
	 */
	public function birthday($params, $prefix = "cmc")
	{
		$req = ($params[3]) ? 'class="required inputbox input-medium"' : 'class="inputbox input-medium"';
		$title = JText::_($params[2]);
		if ($params[3]) {
			$title = $params[2] . ' *';
		}
		$select = '<label for="' . $params[0] . '_month">' . $title . ': </label>';
		$select .= '<select name="' . $prefix . '[groups][' . $params[0] . '][month]" id="'
			. $params[0] . '_month" title="' . JText::_($params[2]) . '" ' . $req . '>';
		$select .= '<option value="">MM</option>';
		for ($i = 1; $i <= 12; $i++) {
			$select .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '">'
				. str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
		}
		$select .= '</select>';
		$select .= '<select name="' . $prefix . '[groups][' . $params[0] . '][day]" id="'
			. $params[0] . '_day" title="' . JText::_($params[2]) . '" ' . $req . '>';
		$select .= '<option value="">DD</option>';
		for ($i = 1; $i <= 31; $i++) {
			$select .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT)
				. '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
		}
		$select .= '</select>';

		return $select;

	}

	/**
	 * @param $params
	 * @param string $prefix
	 * @return string
	 */
	public function phone($params, $phoneformat, $prefix = "cmc")
	{
		if ($phoneformat == 'inter') {
			$phone = $this->text($params);
		} else {
			$class = ($params[3]) ? array('required') : array();
			$class[] = 'phone';
			$title = JText::_($params[2]);
			if ($params[3]) {
				$title = $params[2] . ' *';
			}

			$phone = '<label for="' . $params[0] . '">' . $title . ': </label>';
			$phone .= $this->input(
				$prefix . '[groups][' . $params[0] . '][area]', $params[0], 'class="' .
				implode(' ', $class) . '"', $title, 'size="2" maxlength="3"'
			);
			$phone .= $this->input(
				$prefix . '[groups][' . $params[0] . '][detail1]', $params[0], 'class="' .
				implode(' ', $class) . '"', $title, 'size="2" maxlength="3"'
			);
			$phone .= $this->input(
				$prefix . '[groups][' . $params[0] . '][detail2]', $params[0], 'class="' .
				implode(' ', $class) . '"', $title, 'size="2" maxlength="4"'
			);
		}

		return $phone;
	}

	/**
	 * @param $params
	 * @param string $prefix
	 * @return string
	 */
	public function address($params, $address2, $prefix = "cmc")
	{
		$req = ($params[3]) ? 'class="required inputbox input-medium"' : 'class="inputbox input-medium"';
		$title = JText::_($params[2]);
		if ($params[3]) {
			$title = $params[2] . ' *';
		}
		$address = '<label for="' . $params[0] . '">' . $title . ': </label><br />';

		$address .= $this->input($prefix . '[groups][' . $params[0] . '][addr1]', $params[0], $req, JText::_('MOD_CMC_STREET_ADDRESS'));
		if ($address2) {
			$address .= $this->input($prefix . '[groups][' . $params[0] . '][addr2]', $params[0], $req, JText::_('MOD_CMC_ADDRESS_2'));
		}
		$address .= $this->input($prefix . '[groups][' . $params[0] . '][city]', $params[0], $req, JText::_('MOD_CMC_CITY'));
		$address .= $this->input($prefix . 'jform[groups][' . $params[0] . '][state]', $params[0], $req, JText::_('MOD_CMC_STATE'));
		$address .= $this->input($prefix . '[groups][' . $params[0] . '][zip]', $params[0], $req, JText::_('MOD_CMC_ZIP'));

		$address .= $this->getCountryDropdown('jform[groups][' . $params[0] . '][country]', $params[0], JText::_('MOD_CMC_COUNTRY'), $req) . '<br />';

		return $address;
	}

	/**
	 * @param $name
	 * @param $id
	 * @param $title
	 * @param $req
	 * @return string$prefix
	 */
	private function getCountryDropdown($name, $id, $title, $req)
	{
		$options = $this->countries;

		$result = '<select name="' . $name . '" id="' . $id . '" title="' . $title . '" ' . $req . '>';
		$result .= '<option value=""></option>';
		foreach ($options as $k => $v) {
			$result .= '<option value="' . $k . '">' . ucwords(strtolower($v)) . '</option>';
		}
		$result .= '</select>';

		return $result;
	}


	/**
	 * @param $name
	 * @param $id
	 * @param $class
	 * @param $title
	 * @param string $attrib
	 * @return string
	 */
	private function input($name, $id, $class, $title, $attrib = '')
	{
		return '<input name="' . $name . '" id="' . $id . '" ' . $class . ' type="text" size="25" value="" title="' . $title . '" ' . $attrib . ' />';
	}


}