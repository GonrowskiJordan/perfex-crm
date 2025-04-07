<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (isset($client)) { ?>
    <h4 class="customer-profile-group-heading">
        <?= e(is_empty_customer_company($client->userid) ? _l('emails') : _l('customer_emails')); ?>
    </h4>

    <?php if ($this->session->flashdata('gdpr_delete_warning')) { ?>

    <div class="alert alert-warning">
        [GDPR] The contact you removed has associated proposals using the email address of the contact and other personal

        information. You may want to re-check all proposals related to this customer and remove any personal data from

        proposals linked to this contact.
    </div>
<?php } ?>

<?php
    $table_data = [
        [
            'name'    => _l('mailbox_group'),
            'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-group'],
        ],
        [
            'name'    => _l('mailbox_from_to'),
            'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-from-to'],
        ],
        [
            'name'    => _l('mailbox_subject'),
            'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-subject'],
        ],
        [
            'name'    => _l('mailbox_tag'),
            'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-tag'],
        ],
        [
            'name'    => _l('email_template'),
            'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-template'],
        ],
        [
            'name'    => _l('mailbox_date'),
            'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-date'],
        ]
    ];
    $custom_fields = get_custom_fields('mail_clients', ['show_on_table' => 1]);

    foreach ($custom_fields as $field) {
        array_push($table_data, [
            'name'     => $field['name'],
            'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
        ]);
    }

    echo render_datatable($table_data, 'mailbox-clients'); ?>
<?php } ?>

<script>
    let mailbox_client_id = '<?= isset($client) ? $client->userid : '' ?>';
</script>