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

defined('_JEXEC') or die('Restricted access');

$editor = JFactory::getEditor();

JHTML::_('behavior.mootools');
JHTML::_('behavior.tooltip');

?>

<div id="tiles" class="tiles">
    <form action="index.php" method="post" name="adminForm" id="adminForm" class="form" enctype="multipart/form-data">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_TILES_EDIT_GALLERY'); ?></legend>
            <table>
                <tr>
                    <td width="200" align="left" class="key">
                        <?php echo JText::_('COM_TILES_GALLERY_NAME'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="gallery_name" id="gallery_name" size="50" maxlength="250" value="<?php echo $this->gallery->gallery_name; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100"  class="key">
                        <?php echo JText::_('COM_TILES_GALLERY_DESCRIPTION'); ?>:
                    </td>
                    <td>
                        <textarea class="text_area" type="text" cols="20" rows="4" name="description" id="description" style="width:500px" /><?php echo $this->gallery->description; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_TILES_GALLERY_WIDTH'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="tiles_width" id="tiles_width" size="5" maxlength="10" value="<?php echo $this->gallery->tiles_width; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_TILES_GALLERY_HEIGHT'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="tiles_height" id="tiles_height" size="5" maxlength="10" value="<?php echo $this->gallery->tiles_height; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_TILES_GALLERY_FIELDS_HORIZONTAL'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="tiles_fields_horizontal" id="tiles_fields_horizontal" size="5" maxlength="10" value="<?php echo $this->gallery->tiles_fields_horizontal; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_TILES_GALLERY_FIELDS_VERTICAL'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="tiles_fields_vertical" id="tiles_fields_vertical" size="5" maxlength="10" value="<?php echo $this->gallery->tiles_fields_vertical; ?>" />
                    </td>
                </tr>
                <tr>
                    <td width="100" class="key">
                        <?php echo JText::_('COM_TILES_GALLERY_FIELDS_HORIZONTAL_VISIBLE'); ?>:
                    </td>
                    <td>
                        <input class="required" type="text" name="tiles_fields_horizontal_visible" id="tiles_fields_horizontal_visible" size="5" maxlength="10" value="<?php echo $this->gallery->tiles_fields_horizontal_visible; ?>" />
                    </td>
                </tr>
                <!--
                <tr>
                    <td width="100"  class="key">
                        <?php echo JText::_('COM_TILES_GALLERY_COLOR_PROFILE'); ?>:
                    </td>
                    <td>
                        <?php // TODO:: echo $this->gallery->color_profile; ?>
                        <select id="color_profile" name="color_profile">
                            <option value="default"><?php echo JText::_('COM_TILES_COLOR_DEFAULT'); ?></option>
                            <option value="blue"><?php echo JText::_('COM_TILES_COLOR_BLUE'); ?></option>
                            <option value="grey"><?php echo JText::_('COM_TILES_COLOR_GREY'); ?></option>
                            <option value="chstk "><?php echo JText::_('COM_TILES_COLOR_CUSTOM'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="100"  class="key">
                        <?php echo JText::_('COM_TILES_GALLERY_COLOR_PROFILE_CUSTOM'); ?>:
                    </td>
                    <td>
                        <textarea class="text_area" type="text" cols="20" rows="4" name="description" id="color_profile_custom" style="width:500px" /><?php echo $this->gallery->color_profile_custom; ?></textarea>
                    </td>
                </tr>
                -->
            </table>

        </fieldset>
        <input type="hidden" name="id" value="<?php echo $this->gallery->id; ?>" />
        <input type="hidden" name="option" value="com_tiles" />
        <input type="hidden" name="view" value="editgallery" />
        <input type="hidden" name="model" value="editgallery" />
        <input type="hidden" name="task" value="editgallery" />
    </form>
</div>