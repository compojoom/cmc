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

$_extension = $params->get('extension', '');

JHTML::_('behavior.tooltip');
//JHTML::_('stylesheet', 'ccc.css', 'media/compojoomcc/css/');

$db = JFactory::getDBO();

// Getting extension id

$query = $db->getQuery(true);
$query->select('extension_id')->from('#__extensions')
    ->where('element  = ' . $db->quote($_extension));

$db->setQuery( $query );
$elements = $db->loadObject();

$extension_id = $elements->extension_id;

//echo $extension_id;

// loading menu entries
$query = $db->getQuery(true);
$query->select('*')->from('#__menu')
    ->where(array('component_id = ' . $db->quote($extension_id),
        "menutype = 'main'"
     ));

$db->setQuery( $query );
$elements = $db->loadObjectList();

//var_dump($elements);

$links = array();
$titles = array();
$images = array();

//    echo JText::_('MOD_CCC_OVERVIEW_VERSION');


foreach($elements as $ele){
    $titles[] = JText::_($ele->title);
    $links[] = JRoute::_($ele->link);
    $images[] = $ele->img;
}


?>
<script type="text/javascript">
    window.addEvent('domready', function () {

    });
</script>

<div id="cpanel">
<?php
    for($i = 0; $i < count($links); $i++)
    {
        ?>
        <div class="icon-wrapper">
            <div class="icon">
                <a href="<?php echo $links[$i]; ?>">
                    <img src="<?php echo $images[$i]; ?>" alt=""
                        ><span><?php echo JTEXT::_($titles[$i]); ?></span>
                </a>
            </div>
        </div>
        <?php
    }
    ?>
</div>