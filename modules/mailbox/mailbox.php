<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Mailbox
Module URI: https://codecanyon.net/item/mailbox-webmail-client-for-perfex-crm/25308081
Description: Mailbox is a webmail client for Perfex's dashboard.
Version: 2.0.4
Requires at least: 3.0
Author: Themesic Interactive
Author URI: https://1.envato.market/themesic
*/

define('MAILBOX_MODULE', 'mailbox');
define('MAILBOX_MODULE_UPLOAD_FOLDER', module_dir_path(MAILBOX_MODULE, 'uploads'));

require_once __DIR__.'/vendor/autoload.php';

modules\mailbox\core\Apiinit::the_da_vinci_code(MAILBOX_MODULE);
modules\mailbox\core\Apiinit::ease_of_mind(MAILBOX_MODULE);

hooks()->add_action('after_cron_run', 'scan_email_server');
hooks()->add_action('after_cron_run', 'send_schedule_emails');
hooks()->add_action('app_admin_head', 'mailbox_add_head_components');
hooks()->add_action('app_admin_footer', 'mailbox_load_js');
hooks()->add_action('admin_init', 'mailbox_add_settings_section');
hooks()->add_action('admin_init', 'mailbox_module_init_menu_items');
hooks()->add_filter('migration_tables_to_replace_old_links', 'mailbox_migration_tables_to_replace_old_links');
hooks()->add_action('after_lead_lead_tabs', 'mailbox_after_lead_lead_tabs');
hooks()->add_action('after_lead_tabs_content', 'mailbox_after_lead_tabs_content');
hooks()->add_action('customer_profile_tabs', 'customer_profile_tabs');

/**
 * Injects chat CSS.
 *
 * @return null
 */
function mailbox_add_head_components()
{
    if ('1' == get_option('mailbox_enabled')) {
        $CI = &get_instance();
        echo '<link href="'.base_url('modules/mailbox/assets/css/mailbox_styles.css').'?v='.$CI->app_scripts->core_version().'"  rel="stylesheet" type="text/css" />';
    }
}

/**
 * Injects chat Javascript.
 *
 * @return null
 */
function mailbox_load_js()
{
    if ('1' == get_option('mailbox_enabled')) {
        $CI = &get_instance();
        echo '<script src="'.module_dir_url('mailbox', 'assets/js/mailbox_js.js').'?v='.$CI->app_scripts->core_version().'"></script>';
    }
}

/**
 * Init mailbox module menu items in setup in admin_init hook.
 *
 * @return null
 */
function mailbox_module_init_menu_items()
{
    $CI = &get_instance();
    if ('1' == get_option('mailbox_enabled')) {
        $badge      = '';
        $num_unread = total_rows(db_prefix().'mail_inbox', ['read' => '0', 'to_staff_id' => get_staff_user_id()]);
        if ($num_unread > 0) {
            $badge = ' • '.total_rows(db_prefix().'mail_inbox', ['read' => '0', 'to_staff_id' => get_staff_user_id()]).'';
        }

        $CI->app_menu->add_sidebar_menu_item('mailbox', [
            'name'     => _l('mailbox') . $badge,
            'icon'     => 'fa fa-envelope-square',
            'href'     => admin_url('mailbox'),
            'position' => 6,
        ]);
        $CI->app_menu->add_sidebar_children_item('mailbox', [
            'slug'     => 'mailbox-emails',
            'name'     => _l('mailbox_emails'),
            'href'     => admin_url('mailbox'),
            'position' => 1,
            'badge'    => [],
        ]);
        $CI->app_menu->add_sidebar_children_item('mailbox', [
            'slug'     => 'mailbox-tags',
            'name'     => _l('mailbox_tags'),
            'href'     => admin_url('mailbox/tags'),
            'position' => 2,
            'badge'    => [],
        ]);
        $CI->app_menu->add_sidebar_children_item('mailbox', [
            'slug'     => 'mailbox-contacts',
            'name'     => _l('contacts'),
            'href'     => admin_url('mailbox/contacts'),
            'position' => 3,
            'badge'    => [],
        ]);
        $CI->app_menu->add_sidebar_children_item('mailbox', [
            'slug'     => 'mailbox-email-templates',
            'name'     => _l('email_templates'),
            'href'     => admin_url('mailbox/email_templates'),
            'position' => 4,
            'badge'    => [],
        ]);
        $CI->app_menu->add_sidebar_children_item('mailbox', [
            'slug'     => 'mailbox-auto-replies',
            'name'     => _l('mailbox_auto_replies'),
            'href'     => admin_url('mailbox/auto_replies'),
            'position' => 5,
            'badge'    => [],
        ]);
    }
}

