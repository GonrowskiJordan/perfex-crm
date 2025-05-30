<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?= form_open_multipart(admin_url('projects/upload_file/' . $project->id), ['class' => 'dropzone', 'id' => 'project-files-upload']); ?>
<input type="file" name="file" multiple />
<?= form_close(); ?>
<span
    class="tw-mt-4 tw-inline-block tw-text-sm"><?= _l('project_file_visible_to_customer'); ?></span><br />
<div class="onoffswitch">
    <input type="checkbox" name="visible_to_customer" id="pf_visible_to_customer" class="onoffswitch-checkbox">
    <label class="onoffswitch-label" for="pf_visible_to_customer"></label>
</div>
<div class="tw-flex tw-justify-end tw-items-center tw-space-x-2">
    <button class="gpicker" data-on-pick="projectFileGoogleDriveSave">
        <i class="fa-brands fa-google" aria-hidden="true"></i>
        <?= _l('choose_from_google_drive'); ?>
    </button>
    <div id="dropbox-chooser"></div>
</div>
<div class="clearfix"></div>
<div class="mtop20"></div>
<div class="modal fade bulk_actions" id="project_files_bulk_actions" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <?= _l('bulk_actions'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <?php if (is_admin()) { ?>
                <div class="checkbox checkbox-danger">
                    <input type="checkbox" name="mass_delete" id="mass_delete">
                    <label
                        for="mass_delete"><?= _l('mass_delete'); ?></label>
                </div>
                <hr class="mass_delete_separator" />
                <?php } ?>
                <div id="bulk_change">
                    <div class="form-group">
                        <label
                            class="mtop5"><?= _l('project_file_visible_to_customer'); ?></label>
                        <div class="onoffswitch">
                            <input type="checkbox" name="bulk_visible_to_customer" id="bulk_pf_visible_to_customer"
                                class="onoffswitch-checkbox">
                            <label class="onoffswitch-label" for="bulk_pf_visible_to_customer"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                    data-dismiss="modal"><?= _l('close'); ?></button>
                <a href="#" class="btn btn-primary"
                    onclick="project_files_bulk_action(this); return false;"><?= _l('confirm'); ?></a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<a href="#" data-toggle="modal" data-target="#project_files_bulk_actions" class="bulk-actions-btn table-btn hide"
    data-table=".table-project-files">
    <?= _l('bulk_actions'); ?>
</a>
<a href="#"
    onclick="window.location.href = '<?= admin_url('projects/download_all_files/' . $project->id); ?>'; return false;"
    class="table-btn hide"
    data-table=".table-project-files"><?= _l('download_all'); ?></a>
<div class="clearfix"></div>
<div class="panel_s panel-table-full">
    <div class="panel-body">
        <table class="table dt-table table-project-files" data-order-col="7" data-order-type="desc">
            <thead>
                <tr>
                    <th data-orderable="false"><span class="hide"> - </span>
                        <div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all"
                                data-to-table="project-files"><label></label></div>
                    </th>
                    <th><?= _l('project_file_filename'); ?>
                    </th>
                    <th><?= _l('project_file__filetype'); ?>
                    </th>
                    <th><?= _l('project_discussion_last_activity'); ?>
                    </th>
                    <th><?= _l('project_discussion_total_comments'); ?>
                    </th>
                    <th><?= _l('project_file_visible_to_customer'); ?>
                    </th>
                    <th><?= _l('project_file_uploaded_by'); ?>
                    </th>
                    <th><?= _l('project_file_dateadded'); ?>
                    </th>
                    <th><?= _l('options'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file) {
                    $path = get_upload_path_by_type('project') . $project->id . '/' . $file['file_name']; ?>
                <tr>
                    <td>
                        <div class="checkbox"><input type="checkbox"
                                value="<?= e($file['id']); ?>"><label></label>
                        </div>
                    </td>
                    <td
                        data-order="<?= e($file['file_name']); ?>">
                        <a href="#"
                            onclick="view_project_file(<?= e($file['id']); ?>,<?= e($file['project_id']); ?>); return false;">
                            <?php if (is_image(PROJECT_ATTACHMENTS_FOLDER . $project->id . '/' . $file['file_name']) || (! empty($file['external']) && ! empty($file['thumbnail_link']))) {
                                echo '<div class="text-left"><i class="fa fa-spinner fa-spin mtop30"></i></div>';
                                echo '<img class="project-file-image img-table-loading" src="#" data-orig="' . e(project_file_url($file, true)) . '" width="100">';
                                echo '</div>';
                            }
                    echo $file['subject']; ?></a>
                    </td>
                    <td
                        data-order="<?= e($file['filetype']); ?>">
                        <?= e($file['filetype']); ?>
                    </td>
                    <td
                        data-order="<?= e($file['last_activity']); ?>">
                        <?php
                            if (! is_null($file['last_activity'])) { ?>
                        <span class="text-has-action" data-toggle="tooltip"
                            data-title="<?= e(_dt($file['last_activity'])); ?>">
                            <?= e(time_ago($file['last_activity'])); ?>
                        </span>
                        <?php } else {
                            echo _l('project_discussion_no_activity');
                        } ?>
                    </td>
                    <?php $total_file_comments = total_rows(db_prefix() . 'projectdiscussioncomments', ['discussion_id' => $file['id'], 'discussion_type' => 'file']); ?>
                    <td data-order="<?= e($total_file_comments); ?>">
                        <?= e($total_file_comments); ?>
                    </td>
                    <td
                        data-order="<?= e($file['visible_to_customer']); ?>">
                        <?php
                        $checked = '';
                    if ($file['visible_to_customer'] == 1) {
                        $checked = 'checked';
                    } ?>
                        <div class="onoffswitch">
                            <input type="checkbox"
                                data-switch-url="<?= admin_url(); ?>projects/change_file_visibility"
                                id="<?= e($file['id']); ?>"
                                data-id="<?= e($file['id']); ?>"
                                class="onoffswitch-checkbox"
                                value="<?= e($file['id']); ?>"
                                <?= e($checked); ?>>
                            <label class="onoffswitch-label"
                                for="<?= e($file['id']); ?>"></label>
                        </div>

                    </td>
                    <td>
                        <?php if ($file['staffid'] != 0) {
                            $_data = '<a href="' . admin_url('staff/profile/' . $file['staffid']) . '">' . staff_profile_image($file['staffid'], [
                                'staff-profile-image-small',
                            ]) . '</a>';
                            $_data .= ' <a href="' . admin_url('staff/member/' . $file['staffid']) . '">' . e(get_staff_full_name($file['staffid'])) . '</a>';
                            echo $_data;
                        } else {
                            echo ' <img src="' . e(contact_profile_image_url($file['contact_id'], 'thumb')) . '" class="client-profile-image-small mrigh5">
             <a href="' . admin_url('clients/client/' . get_user_id_by_contact_id($file['contact_id']) . '?contactid=' . $file['contact_id']) . '">' . e(get_contact_full_name($file['contact_id'])) . '</a>';
                        } ?>
                    </td>
                    <td
                        data-order="<?= e($file['dateadded']); ?>">
                        <?= e(_dt($file['dateadded'])); ?>
                    </td>
                    <td>
                        <div class="tw-flex tw-items-center tw-space-x-2">
                            <?php if (empty($file['external'])) {
                                $file_name = $file['original_file_name'] != '' ? $file['original_file_name'] : $file['file_name']; ?>
                            <a href="#" data-toggle="modal"
                                data-original-file-name="<?= e($file_name); ?>"
                                data-filetype="<?= e($file['filetype']); ?>"
                                data-file-name="<?= e($file['original_file_name']); ?>"
                                data-path="<?= PROJECT_ATTACHMENTS_FOLDER . $project->id . '/' . $file['file_name']; ?>"
                                data-target="#send_file"
                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 tw-mt-1">
                                <i class="fa-regular fa-envelope fa-lg"></i>
                            </a>
                            <?php
                            } ?>
                            <?php if ($file['staffid'] == get_staff_user_id() || staff_can('delete', 'projects')) { ?>
                            <a href="<?= admin_url('projects/remove_file/' . $project->id . '/' . $file['id']); ?>"
                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                <i class="fa-regular fa-trash-can fa-lg"></i>
                            </a>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <?php
                } ?>
            </tbody>
        </table>
    </div>
</div>
<div id="project_file_data"></div>
<?php include_once APPPATH . 'views/admin/clients/modals/send_file_modal.php'; ?>