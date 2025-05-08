<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- Modal Email Template -->

<?= form_open(admin_url('mailbox/form_email_template/' . ($emailtemplateid ? '/' . $emailtemplateid : '')), ['id' => 'mailbox-email-template-form', 'autocomplete' => 'off']); ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

    <div class="tw-flex">
        <div>
            <h4 class="modal-title tw-mb-0">
                <?= e($title); ?>
            </h4>
        </div>
    </div>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <?= form_hidden('id', $emailtemplateid); ?>

            <?= render_input('name', 'template_name', isset($email_template) ? $email_template->name : '', 'text'); ?>
            <?= render_input('subject[english]', 'template_subject', isset($email_template) ? $email_template->subject : ''); ?>

            <div style="<?= hooks()->apply_filters('show_deprecated_from_email_header_template_field', false) === false ? 'display:none;' : ''; ?>">
        </div>

        <div class="checkbox checkbox-primary">
            <input type="checkbox" name="plaintext" id="plaintext" <?= isset($email_template) && $email_template->plaintext == 1 ? 'checked' : ''; ?>>
            <label for="plaintext">
                <?= _l('send_as_plain_text'); ?>
            </label>
        </div>

        <div class="checkbox checkbox-primary">
            <input type="checkbox" name="disabled" id="disabled" <?= isset($email_template) && $email_template->active == 0 ? 'checked' : ''; ?>>

            <label data-toggle="tooltip" title="<?= _l('disable_email_from_being_sent'); ?>" for="disabled">
                <?= _l('email_template_disabled'); ?>
            </label>
        </div>

        <hr />

        <?php
            $editors = [];

            array_push($editors, 'message[english]');
        ?>

        <h4 class="tw-font-bold tw-text-base">English</h4>
        <?php if (get_option('disable_ticket_public_url') == '1' && strpos(isset($email_template) ? $email_template->message : '', 'ticket_public_url') !== false) {
            echo '<div class="alert alert-warning">This template contains the <strong/>{ticket_public_url}</strong> merge field, but the public ticket URL is disabled in the settings. Consider updating the merge field to <strong>{ticket_url}</strong> or enable the ticket public URL feature.</div>';
        } ?>
        <?= render_textarea('message[english]', '', isset($email_template) ? $email_template->message : '', ['data-url-converter-callback' => 'myCustomURLConverter'], [], '', 'tinymce tinymce-manual'); ?>

        <?php
            foreach ($available_languages as $availableLanguage) {
                if (isset($email_template)) {
                    $lang_template = $this->emails_model->get([
                        'slug'     => $email_template->slug,
                        'language' => $availableLanguage,
                    ]);
                    if (count($lang_template) > 0) {
                        $lang_used = false;
        
                        if (get_option('active_language') == $availableLanguage || total_rows(db_prefix() . 'staff', ['default_language' => $availableLanguage]) > 0 || total_rows(db_prefix() . 'clients', ['default_language' => $availableLanguage]) > 0) {
                            $lang_used = true;
                        }
        
                        $hide_template_class = '';
                        if ($lang_used == false) {
                            $hide_template_class = 'hide';
                        } ?>
        
                        <hr />
        
                        <h4 class="pointer tw-font-bold tw-text-base" onclick='slideToggle("#temp_<?= e($availableLanguage); ?>");'>
                            <?= e(ucfirst($availableLanguage)); ?>
                        </h4>
        
                        <?php
                        $lang_template = $lang_template[0];

                        array_push($editors, 'message[' . $availableLanguage . ']');
                        echo '<div id="temp_' . $availableLanguage . '" class="tw-mt-3 ' . $hide_template_class . '">';

                        if (get_option('disable_ticket_public_url') == '1' && strpos($lang_template['message'] ?: '', 'ticket_public_url') !== false) {
                            echo '<div class="alert alert-warning">This template contains the <strong>{ticket_public_url}</strong> merge field, but the public ticket URL is disabled in the settings. Consider updating the merge field to <strong> or enable the ticket public URL feature.</div>';
                        }

                        echo render_input('subject[' . $availableLanguage . ']', 'template_subject', $lang_template['subject']);
                        echo '<p class="bold">' . _l('email_template_email_message') . '</p>';
                        echo render_textarea('message[' . $availableLanguage . ']', '', $lang_template['message'], ['data-url-converter-callback' => 'myCustomURLConverter'], [], '', 'tinymce tinymce-manual');
                        echo '</div>';
                    }
                } else {
                    $hide_template_class = 'hide'; ?>
        
                    <hr />
    
                    <h4 class="pointer tw-font-bold tw-text-base" onclick='slideToggle("#temp_<?= e($availableLanguage); ?>");'>
                        <?= e(ucfirst($availableLanguage)); ?>
                    </h4>
                    
                    <?php
                    array_push($editors, 'message[' . $availableLanguage . ']');
                    echo '<div id="temp_' . $availableLanguage . '" class="tw-mt-3 ' . $hide_template_class . '">';

                    if (get_option('disable_ticket_public_url') == '1' && strpos($lang_template['message'] ?: '', 'ticket_public_url') !== false) {
                        echo '<div class="alert alert-warning">This template contains the <strong>{ticket_public_url}</strong> merge field, but the public ticket URL is disabled in the settings. Consider updating the merge field to <strong> or enable the ticket public URL feature.</div>';
                    }

                    echo render_input('subject[' . $availableLanguage . ']', 'template_subject', '');
                    echo '<p class="bold">' . _l('email_template_email_message') . '</p>';
                    echo render_textarea('message[' . $availableLanguage . ']', '', '', ['data-url-converter-callback' => 'myCustomURLConverter'], [], '', 'tinymce tinymce-manual');
                    echo '</div>';
                }
            }
        ?>

            <?php $rel_id = (isset($email_template) ? $email_template->emailtemplateid : false); ?>
            <?= render_custom_fields('maibox_email_templates', $rel_id); ?>
        </div>
    </div>

    <?php hooks()->do_action('after_mailbox_email_template_modal_content_loaded'); ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close'); ?></button>

    <button type="submit" class="btn btn-primary" autocomplete="off" data-loading-text="<?= _l('wait_text'); ?>" data-form="#mailbox-email-template-form"><?= _l('submit'); ?></button>
</div>

<?= form_close(); ?>