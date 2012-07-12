<?php
/**
 * Tiles
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

defined( '_JEXEC' ) or die ( 'Restricted access' );

// Include library dependencies
jimport('joomla.filter.input');

class TableSettings extends JTable
{

    function __construct(&$db)
    {
        parent::__construct( '#__cmc_settings', 'id', $db );
    }
}
?>