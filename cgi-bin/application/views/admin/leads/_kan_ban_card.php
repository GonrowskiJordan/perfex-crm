<?php defined('BASEPATH') or exit('No direct script access allowed');
$lead_already_client_tooltip = '';
$lead_is_client              = $lead['is_lead_client'] !== '0';
if ($lead_is_client) {
    $lead_already_client_tooltip = ' data-toggle="tooltip" title="' . _l('lead_have_client_profile') . '"';
}
if ($lead['status'] == $status['id']) { ?>
<li data-lead-id="<?= e($lead['id']); ?>" <?= e($lead_already_client_tooltip); ?>
    class="lead-kan-ban<?= $lead['assigned'] == get_staff_user_id() ? ' current-user-lead' : ''; ?><?= $lead_is_client && get_option('lead_lock_after_convert_to_customer') == 1 && ! is_admin() ? ' not-sortable' : ''; ?>">
    <div class="panel-body lead-body">
        <div class="tw-flex lead-name">
            <?php if ($lead['assigned'] != 0) { ?>
            <a href="<?= admin_url('profile/' . $lead['assigned']); ?>"
                data-placement="right" data-toggle="tooltip"
                title="<?= e(get_staff_full_name($lead['assigned'])); ?>"
                class="mtop8 tw-mr-1.5">
                <?= staff_profile_image($lead['assigned'], [
                    'staff-profile-image-xs',
                ]); ?></a>
            <?php } ?>
            <a href="<?= admin_url('leads/index/' . e($lead['id'])); ?>"
                title="#<?= e($lead['id']) . ' - ' . e($lead['lead_name']); ?>"
                onclick="init_lead(<?= e($lead['id']); ?>);return false;"
                class="tw-block tw-min-w-0 tw-font-medium">
                <span class="mtop10 mbot10 tw-truncate tw-block">
                    #<?= e($lead['id']) . ' - ' . e($lead['lead_name']); ?>
                </span>
            </a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tw-flex">
                    <div class="tw-grow tw-mr-2">
                        <p class="tw-text-sm tw-mb-0">
                            <?= _l('leads_canban_source', $lead['source_name']); ?>
                        </p>
                        <?php $lead_value = $lead['lead_value'] != 0 ? app_format_money($lead['lead_value'], $base_currency->symbol) : '--'; ?>
                        <p class="tw-text-sm tw-mb-0">
                            <?= e(_l('leads_canban_lead_value', $lead_value)); ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <?php if (is_date($lead['lastcontact']) && $lead['lastcontact'] != '0000-00-00 00:00:00') { ?>
                        <small
                            class="text-dark tw-text-sm"><?= _l('leads_dt_last_contact'); ?>
                            <span class="bold">
                                <span class="text-has-action" data-toggle="tooltip"
                                    data-title="<?= e(_dt($lead['lastcontact'])); ?>">
                                    <?= e(time_ago($lead['lastcontact'])); ?>
                                </span>
                            </span>
                        </small><br />
                        <?php } ?>
                        <small
                            class="text-dark"><?= _l('lead_created'); ?>:
                            <span class="bold">
                                <span class="text-has-action" data-toggle="tooltip"
                                    data-title="<?= e(_dt($lead['dateadded'])); ?>">
                                    <?= e(time_ago($lead['dateadded'])); ?>
                                </span>
                            </span>
                        </small><br />
                        <?php hooks()->do_action('before_leads_kanban_card_icons', $lead); ?>
                        <span class="mright5 mtop5 inline-block text-muted" data-toggle="tooltip" data-placement="left"
                            data-title="<?= _l('leads_canban_notes', $lead['total_notes']); ?>">
                            <i class="fa-regular fa-note-sticky"></i>
                            <?= e($lead['total_notes']); ?>
                        </span>
                        <span class="mtop5 inline-block text-muted" data-placement="left" data-toggle="tooltip"
                            data-title="<?= _l('lead_kan_ban_attachments', $lead['total_files']); ?>">
                            <i class="fa fa-paperclip"></i>
                            <?= e($lead['total_files']); ?>
                        </span>
                        <?php hooks()->do_action('after_leads_kanban_card_icons', $lead); ?>
                    </div>
                </div>
            </div>

            <?php if ($lead['tags']) { ?>
            <div class="col-md-12">
                <div class="kanban-tags tw-text-sm tw-inline-flex">
                    <?= render_tags($lead['tags']); ?>
                </div>
            </div>
            <?php } ?>
            <a href="#" class="pull-right text-muted kan-ban-expand-top"
                onclick="slideToggle('#kan-ban-expand-<?= e($lead['id']); ?>'); return false;">
                <i class="fa fa-expand" aria-hidden="true"></i>
            </a>
            <div class="clearfix no-margin"></div>
            <div id="kan-ban-expand-<?= e($lead['id']); ?>"
                class="padding-10" style="display:none;">
                <div class="clearfix"></div>
                <hr class="hr-10" />
                <p class="text-muted lead-field-heading">
                    <?= _l('lead_title'); ?>
                </p>
                <p class="bold tw-text-sm">
                    <?= e($lead['title'] != '' ? $lead['title'] : '-') ?>
                </p>
                <p class="text-muted lead-field-heading">
                    <?= _l('lead_add_edit_email'); ?>
                </p>
                <p class="bold tw-text-sm">
                    <?= $lead['email'] != '' ? '<a href="mailto:' . e($lead['email']) . '">' . e($lead['email']) . '</a>' : '-' ?>
                </p>
                <p class="text-muted lead-field-heading">
                    <?= _l('lead_website'); ?>
                </p>
                <p class="bold tw-text-sm">
                    <?= $lead['website'] != '' ? '<a href="' . e(maybe_add_http($lead['website'])) . '" target="_blank">' . e($lead['website']) . '</a>' : '-' ?>
                </p>
                <p class="text-muted lead-field-heading">
                    <?= _l('lead_add_edit_phonenumber'); ?>
                </p>
                <p class="bold tw-text-sm">
                    <?= $lead['phonenumber'] != '' ? '<a href="tel:' . e($lead['phonenumber']) . '">' . e($lead['phonenumber']) . '</a>' : '-' ?>
                </p>
                <p class="text-muted lead-field-heading">
                    <?= _l('lead_company'); ?>
                </p>
                <p class="bold tw-text-sm">
                    <?= e($lead['company'] != '' ? $lead['company'] : '-') ?>
                </p>
                <p class="text-muted lead-field-heading">
                    <?= _l('lead_address'); ?>
                </p>
                <p class="bold tw-text-sm">
                    <?= e($lead['address'] != '' ? $lead['address'] : '-') ?>
                </p>
                <p class="text-muted lead-field-heading">
                    <?= _l('lead_city'); ?>
                </p>
                <p class="bold tw-text-sm">
                    <?= e($lead['city'] != '' ? $lead['city'] : '-') ?>
                </p>
                <p class="text-muted lead-field-heading">
                    <?= _l('lead_state'); ?>
                </p>
                <p class="bold tw-text-sm">
                    <?= e($lead['state'] != '' ? $lead['state'] : '-') ?>
                </p>
                <p class="text-muted lead-field-heading">
                    <?= _l('lead_country'); ?>
                </p>
                <p class="bold tw-text-sm">
                    <?= e($lead['country'] != 0 ? get_country($lead['country'])->short_name : '-') ?>
                </p>
                <p class="text-muted lead-field-heading">
                    <?= _l('lead_zip'); ?>
                </p>
                <p class="bold tw-text-sm">
                    <?= e($lead['zip'] != '' ? $lead['zip'] : '-') ?>
                </p>
            </div>
        </div>
    </div>
</li>
<?php }
?>