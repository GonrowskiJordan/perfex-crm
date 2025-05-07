<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * The file is responsible for handing the mailbox installation
 */

add_option('mailbox_enabled', 1);
add_option('mailbox_imap_server', '');
add_option('mailbox_encryption', '');
add_option('mailbox_folder_scan', 'Inbox');
add_option('mailbox_check_every', 3);
add_option('mailbox_only_loop_on_unseen_emails', 1);
add_option('mailbox_enable_html', 0);

if (!$CI->db->table_exists(db_prefix().'mail_inbox')) {
  $CI->db->query('CREATE TABLE `'.db_prefix()."mail_inbox` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `from_staff_id` int(11) NOT NULL DEFAULT '0',
    `to_staff_id` int(11) NOT NULL DEFAULT '0',
    `to` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `cc` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `bcc` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `sender_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `subject` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
    `body` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
    `has_attachment` tinyint(1) NOT NULL DEFAULT '0',
    `date_received` datetime NOT NULL,
    `read` tinyint(1) NOT NULL DEFAULT '0',
    `folder` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inbox',
    `stared` tinyint(1) NOT NULL DEFAULT '0',
    `important` tinyint(1) NOT NULL DEFAULT '0',
    `trash` tinyint(1) NOT NULL DEFAULT '0',
    `from_email` varchar(150) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=".$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix().'mail_outbox')) {
  $CI->db->query('CREATE TABLE `'.db_prefix()."mail_outbox` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `sender_staff_id` int(11) NOT NULL DEFAULT '0',
    `to` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `cc` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `bcc` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `sender_name` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `subject` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
    `body` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
    `has_attachment` tinyint(1) NOT NULL DEFAULT '0',
    `date_sent` datetime NOT NULL,
    `stared` tinyint(1) NOT NULL DEFAULT '0',
    `important` tinyint(1) NOT NULL DEFAULT '0',
    `trash` tinyint(1) NOT NULL DEFAULT '0',
    `reply_from_id` int(11) DEFAULT NULL,
    `reply_type` varchar(45) NOT NULL DEFAULT 'inbox',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=".$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix().'mail_attachment')) {
  $CI->db->query('CREATE TABLE `'.db_prefix()."mail_attachment` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `mail_id` int(11) NOT NULL,
    `file_name` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `file_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `type` varchar(45) NOT NULL DEFAULT 'inbox',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=".$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix() . 'mail_conversation')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . 'mail_conversation` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `outbox_id` int(255) DEFAULT NULL,
    `lead_id` int(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix().'mail_tags')) {
  $CI->db->query('CREATE TABLE `'.db_prefix()."mail_tags` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(127) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `color` varchar(127) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `active` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=".$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix().'mail_auto_replies')) {
  $CI->db->query('CREATE TABLE `'.db_prefix()."mail_auto_replies` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(127) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `pattern` VARCHAR(250) NOT NULL,
    `replyid` int(11) UNSIGNED,
    `body` LONGTEXT NULL,
    `active` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=".$CI->db->char_set.';');
}

if (!$CI->db->table_exists(db_prefix() . 'mail_clients')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . 'mail_clients` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `outbox_id` int(255) DEFAULT NULL,
    `inbox_id` int(255) DEFAULT NULL,
    `client_id` int(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('draft', 'mail_outbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_outbox` ADD COLUMN `draft` tinyint(1) NOT NULL DEFAULT 0;');
}

if (!$CI->db->field_exists('tagid', 'mail_outbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_outbox` ADD COLUMN `tagid` int(11);');
}

if (!$CI->db->field_exists('tagid', 'mail_inbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_inbox` ADD COLUMN `tagid` int(11);');
}

if (!$CI->db->field_exists('templateid', 'mail_outbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_outbox` ADD COLUMN `templateid` int(11);');
}

if (!$CI->db->field_exists('templateid', 'mail_inbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_inbox` ADD COLUMN `templateid` int(11);');
}

if (!$CI->db->field_exists('scheduled_at', 'mail_outbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_outbox` ADD COLUMN `scheduled_at` datetime;');
}

if (!$CI->db->field_exists('scheduled_status', 'mail_outbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_outbox` ADD COLUMN `scheduled_status` VARCHAR(127)  DEFAULT "";');
}

if (!$CI->db->field_exists('assigned_clients', 'mail_inbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_inbox` ADD COLUMN `assigned_clients` VARCHAR(511)  DEFAULT "";');
}

if (!$CI->db->field_exists('assigned_clients', 'mail_outbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_outbox` ADD COLUMN `assigned_clients` VARCHAR(511)  DEFAULT "";');
}

if (!$CI->db->field_exists('taskid', 'mail_inbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_inbox` ADD COLUMN `taskid` int(11);');
}

if (!$CI->db->field_exists('taskid', 'mail_outbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_outbox` ADD COLUMN `taskid` int(11);');
}

if (!$CI->db->field_exists('ticketid', 'mail_inbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_inbox` ADD COLUMN `ticketid` int(11);');
}

if (!$CI->db->field_exists('ticketid', 'mail_outbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_outbox` ADD COLUMN `ticketid` int(11);');
}

if (!$CI->db->field_exists('conversationid', 'mail_inbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_inbox` ADD COLUMN `conversationid` int(11);');
}

if (!$CI->db->field_exists('conversationid', 'mail_outbox')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_outbox` ADD COLUMN `conversationid` int(11);');
}

if (!$CI->db->field_exists('replyid', 'emailtemplates')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'emailtemplates` ADD COLUMN `replyid` int(11);');
}

if (!$CI->db->field_exists('autoreply', 'emailtemplates')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'emailtemplates` ADD COLUMN `autoreply` tinyint(1) NOT NULL DEFAULT "0";');
}

if (!$CI->db->field_exists('pattern', 'mail_auto_replies')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_auto_replies` ADD COLUMN `pattern` VARCHAR(250) NOT NULL;');
}

if ($CI->db->field_exists('subject', 'mail_auto_replies')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_auto_replies` DROP COLUMN `subject`;');
}

if (!$CI->db->field_exists('body', 'mail_auto_replies')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_auto_replies` ADD COLUMN `body` LONGTEXT NULL;');
}

if (!$CI->db->field_exists('color', 'mail_tags')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_tags` ADD COLUMN `color` VARCHAR(127) NOT NULL;');
}

if (!$CI->db->field_exists('mail_password', 'staff')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'staff`  ADD COLUMN `mail_password` VARCHAR(250) NULL');
}

if (!$CI->db->field_exists('mail_signature', 'staff')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'staff`  ADD COLUMN `mail_signature` VARCHAR(250) NULL');
}

if (!$CI->db->field_exists('last_email_check', 'staff')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'staff`  ADD COLUMN `last_email_check` VARCHAR(50) NULL');
}

if (!$CI->db->field_exists('inbox_id', 'mail_conversation')) {
  $CI->db->query('ALTER TABLE `' . db_prefix() . 'mail_conversation` ADD COLUMN `inbox_id` int(255) DEFAULT NULL');
}

// Moving necessary dependencies to the correct place for clean installs of v2.7.0+
$checkfolder = FCPATH . 'application/third_party/php-imap';
$srcloc = APP_MODULES_PATH . 'mailbox/third_party/php-imap'; 
$destloc = FCPATH . 'application/third_party/';

if (!is_dir($checkfolder)){
  mkdir($checkfolder);
  shell_exec("cp -r $srcloc $destloc");
}