/**
 * Init mailbox module setting menu items in setup in admin_init hook.
 *
 * @return null
 */
function mailbox_add_settings_section()
{
    $CI = &get_instance();
    $CI->app->add_settings_section('mailbox-settings', [
       'title'    => _l('mailbox'),
       'position' => 36,
       'children' => [
           [
               'name'     => _l('mailbox_setting'),  // Εδώ προσθέτεις τις ρυθμίσεις για το Mailbox
               'view'     => 'mailbox/mailbox_settings',
               'position' => 1,  // Η θέση των ρυθμίσεων
               'icon'     => 'fa-solid fa-inbox',  // Προαιρετικό, προσθέτεις ένα εικονίδιο αν θέλεις
           ],
       ],
   ]);
}

/**
 * mailbox migration tables to replace old links description.
 *
 * @param array $tables
 *
 * @return array
 */
function mailbox_migration_tables_to_replace_old_links($tables)
{
    $tables[] = [
        'table' => db_prefix().'mail_inbox',
        'field' => 'description',
    ];

    return $tables;
}

/**
 * Scan mailbox from mail-server.
 *
 * @return [bool] [true/false]
 */
function scan_email_server()
{
    $enabled      = get_option('mailbox_enabled');
    $imap_server  = get_option('mailbox_imap_server');
    $encryption   = get_option('mailbox_encryption');
    $folder_scan  = get_option('mailbox_folder_scan');
    $check_every  = '1';
    $unseen_email = get_option('mailbox_only_loop_on_unseen_emails');
    if (1 == $enabled && strlen($imap_server) > 0) {
        $CI = &get_instance();
        $CI->db->select()->from(db_prefix() . 'staff')->where(db_prefix() . 'staff.mail_password !=', '');
        $staffs = $CI->db->get()->result_array();
        
        $CI->db->select()->from(db_prefix() . 'mail_auto_replies')->where(db_prefix() . 'mail_auto_replies.active', true);
        $mail_auto_replies = $CI->db->get()->result_array();

        $inbox_email_ids = [];

        require_once __DIR__ . '/third_party/php-imap/Imap.php';
        include_once __DIR__ . '/third_party/simple_html_dom.php';
        foreach ($staffs as $staff) {
            $last_run    = $staff['last_email_check'];
            $staff_email = $staff['email'];
            $staff_id    = $staff['staffid'];
            $email_pass  = $staff['mail_password'];
            if (empty($last_run) || (time() > $last_run + ($check_every * 60))) {
                require_once __DIR__ . '/third_party/php-imap/Imap.php';
                $CI->db->where('staffid', $staff_id);
                $CI->db->update(db_prefix().'staff', [
                    'last_email_check' => time(),
                ]);
				
				// open connection
				try {
					$imap = new Imap($imap_server, $staff_email, $email_pass, $encryption);

					if (false === $imap->isConnected()) {
						// Capture IMAP errors if any
						$imapErrors = imap_errors();
						if ($imapErrors) {
							$errorMessage = implode(', ', $imapErrors);

							// Check if the last error message is the same as the previous one to avoid duplicates
							static $lastErrorMessage = '';
							if ($errorMessage !== $lastErrorMessage) {
								log_activity('IMAP connection for email ' . $staff_email . ' failed. Error: ' . $errorMessage, null);
								$lastErrorMessage = $errorMessage;  // Store this error to compare with future ones
							}
						}
						continue;
					}

					$imap->selectFolder($folder_scan);

					if (1 == $unseen_email) {
						$emails = $imap->getUnreadMessages();
					} else {
						$emails = $imap->getMessages();
					}
				} catch (Exception $e) {
					log_activity('IMAP connection failed for email: ' . $staff_email . '. Exception: ' . $e->getMessage(), null);
					continue;
				}

                foreach ($emails as $email) {
                    $bodyData = $imap->getBody($email['uid']);
                    $email['body'] = trim($bodyData['body']);

                    if ($bodyData['html']) {
                        $email['body'] = prepare_imap_email_body_html($email['body']);
                    }

                    $email['body']       = handle_google_drive_links_in_text($email['body']);
                    $email['body']       = prepare_imap_email_body_html($email['body']);
                    $data['attachments'] = [];
                    if (isset($email['attachments'])) {
                        foreach ($email['attachments'] as $key => $at) {
                            $_at_name = $email['attachments'][$key]['name'];
                            // Rename the name to filename the model expects filename not name
                            unset($email['attachments'][$key]['name']);
                            $email['attachments'][$key]['filename'] = $_at_name;
                            $_attachment                            = $imap->getAttachment($email['uid'], $key);
                            $email['attachments'][$key]['data']     = $_attachment['content'];
                        }
                        // Add the attchments to data
                        $data['attachments'] = $email['attachments'];
                    } else {
                        // No attachments
                        $data['attachments'] = [];
                    }

                    // Check for To
                    $data['to'] = [];
                    if (isset($email['to'])) {
                        foreach ($email['to'] as $to) {
                            $data['to'][] = trim(preg_replace('/(.*)<(.*)>/', '\\2', $to));
                        }
                    }

                    // Check for CC
                    $data['cc'] = [];
                    if (isset($email['cc'])) {
                        foreach ($email['cc'] as $cc) {
                            $data['cc'][] = trim(preg_replace('/(.*)<(.*)>/', '\\2', $cc));
                        }
                    }

                    if ('true' == hooks()->apply_filters('imap_fetch_from_email_by_reply_to_header', 'true')) {
                        $replyTo = $imap->getReplyToAddresses($email['uid']);

                        if (1 === count($replyTo)) {
                            $email['from'] = $replyTo[0];
                        }
                    }
					$from_email = preg_replace('/(.*)<(.*)>/', '\\2', $email['from']);
					
					$decodedfrom = $from_email;
					
					$data['fromname'] = preg_replace('/(.*)<(.*)>/', '\\1', $email['from']);
					$data['fromname'] = trim(str_replace('"', '', $data['fromname']));

					// Decode sender's name if it's encoded with Base64
					if (preg_match('/\=\?UTF\-8\?B\?(.*?)\?=/i', $data['fromname'], $matches)) {
						$data['fromname'] = base64_decode($matches[1]);
					}

					$inbox = [];
					$inbox['from_email'] = $decodedfrom;
					$from_staff_id = get_staff_id_by_email(trim($decodedfrom));
					if ($from_staff_id) {
						$inbox['from_staff_id'] = $from_staff_id;
					}
					
                    $from = $data['fromname'];
                    $subject = $email['subject'];

                    // Function to decode encoded words (e.g., =?UTF-8?Q?...?=)
                    function decode_mime_header($encoded_text) {
                        if (preg_match('/\=\?UTF\-8\?Q\?(.*?)\?=/i', $encoded_text, $matches)) {
                            $decoded = quoted_printable_decode($matches[1]);
                            // Replace underscores with spaces
                            $decoded = str_replace('_', ' ', $decoded);
                            // Convert to UTF-8 if needed
                            return mb_convert_encoding($decoded, 'UTF-8', 'auto');
                        }
                        return $encoded_text; // Return original if no match
                    }

                    // Normalize sender name
                    $fromname = preg_replace('/(.*)<(.*)>/', '\\1', $from);
                    $fromname = trim(str_replace('"', '', $fromname));
                    $decoded_fromname = decode_mime_header($fromname);

                    // Normalize subject
                    $decoded_subject = decode_mime_header($subject);

                    $inbox['to'] = implode(',', $data['to']);
                    $inbox['cc'] = implode(',', $data['cc']);
                    $inbox['sender_name'] = $decoded_fromname;
                    $inbox['subject'] = $decoded_subject;
                    $inbox['body'] = $email['body'];
                    $inbox['to_staff_id'] = $staff_id;
                    $inbox['date_received'] = date('Y-m-d H:i:s');
                    $inbox['folder'] = 'inbox';

                    $CI->db->insert(db_prefix().'mail_inbox', $inbox);
                    $inbox_id = $CI->db->insert_id();
                    $inbox_email_ids[] = $inbox_id;
                    $path     = MAILBOX_MODULE_UPLOAD_FOLDER.'/inbox/'.$inbox_id.'/';
                    foreach ($data['attachments'] as $attachment) {
                        $filename      = $attachment['filename'];
                        $filenameparts = explode('.', $filename);
                        $extension     = end($filenameparts);
                        $extension     = strtolower($extension);
                        $filename      = implode('', array_slice($filenameparts, 0, 0 - 1));
                        $filename      = trim(preg_replace('/[^a-zA-Z0-9-_ ]/', '', $filename));
                        if (!$filename) {
                            $filename = 'attachment';
                        }
                        if (!file_exists($path)) {
                            mkdir($path, 0755);
                            $fp = fopen($path.'index.html', 'w');
                            fclose($fp);
                        }
                        $filename = unique_filename($path, $filename.'.'.$extension);
                        $fp       = fopen($path.$filename, 'w');
                        fwrite($fp, $attachment['data']);
                        fclose($fp);
                        $matt               = [];
                        $matt['mail_id']    = $inbox_id;
                        $matt['type']       = 'inbox';
                        $matt['file_name']  = $filename;
                        $matt['file_type']  = get_mime_by_extension($filename);
                        $CI->db->insert(db_prefix().'mail_attachment', $matt);
                    }
                    if (count($data['attachments']) > 0) {
                        $CI->db->where('id', $inbox_id);
                        $CI->db->update(db_prefix().'mail_inbox', [
                            'has_attachment' => 1,
                        ]);
                    }

                    if ($inbox_id) {
                        $imap->setUnseenMessage($email['uid']);
                    }
                }
            }
        }
    }

    return false;
}

