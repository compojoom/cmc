<?php

defined('_JEXEC') or die;

// Module Helper for our Positions
jimport('joomla.application.module.helper');

JHTML::_('behavior.tooltip');
echo CompojoomHtmlCtemplate::getHead(CmcHelperBasic::getMenu(), 'cpanel', 'COM_CMC_CPANEL', '');
?>
<div id="updateNotice"></div>

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
							MailChimp helps you design email newsletters, share them on social networks, integrate with services you
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
							echo JText::_('COM_CMC_CONTACT') . ': ' . $details['contact']['fname'] . ' ' . $details['contact']['lname'] . '<br />';
							echo JText::_('COM_CMC_MAILCHIMP_PLAN') . ': ' . $details['plan_type'];
							?>
						</p>
						<a href="https://us1.admin.mailchimp.com/account/plans" target="_blank" class="btn btn-primary button-link">
							<?php echo JText::_('COM_CMC_MAILCHIMP_BUY_CREDITS'); ?>
						</a>
					<?php
					endif;
					?>
				</div>
				<div id="rss" class="tab-pane">
					<?php echo CompojoomHtmlFeed::renderFeed('https://compojoom.com/about/blog/tags/listings/cmc?format=feed&amp;type=rss'); ?>
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

				<script type='text/javascript'><!--//<![CDATA[
					var m3_u = (location.protocol == 'https:' ? 'https://matangazo.compojoom.com/www/delivery/ajs.php' : 'http://matangazo.compojoom.com/www/delivery/ajs.php');
					var m3_r = Math.floor(Math.random() * 99999999999);
					if (!document.MAX_used) document.MAX_used = ',';
					document.write("<scr" + "ipt type='text/javascript' src='" + m3_u);
					document.write("?zoneid=1");
					document.write('&amp;cb=' + m3_r);
					document.write('&amp;isPro=0');
					if (document.MAX_used != ',') document.write("&amp;exclude=" + document.MAX_used);
					document.write(document.charset ? '&amp;charset=' + document.charset : (document.characterSet ? '&amp;charset=' + document.characterSet : ''));
					document.write("&amp;loc=" + escape(window.location));
					if (document.referrer) document.write("&amp;referer=" + escape(document.referrer));
					if (document.context) document.write("&context=" + escape(document.context));
					if (document.mmm_fo) document.write("&amp;mmm_fo=1");
					document.write("'><\/scr" + "ipt>");
					//]]>--></script>
				<noscript><a href='http://matangazo.compojoom.com/www/delivery/ck.php?n=a8ed4360&amp;cb=INSERT_RANDOM_NUMBER_HERE'
				             target='_blank'><img
							src='http://matangazo.compojoom.com/www/delivery/avw.php?zoneid=1&amp;cb=INSERT_RANDOM_NUMBER_HERE&amp;n=a8ed4360'
							border='0' alt=''/></a></noscript>

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
				You don't speak english? <a href="https://compojoom.com/downloads/languages-cool-geil?view=project&id=5" target="_blank">Go here
					and
					download a translation</a>.
			</p>
		</div>

	</div>
</div>

<script type="text/javascript">
	(function($) {
		$(document).ready(function(){
			$.ajax('index.php?option=com_cmc&task=update.updateinfo&tmpl=component', {
				success: function(msg, textStatus, jqXHR)
				{
					// Get rid of junk before and after data
					var match = msg.match(/###([\s\S]*?)###/);
					data = match[1];

					if (data.length)
					{
						$('#updateNotice').html(data);
					}
				}
			})
		});
	})(jQuery);
</script>

<?php
// Show Footer
echo CompojoomHtmlCTemplate::getFooter(CmcHelperBasic::footer());
