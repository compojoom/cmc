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
    <?php echo JText::_('MOD_CCC_CMC_MAILCHIMP_CREATE_ACCOUNT'); ?>
</a>

<?php else : ?>
<h2><?php echo JText::_('MOD_CCC_CMC_MAILCHIMP_ACCOUNT_DETAILS'); ?></h2>
<p>
    <?php
    $details = modCCCMailchimpHelper::getAccountDetails();

    echo JText::_('MOD_CCC_CMC_MAILCHIMP_PLAN') . ': ' . $details['plan_type'];
    ?>
</p>
<a href="https://us1.admin.mailchimp.com/account/plans" target="_blank" class="btn btn-primary button-link">
    <?php echo JText::_('MOD_CCC_CMC_MAILCHIMP_BUY_CREDITS'); ?>
</a>
<?php
endif;
?>
