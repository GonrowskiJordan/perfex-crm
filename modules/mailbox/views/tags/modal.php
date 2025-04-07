<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- Modal Tag -->

<?= form_open(admin_url('mailbox/form_tag/' . ($tag_id ? '/' . $tag_id : '')), ['id' => 'mailbox-tag-form', 'autocomplete' => 'off']); ?>

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
            <?= form_hidden('id', $tag_id); ?>

            <?php $value = (isset($tag) ? $tag->name : ''); ?>
            <?= render_input('name', 'name', $value); ?>

            <?php $rel_id = (isset($tag) ? $tag->id : false); ?>
            <?= render_custom_fields('maibox_tags', $rel_id); ?>

            <div class="form-group" app-field-wrapper="active">
                <label for="active" class="control-label"> 
                    <?= _l('mailbox_active') ?>
                </label>
                <div class="onoffswitch">
                    <input type="checkbox" name="active" class="onoffswitch-checkbox" id="mt_<?= $tag_id ?>" data-id="<?= $tag_id ?>" <?= (isset($tag) ? ($tag->active ? 'checked' : '') : 'checked') ?>>
                    <label class="onoffswitch-label" for="mt_<?= $tag_id ?>"></label>
                </div>
            </div>
        </div>
    </div>

    <?php hooks()->do_action('after_mailbox_tag_modal_content_loaded'); ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= _l('close'); ?></button>

    <button type="submit" class="btn btn-primary" data-loading-text="<?= _l('wait_text'); ?>"  autocomplete="off" data-form="#mailbox-tag-form"><?= _l('submit'); ?></button>
</div>

<?= form_close(); ?>