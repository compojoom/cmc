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

use Joomla\Utilities\ArrayHelper;

JHtml::_('behavior.tooltip');

JHtml::_('stylesheet', 'media/com_cmc/backend/css/cmc.css');

$form = $this->form;

var_dump($form);
die;

echo CompojoomHtmlCtemplate::getHead(CmcHelperBasic::getMenu(), 'users', '', '');
?>
	<div class="box-info">
		<h2>
				<img src="http://www.gravatar.com/avatar/<?php echo md5($this->user->email); ?>?s=40"
				     alt="<?php echo $this->user->firstname . " " . $this->user->lastname; ?>"/>
			<?php echo JText::_('COM_CMC_EDIT_USER'); ?></h2>

		<div id="cmc" class="cmc">
			<form
				action="<?php echo JRoute::_('index.php?option=com_cmc&view=user&layout=edit&id=' . (int) $this->user->id); ?>"
				method="post" name="adminForm" id="adminForm" class="form-horizontal" role="form" enctype="multipart/form-data">

					<?php $fieldsets = $form->getFieldsets('cmc_groups'); ?>

					<?php foreach ($fieldsets as $key => $value) : ?>
						<?php $fields = $form->getFieldset($key); ?>

						<?php foreach ($fields as $field) : ?>
							<?php
							if (strtolower($field->type) != 'radio')
							{
								$field->class .= ' form-control';
							}

							$field->labelclass .= ' col-sm-2 compojoom-control-label'
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

					<!-- Interests / category groups -->
					<?php $fieldsets = $form->getFieldsets('cmc_interests'); ?>
					<?php foreach ($fieldsets as $key => $value) : ?>

						<?php $fields = $form->getFieldset($key); ?>

						<?php foreach ($fields as $field) : ?>
							<?php
							if (strtolower($field->type) != 'radio' && strtolower($field->type) != 'checkboxes')
							{
								$field->class .= ' form-control';
							}

							$field->labelclass .= ' col-sm-2 compojoom-control-label'
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
						<label class="col-sm-2 compojoom-control-label">
							<?php echo JText::_('COM_CMC_MAILCHIMP_ID'); ?>
						</label>
						<div class="col-sm-10">
							<p class="form-control-static"><?php echo $this->user->mc_id; ?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 compojoom-control-label">
							<?php echo JText::_('COM_CMC_WEB_ID'); ?>
						</label>
						<div class="col-sm-10">
							<p class="form-control-static"><?php echo $this->user->web_id; ?></p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 compojoom-control-label">
							<?php echo JText::_('COM_CMC_CLIENTS'); ?>
						</label>
						<div class="col-sm-10">
							<p class="form-control-static">
								<?php echo $this->user->clients; ?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 compojoom-control-label">
							<?php echo JText::_('COM_CMC_LANGUAGE'); ?>
						</label>
						<div class="col-sm-10">
							<p class="form-control-static">
								<?php echo $this->user->language ? $this->user->language : 'en'; ?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 compojoom-control-label">
							<?php echo JText::_('COM_CMC_MEMBER_SINCE'); ?>
						</label>
						<div class="col-sm-10">
							<p class="form-control-static">
								<?php echo $this->user->timestamp; ?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 compojoom-control-label">
							<?php echo JText::_('COM_CMC_LAST_CHANGE'); ?>
						</label>
						<div class="col-sm-10">
							<p class="form-control-static">
								<?php echo $this->user->info_changed; ?>
							</p>
						</div>
					</div>

				</fieldset>

				<input type="hidden" name="task" value=""/>
				<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>

	</div>
<?php
// Show Footer
echo CompojoomHtmlCTemplate::getFooter(CmcHelperBasic::footer());