function send_schedule_emails()
{
    $CI = &get_instance();
    $CI->db->select()->from(db_prefix() . 'mail_outbox')
        ->where(db_prefix() . 'mail_outbox.scheduled_status', 'Scheduled')
        ->where(db_prefix() . 'mail_outbox.scheduled_at <=', date('Y-m-d H:i:s'));
    $scheduled_outboxes = $CI->db->get()->result_array();
    foreach ($scheduled_outboxes as $scheduled_outbox) {
        $CI->db->where('staffid', $scheduled_outbox['sender_staff_id']);
        $staff = $CI->db->select('email')->from(db_prefix().'staff')->get()->row();

        $CI->email->initialize();
        $CI->load->library('email');
        $CI->email->clear(true);
        $CI->email->from($staff->email, $scheduled_outbox['sender_name']);
        $CI->email->to(str_replace(';', ',', $scheduled_outbox['to']));
        if (isset($scheduled_outbox['cc']) && strlen($scheduled_outbox['cc']) > 0) {
            $CI->email->cc($scheduled_outbox['cc']);
        }
        $CI->email->subject($scheduled_outbox['subject']);
        $CI->email->message($scheduled_outbox['body']);
        $outobx_attach_dir = module_dir_url(MAILBOX_MODULE).'uploads/outbox/'.$scheduled_outbox['id'];
        if (file_exists($outobx_attach_dir)) {
            $outbox_files = scandir($outobx_attach_dir);
            $outbox_files = array_diff($outbox_files, array(".", ".."));
            foreach ($outbox_files as $outbox_file) {
                $CI->email->attach($outobx_attach_dir.'/'.$outbox_file);
            }
        }
        $CI->email->send(true);

        $scheduled_outbox['scheduled_status'] = "Sent";
        $CI->db->where('id', $scheduled_outbox['id']);
        $CI->db->update(db_prefix().'mail_outbox', $scheduled_outbox);

        log_activity('Schedule Email Sent - ID: ' . $scheduled_outbox['id'] . ' To: ' . $scheduled_outbox['to']);
    }
}

