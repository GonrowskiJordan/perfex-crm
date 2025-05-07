<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- Modal Auto Reply -->

<?= form_open_multipart(admin_url('mailbox/form_auto_reply/' . ($auto_reply_id ? $auto_reply_id : '')), ['id' => 'mailbox-auto-reply-form']); ?>

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
            <?= form_hidden('id', $auto_reply_id); ?>

            <?php $value = (isset($auto_reply) ? $auto_reply->name : ''); ?>
            <?= render_input('name', 'name', $value); ?>

            <?php echo render_input('pattern', 'mailbox_pattern', (isset($auto_reply) ? $auto_reply->pattern : '')); ?>

            <div class="form-group select-placeholder">
                <label for="replyid" class="control-label"><?= _l('mailbox_reply_template'); ?></label>
                <select name="replyid" data-live-search="true" id="replyid" class="form-control selectpicker" data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                    <option></option>
                    <?php foreach ($email_templates as $email_template) { ?>
                        <option value="<?= $email_template['emailtemplateid']; ?>" <?= isset($auto_reply) && $auto_reply->replyid == $email_template['emailtemplateid'] ? 'selected' : ''; ?>>
                            <?= e($email_template['name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            
            <?php echo render_textarea('body', 'mailbox_body', (isset($auto_reply) ? $auto_reply->body : ''), [], [], '', 'tinymce tinymce-auto-reply'); ?>

            <?php $rel_id = (isset($auto_reply) ? $auto_reply->id : false); ?>
            <?= render_custom_fields('maibox_auto_replies', $rel_id); ?>

            <div class="form-group" app-field-wrapper="active">
                <label for="active" class="control-label"> 
                    <?= _l('mailbox_active') ?>
                </label>
                <div class="onoffswitch">
                    <input type="checkbox" name="active" class="onoffswitch-checkbox" id="ma_<?= $auto_reply_id ?>" data-id="<?= $auto_reply_id ?>" <?= (isset($auto_reply) ? ($auto_reply->active ? 'checked' : '') : 'checked') ?>>
                    <label class="onoffswitch-label" for="ma_<?= $auto_reply_id ?>"></label>
                </div>
            </div>
        </div>
    </div>

    <?php hooks()->do_action('after_mailbox_auto_reply_modal_content_loaded'); ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close'); ?></button>

    <button type="submit" class="btn btn-primary" autocomplete="off" data-loading-text="<?= _l('wait_text'); ?>" data-form="#mailbox-auto-reply-form"><?= _l('submit'); ?></button>
</div>

<?= form_close(); ?>