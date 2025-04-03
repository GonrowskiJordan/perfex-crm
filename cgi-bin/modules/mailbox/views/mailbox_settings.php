<?php defined('BASEPATH') or exit('No direct script access allowed');

$enabled      = get_option('mailbox_enabled');
$imap_server  = get_option('mailbox_imap_server');
$encryption   = get_option('mailbox_encryption');
$folder_scan  = get_option('mailbox_folder_scan');
$check_every  = get_option('mailbox_check_every');
$unseen_email = get_option('mailbox_only_loop_on_unseen_emails');
$enable_html  = get_option('mailbox_enable_html');

?>

<div class="row">
    <div class="col-md-12">
<div class="form-group"> 
    <label for="pusher_chat" class="control-label clearfix">
        <?php echo _l('mailbox_enable_option'); ?>
    </label> 
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_1_mailbox_enabled" name="settings[mailbox_enabled]" value="1" <?php if ('1' == $enabled) {
            echo ' checked';
        } ?>>
        <label for="y_opt_1_mailbox_enabled"><?php echo _l('settings_yes'); ?></label>
    </div> 
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_2_mailbox_enabled" name="settings[mailbox_enabled]" value="0" <?php if ('0' == $enabled) {
            echo ' checked';
        } ?>>
        <label for="y_opt_2_mailbox_enabled">
            <?php echo _l('settings_no'); ?>
        </label>
    </div>
</div> 
<hr/>
<div class="form-group"> 
    <label for="pusher_chat" class="control-label clearfix">
        <?php echo _l('mailbox_only_loop_on_unseen_emails'); ?>
    </label> 
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_1_mailbox_only_loop_on_unseen_emails" name="settings[mailbox_only_loop_on_unseen_emails]" value="1" <?php if ('1' == $unseen_email) {
            echo ' checked';
        } ?>>
        <label for="y_opt_1_mailbox_only_loop_on_unseen_emails"><?php echo _l('settings_yes'); ?></label>
    </div> 
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_2_mailbox_only_loop_on_unseen_emails" name="settings[mailbox_only_loop_on_unseen_emails]" value="0" <?php if ('0' == $unseen_email) {
            echo ' checked';
        } ?>>
        <label for="y_opt_2_mailbox_only_loop_on_unseen_emails">
            <?php echo _l('settings_no'); ?>
        </label>
    </div>
</div> 
<hr/>
<div class="form-group"> 
    <label for="pusher_chat" class="control-label clearfix">
        <?php echo _l('mailbox_enable_html'); ?>
    </label> 
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_1_mailbox_enable_html" name="settings[mailbox_enable_html]" value="1" <?php if ('1' == $enable_html) {
            echo ' checked';
        } ?>>
        <label for="y_opt_1_mailbox_enable_html"><?php echo _l('settings_yes'); ?></label>
    </div> 
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_2_mailbox_enable_html" name="settings[mailbox_enable_html]" value="0" <?php if ('0' == $enable_html) {
            echo ' checked';
        } ?>>
        <label for="y_opt_2_mailbox_enable_html">
            <?php echo _l('settings_no'); ?>
        </label>
    </div>
</div> 
<hr/>

        <?php echo render_input('settings[mailbox_imap_server]', 'leads_email_integration_imap', $imap_server); ?>
		<br>
        <div class="form-group">
           <label for="encryption"><?php echo _l('leads_email_encryption'); ?></label><br />
           <div class="radio radio-primary radio-inline">
              <input type="radio" name="settings[mailbox_encryption]" value="tls" id="tls" <?php if ('tls' == $encryption) {
    echo 'checked';
} ?>>
              <label for="tls">TLS  (Port 143)</label>
           </div>
           <div class="radio radio-primary radio-inline">
              <input type="radio" name="settings[mailbox_encryption]" value="ssl" id="ssl" <?php if ('ssl' == $encryption) {
    echo 'checked';
} ?>>
              <label for="ssl">SSL (Port 993)</label>
           </div>
           <div class="radio radio-primary radio-inline">
              <input type="radio" name="settings[mailbox_encryption]" value="" id="no_enc" <?php if ('' == $encryption) {
    echo 'checked';
} ?>>
              <label for="no_enc"><?php echo _l('leads_email_integration_folder_no_encryption'); ?> (Port 143)</label>
           </div>
        </div>
		<br>
        <?php echo render_input('settings[mailbox_folder_scan]', 'leads_email_integration_folder', $folder_scan); ?>
        <br>
		<?php echo render_input('settings[mailbox_check_every]', 'leads_email_integration_check_every', $check_every, 'number', ['min'=>hooks()->apply_filters('leads_email_integration_check_every', 3), 'data-ays-ignore'=>true]); ?>
    </div>
</div>

