<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="customer_file_share_file_with"
    data-total-contacts="<?= count($contacts); ?>" tabindex="-1"
    role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <?= _l('share_file_with'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <?= form_hidden('file_id'); ?>
                <?= render_select('share_contacts_id[]', $contacts, ['id', ['firstname', 'lastname']], 'customer_contacts', [get_primary_contact_user_id($client->userid)], ['multiple' => true, 'data-actions-box' => true], [], '', '', false); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                    data-dismiss="modal"><?= _l('close'); ?></button>
                <button type="button" class="btn btn-primary"
                    onclick="do_share_file_contacts();"><?= _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<h4 class="customer-profile-group-heading">
    <?= _l('customer_attachments'); ?>
</h4>
<p class="text-info">
    <?= _l('customer_files_info_message'); ?>
</p>
<?php if (isset($client)) { ?>
<?= form_open_multipart(admin_url('clients/upload_attachment/' . $client->userid), ['class' => 'dropzone', 'id' => 'client-attachments-upload']); ?>
<input type="file" name="file" multiple />
<?= form_close(); ?>
<div class="tw-flex tw-justify-end tw-items-center tw-space-x-2 mtop15">
    <button class="gpicker" data-on-pick="customerGoogleDriveSave">
        <i class="fa-brands fa-google" aria-hidden="true"></i>
        <?= _l('choose_from_google_drive'); ?>
    </button>
    <div id="dropbox-chooser"></div>
</div>
<div class="attachments">
    <div class="mtop25">

        <table class="table dt-table" data-order-col="2" data-order-type="desc">
            <thead>
                <tr>
                    <th width="30%">
                        <?= _l('customer_attachments_file'); ?>
                    </th>
                    <th><?= _l('customer_attachments_show_in_customers_area'); ?>
                    </th>
                    <th><?= _l('file_date_uploaded'); ?>
                    </th>
                    <th><?= _l('options'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attachments as $type => $attachment) {
                    $download_indicator = 'id';
                    $key_indicator      = 'rel_id';
                    $upload_path        = get_upload_path_by_type($type);
                    if ($type == 'invoice' || $type == 'proposal' || $type == 'estimate' || $type == 'credit_note') {
                        $url                = site_url() . 'download/file/sales_attachment/';
                        $download_indicator = 'attachment_key';
                    } elseif ($type == 'contract') {
                        $url                = site_url() . 'download/file/contract/';
                        $download_indicator = 'attachment_key';
                    } elseif ($type == 'lead') {
                        $url = site_url() . 'download/file/lead_attachment/';
                    } elseif ($type == 'task') {
                        $url                = site_url() . 'download/file/taskattachment/';
                        $download_indicator = 'attachment_key';
                    } elseif ($type == 'ticket') {
                        $url           = site_url() . 'download/file/ticket/';
                        $key_indicator = 'ticketid';
                    } elseif ($type == 'customer') {
                        $url                = site_url() . 'download/file/client/';
                        $download_indicator = 'attachment_key';
                    } elseif ($type == 'expense') {
                        $url                = site_url() . 'download/file/expense/';
                        $download_indicator = 'rel_id';
                    } ?>
                <?php foreach ($attachment as $_att) {
                    ?>
                <tr
                    id="tr_file_<?= e($_att['id']); ?>">
                    <td>
                        <?php
                                               $path = $upload_path . $_att[$key_indicator] . '/' . $_att['file_name'];
                    $is_image                        = false;
                    if (! isset($_att['external'])) {
                        $attachment_url = $url . $_att[$download_indicator];
                        $is_image       = is_image($path);
                        $img_url        = site_url('download/preview_image?path=' . protected_file_url_by_path($path, true) . '&type=' . $_att['filetype']);
                        $lightBoxUrl    = site_url('download/preview_image?path=' . protected_file_url_by_path($path) . '&type=' . $_att['filetype']);
                    } elseif (isset($_att['external']) && ! empty($_att['external'])) {
                        if (! empty($_att['thumbnail_link']) && $_att['external'] == 'dropbox') {
                            $is_image = true;
                            $img_url  = optimize_dropbox_thumbnail($_att['thumbnail_link']);
                        }

                        $attachment_url = $_att['external_link'];
                    }
                    if ($is_image) {
                        echo '<div class="preview_image">';
                    } ?>
                        <a href="<?php if ($is_image) {
                            echo $lightBoxUrl ?? $img_url;
                        } else {
                            echo $attachment_url;
                        } ?>"
                            <?php if ($is_image) { ?>
                            data-lightbox="customer-profile"
                            <?php } ?> class="display-block mbot5">
                            <?php if ($is_image) { ?>
                            <div class="table-image">
                                <div class="text-center"><i class="fa fa-spinner fa-spin mtop30"></i></div>
                                <img src="#" class="img-table-loading"
                                    data-orig="<?= e($img_url); ?>">
                            </div>
                            <?php } else { ?>
                            <i
                                class="<?= get_mime_class($_att['filetype']); ?>"></i>
                            <?= e($_att['file_name']); ?>
                            <?php } ?>
                        </a>
                        <?php if ($is_image) {
                            echo '</div>';
                        } ?>
                    </td>
                    <td>
                        <div class="onoffswitch" <?php if ($type != 'customer') {?>
                            data-toggle="tooltip"
                            data-title="<?= _l('customer_attachments_show_notice'); ?>"
                            <?php } ?>>
                            <input type="checkbox" <?php if ($type != 'customer') {
                                echo 'disabled';
                            } ?>
                            id="<?= e($_att['id']); ?>"
                            data-id="<?= e($_att['id']); ?>"
                            class="onoffswitch-checkbox customer_file"
                            data-switch-url="<?= admin_url(); ?>misc/toggle_file_visibility"
                            <?php if (isset($_att['visible_to_customer']) && $_att['visible_to_customer'] == 1) {
                                echo 'checked';
                            } ?>>
                            <label class="onoffswitch-label"
                                for="<?= e($_att['id']); ?>"></label>
                        </div>
                        <?php if ($type == 'customer' && $_att['visible_to_customer'] == 1) {
                            $file_visibility_message = '';
                            $total_shares            = total_rows(db_prefix() . 'shared_customer_files', ['file_id' => $_att['id']]);

                            if ($total_shares == 0) {
                                $file_visibility_message = _l('file_share_visibility_notice');
                            } else {
                                $share_contacts_id = get_customer_profile_file_sharing(['file_id' => $_att['id']]);
                                if (count($share_contacts_id) == 0) {
                                    $file_visibility_message = _l('file_share_visibility_notice');
                                }
                            }
                            echo '<span class="text-warning' . (empty($file_visibility_message) || total_rows(db_prefix() . 'contacts', ['userid' => $client->userid]) == 0 ? ' hide' : '') . '">' . $file_visibility_message . '</span>';
                            if (isset($share_contacts_id) && count($share_contacts_id) > 0) {
                                $names             = '';
                                $contacts_selected = '';

                                foreach ($share_contacts_id as $file_share) {
                                    $names             .= e(get_contact_full_name($file_share['contact_id'])) . ', ';
                                    $contacts_selected .= $file_share['contact_id'] . ',';
                                }
                                if ($contacts_selected != '') {
                                    $contacts_selected = substr($contacts_selected, 0, -1);
                                }
                                if ($names != '') {
                                    echo '<a href="#" onclick="do_share_file_contacts(\'' . $contacts_selected . '\',' . $_att['id'] . '); return false;"><i class="fa-regular fa-pen-to-square"></i></a> ' . e(_l('share_file_with_show', mb_substr($names, 0, -2)));
                                }
                            }
                        } ?>
                    </td>
                    <td
                        data-order="<?= e($_att['dateadded']); ?>">
                        <?= e(_dt($_att['dateadded'])); ?>
                    </td>
                    <td>
                        <div class="tw-flex tw-items-center tw-space-x-2">
                            <?php if (! isset($_att['external'])) { ?>
                            <a href="#" data-toggle="modal"
                                data-file-name="<?= e($_att['file_name']); ?>"
                                data-filetype="<?= e($_att['filetype']); ?>"
                                data-path="<?= e($path); ?>"
                                data-target="#send_file"
                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 tw-mt-1">
                                <i class="fa-regular fa-envelope fa-lg"></i>
                            </a>
                            <?php } elseif (isset($_att['external']) && ! empty($_att['external'])) {
                                echo '<a href="' . $_att['external_link'] . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" target="_blank">' . ($_att['external'] == 'dropbox' ? '<i class="fa fa-dropbox fa-lg"></i>' : '<i class="fa-brands fa-google fa-lg"></i>') . '</a>';
                            } ?>
                            <?php if ($type == 'customer') { ?>
                            <a href="<?= admin_url('clients/delete_attachment/' . $_att['rel_id'] . '/' . $_att['id']); ?>"
                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                <i class="fa-regular fa-trash-can fa-lg"></i>
                            </a>
                            <?php } ?>
                        </div>
                    </td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$this->load->view('admin/clients/modals/send_file_modal');
} ?>