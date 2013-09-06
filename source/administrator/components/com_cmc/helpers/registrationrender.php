<?php
/**
 * Compojoom Community-Builder Plugin
 * @package Joomla!
 * @Copyright (C) 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/

defined('_JEXEC') or die('Restricted access');

/**
 * Helper class for Registration plugins
 * Class CmcHelperRegistration
 */

class CmcHelperRegistrationrender
{
    private static $instance;

    public static $countries = array(
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


    /**
     * @param int $plugin
     */
    public static function renderForm($introtext, $outrotext, $outrotext2, $fields, $interests, $listid, $plugin = _CPLG_JOOMLA)
    {
        $html = "";

        $html .= '<div id="intro_text">';

        if ($introtext) {
            $html .= "<p class=\"intro\">";
            $html .= $introtext;
            $html .= "</p>";
        }

        if ($plugin == _CPLG_CB) {
            $html .= "<table class=\"content_pane\" style=\"width: 100%; border: 0 !important;\">";
        }

        if (is_array($fields)) {
            foreach ($fields as $f) {
                $field = explode(';', $f);
                // Render field
                if ($plugin == _CPLG_JOOMLA) {
                    $html .= CmcHelperRegistrationrender::renderField($field);
                } else if ($plugin == _CPLG_CB) {
                    $html .= CmcHelperRegistrationrender::renderCBField($field);
                } else if ($plugin == _CPLG_JOMSOCIAL) {
                    $html .= CmcHelperRegistrationrender::renderField($field);
                }
            }
        }

        if (is_array($interests)) {
            foreach ($interests as $i) {

                $interest = explode(';', $i);
                $groups = explode('####', $interest[3]);

                $html .= '<div class="signup-title">' . JText::_($interest[2]) . '</div>';

                switch ($interest[1]) {
                    case 'checkboxes':
                        foreach ($groups as $g) {
                            $o = explode('##', $g);
                            $html .= '<label for="' . $interest[0] . '_' . $o[0]
                                . '" class="checkbox"><input type="checkbox" name="cmc[interests]['
                                . $interest[0] . '][]" id="' . $interest[0] . '_' . str_replace(' ', '_', $o[0])
                                . '" class="submitMerge inputbox" value="' . $o[0] . '" />' . JText::_($o[1]) . '</label>';
                        }
                        break;
                    case 'radio':
                        foreach ($groups as $g) {
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
                        foreach ($groups as $g) {
                            $o = explode('##', $g);
                            $html .= '<option value="' . $o[0] . '">' . JText::_($o[1]) . '</option>';
                        }
                        $html .= '</select><br />';
                        break;
                }
            }
        }

        if ($plugin == _CPLG_CB) {
            $html .= "</table>";
        }

        $html .= '<input type="hidden" name="cmc[listid]" value="' . $listid . '" />';

        if ($outrotext) {
            $html .= '<div class="outro1">';
            $html .= '<p class="outro">' . $outrotext . '</p>';
            $html .= '</div>';

        }

        if ($outrotext2) {
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
    public static function renderField($field)
    {
        $fieldname = $field[0];
        $fieldtype = $field[1];
        $label = $field[2];
        $val = $field[3];

        if ($fieldtype == "text") {
            return CmcHelperRegistrationrender::text($field);
        } else if ($fieldtype == "radio") {

        } else {
            // Fallback
            return CmcHelperRegistrationrender::text($field);
        }
    }

    /**
     * @param $field
     * @return string
     */
    private static function renderCBField($field)
    {
        $inputfield = CmcHelperRegistrationrender::renderField($field);

        $h = "<tr class=\"sectiontableentry1 cbft_predefined\">\n";
        $h .= "<td class=\"titleCell\">";
        $h .= $field[2] . ":"; // TODO Make label
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
    public static function text($params, $prefix = "cmc")
    {
        $class = $params[3] ? array('required', 'inputbox', 'input-medium') : array('inputbox', 'input-medium');
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

        return CmcHelperRegistrationrender::_input(
            $prefix . '[groups]['. $params[0] . ']', $params[0],
            'class="' . implode(' ', $class) . '"', $title
        );
    }

    /**
     * @param $name
     * @param $id
     * @param $class
     * @param $title
     * @param string $attrib
     * @return string
     */
    private static function _input($name, $id, $class, $title, $attrib = '')
    {
        return '<input name="' . $name . '" id="' . $id . '" ' . $class . ' type="text" size="25" value="" title="' . $title . '" ' . $attrib . ' />';
    }




}