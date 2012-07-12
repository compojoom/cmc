<?php
/**
 * Cmc
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 stable $
 **/

defined('_JEXEC') or die('Restricted access');

class CmcSettingsHelper
{
    private static $instance;

    /**
     *
     * @param <type> $title - the name of the variable
     * @param <type> $default - default value
     * @return <type>
     */
    public static function getSettings($title = '', $default = '')
    {
        if (!isset(self::$instance)) {
            self::$instance = self::_loadSettings();
        }
        return self::$instance->get($title, $default);
    }

    /**
     *
     * @return JObject - loads a singleton object with all settings
     */
    private static function _loadSettings()
    {
        $db = JFactory::getDBO();
        $settings = new JObject();

        $query = ' SELECT st.title, st.value'
            . ' FROM #__cmc_settings AS st'
            . ' ORDER BY st.id';

        $db->setQuery($query);
        $data = $db->loadObjectList();
        foreach ($data as $value) {
            $settings->set($value->title, $value->value);
        }

        // grab the settings from the menu and merge them in the object
        $app = JFactory::getApplication();
        $menu = $app->getMenu();
        if (is_object($menu)) {
            if ($item = $menu->getActive()) {
                $menuParams = $menu->getParams($item->id);
                foreach ($menuParams->toArray() as $key => $value) {
                    if ($key == 'show_page_heading') {
                        $key = 'show_page_title';
                    }
                    $settings->set($key, $value);
                }
            }
        }

        return $settings;
    }

    /**
     *
     * @param <type> $id
     * @param <type> $title
     * @param <type> $value
     * @param <type> $class
     * @param <type> $rows
     * @param <type> $cols
     * @param <type> $style
     * @return <type>
     */
    public function getTextareaSettings($id, $title, $value, $class = 'text_area', $rows = 8, $cols = 50, $style = 'width:300px')
    {
        return '<textarea class="' . $class . '" name="cmcset[' . $id . ']" id="cmcset[' . $id . ']" rows="'
            . $rows . '" cols="' . $cols . '" style="' . $style . '" title="' . JText::_('COM_CMC_' . $title . '_DESC')
            . '" />' . $value . '</textarea>';
    }

    /**
     *
     * @param <type> $id
     * @param <type> $title
     * @param <type> $value
     * @param <type> $values
     * @param <type> $class
     * @param <type> $size
     * @param <type> $maxlength
     * @param <type> $style
     * @return string
     */
    public static function getSelectSettings($id, $title, $value, $values, $class = 'inputbox', $size = 50, $maxlength = 255, $style = 'width:300px')
    {

        $valuesArray = TilesSettingsHelper::getSettingsValues($values);

        $select = '<select name="cmcset[' . $id . ']" id="cmcset[' . $id . ']" class="' . $class . '">' . "\n";
        foreach ($valuesArray as $valueOption) {
            if ($value == $valueOption['id']) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
            $text = strtoupper(str_replace(' ', '_', $valueOption['value']));
            $text = str_replace('(', '', $text);
            $text = str_replace(')', '', $text);
            $text = str_replace(':', '', $text);
            $text = str_replace('.', '', $text);
            $text = str_replace('-', '', $text);
            $text = str_replace('__', '_', $text);
            $select .= '<option value="' . $valueOption['id'] . '" ' . $selected . '>' . JText::_('COM_CMC_' . $text)
                . '</option>' . "\n";
        }
        $select .= '</select>' . "\n";

        return $select;
    }

    /**
     *
     * @param <type> $id
     * @param <type> $title
     * @param <type> $value
     * @param <type> $class
     * @param <type> $size
     * @param <type> $maxlength
     * @param <type> $style
     * @return <type>
     */
    public static function getTextSettings($id, $title, $value, $class = 'text_area', $size = 50, $maxlength = 255, $style = 'width:300px')
    {

        return '<input class="' . $class . '" type="text" name="cmcset[' . $id . ']"
            id="cmcset[' . $id . ']" value="' . $value . '" size="' . $size . '"
            maxlength="' . $maxlength . '" style="' . $style . '" title="' .
            JText::_('COM_CMC_' . strtoupper($title) . '_DESC') . '" />';
    }

    /**
     *
     * @param <type> $params
     * @return <type>
     */
    public static function getSettingsValues($params)
    {

        $regex_one = '/({\s*)(.*?)(})/si';
        $regex_all = '/{\s*.*?}/si';
        $matches = array();
        $count_matches = preg_match_all($regex_all, $params, $matches, PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

        $values = array();

        for ($i = 0; $i < $count_matches; $i++) {

            $matukio = $matches[0][$i][0];
            preg_match($regex_one, $matukio, $matukioParts);
            $values_replace = array("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
            $values = explode("=", $matukioParts[2], 2);

            foreach ($values_replace as $key2 => $values2) {
                $values = preg_replace($values2, '', $values);
            }

            $returnValues[$i]['id'] = $values[0];
            $returnValues[$i]['value'] = $values[1];
        }

        return $returnValues;
    }
}

?>