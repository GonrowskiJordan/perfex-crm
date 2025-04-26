<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Get staff id by email.
 *
 * @param string $email
 *
 * @return string staff id
 */
function get_staff_id_by_email($email)
{
    $CI = &get_instance();

    $staff = $CI->app_object_cache->get('staff-id-by-email-'.$email);

    if (!$staff) {
        $CI->db->where('email', $email);
        $staff = $CI->db->select('staffid')->from(db_prefix().'staff')->get()->row();
        $CI->app_object_cache->add('staff-id-by-email-'.$email, $staff);
    }

    return $staff ? $staff->staffid : 0;
}

/**
 * Get staff email by id.
 *
 * @param string $email
 *
 * @return string staff id
 */
function get_staff_email_by_id($id)
{
    $CI = &get_instance();

    $staff = $CI->app_object_cache->get('staff-email-by-id-'.$id);

    if (!$staff) {
        $CI->db->where('staffid', $id);
        $staff = $CI->db->select('email')->from(db_prefix().'staff')->get()->row();
        $CI->app_object_cache->add('staff-email-by-id-'.$id, $staff);
    }

    return $staff ? $staff->email : '';
}

/**
 * Check for outbox attachment after inserting outbox to database.
 *
 * @param mixed $outbox_id
 *
 * @return mixed false if no attachment || array uploaded attachments
 */
function handle_mail_attachments($mail_id, $type = 'inbox', $index_name = 'attachments', $method='move')
{
    $path           = MAILBOX_MODULE_UPLOAD_FOLDER.'/'.$type.'/'.$mail_id.'/';

    $uploaded_files = [];

    if (isset($_FILES[$index_name])) {
        _file_attachments_index_fix($index_name);

        for ($i = 0; $i < count($_FILES[$index_name]['name']); ++$i) {
            hooks()->do_action('before_upload_outbox_attachment', $mail_id);
            if ($i <= 100) {
                // Get the temp file path
                $tmpFilePath = $_FILES[$index_name]['tmp_name'][$i];
                // Make sure we have a filepath
                if (!empty($tmpFilePath) && '' != $tmpFilePath) {
                    // Getting file extension
                    $extension          = strtolower(pathinfo($_FILES[$index_name]['name'][$i], PATHINFO_EXTENSION));
                    $allowed_extensions = explode(',', get_option('allowed_files'));
                    $allowed_extensions = array_map('trim', $allowed_extensions);
                    // Check for all cases if this extension is allowed
                    if (!in_array('.'.$extension, $allowed_extensions)) {
                        continue;
                    }
                    _maybe_create_upload_path($path);
                    $filename    = unique_filename($path, $_FILES[$index_name]['name'][$i]);
                    $filename    = str_replace(' ', '_', $filename);
                    $newFilePath = $path.$filename;
                    // Upload the file into the temp dir
                    if ('copy' == $method) {
                        if (copy($tmpFilePath, $newFilePath)) {
                            array_push($uploaded_files, [
                                    'file_name'  => $filename,
                                    'file_type'  => $_FILES[$index_name]['type'][$i],
                                    ]);
                        }
                    } else {
                        if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                            array_push($uploaded_files, [
                                    'file_name'  => $filename,
                                    'file_type'  => $_FILES[$index_name]['type'][$i],
                                    ]);
                        }
                    }
                }
            }
        }
    }
    if (count($uploaded_files) > 0) {
        return $uploaded_files;
    }

    return false;
}

/**
 * text limiter.
 *
 * @param string $str
 * @param int    $limit
 * @param string $end_char
 *
 * @return string
 */
function text_limiter($str, $limit = 100, $end_char = '&#8230;')
{
    if ('' === trim($str)) {
        return $str;
    }

    preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

    if (strlen($str) === strlen($matches[0])) {
        $end_char = '';
    }

    return rtrim($matches[0]).$end_char;
}

/**
 * Check whether column exists in a table
 * Custom function because Codeigniter is caching the tables and this is causing issues in migrations.
 *
 * @param string $column column name to check
 * @param string $table  table name to check
 *
 * @return bool
 */
function column_exists($column, $table)
{
    if (!startsWith($table, db_prefix())) {
        $table = db_prefix().$table;
    }

    $result = get_instance()->db->query('SHOW COLUMNS FROM '.$table." LIKE '".$column."';")->row();

    return (bool) $result;
}

/**
 * prepare imap email body html.
 *
 * @param string $body
 *
 * @return string
 */
function prepare_imap_email_body_html($body)
{
    // Disable HTML rendering if setting is turned off
    if ('0' == get_option('mailbox_enable_html')) {
        // Trim message
        $body = trim($body);
        $body = str_replace('&nbsp;', ' ', $body);

        // Strip unwanted tags while allowing specific safe tags
        // We are only allowing a limited set of tags to prevent XSS attacks
        $allowed_tags = '<br><a><p><b><i><u><strong><em><ul><ol><li><blockquote><span><div><hr>';
        $body = strip_tags($body, $allowed_tags);

        // Remove dangerous inline event handlers or scripts
        $body = preg_replace('/<[^>]+(on\w+|javascript:|data:|<script|<iframe)[^>]*>/i', '', $body);

        // Normalize new lines (convert them to <br> tags)
        $body = preg_replace("/[\r\n]+/", "\n", $body);
        $body = preg_replace('/\n(\s*\n)+/', '<br />', $body);
        $body = preg_replace('/\n/', '<br>', $body);
    }

    return $body;
}

/**
 * Get email attachments.
 *
 * @param int    $mail_id
 * @param string $type
 *
 * @return array
 */
function get_mail_attachment($mail_id, $type='inbox')
{
    $CI = &get_instance();
    $CI->db->where('mail_id', $mail_id);
    $CI->db->where('type', $type);

    return  $CI->db->get(db_prefix().'mail_attachment')->result_array();
}

/**
 * Get email tags.
 *
 * @param int    $active
 * @param string $type
 *
 * @return array
 */
function get_mail_tags($active = true)
{
    $CI = &get_instance();
    if ($active == true) {
        $CI->db->where('active', true);
    } else if ($active == false) {
        $CI->db->where('active', false);
    }

    return  $CI->db->get(db_prefix().'mail_tags')->result_array();
}

/**
 * Get email tags.
 *
 * @param int    $active
 * @param string $type
 *
 * @return array
 */
function get_mail_templates($active = true)
{
    $CI = &get_instance();
    $CI->db->where('language', 'english');
    if ($active == true) {
        $CI->db->where('active', true);
    } else if ($active == false) {
        $CI->db->where('active', false);
    }

    return $CI->db->get(db_prefix().'emailtemplates')->result_array();
}

function mailbox_get_client_companies($mailbox_id, $type = 'inbox', $is_string = true) {
    $client_compaies = [];
    $client_ids = [];
    $CI = &get_instance();
    $CI->db->where($type . '_id', $mailbox_id);
    $mail_clients = $CI->db->get(db_prefix().'mail_clients')->result_array();
    foreach ($mail_clients as $mail_client) {
        $CI->db->where('userid', $mail_client['client_id']);
        $client = $CI->db->get(db_prefix().'clients')->row();
        if ($client) {
            if (!in_array($client->company, $client_compaies)) {
                $client_compaies[] = $client->company;
            }
            if (!in_array($client->userid, $client_ids)) {
                $client_ids[] = $client->userid;
            }
        }
    }
    if ($is_string) {
        return implode(", ", $client_compaies);
    }
    return $client_ids;
}