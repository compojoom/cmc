<?php
/**
 * @package    Cmc
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       19.06.15
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');

JHTML::_('stylesheet', 'media/com_cmc/backend/css/cmc.css');

$form = $this->form;
?>
<?php
echo CompojoomHtmlCtemplate::getHead(CmcHelperBasic::getMenu(), 'users', '', '');
?>
	<div class="box-info full">
		<h2><?php echo JText::_('COM_CMC_EDIT_USER'); ?></h2>

		<div id="cmc" class="cmc">
			<form
				action="<?php echo JRoute::_('index.php?option=com_cmc&view=user&layout=edit&id=' . (int) $this->user->id); ?>"
				method="post" name="adminForm" id="adminForm" class="form" enctype="multipart/form-data">
				<fieldset class="adminform">
					<div id="cmc_gravatar">
						<img src="http://www.gravatar.com/avatar/<?php echo md5($this->user->email); ?>?s=140"
						     alt="<?php echo $this->user->firstname . " " . $this->user->lastname; ?>"/>
					</div>


					<?php $fieldsets = $form->getFieldsets('cmc_groups'); ?>

					<?php foreach ($fieldsets as $key => $value) : ?>
						<?php $fields = $form->getFieldset($key); ?>

						<?php foreach ($fields as $field) : ?>
							<?php
							if (strtolower($field->type) != 'radio')
							{
								$field->class .= ' form-control';
							}

							$field->labelclass .= ' col-sm-2 control-label'
							?>
							<?php if (strtolower($field->type) != 'spacer') : ?>
								<div class="form-group">
									<?php echo $field->label; ?>
									<div class="col-sm-10">
										<?php echo $field->input; ?>
									</div>
								</div>
							<?php else : ?>
								<hr/>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endforeach; ?>

					<?php $fieldsets = $form->getFieldsets('cmc_interests'); ?>
					<?php foreach ($fieldsets as $key => $value) : ?>


						<?php $fields = $form->getFieldset($key); ?>

						<?php foreach ($fields as $field) : ?>
							<?php
							if (strtolower($field->type) != 'radio' && strtolower($field->type) != 'checkboxes')
							{
								$field->class .= ' form-control';
							}

							$field->labelclass .= ' col-sm-2 control-label'
							?>

							<?php if (strtolower($field->type) != 'spacer') : ?>
								<div class="form-group">
									<?php echo $field->label; ?>
									<div class="col-sm-10">
										<?php echo $field->input; ?>
									</div>
								</div>
							<?php else : ?>

							<?php endif; ?>
						<?php endforeach; ?>

					<?php endforeach; ?>

<div class="clearfix"></div>


					<div class="form-group">
						<?php echo $this->form->getLabel('list_id'); ?>
						<div class="col-sm-10">
							<?php echo $this->form->getInput('list_id'); ?>
						</div>
					</div>

					<div class="form-group">
						<?php echo $this->form->getLabel('status'); ?>
						<div class="col-sm-10">
							<?php echo $this->form->getInput('status'); ?>
						</div>
					</div>

					<div class="form-group">
						<?php echo $this->form->getLabel('email_type'); ?>
						<div class="col-sm-10">
							<?php echo $this->form->getInput('email_type'); ?>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-2">
							<?php echo JText::_('COM_CMC_MAILCHIMP_ID'); ?>:
						</div>
						<div class="col-sm-10">
							<?php echo $this->user->mc_id; ?>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-2">
							<?php echo JText::_('COM_CMC_WEB_ID'); ?>:
						</div>
						<div class="col-sm-10">
							<?php echo $this->user->web_id; ?>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-2">
							<?php echo JText::_('COM_CMC_CLIENTS'); ?>:
						</div>
						<div class="col-sm-10">
							<?php echo CmcHelperBasic::array_implode(" = ", ", ", json_decode($this->user->clients, true)); ?>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-2">
							<?php echo JText::_('COM_CMC_LANGUAGE'); ?>:
						</div>
						<div class="col-sm-10">
							<?php echo $this->user->language ? $this->user->language : 'en'; ?>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-2">
							<?php echo JText::_('COM_CMC_MEMBER_SINCE'); ?>:
						</div>
						<div class="col-sm-10">
							<?php echo $this->user->timestamp; ?>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-2">
							<?php echo JText::_('COM_CMC_LAST_CHANGE'); ?>:
						</div>
						<div class="col-sm-10">
							<?php echo $this->user->info_changed; ?>
						</div>
					</div>

				</fieldset>

				<input type="hidden" name="task" value=""/>
				<?php echo JHTML::_('form.token'); ?>
			</form>
		</div>

	</div>
<?php
// Show Footer
echo CompojoomHtmlCTemplate::getFooter(CmcHelperBasic::footer());
