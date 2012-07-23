<?php
/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 23.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

class cmcForm
{

    private $countries = array(
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

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function text($params)
    {
        $class = $params[3] ? array('required', 'inputbox') : array('inputbox');
        $validate = array(
            'email' => 'validate-email',
            'number' => 'validate-digits',
            'url' => 'validate-url',
            'phone' => 'validate-digits'
        );

        if (isset($validate[$params[1]])) {
            $class[] = $validate[$params[1]];
        }

        $title = JText::_($params[2]);
        if ($params[3]) {
            $title = $title . ' *';
        }

        return $this->input($params[0], $params[0], 'class="' . implode(' ', $class) . '"', $title);
    }

    private function input($name, $id, $class, $title, $attrib = '')
    {
        return '<input name="' . $name . '" id="' . $id . '" ' . $class . ' type="text" value="" title="' . $title . '" ' . $attrib . ' />';

    }

    public function dropdown($params)
    {
        $choices = explode('##', $params[4]);
        $req = ($params[3]) ? 'class="required inputbox"' : 'class="inputbox"';
        $title = JText::_($params[2]);
        if ($params[3]) {
            $title = JText::_($params[2]) . ' *';
        }
        echo '<div class="mcsignupTitle">' . $title . '</div>';
        $select = '<select name="' . $params[0] . '" id="' . $params[0] . '" ' . $req . '>';
        if (!$params[3]) {
            $select .= '<option value=""></option>';
        }
        foreach ($choices as $ch) {
            $select .= '<option value="' . $ch . '">' . $ch . '</option>';
        }
        $select .= '</select><br />';

        return $select;
    }

    public function radio($params)
    {
        $choices = explode('##', $params[4]);
        $req = ($params[3]) ? 'class="required inputbox"' : 'class="inputbox"';
        $title = JText::_($params[2]);
        if ($params[3]) {
            $title = JText::_($params[2]) . ' *';
        }
        $radio = '<div class="mcsignupTitle">' . $title . '</div>';
        foreach ($choices as $ch) {
            $radio .= '<input type="radio" name="' . $params[0] . '" id="' . $params[0] . '_' . str_replace(' ', '_', $ch) . '" ' . $req . ' value="' . $ch . '" title="' . JText::_($title) . '" /><label for="' . $params[0] . '_' . str_replace(' ', '_', $ch) . '">' . JText::_($ch) . '</label><br />';
        }

        return $radio;
    }


    public function date($params)
    {
        JHTML::_('behavior.calendar');
        $title = JText::_($params[2]);
        if ($params[3]) {
            $title = $params[2] . ' *';
        }
        $attributes = array('maxlength' => '10', 'style' => 'width:85%;', 'title' => $title);
        if ($params[3]) {
            $attributes['class'] = 'required inputbox';
        } else {
            $attributes['class'] = 'inputbox';
        }
        return JHTML::calendar($title, $params[0], $params[0], $this->params->get('dateFormat', '%Y-%m-%d'), $attributes);

    }

    public function birthday($params)
    {
        $req = ($params[3]) ? 'class="required inputbox"' : 'class="inputbox"';
        $title = JText::_($params[2]);
        if ($params[3]) {
            $title = $params[2] . ' *';
        }
        $select = '<label for="' . $params[0] . '_month">' . $title . ': </label>';
        $select .= '<select name="' . $params[0] . '#*#month" id="' . $params[0] . '_month" title="' . JText::_($params[2]) . '" ' . $req . '>';
        $select .= '<option value="">MM</option>';
        for ($i = 1; $i <= 12; $i++) {
            $select .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
        }
        $select .= '</select>';
        $select .= '<select name="' . $params[0] . '#*#day" id="' . $params[0] . '_day" title="' . JText::_($params[2]) . '" ' . $req . '>';
        $select .= '<option value="">DD</option>';
        for ($i = 1; $i <= 31; $i++) {
            $select .= '<option value="' . str_pad($i, 2, '0', STR_PAD_LEFT) . '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>';
        }
        $select .= '</select>';

        return $select;

    }

    public function phone($params)
    {
        if ($this->params->get('phoneFormat', 'inter') == 'inter') {
            $phone = $this->text($params);
        } else {
            $class = ($params[3]) ? array('required') : array();
            $class[] = 'phone';
            $title = JText::_($params[2]);
            if ($params[3]) {
                $title = $params[2] . ' *';
            }

            $phone = '<label for="' . $params[0] . '">' . $title . ': </label>';
            $phone .= $this->input($params[0] . '*#*1', $params[0], 'class="' . implode(' ', $class) . '"', $title, 'size="2" maxlength="3"');
            $phone .= $this->input($params[0] . '*#*2', $params[0], 'class="' . implode(' ', $class) . '"', $title, 'size="2" maxlength="3"');
            $phone .= $this->input($params[0] . '*#*3', $params[0], 'class="' . implode(' ', $class) . '"', $title, 'size="2" maxlength="4"');
        }

        return $phone;
    }

    public function address($params)
    {
        $req = ($params[3]) ? 'class="required inputbox"' : 'class="inputbox"';
        $title = JText::_($params[2]);
        if ($params[3]) {
            $title = $params[2] . ' *';
        }
        $address = '<label for="' . $params[0] . '">' . $title . ': </label><br />';

        $address .= $this->input($params[0] . '***addr1', $params[0], $req, JText::_('MOD_CMC_STREET_ADDRESS'));
        if ($this->params->get('address2', 0)) {
            $address .= $this->input($params[0] . '***addr2', $params[0], $req, JText::_('MOD_CMC_ADDRESS_2'));
        }
        $address .= $this->input($params[0] . '***city', $params[0], $req, JText::_('MOD_CMC_CITY'));
        $address .= $this->input($params[0] . '***state', $params[0], $req, JText::_('MOD_CMC_STATE'));
        $address .= $this->input($params[0] . '***zip', $params[0], $req, JText::_('MOD_CMC_ZIP'));

        $address .= $this->getCountryDropdown($params[0] . '***country', $params[0], JText::_('MOD_CMC_COUNTRY'), $req) . '<br />';

        return $address;
    }

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

    public function __call($function, $arguments)
    {

        if (in_array($function, array('text', 'email', 'imageurl', 'number', 'zip', 'url'))) {
            $function = 'text';
        }
        if(method_exists($this, $function)) {
            return call_user_func_array(array($this, $function), $arguments);
        } else {
            JError::raiseWarning('404',JText::sprintf('MOD_CMC_UNSUPPORTED_FIELD', $function));
        }

    }
}