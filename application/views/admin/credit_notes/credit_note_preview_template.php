<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?= form_hidden('_attachment_sale_id', $credit_note->id); ?>
<?= form_hidden('_attachment_sale_type', 'credit_note'); ?>
<div class="col-md-12 no-padding">
    <div class="panel_s">
        <div class="panel-body">
            <div class="horizontal-scrollable-tabs preview-tabs-top panel-full-width-tabs">
                <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                <div class="horizontal-tabs">
                    <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tab_credit_note" aria-controls="tab_credit_note" role="tab" data-toggle="tab">
                                <?= _l('credit_note'); ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#invoices_credited" aria-controls="invoices_credited" role="tab" data-toggle="tab">
                                <?= _l('invoices_credited'); ?>
                                <?php if (count($credit_note->applied_credits) > 0) {
                                    echo '<span class="badge">' . count($credit_note->applied_credits) . '</span>';
                                } ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#tab_refunds" aria-controls="tab_refunds" role="tab" data-toggle="tab">
                                <?= _l('refunds'); ?>
                                <?php if (count($credit_note->refunds) > 0) {
                                    echo '<span class="badge">' . count($credit_note->refunds) . '</span>';
                                } ?>
                            </a>
                        </li>
                        <li role="presentation" class="tab-separator">
                            <a href="#tab_reminders"
                                onclick="initDataTable('.table-reminders', admin_url + 'misc/get_reminders/' + <?= $credit_note->id; ?> + '/' + 'credit_note', undefined, undefined, undefined,[1,'asc']); return false;"
                                aria-controls="tab_reminders" role="tab" data-toggle="tab">
                                <?= _l('reminders'); ?>
                                <?php
