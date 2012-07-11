<?php
/**
 * Compojoom Control Center
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

$extension = $params->get('extension', '');

require_once (JPATH_ADMINISTRATOR . "/components/" . $extension . "/liveupdate/liveupdate.php");

$updateinfos = LiveUpdate::getUpdateInformation(true);

?>
<div style="padding: 12px;">
    <?php
        if($updateinfos->hasUpdates) {
            echo "<h2>" . JText::_('MOD_CCC_CMC_UPDATE_UPDATE_FOUND') . "</h2>";
            echo "<p>";
                echo JText::_('MOD_CCC_CMC_UPDATE_NEW_VERSION') . ": " . $updateinfos->version . "<br />";
                echo JText::_('MOD_CCC_CMC_UPDATE_NEW_VERSION_DATE') . ": " . $updateinfos->date . "<br />";
            echo "</p>";
            echo "<p>";
                echo JText::_('MOD_CCC_CMC_UPDATE_HOWTO_UPDATE_TEXT');
            echo "</p>";
        } else {
            echo JText::_('MOD_CCC_CMC_UPDATE_NO_UPDATES');
        }
    ?>
</div>