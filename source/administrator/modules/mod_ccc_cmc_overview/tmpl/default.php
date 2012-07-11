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

defined('_JEXEC') or die();

$version = $params->get('version', '2.0.0');
$copyright = $params->get('copyrigh', 'Copyright (C) 2012 compojoom.com');
$license = $version = $params->get('license', 'GPL v2');
$translation = $params->get('translation', '');
$description = $params->get('description', '');

?>
<div style="padding: 12px;">
    <h2><?php echo JText::_('MOD_CCC_CMC_OVERVIEW_VERSION'); ?></h2>
    <p>
        <?php echo $version; ?>
    </p>
    <h2><?php echo JText::_('MOD_CCC_CMC_OVERVIEW_COPYRIGHT'); ?></h2>
    <p>
        <?php echo $copyright; ?>
    </p>
    <h2><?php echo JText::_('MOD_CCC_CMC_OVERVIEW_LICENSE'); ?></h2>
    <p>
        <?php echo $license; ?>
    </p>
    <h2><?php echo JText::_('MOD_CCC_CMC_OVERVIEW_TRANSLATION'); ?></h2>
    <p>
        <?php echo $translation; ?>
    </p>
    <!-- Description -->
    <p>
        <?php echo $description; ?>
    </p>
</div>