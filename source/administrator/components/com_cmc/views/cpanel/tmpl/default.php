<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Module Helper for our Positions
jimport('joomla.application.module.helper');

JHtml::_('behavior.tooltip');
?>
	<div id="updateNotice"></div>
	<div id="jedNotice"></div>

	<div class="row">
		<div class="col-sm-6">
			<div class=" box-info text-center">
				<ul class="dashboard-icons">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_cmc&view=lists'); ?>">
							<span class="fa fa-list-alt fa-5x"></span>
							<span class="text-icon"><?php echo JText::_('COM_CMC_LISTS'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_cmc&view=users'); ?>">
							<span class="fa fa-users fa-5x"></span>
							<span class="text-icon"><?php echo JText::_('COM_CMC_USERS'); ?></span>
						</a>
					</li>
				</ul>
			</div>
			<div class=" box-info full">
				<ul class="nav nav-tabs nav-justified">
					<li class="active">
						<a data-toggle="tab" href="#mailchimp">
							<?php echo JText::_('COM_CMC_MAILCHIMP_ACCOUNT_DETAILS'); ?>
						</a>
					</li>
					<li>
						<a data-toggle="tab" href="#rss">
							<?php echo JText::_('COM_CMC_LATEST_NEWS'); ?>
						</a>
					</li>
					<li>
						<a data-toggle="tab" href="#version">
							<?php echo JText::_('LIB_COMPOJOOM_VERSION_INFO'); ?>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="mailchimp" class="tab-pane active">
						<?php
						$apiKey = CmcHelperBasic::getComponent('com_cmc')->params->get('api_key');
						?>
						<?php
						if (!$apiKey) :
							?>
							<h1>Easy Email Newsletters</h1>
							<p>
								MailChimp helps you design email newsletters, share them on social networks, integrate
								with services you
								already
								use,
								and track your results. It's like your own personal publishing platform.
							</p>
							<a href="https://mailchimp.com/signup/?pid=compojoom&source=website" target="_blank"
							   class="btn btn-primary button-link">
								<?php echo JText::_('COM_CMC_MAILCHIMP_CREATE_ACCOUNT'); ?>
							</a>

						<?php else : ?>
							<p>
								<?php
								$details = $this->getAccountDetails();
								echo JText::_('COM_CMC_CONTACT') . ': ' . $details['account_name'] . '<br />';
								echo JText::_('COM_CMC_MAILCHIMP_SUBSCRIBERS') . ': ' . $details['total_subscribers'];
								?>
							</p>
							<a href="https://us1.admin.mailchimp.com/account/billing-plan/?pid=compojoom&source=website"
							   target="_blank"
							   class="btn btn-primary button-link">
								<?php echo JText::_('COM_CMC_MAILCHIMP_BUY_CREDITS'); ?>
							</a>
							<?php
						endif;
						?>
					</div>
					<div id="rss" class="tab-pane">
						<?php echo CompojoomHtmlFeed::renderFeed('https://compojoom.com/blog/tags/listings/cmc?format=feed&amp;type=rss'); ?>
					</div>
					<div id="version" class="tab-pane">
						<?php echo $this->loadTemplate('version'); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="box-info">
				<h2>Ads from compojoom.com</h2>

				<div class="text-center">
					<!--/* Ads for our products */-->
					<script type="text/javascript"
					        src="https://partners.compojoom.com/scripts/banner.php?a_aid=compojoom&a_bid=30f713ae"></script>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="box-info">
				<p>
					<strong>
						CMC <?php echo CmcHelperBasic::getComponent('com_cmc')->manifest_cache->get('version'); ?></strong>
					<br/>
					<small>
						Copyright 2008&ndash;<?php echo date('Y'); ?> &copy;compojoom.com
					</small>

					<br/>

					<strong>
						If you use CMC, please post a rating and a review at the
						<a href="http://extensions.joomla.org/extensions/content-sharing/mailing-a-newsletter-bridges/21710"
						   target="_blank">Joomla! Extensions Directory</a>.
					</strong>
				</p>

				<p>
					<small>
						CMC is Free software released under the
						<a href="www.gnu.org/licenses/gpl.html">GNU General Public License,</a>
						version 2 of the license or &ndash;at your option&ndash; any later version
						published by the Free Software Foundation.
					</small>
				</p>
				<p>
<span>
    <a href="https://mailchimp.com/?pid=compojoom&source=website" target="_blank">MailChimp</a>® is a registered trademark of The Rocket Science Group
</span>
				</p>

				<p>
					You don't speak english? <a
							href="https://compojoom.com/downloads/languages-cool-geil?view=project&id=5"
							target="_blank">Go
						here
						and
						download a translation</a>.
				</p>
			</div>
		</div>
	</div>

	<script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                $.ajax('index.php?option=com_cmc&task=update.updateinfo&tmpl=component', {
                    success: function (msg, textStatus, jqXHR) {
                        // Get rid of junk before and after data
                        var match = msg.match(/###([\s\S]*?)###/);
                        data = match[1];

                        if (data.length) {
                            $('#updateNotice').html(data);
                        }
                    }
                });
                $.ajax('index.php?option=com_cmc&task=jed.reviewed&tmpl=component&<?php echo JSession::getFormToken(); ?>=1', {
                    success: function (msg, textStatus, jqXHR) {
                        // Get rid of junk before and after data
                        var match = msg.match(/###([\s\S]*?)###/);
                        data = match[1];

                        if (data.length) {
                            $('#jedNotice').html(data);
                        }
                    }
                });
            });
        })(jQuery);
	</script>

<?php if ($this->updateStats): ?>
	<script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                $.ajax('index.php?option=com_cmc&task=stats.send&tmpl=component&<?php echo JSession::getFormToken(); ?>=1', {
                    dataType: 'json',
                    success: function (msg) {
                    }
                });
            });
        })(jQuery);
	</script>
	<?php
endif;