/**
 * Load the module helper.
 */
$CI = &get_instance();
$CI->load->helper(MAILBOX_MODULE.'/mailbox');

/*
 * Register the activation mailbox
 */
register_activation_hook(MAILBOX_MODULE, 'mailbox_activation_hook');

/**
 * The activation function.
 */
function mailbox_activation_hook()
{
    $CI = &get_instance();
    require_once __DIR__.'/install.php';
}

/*
 * Register mailbox language files
 */
register_language_files(MAILBOX_MODULE, [MAILBOX_MODULE]);


hooks()->add_action('app_init', MAILBOX_MODULE.'_actLib');
function mailbox_actLib()
{
    $CI = &get_instance();
    $CI->load->library(MAILBOX_MODULE.'/Mailbox_aeiou');
    $envato_res = $CI->mailbox_aeiou->validatePurchase(MAILBOX_MODULE);
    if (!$envato_res) {
        set_alert('danger', 'One of your modules failed its verification and got deactivated. Please reactivate or contact support.');
    }
}

hooks()->add_action('pre_activate_module', MAILBOX_MODULE.'_sidecheck');
function mailbox_sidecheck($module_name)
{
    if (MAILBOX_MODULE == $module_name['system_name']) {
        modules\mailbox\core\Apiinit::activate($module_name);
    }
}

hooks()->add_action('pre_deactivate_module', MAILBOX_MODULE.'_deregister');
function mailbox_deregister($module_name)
{
    if (MAILBOX_MODULE == $module_name['system_name']) {
        delete_option(MAILBOX_MODULE.'_verification_id');
        delete_option(MAILBOX_MODULE.'_last_verification');
        delete_option(MAILBOX_MODULE.'_product_token');
        delete_option(MAILBOX_MODULE.'_heartbeat');
    }
}

