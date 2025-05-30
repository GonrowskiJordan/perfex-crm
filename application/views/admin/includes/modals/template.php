<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="TemplateModal">
    <div class="modal-dialog modal-xxl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <?= e($title); ?>
                </h4>
            </div>
            <?php if (! isset($template)) {
                echo form_open('admin/templates/template', ['id' => 'template-form']);
            } else {
                echo form_open('admin/templates/template/' . $id, ['id' => 'template-form']);
            } ?>
            <div class="modal-body">
                <?= form_hidden('rel_type', $rel_type); ?>
                <!-- so when modal is submitted, it returns to the proposal/contract that was being edited. -->
                <?= form_hidden('rel_id', $rel_id); ?>
                <?= render_input('name', 'template_name', isset($template) ? $template->name : ''); ?>
                <?= render_textarea('content', 'template_content', isset($template) ? $template->content : '', [], [], '', 'tinymce-template'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close_btn"
                    data-dismiss="modal"><?= _l('close'); ?></button>
                <button type="submit"
                    class="btn btn-primary"><?= _l('submit'); ?></button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>