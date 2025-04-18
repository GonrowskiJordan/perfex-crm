<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-3">
                    <a href="#" class="btn btn-primary" onclick="new_spam_filter(); return false">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?= _l('new_spam_filter'); ?>
                    </a>
                </div>
                <ul class="nav nav-tabs nav-tabs-segmented tw-mb-3" role="tablist" id="filters_types">
                    <li role="presentation" class="active"><a href="#sender" aria-controls="sender" role="tab"
                            data-toggle="tab"><?= _l('spam_filter_blocked_senders'); ?></a>
                    </li>
                    <li role="presentation"><a href="#subject" aria-controls="subject" role="tab"
                            data-toggle="tab"><?= _l('spam_filter_blocked_subjects'); ?></a>
                    </li>
                    <li role="presentation"><a href="#phrase" aria-controls="phrase" role="tab"
                            data-toggle="tab"><?= _l('spam_filter_blocked_phrases'); ?></a>
                    </li>
                </ul>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tab-content panel-table-full">
                            <div role="tabpanel" class="tab-pane active" id="sender">
                                <?php render_datatable([_l('spam_filter_content'), _l('options')], 'sender'); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="subject">
                                <?php render_datatable([_l('spam_filter_content'), _l('options')], 'subject'); ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="phrase">
                                <?php render_datatable([_l('spam_filter_content'), _l('options')], 'phrase'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="spam_filter" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?= form_open(admin_url('spam_filters/filter/' . $rel_type)); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span
                        class="edit-title"><?= _l('spamfilter_edit_heading'); ?></span>
                    <span
                        class="add-title"><?= _l('spamfilter_add_heading'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="spam_filter_additional"></div>
                        <div class="form-group">
                            <label
                                for="type"><?= _l('spamfilter_type'); ?></label>
                            <select name="type" id="type" class="selectpicker" data-width="100%"
                                data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                                <option value=""></option>
                                <option value="sender">
                                    <?= _l('spamfilter_type_sender'); ?>
                                </option>
                                <option value="subject">
                                    <?= _l('spamfilter_type_subject'); ?>
                                </option>
                                <option value="phrase">
                                    <?= _l('spamfilter_type_phrase'); ?>
                                </option>
                            </select>
                        </div>
                        <?= render_textarea('value', 'spam_filter_content'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                    data-dismiss="modal"><?= _l('close'); ?></button>
                <button type="submit"
                    class="btn btn-primary"><?= _l('submit'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?= form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
<script>
    $(function() {
        initDataTable('.table-sender',
            '<?= admin_url('spam_filters/view/' . $rel_type); ?>/sender',
            [1], [
                1
            ]);
        initDataTable('.table-subject',
            '<?= admin_url('spam_filters/view/' . $rel_type); ?>/subject',
            [1], [
                1
            ]);
        initDataTable('.table-phrase',
            '<?= admin_url('spam_filters/view/' . $rel_type); ?>/phrase',
            [1], [
                1
            ]);
        appValidateForm($('form'), {
            value: 'required',
            type: 'required'
        }, manage_spam_filters);

        $('#spam_filter').on('hidden.bs.modal', function(event) {
            $('#spam_filter select').selectpicker('val', '');
            $('#spam_filter textarea').val('');
            $('#spam_filter #spam_filter_additional').html('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
    });

    function manage_spam_filters(form) {
        var original_type = $('input[name="original_type"]').val();

        $('input[name="original_type"]').remove();
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {

            response = JSON.parse(response);

            if (response.success) {
                var type = $('select[name="type"]').selectpicker('val');
                $('.table-' + type).DataTable().ajax.reload();
                if (type != original_type) {
                    $('.table-' + original_type).DataTable().ajax.reload();
                }
                alert_float('success', response.message);
            }

            $('a[href="#' + type + '"]').click();

            $(form).trigger('reinitialize.areYouSure');
            $('#spam_filter').modal('hide');

        });
        return false;
    }

    function new_spam_filter() {
        $('#spam_filter').modal('show');
        $('.edit-title').addClass('hide');

        // Set the default select type selected by the active tab
        var activeTab = $('#filters_types').find('li.active a');
        var activeType = activeTab.attr('href');
        // Remove the # attribute, e.q. #subject
        activeType = activeType.substr(1);
        if (activeType) {
            $('select[name="type"]').selectpicker('val', activeType);
        }
    }

    function edit_spam_filter(invoker, id) {
        var type = $(invoker).data('type');
        var value = $(invoker).data('value');
        $('#spam_filter_additional').append(hidden_input('id', id));
        $('#spam_filter_additional').append(hidden_input('original_type', type));
        $('#spam_filter select').selectpicker('val', type);
        $('#spam_filter textarea').val(value);
        $('#spam_filter').modal('show');
        $('.add-title').addClass('hide');
    }
</script>
</body>

</html>