function mailbox_after_lead_lead_tabs(){
   echo '<li role="presentation">
            <a href="#conversation" aria-controls="conversation" role="tab" data-toggle="tab">
                ' . _l("conversation") . '
            </a>
        </li>';
}

function mailbox_after_lead_tabs_content($data){
    if ($data) {
        $CI = &get_instance();
        $id = $data->id;
        $data = array();
        $CI->db->select('*');
        $CI->db->from('mail_conversation');
        $CI->db->where('lead_id', $id);
        $result = $CI->db->get()->result_array();
        $getdata = [];
        foreach ($result as $key => $value) {
        if ($value['inbox_id']) {
                $CI->db->select('*');
                $CI->db->from('mail_inbox');
                $CI->db->join('mail_conversation', 'mail_inbox.id = mail_conversation.inbox_id');
                $CI->db->where('mail_inbox.id', $value['inbox_id']);
                $result_array = $CI->db->get()->result_array();
                if ($result_array) {
                    $getdata[] = $result_array[0];
                }
            } else {
                $CI->db->select('*');
                $CI->db->from('mail_outbox');
                $CI->db->join('mail_conversation', 'mail_outbox.id = mail_conversation.outbox_id');
                $CI->db->where('mail_outbox.id', $value['outbox_id']);
                $result_array = $CI->db->get()->result_array();
                if ($result_array) {
                    $getdata[] = $result_array[0];
                }
            }
        }
        $data['conversation']  = $getdata;
        $data['module_dir_url'] = module_dir_url(MAILBOX_MODULE);
        $CI = &get_instance();
        echo $CI->load->view('mailbox/conversation', $data, true);
    }
}

function mailbox_supported_until() {
    if (get_option('extra_support_notice') == 0) {
        return;
    } else {
        $supported_until = get_option(MAILBOX_MODULE.'_supported_until'); 
        if (empty($supported_until)) {
            return;
        }
		$date_only = substr($supported_until, 0, 10);
		$supported_until_timestamp = strtotime($date_only);
		$current_date_timestamp = time();
		if ($supported_until_timestamp < ($current_date_timestamp - (6 * 30 * 24 * 60 * 60))) {
            echo '<div class="supported_until alert alert-warning" style="font-size: 16px; background-color: #fff3cd; border-color: #ffeeba; color: #856404; 
                position: fixed; top: 50px; left: 50%; padding: 20px; transform: translateX(-50%); z-index: 9999; width: 90%; max-width: 600px; box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;">
                    <img style="max-width:100px;" src="https://themesic.com/wp-content/uploads/2023/07/cropped-logo-with-text-minus.png"><br><br>
                    <p>⚠️ The support period for one of your modules seems over.<br><br>We offer an alternative way to receive <strong>free support</strong> for potential issues,<br>simply by rating our product on <img style="max-width:80px;" src="https://themesic.com/wp-content/plugins/fast-plugin/assets/images/envato.svg">. <a href="https://1.envato.market/themesic" target="_blank" style="text-decoration:underline !important;"><strong> Click here to do that</strong></a> 👈</p><br>
                    <p>Your feedback help us continue developing and improving the product!</p>
                    <br /><br />
                    <a href="?dismiss=true" class="alert-link" style="text-decoration:underline !important;">Okay, thanks for the notice</a> ✔️
                </div></center>';
		}
    } 
}

// Check for the dismiss URL and update the option
if (isset($_GET['dismiss']) && $_GET['dismiss'] === 'true') {
    update_option('extra_support_notice', 0); // Dismiss the notice
    // Redirect to clear the URL parameter and avoid it being triggered again
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

hooks()->add_action('app_admin_head', 'mailbox_supported_until');

function mailbox_hide_support_extension() {
    echo "<script>
        jQuery(document).ready(function($) {
            // Get all elements with class 'supported_until'
            var divs = $('.supported_until');
            console.log('Total .supported_until divs:', divs.length); // Log how many divs are rendered
            
            // If more than one div, hide all except the first
            if (divs.length > 1) {
                divs.slice(1).hide(); // Hide all but the first one
            }
        });
    </script>";
}

hooks()->add_action('app_admin_footer', 'mailbox_hide_support_extension');

function customer_profile_tabs($tabs) {
    $tabs['mail'] = [
        'slug' => 'email',
        'name' => 'eMails',
        'icon' => 'fa fa-user-circle',
        'view' => 'mailbox/mailbox_clients',
        'position' => '150',
        'badge' => [],
        'href' => 'admin/mailbox/client_mails',
        'children' => [],
    ];

    return $tabs;
}