<?php
/**
 * Compojoom System Plugin
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import libaries
jimport('joomla.event.plugin');

JLoader::discover('CmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');   // Hmm not working?


class plgSystemECom360_akeeba extends JPlugin {

    /**
     * object(AkeebasubsTableSubscription)#647 (39) {
         * ["_selfCache":"AkeebasubsTableSubscription":private]=> NULL ["_dontCheckPaymentID"]=> bool(true)
         * ["_trigger_events":protected]=> bool(false) ["_columnAlias":protected]=> array(0) {
         * }
         * ["_autoChecks":protected]=> bool(false) ["_skipChecks":protected]=> array(0) {
         * }
         * ["_tbl":protected]=> string(27) "#__akeebasubs_subscriptions" ["_tbl_key":protected]=> string(26) "akeebasubs_subscription_id"
         * ["_db":protected]=> object(JDatabaseMySQLi)#16 (19) { ["name"]=> string(6) "mysqli" ["nameQuote":protected]=> string(1) "`"
         * ["nullDate":protected]=> string(19) "0000-00-00 00:00:00" ["dbMinimum":protected]=> string(5) "5.0.4"
         * ["_database":"JDatabase":private]=> string(4) "j25d" ["connection":protected]=> object(mysqli)#17 (19) {
         * ["affected_rows"]=> int(1) ["client_info"]=> string(6) "5.5.25" ["client_version"]=> int(50525) ["connect_errno"]=> int(0)
         * ["connect_error"]=> NULL ["errno"]=> int(0) ["error"]=> string(0) "" ["error_list"]=> array(0) { } ["field_count"]=> int(0)
         * ["host_info"]=> string(25) "Localhost via UNIX socket" ["info"]=> NULL ["insert_id"]=> int(1) ["server_info"]=> string(6) "5.5.25"
         * ["server_version"]=> int(50525) ["stat"]=> string(139) "Uptime: 107542 Threads: 1 Questions: 29511 Slow queries: 0 Opens: 491 *
     * Flush tables: 1 Open tables: 353 Queries per second avg: 0.274"
         * ["sqlstate"]=> string(5) "00000" ["protocol_version"]=> int(10) ["thread_id"]=> int(565) ["warning_count"]=> int(0)
     * }
     * ["count":protected]=> int(52) ["cursor":protected]=> bool(true) ["debug":protected]=> bool(true) ["limit":protected]=> int(0)
     * ["log":protected]=> array(52) {
        * [0]=> string(88) "SELECT `data` FROM `jos_session` WHERE `session_id` = '15e28366a564a5465609e65992e51c1a'"
     *[1]=> string(159) "SELECT folder AS type, element AS name, params FROM jos_extensions WHERE enabled >= 1 AND type ='plugin' AND state >= 0 AND access IN (1,1,2) ORDER BY ordering"
     * [2]=> string(142) "SELECT extension_id AS id, element AS "option", params, enabled FROM jos_extensions WHERE `type` = 'component' AND `element` = 'com_languages'"
     * [3]=> string(143) "SELECT extension_id AS id, element AS "option", params, enabled FROM jos_extensions WHERE `type` = 'component' AND `element` = 'com_akeebasubs'"
     * [4]=> string(385) "SELECT m.id, m.menutype, m.title, m.alias, m.note, m.path AS route, m.link, m.type, m.level, m.language,m.browserNav, m.access, m.params, m.home, m.img,
     * m.template_style_id, m.component_id, m.parent_id,e.element as component FROM jos_menu AS m LEFT JOIN jos_extensions AS e ON m.component_id = e.extension_id
     * WHERE m.published = 1 AND m.parent_id > 0 AND m.client_id = 0 ORDER BY m.lft" [5]=> string(67) "SELECT * FROM jos_languages WHERE published=1 ORDER BY ordering ASC"
     * [6]=> string(209) "SELECT id, home, template, s.params FROM jos_template_styles as s LEFT JOIN jos_extensions as e ON e.element=s.template AND e.type='template'
     * AND e.client_id=s.client_id WHERE s.client_id = 0 AND e.enabled = 1" [7]=> string(46) "SHOW FULL COLUMNS FROM `jos_akeebasubs_levels`" [8]=> string(96) "SELECT * FROM
     * `jos_akeebasubs_levels` WHERE `slug` = 'testsub' ORDER BY akeebasubs_level_id DESC" [9]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'"
     * [10]=> string(34) "SHOW FULL COLUMNS FROM `jos_users`" [11]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [12]=> string(68)
     * "SELECT * FROM `jos_users` WHERE `username` = 'test' ORDER BY id DESC" [13]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [14]=> string(48
     * ) "SHOW FULL COLUMNS FROM `jos_akeebasubs_upgrades`" [15]=> string(111) "SELECT * FROM `jos_akeebasubs_upgrades` WHERE `to_id` = 1 AND `enabled` = 1 ORDER BY akeebasubs_upgrade_
     * id DESC" [16]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [17]=> string(48) "SHOW FULL COLUMNS FROM `jos_akeebasubs_taxrules`" [18]
     * => string(81) "SELECT * FROM `jos_akeebasubs_taxrules` WHERE `enabled`='1' ORDER BY ordering ASC" [19]=> string(71) "SELECT * FROM `jos_akeebasubs_levels` ORDER BY akeebasubs_lev
     * el_id DESC" [20]=> string(52) "SHOW FULL COLUMNS FROM `jos_akeebasubs_customfields`" [21]=> string(52) "SHOW FULL COLUMNS FROM `jos_akeebasubs_customfields`" [22]=> string(65) "SEL
     * ECT * FROM `jos_akeebasubs_customfields` WHERE `enabled` = '1'" [23]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [24]=> string(68) "S
     * ELECT * FROM `jos_users` WHERE `username` = 'test' ORDER BY id DESC" [25]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_lev
     * el_id` = '1'" [26]=> string(111) "SELECT * FROM `jos_akeebasubs_upgrades` WHERE `to_id` = 1 AND `enabled` = 1 ORDER BY akeebasubs_upgrade_id DESC
     * " [27]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [28]=> string(81) "SELECT * FROM `jos_akeebasubs_t
     * axrules` WHERE `enabled`='1' ORDER BY ordering ASC" [29]=> string(52) "SHOW FULL COLUMNS FROM `jos_akeebasubs_customfields`" [30]=> string(65) "SELECT
     * * FROM `jos_akeebasubs_customfields` WHERE `enabled` = '1'" [31]=> string(53) "SHOW FULL COLUMNS FROM `jos_akeebasubs_subscriptions`" [32]=> string(73
     * 4) "SELECT `tbl`.*,`l`.`title`,`l`.`image`,`u`.`name`,`u`.`username`,`u`.`email`,`u`.`block`,`a`.`isbusiness`,`a`.`businessname`,`a`.`occupation`,`a`.`
     * vatnumber`,`a`.`viesregistered`,`a`.`taxauthority`,`a`.`address1`,`a`.`address2`,`a`.`city`,`a`.`state` AS `userstate`,`a`.`zip`,`a`.`country`,`a`.`params` A
     * S `userparams`,`a`.`notes` AS `usernotes` FROM `jos_akeebasubs_subscriptions` AS `tbl` INNER JOIN `jos_akeebasubs_levels` AS `l` ON `l`.`akeebasubs_level_id`
     * = `tbl`.`akeebasubs_level_id` LEFT OUTER JOIN `jos_users` AS `u` ON `u`.`id` = `tbl`.`user_id` LEFT OUTER JOIN `jos_akeebasubs_users` AS `a` ON `a`.`user_id`
     * = `tbl`.`user_id` WHERE `tbl`.`state` IN ('C') AND `tbl`.`user_id` = '59' ORDER BY akeebasubs_subscription_id DESC" [33]=> string(91) "SELECT * FROM `jos_akeeba
     * subs_levels` WHERE `enabled` = 1 ORDER BY akeebasubs_level_id DESC" [34]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [35
     * ]=> string(734) "SELECT `tbl`.*,`l`.`title`,`l`.`image`,`u`.`name`,`u`.`username`,`u`.`email`,`u`.`block`,`a`.`isbusiness`,`a`.`businessname`,`a`.`occupa
     * tion`,`a`.`vatnumber`,`a`.`viesregistered`,`a`.`taxauthority`,`a`.`address1`,`a`.`address2`,`a`.`city`,`a`.`state` AS `userstate`,`a`.`zip`,`a`.`cou
     * ntry`,`a`.`params` AS `userparams`,`a`.`notes` AS `usernotes` FROM `jos_akeebasubs_subscriptions` AS `tbl` INNER JOIN `jos_akeebasubs_levels` AS `l
     * ` ON `l`.`akeebasubs_level_id` = `tbl`.`akeebasubs_level_id` LEFT OUTER JOIN `jos_users` AS `u` ON `u`.`id` = `tbl`.`user_id` LEFT OUTER JOIN `jos_akeeb
     * asubs_users` AS `a` ON `a`.`user_id` = `tbl`.`user_id` WHERE `tbl`.`state` IN ('N') AND `tbl`.`user_id` = '59' ORDER BY akeebasubs_subscription_id DESC
     * " [36]=> string(41) "SELECT * FROM jos_users WHERE `id` = '59'" [37]=> string(495) "UPDATE `jos_users` SET `name`='test name',`username`='test',`email`=
     * 'test@vicube.de',`password`='17e0f2ed0ba08a7f2c8082c358f2762c:3xxPcinwnzZPDAJX5kOAm9cWHj6nvoWF',`usertyp
     * e`='',`block`='0',`sendEmail`='0',`registerDate`='2012-10-14 11:24:38',`lastvisitDate`='2012-12-20 13:49:23',`act
     * ivation`='',`params`='{\"admin_style\":\"\",\"admin_language\":\"\",\"language\":\"\",\"editor\":\"\",\"helpsite\":\"\",
     * \"timezone\":\"\"}',`lastResetTime`='0000-00-00 00:00:00',`resetCount`='0' WHERE `id`='59'" [38]=> string(75) "SELECT * FRO
     * M `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [39]=> string(68) "SELECT * FROM `jos_users` WHERE `username` = '
     * test' ORDER BY id DESC" [40]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [41]=> string(111)
     * "SELECT * FROM `jos_akeebasubs_upgrades` WHERE `to_id` = 1 AND `enabled` = 1 ORDER BY akeebasubs_upgrade_id DESC" [42]=> string(7
     * 5) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [43]=> string(81) "SELECT * FROM `jos_akeebasub
     * s_taxrules` WHERE `enabled`='1' ORDER BY ordering ASC" [44]=> string(52) "SHOW FULL COLUMNS FROM
     * `jos_akeebasubs_customfields`" [45]=> string(65) "SELECT * FROM `jos_akeebasubs_customfields` WHER
     * E `enabled` = '1'" [46]=> string(45) "SHOW FULL COLUMNS FROM `jos_akeebasubs_users`" [47]=> string(207
     * ) "SELECT `tbl`.*,`u`.`name`,`u`.`username`,`u`.`email` FROM `jos_akeebasubs_users` AS `tbl` INNER JOIN `jo
     * s_users` AS `u` ON `u`.`id` = `tbl`.`user_id` WHERE `tbl`.`user_id`=59 ORDER BY akeebasubs_user_id DESC" [48]
     * => string(302) "INSERT INTO `jos_akeebasubs_users` (`akeebasubs_user_id`,`user_id`,`isbusiness`,`businessname`,`o
     * ccupation`,`vatnumber`,`viesregistered`,`taxauthority`,`address1`,`address2`,`city`,`state`,`zip`,`country`,`param
     * s`) VALUES ('0','59','0','','','','','','teststr. 23','','Testhausen','','18317','AD','[]')" [49]=> string(771) "SELE
     * CT `tbl`.*,`l`.`title`,`l`.`image`,`u`.`name`,`u`.`username`,`u`.`email`,`u`.`block`,`a`.`isbusiness`,`a`.`businessnam
     * e`,`a`.`occupation`,`a`.`vatnumber`,`a`.`viesregistered`,`a`.`taxauthority`,`a`.`address1`,`a`.`address2`,`a`.`city`,`a`.
     * `state` AS `userstate`,`a`.`zip`,`a`.`country`,`a`.`params` AS `userparams`,`a`.`notes` AS `usernotes` FROM `jos_akeebasu
     * bs_subscriptions` AS `tbl` INNER JOIN `jos_akeebasubs_levels` AS `l` ON `l`.`akeebasubs_level_id` = `tbl`.`akeebasubs_level_id
     * ` LEFT OUTER JOIN `jos_users` AS `u` ON `u`.`id` = `tbl`.`user_id` LEFT OUTER JOIN `jos_akeebasubs_users` AS `a` ON `a`.`user_id
     * ` = `tbl`.`user_id` WHERE `tbl`.`enabled` = '1' AND `tbl`.`akeebasubs_level_id` = '1' AND `tbl`.`user_id` = '59' ORDER BY akeebas
     * ubs_subscription_id DESC" [50]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [51]=> string
     * (667) "INSERT INTO `jos_akeebasubs_subscriptions` (`akeebasubs_subscription_id`,`user_id`,`akeebasubs_level_id`,`publish_up`,`publi
     * sh_down`,`notes`,`enabled`,`processor`,`processor_key`,`state`,`net_amou
     * nt`,`tax_amount`,`gross_amount`,`tax_percent`,`created_on`,`params`,`akeebasubs_coupon_id
     * `,`akeebasubs_upgrade_id`,`akeebasubs_affiliate_id`,`affiliate_comission`,`prediscount_amount`,`discount
     * _amount`,`contact_flag`,`first_contact`,`second_contact`) VALUES ('0','59','1','2012-12-20 13:49:58','2013-1
     * 2-20 13:49:58','','0','offline','','N','10','0.00','10.00','0.00','2012-12-20 13:49:58','','0','0','0','0','10.00','0.00','0','0000-00-00 00:00:00','0000-00-00 00:00:00')"
     * }
     *
     * ["offset":protected]=> int(0)
     * ["sql":protected]=> string(666) "INSERT INTO `#__akeebasubs_subscriptions` (`akeebasubs_subscription_id`,`user_id`,`akeebasubs_level_id`,
     * `publish_up`,`publish_down`,`notes`,`enabled`,`processor`,`processor_key`,`state`,`net_amount`,`tax_amount`,`gross_amount`,`tax_percent`
     * ,`created_on`,`params`,`akeebasubs_coupon_id`,`akeebasubs_upgrade_id`,`akeebasubs_affiliate_id`,`affiliate_comission`,`prediscount_amount
     *
     * `,`discount_amount`,`contact_flag`,`first_contact`,`second_contact`) VALUES ('0','59','1','2012-12-20 13:49:58','2013-12-20 13:49:58','',
     * '0','offline','','N','10','0.00','10.00','0.00','2012-12-20 13:49:58','','0','0','0','0','10.00','0.00','0','0000-00-00 00:00:00','0000-00-0
     * 0 00:00:00')" ["tablePrefix":protected]=> string(4) "jos_" ["utf":protected]=> bool(true) ["errorNum":protected]=> int(0) ["errorMsg":protected
     * ]=> string(0) "" ["hasQuoted":protected]=> bool(false) ["quoted":protected]=> array(0) { } } ["_trackAssets":protected]=> bool(false) ["_rules":protected]=> NULL
     * ["_locked":protected]=> bool(false) ["_errors":protected]=> array(0) { }
     *
     * ["akeebasubs_subscription_id"]=> int(1) ["user_id"]=> string(2) "59" ["akeebasubs_level_id"]=> int(1)
     * ["publish_up"]=> string(19) "2012-12-20 13:49:58" ["publish_down"]=> string(19) "2013-12-20 13:49:58"
     * ["notes"]=> string(0) "" ["enabled"]=> int(0) ["processor"]=> string(7) "offline" ["processor_key"]=> string(0) ""
     * ["state"]=> string(1) "N" ["net_amount"]=> float(10) ["tax_amount"]=> string(4) "0.00" ["gross_amount"]=> string(5) "10.00"
     * ["tax_percent"]=> string(4) "0.00" ["created_on"]=> string(19) "2012-12-20 13:49:58" ["params"]=> string(0) "" ["akeebasubs_coupon_id"]=> int(0)
     * ["akeebasubs_upgrade_id"]=> int(0) ["akeebasubs_affiliate_id"]=> int(0) ["affiliate_comission"]=> int(0) ["akeebasubs_invoice_id"]=> NULL
     * ["prediscount_amount"]=> string(5) "10.00" ["discount_amount"]=> string(4) "0.00" ["contact_flag"]=> int(0) ["first_contact"]=> string(19) "0000-00-00 00:00:00"
     * ["second_contact"]=> string(19) "0000-00-00 00:00:00" } array(4) { ["status"]=> string(3) "new" ["previous"]=> NULL
     * ["current"]=> object(AkeebasubsTableSubscription)#724 (39) { ["_selfCache":"AkeebasubsTableSubscription":private]=> NULL
     * ["_dontCheckPaymentID"]=> bool(true) ["_trigger_events":protected]=> bool(false) ["_columnAlias":protected]=> array(0) { }
     * ["_autoChecks":protected]=> bool(false) ["_skipChecks":protected]=> array(0) { } ["_tbl":protected]=> string(27) "#__akeebasubs_subscriptions"
     * ["_tbl_key":protected]=> string(26) "akeebasubs_subscription_id" ["_db":protected]=> object(JDatabaseMySQLi)#16 (19) { [
     * "name"]=> string(6) "mysqli" ["nameQuote":protected]=> string(1) "`" ["nullDate":protected]=> string(19) "0000-00-00 00:00:00"
     * ["dbMinimum":protected]=> string(5) "5.0.4" ["_database":"JDatabase":private]=> string(4) "j25d" ["connection":protected]=>
     * object(mysqli)#17 (19) { ["affected_rows"]=> int(-1) ["client_info"]=> string(6) "5.5.25" ["client_version"]=> int(50525)
     * ["connect_errno"]=> int(0) ["connect_error"]=> NULL ["errno"]=> int(0) ["error"]=> string(0) "" ["error_list"]=> array(0) { }
     * ["field_count"]=> int(0) ["host_info"]=> string(25) "Localhost via UNIX socket" ["info"]=> NULL ["insert_id"]=> int(1)
     * ["server_info"]=> string(6) "5.5.25" ["server_version"]=> int(50525) ["stat"]=> string(139) "Uptime: 107542 Threads: 1 Questions: 29511 Slow queries: 0 Opens: 491 Flush tables: 1 Open tables: 353 Queries per second avg: 0.274" ["sqlstate"]=> string(5) "00000" ["protocol_version"]=> int(10) ["thread_id"]=> int(565) ["warning_count"]=> int(0) } ["count":protected]=> int(52) ["cursor":protected]=> bool(true) ["debug":protected]=> bool(true) ["limit":protected]=> int(0) ["log":protected]=> array(52) { [0]=> string(88) "SELECT `data` FROM `jos_session` WHERE `session_id` = '15e28366a564a5465609e65992e51c1a'" [1]=> string(159) "SELECT folder AS type, element AS name, params FROM jos_extensions WHERE enabled >= 1 AND type ='plugin' AND state >= 0 AND access IN (1,1,2) ORDER BY ordering" [2]=> string(142) "SELECT extension_id AS id, element AS "option", params, enabled FROM jos_extensions WHERE `type` = 'component' AND `element` = 'com_languages'" [3]=> string(143) "SELECT extension_id AS id, element AS "option", params, enabled FROM jos_extensions WHERE `type` = 'component' AND `element` = 'com_akeebasubs'" [4]=> string(385) "SELECT m.id, m.menutype, m.title, m.alias, m.note, m.path AS route, m.link, m.type, m.level, m.language,m.browserNav, m.access, m.params, m.home, m.img, m.template_style_id, m.component_id, m.parent_id,e.element as component FROM jos_menu AS m LEFT JOIN jos_extensions AS e ON m.component_id = e.extension_id WHERE m.published = 1 AND m.parent_id > 0 AND m.client_id = 0 ORDER BY m.lft" [5]=> string(67) "SELECT * FROM jos_languages WHERE published=1 ORDER BY ordering ASC" [6]=> string(209) "SELECT id, home, template, s.params FROM jos_template_styles as s LEFT JOIN jos_extensions as e ON e.element=s.template AND e.type='template' AND e.client_id=s.client_id WHERE s.client_id = 0 AND e.enabled = 1" [7]=> string(46) "SHOW FULL COLUMNS FROM `jos_akeebasubs_levels`" [8]=> string(96) "SELECT * FROM `jos_akeebasubs_levels` WHERE `slug` = 'testsub' ORDER BY akeebasubs_level_id DESC" [9]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [10]=> string(34) "SHOW FULL COLUMNS FROM `jos_users`" [11]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [12]=> string(68) "SELECT * FROM `jos_users` WHERE `username` = 'test' ORDER BY id DESC" [13]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [14]=> string(48) "SHOW FULL COLUMNS FROM `jos_akeebasubs_upgrades`" [15]=> string(111) "SELECT * FROM `jos_akeebasubs_upgrades` WHERE `to_id` = 1 AND `enabled` = 1 ORDER BY akeebasubs_upgrade_id DESC" [16]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [17]=> string(48) "SHOW FULL COLUMNS FROM `jos_akeebasubs_taxrules`" [18]=> string(81) "SELECT * FROM `jos_akeebasubs_taxrules` WHERE `enabled`='1' ORDER BY ordering ASC" [19]=> string(71) "SELECT * FROM `jos_akeebasubs_levels` ORDER BY akeebasubs_level_id DESC" [20]=> string(52) "SHOW FULL COLUMNS FROM `jos_akeebasubs_customfields`" [21]=> string(52) "SHOW FULL COLUMNS FROM `jos_akeebasubs_customfields`" [22]=> string(65) "SELECT * FROM `jos_akeebasubs_customfields` WHERE `enabled` = '1'" [23]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [24]=> string(68) "SELECT * FROM `jos_users` WHERE `username` = 'test' ORDER BY id DESC" [25]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [26]=> string(111) "SELECT * FROM `jos_akeebasubs_upgrades` WHERE `to_id` = 1 AND `enabled` = 1 ORDER BY akeebasubs_upgrade_id DESC" [27]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [28]=> string(81) "SELECT * FROM `jos_akeebasubs_taxrules` WHERE `enabled`='1' ORDER BY ordering ASC" [29]=> string(52) "SHOW FULL COLUMNS FROM `jos_akeebasubs_customfields`" [30]=> string(65) "SELECT * FROM `jos_akeebasubs_customfields` WHERE `enabled` = '1'" [31]=> string(53) "SHOW FULL COLUMNS FROM `jos_akeebasubs_subscriptions`" [32]=> string(734) "SELECT `tbl`.*,`l`.`title`,`l`.`image`,`u`.`name`,`u`.`username`,`u`.`email`,`u`.`block`,`a`.`isbusiness`,`a`.`businessname`,`a`.`occupation`,`a`.`vatnumber`,`a`.`viesregistered`,`a`.`taxauthority`,`a`.`address1`,`a`.`address2`,`a`.`city`,`a`.`state` AS `userstate`,`a`.`zip`,`a`.`country`,`a`.`params` AS `userparams`,`a`.`notes` AS `usernotes` FROM `jos_akeebasubs_subscriptions` AS `tbl` INNER JOIN `jos_akeebasubs_levels` AS `l` ON `l`.`akeebasubs_level_id` = `tbl`.`akeebasubs_level_id` LEFT OUTER JOIN `jos_users` AS `u` ON `u`.`id` = `tbl`.`user_id` LEFT OUTER JOIN `jos_akeebasubs_users` AS `a` ON `a`.`user_id` = `tbl`.`user_id` WHERE `tbl`.`state` IN ('C') AND `tbl`.`user_id` = '59' ORDER BY akeebasubs_subscription_id DESC" [33]=> string(91) "SELECT * FROM `jos_akeebasubs_levels` WHERE `enabled` = 1 ORDER BY akeebasubs_level_id DESC" [34]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [35]=> string(734) "SELECT `tbl`.*,`l`.`title`,`l`.`image`,`u`.`name`,`u`.`username`,`u`.`email`,`u`.`block`,`a`.`isbusiness`,`a`.`businessname`,`a`.`occupation`,`a`.`vatnumber`,`a`.`viesregistered`,`a`.`taxauthority`,`a`.`address1`,`a`.`address2`,`a`.`city`,`a`.`state` AS `userstate`,`a`.`zip`,`a`.`country`,`a`.`params` AS `userparams`,`a`.`notes` AS `usernotes` FROM `jos_akeebasubs_subscriptions` AS `tbl` INNER JOIN `jos_akeebasubs_levels` AS `l` ON `l`.`akeebasubs_level_id` = `tbl`.`akeebasubs_level_id` LEFT OUTER JOIN `jos_users` AS `u` ON `u`.`id` = `tbl`.`user_id` LEFT OUTER JOIN `jos_akeebasubs_users` AS `a` ON `a`.`user_id` = `tbl`.`user_id` WHERE `tbl`.`state` IN ('N') AND `tbl`.`user_id` = '59' ORDER BY akeebasubs_subscription_id DESC" [36]=> string(41) "SELECT * FROM jos_users WHERE `id` = '59'" [37]=> string(495) "UPDATE `jos_users` SET `name`='test name',`username`='test',`email`='test@vicube.de',`password`='17e0f2ed0ba08a7f2c8082c358f2762c:3xxPcinwnzZPDAJX5kOAm9cWHj6nvoWF',`usertype`='',`block`='0',`sendEmail`='0',`registerDate`='2012-10-14 11:24:38',`lastvisitDate`='2012-12-20 13:49:23',`activation`='',`params`='{\"admin_style\":\"\",\"admin_language\":\"\",\"language\":\"\",\"editor\":\"\",\"helpsite\":\"\",\"timezone\":\"\"}',`lastResetTime`='0000-00-00 00:00:00',`resetCount`='0' WHERE `id`='59'" [38]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [39]=> string(68) "SELECT * FROM `jos_users` WHERE `username` = 'test' ORDER BY id DESC" [40]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [41]=> string(111) "SELECT * FROM `jos_akeebasubs_upgrades` WHERE `to_id` = 1 AND `enabled` = 1 ORDER BY akeebasubs_upgrade_id DESC" [42]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [43]=> string(81) "SELECT * FROM `jos_akeebasubs_taxrules` WHERE `enabled`='1' ORDER BY ordering ASC" [44]=> string(52) "SHOW FULL COLUMNS FROM `jos_akeebasubs_customfields`" [45]=> string(65) "SELECT * FROM `jos_akeebasubs_customfields` WHERE `enabled` = '1'" [46]=> string(45) "SHOW FULL COLUMNS FROM `jos_akeebasubs_users`" [47]=> string(207) "SELECT `tbl`.*,`u`.`name`,`u`.`username`,`u`.`email` FROM `jos_akeebasubs_users` AS `tbl` INNER JOIN `jos_users` AS `u` ON `u`.`id` = `tbl`.`user_id` WHERE `tbl`.`user_id`=59 ORDER BY akeebasubs_user_id DESC" [48]=> string(302) "INSERT INTO `jos_akeebasubs_users` (`akeebasubs_user_id`,`user_id`,`isbusiness`,`businessname`,`occupation`,`vatnumber`,`viesregistered`,`taxauthority`,`address1`,`address2`,`city`,`state`,`zip`,`country`,`params`) VALUES ('0','59','0','','','','','','teststr. 23','','Testhausen','','18317','AD','[]')" [49]=> string(771) "SELECT `tbl`.*,`l`.`title`,`l`.`image`,`u`.`name`,`u`.`username`,`u`.`email`,`u`.`block`,`a`.`isbusiness`,`a`.`businessname`,`a`.`occupation`,`a`.`vatnumber`,`a`.`viesregistered`,`a`.`taxauthority`,`a`.`address1`,`a`.`address2`,`a`.`city`,`a`.`state` AS `userstate`,`a`.`zip`,`a`.`country`,`a`.`params` AS `userparams`,`a`.`notes` AS `usernotes` FROM `jos_akeebasubs_subscriptions` AS `tbl` INNER JOIN `jos_akeebasubs_levels` AS `l` ON `l`.`akeebasubs_level_id` = `tbl`.`akeebasubs_level_id` LEFT OUTER JOIN `jos_users` AS `u` ON `u`.`id` = `tbl`.`user_id` LEFT OUTER JOIN `jos_akeebasubs_users` AS `a` ON `a`.`user_id` = `tbl`.`user_id` WHERE `tbl`.`enabled` = '1' AND `tbl`.`akeebasubs_level_id` = '1' AND `tbl`.`user_id` = '59' ORDER BY akeebasubs_subscription_id DESC" [50]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [51]=> string(667) "INSERT INTO `jos_akeebasubs_subscriptions` (`akeebasubs_subscription_id`,`user_id`,`akeebasubs_level_id`,`publish_up`,`publish_down`,`notes`,`enabled`,`processor`,`processor_key`,`state`,`net_amount`,`tax_amount`,`gross_amount`,`tax_percent`,`created_on`,`params`,`akeebasubs_coupon_id`,`akeebasubs_upgrade_id`,`akeebasubs_affiliate_id`,`affiliate_comission`,`prediscount_amount`,`discount_amount`,`contact_flag`,`first_contact`,`second_contact`) VALUES ('0','59','1','2012-12-20 13:49:58','2013-12-20 13:49:58','','0','offline','','N','10','0.00','10.00','0.00','2012-12-20 13:49:58','','0','0','0','0','10.00','0.00','0','0000-00-00 00:00:00','0000-00-00 00:00:00')" } ["offset":protected]=> int(0) ["sql":protected]=> string(666) "INSERT INTO `#__akeebasubs_subscriptions` (`akeebasubs_subscription_id`,`user_id`,`akeebasubs_level_id`,`publish_up`,`publish_down`,`notes`,`enabled`,`processor`,`processor_key`,`state`,`net_amount`,`tax_amount`,`gross_amount`,`tax_percent`,`created_on`,`params`,`akeebasubs_coupon_id`,`akeebasubs_upgrade_id`,`akeebasubs_affiliate_id`,`affiliate_comission`,`prediscount_amount`,`discount_amount`,`contact_flag`,`first_contact`,`second_contact`) VALUES ('0','59','1','2012-12-20 13:49:58','2013-12-20 13:49:58','','0','offline','','N','10','0.00','10.00','0.00','2012-12-20 13:49:58','','0','0','0','0','10.00','0.00','0','0000-00-00 00:00:00','0000-00-00 00:00:00')" ["tablePrefix":protected]=> string(4) "jos_" ["utf":protected]=> bool(true) ["errorNum":protected]=> int(0) ["errorMsg":protected]=> string(0) "" ["hasQuoted":protected]=> bool(false) ["quoted":protected]=> array(0) { } } ["_trackAssets":protected]=> bool(false) ["_rules":protected]=> NULL ["_locked":protected]=> bool(false) ["_errors":protected]=> array(0) { } ["akeebasubs_subscription_id"]=> int(1) ["user_id"]=> string(2) "59" ["akeebasubs_level_id"]=> int(1) ["publish_up"]=> string(19) "2012-12-20 13:49:58" ["publish_down"]=> string(19) "2013-12-20 13:49:58" ["notes"]=> string(0) "" ["enabled"]=> int(0) ["processor"]=> string(7) "offline" ["processor_key"]=> string(0) "" ["state"]=> string(1) "N" ["net_amount"]=> float(10) ["tax_amount"]=> string(4) "0.00" ["gross_amount"]=> string(5) "10.00" ["tax_percent"]=> string(4) "0.00" ["created_on"]=> string(19) "2012-12-20 13:49:58" ["params"]=> string(0) "" ["akeebasubs_coupon_id"]=> int(0) ["akeebasubs_upgrade_id"]=> int(0) ["akeebasubs_affiliate_id"]=> int(0) ["affiliate_comission"]=> int(0) ["akeebasubs_invoice_id"]=> NULL ["prediscount_amount"]=> string(5) "10.00" ["discount_amount"]=> string(4) "0.00" ["contact_flag"]=> int(0) ["first_contact"]=> string(19) "0000-00-00 00:00:00" ["second_contact"]=> string(19) "0000-00-00 00:00:00" } ["modified"]=> object(AkeebasubsTableSubscription)#684 (39) { ["_selfCache":"AkeebasubsTableSubscription":private]=> NULL ["_dontCheckPaymentID"]=> bool(true) ["_trigger_events":protected]=> bool(false) ["_columnAlias":protected]=> array(0) { } ["_autoChecks":protected]=> bool(false) ["_skipChecks":protected]=> array(0) { } ["_tbl":protected]=> string(27) "#__akeebasubs_subscriptions" ["_tbl_key":protected]=> string(26) "akeebasubs_subscription_id" ["_db":protected]=> object(JDatabaseMySQLi)#16 (19) { ["name"]=> string(6) "mysqli" ["nameQuote":protected]=> string(1) "`" ["nullDate":protected]=> string(19) "0000-00-00 00:00:00" ["dbMinimum":protected]=> string(5) "5.0.4" ["_database":"JDatabase":private]=> string(4) "j25d" ["connection":protected]=> object(mysqli)#17 (19) { ["affected_rows"]=> int(-1) ["client_info"]=> string(6) "5.5.25" ["client_version"]=> int(50525) ["connect_errno"]=> int(0) ["connect_error"]=> NULL ["errno"]=> int(0) ["error"]=> string(0) "" ["error_list"]=> array(0) { } ["field_count"]=> int(0) ["host_info"]=> string(25) "Localhost via UNIX socket" ["info"]=> NULL ["insert_id"]=> int(1) ["server_info"]=> string(6) "5.5.25" ["server_version"]=> int(50525) ["stat"]=> string(139) "Uptime: 107542 Threads: 1 Questions: 29511 Slow queries: 0 Opens: 491 Flush tables: 1 Open tables: 353 Queries per second avg: 0.274" ["sqlstate"]=> string(5) "00000" ["protocol_version"]=> int(10) ["thread_id"]=> int(565) ["warning_count"]=> int(0) } ["count":protected]=> int(52) ["cursor":protected]=> bool(true) ["debug":protected]=> bool(true) ["limit":protected]=> int(0) ["log":protected]=> array(52) { [0]=> string(88) "SELECT `data` FROM `jos_session` WHERE `session_id` = '15e28366a564a5465609e65992e51c1a'" [1]=> string(159) "SELECT folder AS type, element AS name, params FROM jos_extensions WHERE enabled >= 1 AND type ='plugin' AND state >= 0 AND access IN (1,1,2) ORDER BY ordering" [2]=> string(142) "SELECT extension_id AS id, element AS "option", params, enabled FROM jos_extensions WHERE `type` = 'component' AND `element` = 'com_languages'" [3]=> string(143) "SELECT extension_id AS id, element AS "option", params, enabled FROM jos_extensions WHERE `type` = 'component' AND `element` = 'com_akeebasubs'" [4]=> string(385) "SELECT m.id, m.menutype, m.title, m.alias, m.note, m.path AS route, m.link, m.type, m.level, m.language,m.browserNav, m.access, m.params, m.home, m.img, m.template_style_id, m.component_id, m.parent_id,e.element as component FROM jos_menu AS m LEFT JOIN jos_extensions AS e ON m.component_id = e.extension_id WHERE m.published = 1 AND m.parent_id > 0 AND m.client_id = 0 ORDER BY m.lft" [5]=> string(67) "SELECT * FROM jos_languages WHERE published=1 ORDER BY ordering ASC" [6]=> string(209) "SELECT id, home, template, s.params FROM jos_template_styles as s LEFT JOIN jos_extensions as e ON e.element=s.template AND e.type='template' AND e.client_id=s.client_id WHERE s.client_id = 0 AND e.enabled = 1" [7]=> string(46) "SHOW FULL COLUMNS FROM `jos_akeebasubs_levels`" [8]=> string(96) "SELECT * FROM `jos_akeebasubs_levels` WHERE `slug` = 'testsub' ORDER BY akeebasubs_level_id DESC" [9]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [10]=> string(34) "SHOW FULL COLUMNS FROM `jos_users`" [11]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [12]=> string(68) "SELECT * FROM `jos_users` WHERE `username` = 'test' ORDER BY id DESC" [13]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [14]=> string(48) "SHOW FULL COLUMNS FROM `jos_akeebasubs_upgrades`" [15]=> string(111) "SELECT * FROM `jos_akeebasubs_upgrades` WHERE `to_id` = 1 AND `enabled` = 1 ORDER BY akeebasubs_upgrade_id DESC" [16]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [17]=> string(48) "SHOW FULL COLUMNS FROM `jos_akeebasubs_taxrules`" [18]=> string(81) "SELECT * FROM `jos_akeebasubs_taxrules` WHERE `enabled`='1' ORDER BY ordering ASC" [19]=> string(71) "SELECT * FROM `jos_akeebasubs_levels` ORDER BY akeebasubs_level_id DESC" [20]=> string(52) "SHOW FULL COLUMNS FROM `jos_akeebasubs_customfields`" [21]=> string(52) "SHOW FULL COLUMNS FROM `jos_akeebasubs_customfields`" [22]=> string(65) "SELECT * FROM `jos_akeebasubs_customfields` WHERE `enabled` = '1'" [23]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [24]=> string(68) "SELECT * FROM `jos_users` WHERE `username` = 'test' ORDER BY id DESC" [25]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [26]=> string(111) "SELECT * FROM `jos_akeebasubs_upgrades` WHERE `to_id` = 1 AND `enabled` = 1 ORDER BY akeebasubs_upgrade_id DESC" [27]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [28]=> string(81) "SELECT * FROM `jos_akeebasubs_taxrules` WHERE `enabled`='1' ORDER BY ordering ASC" [29]=> string(52) "SHOW FULL COLUMNS FROM `jos_akeebasubs_customfields`" [30]=> string(65) "SELECT * FROM `jos_akeebasubs_customfields` WHERE `enabled` = '1'" [31]=> string(53) "SHOW FULL COLUMNS FROM `jos_akeebasubs_subscriptions`" [32]=> string(734) "SELECT `tbl`.*,`l`.`title`,`l`.`image`,`u`.`name`,`u`.`username`,`u`.`email`,`u`.`block`,`a`.`isbusiness`,`a`.`businessname`,`a`.`occupation`,`a`.`vatnumber`,`a`.`viesregistered`,`a`.`taxauthority`,`a`.`address1`,`a`.`address2`,`a`.`city`,`a`.`state` AS `userstate`,`a`.`zip`,`a`.`country`,`a`.`params` AS `userparams`,`a`.`notes` AS `usernotes` FROM `jos_akeebasubs_subscriptions` AS `tbl` INNER JOIN `jos_akeebasubs_levels` AS `l` ON `l`.`akeebasubs_level_id` = `tbl`.`akeebasubs_level_id` LEFT OUTER JOIN `jos_users` AS `u` ON `u`.`id` = `tbl`.`user_id` LEFT OUTER JOIN `jos_akeebasubs_users` AS `a` ON `a`.`user_id` = `tbl`.`user_id` WHERE `tbl`.`state` IN ('C') AND `tbl`.`user_id` = '59' ORDER BY akeebasubs_subscription_id DESC" [33]=> string(91) "SELECT * FROM `jos_akeebasubs_levels` WHERE `enabled` = 1 ORDER BY akeebasubs_level_id DESC" [34]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [35]=> string(734) "SELECT `tbl`.*,`l`.`title`,`l`.`image`,`u`.`name`,`u`.`username`,`u`.`email`,`u`.`block`,`a`.`isbusiness`,`a`.`businessname`,`a`.`occupation`,`a`.`vatnumber`,`a`.`viesregistered`,`a`.`taxauthority`,`a`.`address1`,`a`.`address2`,`a`.`city`,`a`.`state` AS `userstate`,`a`.`zip`,`a`.`country`,`a`.`params` AS `userparams`,`a`.`notes` AS `usernotes` FROM `jos_akeebasubs_subscriptions` AS `tbl` INNER JOIN `jos_akeebasubs_levels` AS `l` ON `l`.`akeebasubs_level_id` = `tbl`.`akeebasubs_level_id` LEFT OUTER JOIN `jos_users` AS `u` ON `u`.`id` = `tbl`.`user_id` LEFT OUTER JOIN `jos_akeebasubs_users` AS `a` ON `a`.`user_id` = `tbl`.`user_id` WHERE `tbl`.`state` IN ('N') AND `tbl`.`user_id` = '59' ORDER BY akeebasubs_subscription_id DESC" [36]=> string(41) "SELECT * FROM jos_users WHERE `id` = '59'" [37]=> string(495) "UPDATE `jos_users` SET `name`='test name',`username`='test',`email`='test@vicube.de',`password`='17e0f2ed0ba08a7f2c8082c358f2762c:3xxPcinwnzZPDAJX5kOAm9cWHj6nvoWF',`usertype`='',`block`='0',`sendEmail`='0',`registerDate`='2012-10-14 11:24:38',`lastvisitDate`='2012-12-20 13:49:23',`activation`='',`params`='{\"admin_style\":\"\",\"admin_language\":\"\",\"language\":\"\",\"editor\":\"\",\"helpsite\":\"\",\"timezone\":\"\"}',`lastResetTime`='0000-00-00 00:00:00',`resetCount`='0' WHERE `id`='59'" [38]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [39]=> string(68) "SELECT * FROM `jos_users` WHERE `username` = 'test' ORDER BY id DESC" [40]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [41]=> string(111) "SELECT * FROM `jos_akeebasubs_upgrades` WHERE `to_id` = 1 AND `enabled` = 1 ORDER BY akeebasubs_upgrade_id DESC" [42]=> string(75) "SELECT * FROM `jos_users` WHERE `email` = 'test@vicube.de' ORDER BY id DESC" [43]=> string(81) "SELECT * FROM `jos_akeebasubs_taxrules` WHERE `enabled`='1' ORDER BY ordering ASC" [44]=> string(52) "SHOW FULL COLUMNS FROM `jos_akeebasubs_customfields`" [45]=> string(65) "SELECT * FROM `jos_akeebasubs_customfields` WHERE `enabled` = '1'" [46]=> string(45) "SHOW FULL COLUMNS FROM `jos_akeebasubs_users`" [47]=> string(207) "SELECT `tbl`.*,`u`.`name`,`u`.`username`,`u`.`email` FROM `jos_akeebasubs_users` AS `tbl` INNER JOIN `jos_users` AS `u` ON `u`.`id` = `tbl`.`user_id` WHERE `tbl`.`user_id`=59 ORDER BY akeebasubs_user_id DESC" [48]=> string(302) "INSERT INTO `jos_akeebasubs_users` (`akeebasubs_user_id`,`user_id`,`isbusiness`,`businessname`,`occupation`,`vatnumber`,`viesregistered`,`taxauthority`,`address1`,`address2`,`city`,`state`,`zip`,`country`,`params`) VALUES ('0','59','0','','','','','','teststr. 23','','Testhausen','','18317','AD','[]')" [49]=> string(771) "SELECT `tbl`.*,`l`.`title`,`l`.`image`,`u`.`name`,`u`.`username`,`u`.`email`,`u`.`block`,`a`.`isbusiness`,`a`.`businessname`,`a`.`occupation`,`a`.`vatnumber`,`a`.`viesregistered`,`a`.`taxauthority`,`a`.`address1`,`a`.`address2`,`a`.`city`,`a`.`state` AS `userstate`,`a`.`zip`,`a`.`country`,`a`.`params` AS `userparams`,`a`.`notes` AS `usernotes` FROM `jos_akeebasubs_subscriptions` AS `tbl` INNER JOIN `jos_akeebasubs_levels` AS `l` ON `l`.`akeebasubs_level_id` = `tbl`.`akeebasubs_level_id` LEFT OUTER JOIN `jos_users` AS `u` ON `u`.`id` = `tbl`.`user_id` LEFT OUTER JOIN `jos_akeebasubs_users` AS `a` ON `a`.`user_id` = `tbl`.`user_id` WHERE `tbl`.`enabled` = '1' AND `tbl`.`akeebasubs_level_id` = '1' AND `tbl`.`user_id` = '59' ORDER BY akeebasubs_subscription_id DESC" [50]=> string(69) "SELECT * FROM jos_akeebasubs_levels WHERE `akeebasubs_level_id` = '1'" [51]=> string(667) "INSERT INTO `jos_akeebasubs_subscriptions` (`akeebasubs_subscription_id`,`user_id`,`akeebasubs_level_id`,`publish_up`,`publish_down`,`notes`,`enabled`,`processor`,`processor_key`,`state`,`net_amount`,`tax_amount`,`gross_amount`,`tax_percent`,`created_on`,`params`,`akeebasubs_coupon_id`,`akeebasubs_upgrade_id`,`akeebasubs_affiliate_id`,`affiliate_comission`,`prediscount_amount`,`discount_amount`,`contact_flag`,`first_contact`,`second_contact`) VALUES ('0','59','1','2012-12-20 13:49:58','2013-12-20 13:49:58','','0','offline','','N','10','0.00','10.00','0.00','2012-12-20 13:49:58','','0','0','0','0','10.00','0.00','0','0000-00-00 00:00:00','0000-00-00 00:00:00')" } ["offset":protected]=> int(0) ["sql":protected]=> string(666) "INSERT INTO `#__akeebasubs_subscriptions` (`akeebasubs_subscription_id`,`user_id`,`akeebasubs_level_id`,`publish_up`,`publish_down`,`notes`,`enabled`,`processor`,`processor_key`,`state`,`net_amount`,`tax_amount`,`gross_amount`,`tax_percent`,`created_on`,`params`,`akeebasubs_coupon_id`,`akeebasubs_upgrade_id`,`akeebasubs_affiliate_id`,`affiliate_comission`,`prediscount_amount`,`discount_amount`,`contact_flag`,`first_contact`,`second_contact`) VALUES ('0','59','1','2012-12-20 13:49:58','2013-12-20 13:49:58','','0','offline','','N','10','0.00','10.00','0.00','2012-12-20 13:49:58','','0','0','0','0','10.00','0.00','0','0000-00-00 00:00:00','0000-00-00 00:00:00')" ["tablePrefix":protected]=> string(4) "jos_" ["utf":protected]=> bool(true) ["errorNum":protected]=> int(0) ["errorMsg":protected]=> string(0) "" ["hasQuoted":protected]=> bool(false) ["quoted":protected]=> array(0) { } } ["_trackAssets":protected]=> bool(false) ["_rules":protected]=> NULL ["_locked":protected]=> bool(false) ["_errors":protected]=> array(0) { }
     *
     *
     *
     * ["akeebasubs_subscription_id"]=> int(1) ["user_id"]=> string(2) "59" ["akeebasubs_level_id"]=> int(1) ["publish_up"]=> string(19) "2012-12-20 13:49:58"
     * ["publish_down"]=> string(19) "2013-12-20 13:49:58" ["notes"]=> string(0) "" ["enabled"]=> int(0) ["processor"]=> string(7) "offline"
     * ["processor_key"]=> string(0) "" ["state"]=> string(1) "N" ["net_amount"]=> float(10) ["tax_amount"]=> string(4) "0.00"
     * ["gross_amount"]=> string(5) "10.00" ["tax_percent"]=> string(4) "0.00" ["created_on"]=> string(19) "2012-12-20 13:49:58"
     * ["params"]=> string(0) "" ["akeebasubs_coupon_id"]=> int(0)
     * ["akeebasubs_upgrade_id"]=> int(0) ["akeebasubs_affiliate_id"]=> int(0) ["affiliate_comission"]=> int(0)
     * ["akeebasubs_invoice_id"]=> NULL ["prediscount_amount"]=> string(5) "10.00" ["discount_amount"]=> string(4) "0.00"
     * ["contact_flag"]=> int(0) ["first_contact"]=> string(19) "0000-00-00 00:00:00" ["second_contact"]=> string(19) "0000-00-00 00:00:00" } }
     */



    /**
     * @param $row
     * @param $info
     */

    public function onAKSubscriptionChange($row, $info){

        if($row->state == 'N' || $row->state == 'X')
            return;

        if(array_key_exists('state', (array)$info['modified']) && in_array($row->state, array('P','C'))) {
            if($row->enabled) {
                if(is_object($info['previous']) && $info['previous']->state == 'P') {
                    // A pending subscription just got paid
                    //echo "ASDF";
                    echo "PENDINGPAID";
                    notifyMC($row, $info);
                } else {
                    // A new subscription just got paid; send new subscription notification
                    notifyMC($row, $info);
                    echo "NEWPAID";
                }
            } elseif($row->state == 'C') {
                if($row->contact_flag <= 2) {
                    // A new subscription which is for a renewal (will be active in a future date)
                    echo "RENEW";
                }
            } else {
                // A new subscription which is pending payment by the processor
                echo "PENDING";
            }
        }

        die("");
    }

    function notifyMC($row, $info, $type = "new") {
        $session = JFactory::getSession();
        $mc = $session->get( 'mc', '0' );

        // Trigger plugin only if user comes from Mailchimp
        if(!$mc) {
            return;
        }

        echo JPATH_ADMINISTRATOR . 'components/com_cmc/helpers/';

        $mc_cid = $session->get('mc_cid', '');
        $mc_eid = $session->get('mc_eid', '');

        $params = JComponentHelper::getParams('com_cmc');
        $api_key = $params->get("api_key", '');
        $shop_name = $params->get("shop_name", "Your shop");
        $shop_id = $params->get("shop_id", 42);

        //var_dump($order);

        echo "MC_EID: " . $mc_eid . "<br />";
        echo "MC_CID: " . $mc_eid;

        /**
         * ($api_key, $mc_cid, $mc_eid, $store_id, $store_name = "Store name", $order_id = 0, $total_amount = 0,
         * $tax_amount = 0, $shipping_amount = 0,
         * $products = array(0 => array("product_id" => 0, "sku" => "", "product_name" => "", "category_id" => 0, "category_name" => "", "qty" => 1.00, "cost" => 0.00))
         */

        $akeeba_subscription_name = "TODO Query";

        $products = array( 0 => array(
            "product_id" => $info['current']->akeebasubs_level_id, "sku" => "", "product_name" => $akeeba_subscription_name,
            "category_id" => 0, "category_name" => "", "qty" => 1.00,         // No category id, qty always 1
            "cost" =>  $info['current']->gross_amount
            )
        );

        /**
         * ["akeebasubs_subscription_id"]=> int(1) ["user_id"]=> string(2) "59" ["akeebasubs_level_id"]=> int(1) ["publish_up"]=> string(19) "2012-12-20 13:49:58"
         * ["publish_down"]=> string(19) "2013-12-20 13:49:58" ["notes"]=> string(0) "" ["enabled"]=> int(0) ["processor"]=> string(7) "offline"
         * ["processor_key"]=> string(0) "" ["state"]=> string(1) "N" ["net_amount"]=> float(10) ["tax_amount"]=> string(4) "0.00"
         * ["gross_amount"]=> string(5) "10.00" ["tax_percent"]=> string(4) "0.00" ["created_on"]=> string(19) "2012-12-20 13:49:58"
         * ["params"]=> string(0) "" ["akeebasubs_coupon_id"]=> int(0)
         * ["akeebasubs_upgrade_id"]=> int(0) ["akeebasubs_affiliate_id"]=> int(0) ["affiliate_comission"]=> int(0)
         * ["akeebasubs_invoice_id"]=> NULL ["prediscount_amount"]=> string(5) "10.00" ["discount_amount"]=> string(4) "0.00"
         * ["contact_flag"]=> int(0) ["first_contact"]=> string(19) "0000-00-00 00:00:00" ["second_contact"]=> string(19) "0000-00-00 00:00:00" } }
         */

        CmcHelperEcom360::sendOrderInformations($api_key, $mc_cid, $mc_eid, $shop_id, $shop_name, $info['current']->akeebasubs_subscription_id, $info['current']->gross_amount,
            $info['current']->tax_percent, 0.00, $products // No shipping
        );
    }
}