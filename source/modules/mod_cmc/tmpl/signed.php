<?php
/**
 * @package    com_cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       15.07.2014
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

?>

<?php echo JText::_('MOD_CMC_ALREADY_ON_THE_LIST'); ?>

<a href="<?php echo JText::_('index.php?option=com_cmc&task=subscription.delete&listid='.$params->get('listid').'&'.JFactory::getSession()->getFormToken().'=1'); ?>" class="btn">
	<?php echo JText::_('MOD_CMC_UNSUBSCRIBE'); ?>
</a>