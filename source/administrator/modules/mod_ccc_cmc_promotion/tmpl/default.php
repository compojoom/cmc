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

jimport( 'joomla.filesystem.folder' );

//echo $_image_path;
$_image_links = JPATH_ROOT . $params->get('linklist', '/media/mod_ccc_cmc_promotion/images/linklist.txt');

$imageFilePath = JPATH_ROOT .$params->get('imagepath', '/media/mod_ccc_cmc_promotion/images/');
$imageUrlPath = JURI::root() . $params->get('imagepath', '/media/mod_ccc_cmc_promotion/images/');


// Check if folder exists
if(!JFile::exists($_image_links)) {
    echo "No images in path: " . $imageFilePath;
    return;
}

$_images = null;

if(JFolder::exists($imageFilePath)) {
    $_images = Jfolder::files($imageFilePath, '.gif|.png|.jpg');
} else {
    echo "No images";
}

$fileContent = JFile::read($_image_links);
$_links = explode(";", $fileContent);

JHTML::_('behavior.tooltip');
//JHTML::_('stylesheet', 'ccc.css', 'media/compojoomcc/css/');
JHTML::_('script', 'Loop.js', 'media/mod_ccc_cmc_promotion/js/');
JHTML::_('script', 'SlideShow.js', 'media/mod_ccc_cmc_promotion/js/');
JHTML::_('script', 'SlideShow.CSS.js', 'media/mod_ccc_cmc_promotion/js/');

?>
<script type="text/javascript">
    window.addEvent('domready', function () {
        var basic;
        basic = new SlideShow('promotion-slideshow', {
            autoplay: true,
            delay: 3000,
            transition: 'blindDown'
        });
    });
</script>

<div align ="center">
    <div id="promotion-slideshow" style="width: 400px; height: 168px; overflow: hidden;">
        <?php
            for($i = 0; $i < count($_images); $i++) {
                $img = $_images[$i];

                $link = "#";
                if(!empty($_links)) {
                    $link = $_links[$i];
                    //echo $link;
                }

                echo '<div style="background: url('. $imageUrlPath . $img .') no-repeat; width: 400px; height: 168px;"> ';
                    echo '<a href ="' . $link . '" style="position: absolute; width: 100%; height: 100%; opacity: 0"></a>';
                echo '</div>';
            }
        ?>
    </div>
</div>