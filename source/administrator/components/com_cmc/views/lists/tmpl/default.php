<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.filter.output');
JHtml::_('stylesheet', 'media/com_cmc/backend/css/cmc.css');

JHtml::script('media/com_cmc/backend/js/jquery.iframe-auto-height.js');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

JFactory::getDocument()->addScriptDeclaration('
	tourTranslations = {
		"identifier": "' . JText::_('COM_XY_BLA') . '",
	}
')

?>
<script type="text/javascript">
	var $ = jQuery;

	function closeIFrame() {
		$('#lists-to-sync').css('display', 'none');
	}

	Joomla.submitbutton = function (pressbutton) {
		if (pressbutton == 'lists.sync') {
			$('#lists-to-sync').css('display', 'block');
		} else if (pressbutton == 'lists.new') {
			$('#list-new').css('display', 'block');
		} else {
			Joomla.submitform(pressbutton);
		}
	}
</script>

<iframe id="lists-to-sync" class="box-info full"
        src="index.php?option=com_cmc&view=sync&tmpl=component"
        style="display: none;" height="350px"></iframe>

<form action="<?php echo JRoute::_('index.php?option=com_cmc&task=lists.create'); ?>" method="post"
      name="listForm" id="listForm">
	<div id="list-new" style="display: none;">
		<h2><?php echo JText::_('COM_CMC_CREATE_LIST'); ?></h2>
		<p class="intro">
		</p>
		<div class="control-group">
			<div class="form-group">
				<label for="name"><?php echo JText::_('COM_CMC_LIST_NAME'); ?> *</label>

				<input type="text" name="name" id="name" class="form-control" required="required"/>
			</div>

			<h3><?php echo JText::_('COM_CMC_COMPANY_DETAILS'); ?></h3>

			<div class="form-group">
				<label for="company"><?php echo JText::_('COM_CMC_COMPANY'); ?> *</label>

				<input type="text" name="contact[company]" id="company" class="form-control" required="required"/>
			</div>

			<div class="form-group">
				<label for="address1"><?php echo JText::_('COM_CMC_ADDRESS1'); ?> *</label>

				<input type="text" name="contact[address1]" id="address1" class="form-control" required="required"/>
			</div>

			<div class="form-group">
				<label for="city"><?php echo JText::_('COM_CMC_CITY'); ?> *</label>

				<input type="text" name="contact[city]" id="city" class="form-control" required="required"/>
			</div>

			<div class="form-group">
				<label for="state"><?php echo JText::_('COM_CMC_STATE'); ?> *</label>

				<input type="text" name="contact[state]" id="state" class="form-control" required="required"/>
			</div>

			<div class="form-group">
				<label for="zip"><?php echo JText::_('COM_CMC_ZIP'); ?> *</label>

				<input type="text" name="contact[zip]" id="zip" class="form-control" required="required"/>
			</div>

			<div class="form-group">
				<label for="country"><?php echo JText::_('COM_CMC_COUNTRY'); ?> *</label>

				<input type="text" name="contact[country]" id="country" class="form-control" required="required"/>
			</div>

			<div class="form-group">
				<label for="permission_reminder"><?php echo JText::_('COM_CMC_PERMISSION_REMINDER'); ?> *</label>

				<textarea name="permission_reminder" id="permission_reminder" class="form-control" required="required"></textarea>
			</div>

			<h3><?php echo JText::_('COM_CMC_CAMPAIGN_DETAILS'); ?></h3>

			<div class="form-group">
				<label for="from_name"><?php echo JText::_('COM_CMC_FROM_NAME'); ?> *</label>

				<input type="text" name="campaign_defaults[from_name]" id="from_name" class="form-control" required="required"/>
			</div>

			<div class="form-group">
				<label for="from_email"><?php echo JText::_('COM_CMC_FROM_EMAIL'); ?> *</label>

				<input type="text" name="campaign_defaults[from_email]" id="from_email" class="form-control" required="required"/>
			</div>

			<div class="form-group">
				<label for="subject"><?php echo JText::_('COM_CMC_SUBJECT'); ?> *</label>

				<input type="text" name="campaign_defaults[subject]" id="subject" class="form-control" required="required"/>
			</div>

			<div class="form-group">
				<label for="language"><?php echo JText::_('COM_CMC_LANGUAGE'); ?> *</label>

				<input type="text" name="campaign_defaults[language]" id="language" class="form-control" required="required" value="en_US" />
			</div>

			<div class="form-group">
				<label for="email_type_option"><?php echo JText::_('COM_CMC_EMAIL_TYPE_OPTION'); ?></label>

				<select name="email_type_option" id="email_type_option">
					<option selected="selected" value="true"><?php echo JText::_('JYES'); ?></option>
					<option value="false"><?php echo JText::_('JNO'); ?></option>
				</select>
			</div>
		</div>

		<button id="btnAddList" class="btn btn-primary"><?php echo JText::_('COM_CMC_CREATE_LIST'); ?></button>
	</div>
</form>

<div class="box-info full">
	<form action="<?php echo JRoute::_('index.php?option=com_cmc&view=lists'); ?>" method="post"
	      name="adminForm" id="adminForm">
		<?php if (count($this->items)) : ?>
			<div id="filter-bar" class="btn-toolbar">
				<div class="filter-search fltlft btn-group pull-left">
					<label class="filter-search-lbl element-invisible"
					       for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
					<input type="text" name="filter_search" id="filter_search"
					       value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
					       title="<?php echo JText::_('COM_CMC_SEARCH_IN_TITLE'); ?>"
					       placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"/>
				</div>
				<div class="btn-group pull-left hidden-phone">
					<?php if (JVERSION > 2.5) : ?>
						<button class="btn" type="submit"><i class="icon-search"></i></button>
						<button class="btn" type="button"
						        onclick="document.id('filter_search').value='';this.form.submit();"><i
									class="icon-remove"></i>
						</button>
					<?php else : ?>
						<button class="btn" type="submit"
						        style="margin:0"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
						<button class="btn" type="button" style="margin:0"
						        onclick="document.id('filter_search').value='';this.form.submit();">
							<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
						</button>

					<?php endif; ?>
				</div>

			</div>
			<div class="clr"></div>

			<div class="table-responsive">
				<table class="table table-hover table-striped">
					<thead>
					<tr>
						<th width="5">#</th>
						<th width="5">
							<input type="checkbox" name="checkall-toggle" value=""
							       title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
							       onclick="Joomla.checkAll(this)"/>
						</th>
						<th class="title">
							<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'cc.list_name', $listDirn, $listOrder); ?>
						</th>
						<th width="10%"><?php echo JText::_('JGRID_HEADING_ID'); ?></th>
						<th width="10%"><?php echo JText::_('COM_CMC_MC_ID'); ?></th>
						<th width="20%"><?php echo JText::_('COM_CMC_DEFAULT_FROM_NAME'); ?></th>
						<th width="20%"><?php echo JText::_('COM_CMC_DEFAULT_FROM_MAIL'); ?></th>
						<th width="10%"><?php echo JText::_('COM_CMC_DEFAULT_LANGUAGE'); ?></th>
						<th width="5%" nowrap="nowrap"><?php echo JText::_('COM_CMC_VISIBILITY'); ?></th>
					</tr>
					</thead>
					<tfoot>
					<tr>
						<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
					</tr>
					</tfoot>
					<tbody>
					<?php foreach ($this->items as $i => $item) : ?>
						<tr>
							<td><?php echo $this->pagination->getRowOffset($i); ?></td>

							<td>
								<?php echo JHTML::_('grid.id', $i, $item->mc_id); ?>
							</td>
							<td>
								<?php echo $item->list_name; ?>
							</td>
							<td>
								<?php echo $item->id; ?>
							</td>
							<td>
								<?php echo $item->mc_id; ?>
							</td>
							<td>
								<?php echo $item->default_from_name; ?>
							</td>
							<td>
								<?php echo $item->default_from_email; ?>
							</td>
							<td>
								<?php echo $item->default_language; ?>
							</td>
							<td>
								<?php echo $item->visibility; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php else : ?>
			<div style="text-align: center;"
			     class="alert alert-info"><?php echo JText::_('COM_CMC_NO_LISTS'); ?></div>
		<?php endif; ?>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0"/>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>

		<?php echo JHTML::_('form.token'); ?>
	</form>

	<div class="clear"></div>
</div>