$total_reminders = total_rows(
    db_prefix() . 'reminders',
    [
        'isnotified' => 0,
        'staff'      => get_staff_user_id(),
        'rel_type'   => 'credit_note',
        'rel_id'     => $credit_note->id,
    ]
);
if ($total_reminders > 0) {
    echo '<span class="badge">' . $total_reminders . '</span>';
}
?>
                            </a>
                        </li>
                        <li role="presentation" data-toggle="tooltip"
                            title="<?= _l('emails_tracking'); ?>"
                            class="tab-separator">
                            <a href="#tab_emails_tracking" aria-controls="tab_emails_tracking" role="tab"
                                data-toggle="tab">
                                <?php if (! is_mobile()) { ?>
                                <i class="fa-regular fa-envelope-open" aria-hidden="true"></i>
                                <?php } else { ?>
                                <?= _l('emails_tracking'); ?>
                                <?php } ?>
                            </a>
                        </li>
                        <li role="presentation" class="tab-separator toggle_view">
                            <a href="#" onclick="small_table_full_view(); return false;" data-placement="left"
                                data-toggle="tooltip"
                                data-title="<?= _l('toggle_full_view'); ?>">
                                <i class="fa fa-expand"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mtop20">
                <div class="col-md-3">
                    <?= format_credit_note_status($credit_note->status); ?>
                </div>
                <div class="col-md-9">
                    <div class="visible-xs">
                        <div class="mtop10"></div>
                    </div>
                    <div class="pull-right _buttons">
                        <?php if ($credit_note->status == 1 && ! empty($credit_note->clientid)) { ?>
                        <a href="#" data-toggle="modal" data-target="#apply_credits" class="btn btn-primary">
                            <?= _l('apply_to_invoice'); ?>
                        </a>
                        <?php } ?>
                        <?php if (staff_can('edit', 'credit_notes') && $credit_note->status != 3) { ?>
                        <a href="<?= admin_url('credit_notes/credit_note/' . $credit_note->id); ?>"
                            class="btn btn-default btn-with-tooltip" data-toggle="tooltip"
                            title="<?= e(_l('edit', _l('credit_note_lowercase'))); ?>"
                            data-placement="bottom">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <?php } ?>
                        <div class="btn-group">
                            <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"><i class="fa-regular fa-file-pdf"></i>
                                <?= is_mobile() ? ' PDF' : ''; ?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="hidden-xs">
                                    <a
                                        href="<?= admin_url('credit_notes/pdf/' . $credit_note->id . '?output_type=I'); ?>">
                                        <?= _l('view_pdf'); ?>
                                    </a>
                                </li>
                                <li class="hidden-xs">
                                    <a href="<?= admin_url('credit_notes/pdf/' . $credit_note->id . '?output_type=I'); ?>"
                                        target="_blank">
                                        <?= _l('view_pdf_in_new_window'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="<?= admin_url('credit_notes/pdf/' . $credit_note->id); ?>">
                                        <?= _l('download'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= admin_url('credit_notes/pdf/' . $credit_note->id . '?print=true'); ?>"
                                        target="_blank">
                                        <?= _l('print'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <?php if ($credit_note->status != 3 && ! empty($credit_note->clientid)) { ?>
                        <a href="#" class="credit-note-send-to-client btn btn-default" data-toggle="modal"
                            data-target="#credit_note_send_to_client_modal">
                            <i class="fa-regular fa-envelope"></i>
                        </a>
                        <?php } ?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default pull-left dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?= _l('more'); ?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <?php hooks()->do_action('credit_note_menu_links_start', $credit_note); ?>
                                <?php
                                   if ($credit_note->status == 1 && staff_can('edit', 'credit_notes')) { ?>
                                <li>
                                    <a href="#" onclick="refund_credit_note(); return false;" id="credit_note_refund">
                                        <?= _l('refund'); ?>
                                    </a>
                                </li>
                                <?php }
                                   // You can only mark as void, if it's not closed, not void, no credits applied, no refunds applied
if ($credit_note->status != 2 && $credit_note->status != 3 && ! $credit_note->credits_used && ! $credit_note->total_refunds && staff_can('edit', 'credit_notes')) { ?>
                                <li>
                                    <a
                                        href="<?= admin_url('credit_notes/mark_void/' . $credit_note->id); ?>">
                                        <?= _l('credit_note_status_void'); ?>
                                    </a>
                                </li>
                                <?php } elseif ($credit_note->status == 3 && staff_can('edit', 'credit_notes')) { ?>
                                <li>
                                    <a
                                        href="<?= admin_url('credit_notes/mark_open/' . $credit_note->id); ?>">
                                        <?= _l('credit_note_mark_as_open'); ?>
                                    </a>
                                </li>
                                <?php } ?>
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#sales_attach_file">
                                        <?= _l('invoice_attach_file'); ?>
                                    </a>
                                </li>
                                <?php
if (staff_can('delete', 'credit_notes')) {
    $delete_tooltip = '';
    $allow_delete   = true;
    if ($credit_note->status == 2) {
        $delete_tooltip = _l('credits_applied_cant_delete_status_closed');
        $allow_delete   = false;
    } elseif ($credit_note->credits_used) {
        $delete_tooltip = _l('credits_applied_cant_delete_credit_note');
        $allow_delete   = false;
    } elseif ($credit_note->total_refunds) {
        $allow_delete   = false;
        $delete_tooltip = _l('refunds_applied_cant_delete_credit_note');
    } ?>
                                <li>
                                    <a data-toggle="tooltip"
                                        data-title="<?= e($delete_tooltip); ?>"
                                        href="<?= admin_url('credit_notes/delete/' . $credit_note->id); ?>"
                                        class="text-danger delete-text<?= $allow_delete ? ' _delete' : ''; ?>"
                                        <?= ! $allow_delete ? 'style="cursor:not-allowed;" onclick="return false;"' : ''; ?>>
                                        <?= _l('delete'); ?>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr class="hr-panel-separator" />
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane ptop10 active" id="tab_credit_note">
                    <div id="credit-note-preview">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <h4 class="bold">
                                    <a
                                        href="<?= admin_url('credit_notes/credit_note/' . $credit_note->id); ?>">
                                        <span id="credit-note-number">
                                            <?= e(format_credit_note_number($credit_note->id)); ?>
                                        </span>
                                    </a>
                                </h4>
                                <address class="tw-text-neutral-500">
                                    <?= format_organization_info(); ?>
                                </address>
                            </div>
                            <div class="col-sm-6 text-right">
                                <span
                                    class="bold"><?= _l('credit_note_bill_to'); ?></span>
                                <address class="tw-text-neutral-500">
                                    <?= format_customer_info($credit_note, 'credit_note', 'billing', true); ?>
                                </address>
                                <?php if ($credit_note->include_shipping == 1 && $credit_note->show_shipping_on_credit_note == 1) { ?>
                                <span
                                    class="bold"><?= _l('ship_to'); ?></span>
                                <address class="tw-text-neutral-500">
                                    <?= format_customer_info($credit_note, 'credit_note', 'shipping'); ?>
                                </address>
                                <?php } ?>
                                <p class="no-mbot">
                                    <span class="bold">
                                        <?= _l('credit_note_date'); ?>:
                                    </span>
                                    <?= _d($credit_note->date)?>
                                </p>
                                <?php if (! empty($credit_note->reference_no)) { ?>
                                <p class="no-mbot">
                                    <span
                                        class="bold"><?= _l('reference_no'); ?>:</span>
                                    <?= e($credit_note->reference_no); ?>
                                </p>
                                <?php } ?>
                                <?php if ($credit_note->project_id && get_option('show_project_on_credit_note') == '1') { ?>
                                <p class="no-mbot">
                                    <span
                                        class="bold"><?= _l('project'); ?>:</span>
                                    <?= e(get_project_name_by_id($credit_note->project_id)); ?>
                                </p>
                                <?php } ?>
                                <?php $pdf_custom_fields = get_custom_fields('credit_note', ['show_on_pdf' => 1]); ?>
                                <?php foreach ($pdf_custom_fields as $field) {
                                    $value = get_custom_field_value($credit_note->id, $field['id'], 'credit_note');
                                    if ($value == '') {
                                        continue;
                                    } ?>
                                <p class="no-mbot">
                                    <span
                                        class="bold"><?= e($field['name']); ?>:
                                    </span>
                                    <?= $value; ?>
                                </p>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <?php $items = get_items_table_data($credit_note, 'credit_note', 'html', true); ?>
                                    <?= $items->table(); ?>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-7">
                                <table class="table text-right">
                                    <tbody>
                                        <tr id="subtotal">
                                            <td>
                                                <span class="tw-font-medium tw-text-neutral-700">
                                                    <?= _l('credit_note_subtotal'); ?>
                                                </span>
                                            </td>
                                            <td class="subtotal">
                                                <?= e(app_format_money($credit_note->subtotal, $credit_note->currency_name)); ?>
                                            </td>
                                        </tr>
                                        <?php if (is_sale_discount_applied($credit_note)) { ?>
                                        <tr>
                                            <td>
                                                <span class="tw-font-medium tw-text-neutral-700">
                                                    <?= _l('credit_note_discount'); ?>
                                                    <?php if (is_sale_discount($credit_note, 'percent')) { ?>
                                                    (<?= e(app_format_number($credit_note->discount_percent, true)); ?>%)
                                                    <?php } ?>
                                                </span>
                                            </td>
                                            <td class="discount">
                                                <?= e('-' . app_format_money($credit_note->discount_total, $credit_note->currency_name)); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <?php foreach ($items->taxes() as $tax) {
                                            echo '<tr class="tax-area"><td class="tw-font-medium !tw-text-neutral-700">' . e($tax['taxname']) . ' (' . e(app_format_number($tax['taxrate'])) . '%)</td><td>' . e(app_format_money($tax['total_tax'], $credit_note->currency_name)) . '</td></tr>';
                                        } ?>
                                        <?php if ((int) $credit_note->adjustment != 0) { ?>
                                        <tr>
                                            <td>
                                                <span
                                                    class="tw-font-medium tw-text-neutral-700"><?= _l('credit_note_adjustment'); ?></span>
                                            </td>
                                            <td class="adjustment">
                                                <?= e(app_format_money($credit_note->adjustment, $credit_note->currency_name)); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td>
                                                <span
                                                    class="tw-font-medium tw-text-neutral-700"><?= _l('credit_note_total'); ?></span>
                                            </td>
                                            <td class="total">
                                                <?= e(app_format_money($credit_note->total, $credit_note->currency_name)); ?>
                                            </td>
                                        </tr>
                                        <?php if ($credit_note->credits_used) { ?>
                                        <tr>
                                            <td>
                                                <span class="tw-font-medium tw-text-neutral-700">
                                                    <?= _l('credits_used'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= e('-' . app_format_money($credit_note->credits_used, $credit_note->currency_name)); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <?php if ($credit_note->total_refunds) { ?>
                                        <tr>
                                            <td>
                                                <span class="tw-font-medium tw-text-neutral-700">
                                                    <?= _l('refund'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= e('-' . app_format_money($credit_note->total_refunds, $credit_note->currency_name)); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td>
                                                <span class="tw-font-medium tw-text-neutral-700">
                                                    <?= _l('credits_remaining'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= e(app_format_money($credit_note->remaining_credits, $credit_note->currency_name)); ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php if ($credit_note->clientnote != '') { ?>
                            <div class="col-md-12 mtop15">
                                <p class="tw-text-neutral-700 tw-font-medium">
                                    <?= _l('credit_note_client_note'); ?>
                                </p>
                                <div class="tw-text-neutral-500 tw-leading-relaxed">
                                    <?= process_text_content_for_display($credit_note->clientnote); ?>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if ($credit_note->terms != '') { ?>
                            <div class="col-md-12 mtop15">
                                <p class="tw-text-neutral-700 tw-font-medium">
                                    <?= _l('terms_and_conditions'); ?>
                                </p>
                                <div class="tw-text-neutral-500 tw-leading-relaxed">
                                    <?= process_text_content_for_display($credit_note->terms); ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <?php
                                                          if (count($credit_note->attachments) > 0) { ?>
                        <div class="clearfix"></div>
                        <hr />
                        <p class="tw-text-neutral-700 tw-font-medium">
                            <?= _l('credit_note_files'); ?>
                        </p>
                        <?php foreach ($credit_note->attachments as $attachment) {
                            $attachment_url = site_url('download/file/sales_attachment/' . $attachment['attachment_key']);
                            if (! empty($attachment['external'])) {
                                $attachment_url = $attachment['external_link'];
                            } ?>
                        <div class="mbot15 row inline-block full-width"
                            data-attachment-id="<?= e($attachment['id']); ?>">
                            <div class="col-md-8">
                                <a href="<?= e($attachment_url); ?>"
                                    target="_blank"><?= e($attachment['file_name']); ?></a>
                                <br />
                                <small class="text-muted">
                                    <?= e($attachment['filetype']); ?></small>
                            </div>
                            <div class="col-md-4 text-right">
                                <?php if ($attachment['staffid'] == get_staff_user_id() || is_admin()) { ?>
                                <a href="#" class="text-muted"
                                    onclick="delete_credit_note_attachment(<?= e($attachment['id']); ?>); return false;">
                                    <i class="fa-regular fa-trash-can"></i>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="invoices_credited">
                    <?php if (count($credit_note->applied_credits) == 0) {
                        echo '<div class="alert alert-info no-mbot">';
                        echo _l('credited_invoices_not_found');
                        echo '</div>';
                    } else { ?>
                    <table class="table table-bordered no-mtop">
                        <thead>
                            <tr>
                                <th><span
                                        class="bold"><?= _l('credit_invoice_number'); ?></span>
                                </th>
                                <th><span
                                        class="bold"><?= _l('amount_credited'); ?></span>
                                </th>
                                <th><span
                                        class="bold"><?= _l('credit_date'); ?></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($credit_note->applied_credits as $credit) { ?>
                            <tr>
                                <td>
                                    <a
                                        href="<?= admin_url('invoices/list_invoices/' . $credit['invoice_id']); ?>">
                                        <?= e(format_invoice_number($credit['invoice_id'])); ?>
                                    </a>
                                </td>
                                <td>
                                    <?= e(app_format_money($credit['amount'], $credit_note->currency_name)); ?>
                                </td>
                                <td>
                                    <?= e(_d($credit['date'])); ?>
                                    <?php if (staff_can('delete', 'credit_notes')) { ?>
                                    <a href="<?= admin_url('credit_notes/delete_credit_note_applied_credit/' . $credit['id'] . '/' . $credit['credit_id'] . '/' . $credit['invoice_id']); ?>"
                                        class="pull-right text-danger _delete"><i class="fa fa-trash"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php }  ?>
                </div>
                <div role="tabpanel" class="tab-pane ptop10" id="tab_emails_tracking">
                    <?php
                  $this->load->view(
                      'admin/includes/emails_tracking',
                      [
                          'tracked_emails' => get_tracked_emails($credit_note->id, 'credit_note'), ]
                  );
?>
                </div>
                <div role="tabpanel" class="tab-pane" id="tab_refunds">
                    <?php if (count($credit_note->refunds) == 0) {
                        echo '<div class="alert alert-info no-mbot">';
                        echo _l('not_refunds_found');
                        echo '</div>';
                    } else { ?>
                    <table class="table table-bordered no-mtop">
                        <thead>
                            <tr>
                                <th><span
                                        class="bold"><?= _l('credit_date'); ?></span>
                                </th>
                                <th><span
                                        class="bold"><?= _l('refund_amount'); ?></span>
                                </th>
                                <th><span
                                        class="bold"><?= _l('payment_mode'); ?></span>
                                </th>
                                <th><span
                                        class="bold"><?= _l('note'); ?></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($credit_note->refunds as $refund) { ?>
                            <tr>
                                <td>
                                    <?= e(_d($refund['refunded_on'])); ?>
                                </td>
                                <td>
                                    <?= e(app_format_money($refund['amount'], $credit_note->currency_name)); ?>
                                </td>
                                <td>
                                    <?= e($refund['payment_mode_name']); ?>
                                </td>
                                <td>
                                    <?php if (staff_can('delete', 'credit_notes')) { ?>
                                    <a href="<?= admin_url('credit_notes/delete_refund/' . $refund['id'] . '/' . $refund['credit_note_id']); ?>"
                                        class="pull-right text-danger _delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <?php } ?>
                                    <?php if (staff_can('edit', 'credit_notes')) { ?>
                                    <a href="#"
                                        onclick="edit_refund(<?= e($refund['id']); ?>); return false;"
                                        class="pull-right mright5">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <?php } ?>
                                    <?= e($refund['note']); ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php }  ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="tab_reminders">
                    <a href="#" class="btn btn-primary" data-toggle="modal"
                        data-target=".reminder-modal-credit_note-<?= e($credit_note->id); ?>"><i
                            class="fa-regular fa-bell"></i>
                        <?= _l('credit_note_set_reminder_title'); ?></a>
                    <hr />
                    <?php render_datatable([_l('reminder_description'), _l('reminder_date'), _l('reminder_staff'), _l('reminder_is_notified')], 'reminders'); ?>
                    <?php $this->load->view('admin/includes/modals/reminder', ['id' => $credit_note->id, 'name' => 'credit_note', 'members' => $members, 'reminder_title' => _l('credit_note_set_reminder_title')]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php $this->load->view('admin/credit_notes/send_to_client'); ?>
<?php $this->load->view('admin/credit_notes/apply_credits_to_invoices'); ?>
<script>
    init_items_sortable(true);
    init_btn_with_tooltips();
    init_datepicker();
    init_selectpicker();
    init_form_reminder();
    init_tabs_scrollable();

    function refund_credit_note() {
        var credit_note_id = <?= e($credit_note->id); ?> ;
        $('#credit_note').load(admin_url + 'credit_notes/refund/' + credit_note_id);
    }

    function edit_refund(refund_id) {
        var credit_note_id = <?= e($credit_note->id); ?> ;
        $('#credit_note').load(admin_url + 'credit_notes/refund/' + credit_note_id + '/' + refund_id);
    }
</script>