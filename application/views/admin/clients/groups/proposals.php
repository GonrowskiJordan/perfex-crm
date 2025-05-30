<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($client)) { ?>
<h4 class="customer-profile-group-heading">
    <?= _l('proposals'); ?></h4>
<?php if (staff_can('create', 'proposals')) { ?>
<a href="<?= admin_url('proposals/proposal?rel_type=customer&rel_id=' . $client->userid); ?>"
    class="btn btn-primary mbot15<?= $client->active == 0 ? ' disabled' : ''; ?>">
    <i class="fa-regular fa-plus tw-mr-1"></i>
    <?= _l('new_proposal'); ?>
</a>
<?php } ?>
<?php if (total_rows(db_prefix() . 'proposals', ['rel_type' => 'customer', 'rel_id' => $client->userid]) > 0 && (staff_can('create', 'proposals') || staff_can('edit', 'proposals'))) { ?>
<a href="#" class="btn btn-primary mbot15" data-toggle="modal" data-target="#sync_data_proposal_data">
    <?= _l('sync_data'); ?>
</a>
<?php $this->load->view(
    'admin/proposals/sync_data',
    [
        'related'  => $client, 'rel_id' => $client->userid,
        'rel_type' => 'customer', ]
); ?>
<?php } ?>
<?php
    $table_data = [
        _l('proposal') . ' #',
        _l('proposal_subject'),
        _l('proposal_total'),
        _l('proposal_date'),
        _l('proposal_open_till'),
        _l('tags'),
        _l('proposal_date_created'),
        _l('proposal_status'), ];
    $custom_fields = get_custom_fields('proposal', ['show_on_table' => 1]);

    foreach ($custom_fields as $field) {
        array_push($table_data, [
            'name'     => $field['name'],
            'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
        ]);
    }

    $table_data = hooks()->apply_filters('proposals_relation_table_columns', $table_data);

    render_datatable($table_data, 'proposals-client-profile', [], [
        'data-last-order-identifier' => 'proposals-relation',
        'data-default-order'         => get_table_last_order('proposals-relation'),
    ]);
    ?>
<?php } ?>