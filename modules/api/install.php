<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI =& get_instance();

if (!$CI->db->table_exists(db_prefix() . 'user_api')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'user_api` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `user` VARCHAR(50) NOT NULL,
        `name` VARCHAR(50) NOT NULL,
        `token` VARCHAR(255) NOT NULL,
        `expiration_date` DATETIME,
        `permission_enable` TINYINT(4) DEFAULT 0,
        PRIMARY KEY (`id`));
    ');
} else {
    if (!$CI->db->field_exists('permission_enable', db_prefix() . 'user_api')) {
        $CI->db->query('ALTER TABLE `' . db_prefix() . 'user_api`
            ADD `permission_enable` TINYINT(4) DEFAULT 0;
        ');
    }
}

if (!$CI->db->table_exists(db_prefix() . 'user_api_permissions')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'user_api_permissions` (
        `api_id` INT(11) NOT NULL,
        `feature` VARCHAR(50) NOT NULL,
        `capability` VARCHAR(50) NOT NULL);
    ');
}

if ($CI->db->field_exists('password', db_prefix() . 'user_api')) {
    $CI->db->query('ALTER TABLE ' . db_prefix() . 'user_api DROP `password`');
}

if ($CI->db->field_exists('expiration_date', db_prefix() . 'user_api')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'user_api` MODIFY COLUMN `expiration_date` DATETIME NULL');
}
 
if (!$CI->db->field_exists('quota_limit', db_prefix() . 'user_api')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'user_api` ADD COLUMN `quota_limit` INT(11) NOT NULL DEFAULT 1000');
}

if (!$CI->db->field_exists('quota_remaining', db_prefix() . 'user_api')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'user_api` ADD COLUMN `quota_remaining` INT(11) NOT NULL DEFAULT 1000');
}

if (!$CI->db->field_exists('quota_reset', db_prefix() . 'user_api')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'user_api` ADD COLUMN `quota_reset` DATETIME NOT NULL');
}

if (!$CI->db->field_exists('rate_limit', db_prefix() . 'user_api')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'user_api` ADD COLUMN `rate_limit` INT(11) NOT NULL DEFAULT 60');
}

if (!$CI->db->field_exists('rate_remaining', db_prefix() . 'user_api')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'user_api` ADD COLUMN `rate_remaining` INT(11) NOT NULL DEFAULT 60');
}

if (!$CI->db->field_exists('rate_reset', db_prefix() . 'user_api')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'user_api` ADD COLUMN `rate_reset` DATETIME NOT NULL');
}

if (!$CI->db->table_exists(db_prefix() . 'user_api_limit')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'user_api_limit` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `api_id` INT(11) NOT NULL,
        `uri` VARCHAR(511) NOT NULL,
        `class` VARCHAR(511) NOT NULL,
        `method` VARCHAR(511) NOT NULL,
        `ip_address` VARCHAR(63) NOT NULL,
        `time` DATETIME NOT NULL,
        PRIMARY KEY (`id`));
    ');
}