<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="inline-block new-tag-wrapper">
                            <a href="#" onclick="view_mailbox_auto_reply(); return false;" class="btn btn-primary new-tag mbot15">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?= _l('new_mailbox_auto_reply'); ?>
                            </a>
                        </div>

                        <div class="clearfix"></div>

                        <?php
                            $table_data = [
                                _l('name'),
                                _l('mailbox_pattern'),
                                _l('mailbox_subject'),
                                _l('mailbox_reply_template'),
                                [
                                    'name' => _l('mailbox_active'),
                                    'th_attrs' => ['class' => 'text-center']
                                ]
                            ];
                            $custom_fields = get_custom_fields('mail_auto_replies', ['show_on_table' => 1]);
                            foreach ($custom_fields as $field)
                            {
                                array_push($table_data, ['name' => $field['name'], 'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1], ]);
                            }
                            render_datatable($table_data, 'mailbox-auto-replies');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<div class="modal fade" id="autoReplyModal" tabindex="-1" role="dialog" aria-labelledby="autoReplyModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>

<script>
    $(function() {
        initDataTable('.table-mailbox-auto-replies', window.location.href, undefined, 'undefined', [0, 'asc']);

        $("body").on("change", ".table-mailbox-auto-replies .onoffswitch input", function (event, state) {
            var switch_url = $(this).data("switch-url");

            if (!switch_url) {
                return;
            }

            switch_field(this);
        });
    });
</script>
</body>
</html>