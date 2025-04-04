<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="task">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="tw-flex tw-items-center">
                        <h3 class="tw-font-semibold tw-text-xl tw-my-0 tw-flex tw-items-center">
                            <?php if (
                                $project->settings->edit_tasks == 1
                                && $view_task->is_added_from_contact == 1
                                && $view_task->addedfrom == get_contact_user_id()
                                && $view_task->billed == 0
                            ) { ?>
                            <a href="<?= site_url('clients/project/' . $project->id . '?group=edit_task&taskid=' . $view_task->id); ?>"
                                class="tw-text-neutral-500 hover:tw-text-neutral-700 active:tw-text-neutral-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            <?php } ?>
                            <?= e($view_task->name); ?>
                        </h3>
                        <span class="task-single-status tw-ml-3 tw-mt-0.5">
                            <?= format_task_status($view_task->status); ?>
                        </span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="task-info pull-left no-p-left">
                        <h5 class="no-margin">
                            <?= _l('task_single_priority'); ?>:
                            <?= e(task_priority($view_task->priority)); ?>
                        </h5>
                    </div>
                    <div class="text-success task-info pull-left">
                        <h5 class="no-margin">
                            <?= _l('task_single_start_date'); ?>:
                            <?= e(_d($view_task->startdate)); ?>
                        </h5>
                    </div>
                    <div
                        class="task-info pull-left <?= $view_task->status != Tasks_model::STATUS_COMPLETE ? 'text-danger' : 'text-info'; ?><?= ! $view_task->duedate ? ' hide' : ''; ?>">
                        <h5 class="no-margin">
                            <?= _l('task_single_due_date'); ?>:
                            <?= e(_d($view_task->duedate)); ?>
                        </h5>
                    </div>
                    <?php if ($view_task->status == Tasks_model::STATUS_COMPLETE) { ?>
                    <div class="pull-left task-info text-success">
                        <h5 class="no-margin">
                            <?= _l('task_single_finished'); ?>:
                            <?= e(_dt($view_task->datefinished)); ?>
                        </h5>
                    </div>
                    <?php } ?>
                    <?php if ($project->settings->view_task_total_logged_time == 1) { ?>
                    <div class="pull-left task-info">
                        <h5 class="no-margin">
                            <?= _l('task_total_logged_time'); ?>
                            <?= e(seconds_to_time_format($this->tasks_model->calc_task_total_time($view_task->id))); ?>
                        </h5>
                    </div>
                    <?php } ?>
                    <?php if ($view_task->billed == 1) { ?>
                    <div class="clearfix"></div>
                    <p class="no-mbot mtop15">
                        <?= e(_l('task_is_billed', format_invoice_number($view_task->invoice_id))); ?>
                    </p>
                    <?php } ?>
                </div>
            </div>
            <?php if ($project->settings->view_team_members == 1) { ?>
            <div class="clearfix"></div>
            <hr />
            <div class="row mbot20">
                <div class="col-md-3">
                    <i class="fa-regular fa-user"></i> <span
                        class="bold"><?= _l('task_single_assignees'); ?></span>
                </div>
                <div class="col-md-9" id="assignees">
                    <?php $_assignees = ''; ?>

                    <?php foreach ($view_task->assignees as $assignee) {
                        $_assignees .= '
        <div data-toggle="tooltip" class="pull-left mleft5 task-user" data-title="' . e(get_staff_full_name($assignee['assigneeid'])) . '">'
                        . staff_profile_image($assignee['assigneeid'], ['staff-profile-image-small']) . '</div>';
                    }

                echo $_assignees ?: '<div class="task-connectors-no-indicator display-block">' . _l('task_no_assignees') . '</div>';
                ?>
                </div>
            </div>
            <?php } ?>
            <?php if ($project->settings->view_task_checklist_items == 1) { ?>
            <?php if (count($view_task->checklist_items) > 0) { ?>
            <hr />
            <h4 class="bold mbot15">
                <?= _l('task_checklist_items'); ?>
            </h4>
            <?php } ?>
            <?php foreach ($view_task->checklist_items as $list) { ?>
            <p
                class="<?= $list['finished'] == 1 ? 'line-throught' : ''; ?>">
                <span
                    class="task-checklist-indicator <?= $list['finished'] == 1 ? 'text-success' : 'text-muted'; ?>">
                    <i class="fa fa-check"></i>
                </span>&nbsp;
                <?= e($list['description']); ?>
            </p>
            <?php } ?>
            <?php } ?>
            <?php $custom_fields = get_custom_fields('tasks', ['show_on_client_portal' => 1]); ?>
            <?php if (count($custom_fields) > 0) { ?>
            <div class="row">
                <?php foreach ($custom_fields as $field) { ?>
                <?php $value = get_custom_field_value($view_task->id, $field['id'], 'tasks');
                    if ($value == '') {
                        continue;
                    } $custom_fields_found = true; ?>
                <div class="col-md-9">
                    <span
                        class="bold"><?= e(ucfirst($field['name'])); ?></span>
                    <br />
                    <div class="text-left">
                        <?= $value; ?>
                    </div>
                </div>
                <?php } ?>
                <?php
                    // Add separator if custom fields found for output
                    if (isset($custom_fields_found)) {?>
                <div class="clearfix"></div>
                <hr />
                <?php } ?>
            </div>
            <?php } ?>
            <?php if ($project->settings->view_task_attachments == 1) { ?>
            <?php
            $attachments_data                    = [];
                $comments_attachments            = [];
                $i                               = 1;
                $show_more_link_task_attachments = hooks()->apply_filters('show_more_link_task_attachments_customers_area', 3);
                if (count($view_task->attachments) > 0) { ?>
            <hr />
            <div class="row task_attachments_wrapper">
                <div class="col-md-12">
                    <h4 class="bold font-medium">
                        <?= _l('task_view_attachments'); ?>
                    </h4>
                    <div class="row">
                        <?php foreach ($view_task->attachments as $attachment) {
                            ob_start(); ?>
                        <div class="col-md-4 task-attachment-col<?= $i > $show_more_link_task_attachments ? ' task-attachment-col-more' : ''; ?>"
                            data-num="<?= e($i); ?>" <?= $i > $show_more_link_task_attachments ? 'style="display:none;"' : ''; ?>>

                            <ul class="list-unstyled">
                                <li class="mbot10 task-attachment">
                                    <div class="mbot10 pull-right task-attachment-user">
                                        <?= e(_l('project_file_uploaded_by') . ' ' . ($attachment['staffid'] != 0 ? get_staff_full_name($attachment['staffid']) : get_contact_full_name($attachment['contact_id']))); ?>
                                        <?php if (get_option('allow_contact_to_delete_files') == 1 && $attachment['contact_id'] == get_contact_user_id()) { ?>
                                        <a href="<?= site_url('clients/delete_file/' . $attachment['id'] . '/task?project_id=' . $project->id); ?>"
                                            class="text-danger _delete pull-right"><i class="fa fa-remove"></i></a>
                                        <?php } ?>
                                    </div>
                                    <?php
                                                                                                                                                                                          $externalPreview = false;
                            $is_image                                                                                                                                                                      = false;
                            $path                                                                                                                                                                          = get_upload_path_by_type('task') . $view_task->id . '/' . $attachment['file_name'];
                            $href_url                                                                                                                                                                      = site_url('download/file/taskattachment/' . $attachment['attachment_key']);
                            $isHtml5Video                                                                                                                                                                  = is_html5_video($path);
                            if (empty($attachment['external'])) {
                                $is_image = is_image($path);
                                $img_url  = site_url('download/preview_image?path=' . protected_file_url_by_path($path, true) . '&type=' . $attachment['filetype']);
                            } elseif ((! empty($attachment['thumbnail_link']) || ! empty($attachment['external']))
                                           && ! empty($attachment['thumbnail_link'])) {
                                $is_image        = true;
                                $img_url         = optimize_dropbox_thumbnail($attachment['thumbnail_link']);
                                $externalPreview = $img_url;
                                $href_url        = $attachment['external_link'];
                            } elseif (! empty($attachment['external']) && empty($attachment['thumbnail_link'])) {
                                $href_url = $attachment['external_link'];
                            }
                            if (! empty($attachment['external']) && $attachment['external'] == 'dropbox' && $is_image) { ?>
                                    <a href="<?= e($href_url); ?>"
                                        target="_blank" class="open-in-external" data-toggle="tooltip"
                                        data-title="<?= _l('open_in_dropbox'); ?>"><i
                                            class="fa fa-dropbox" aria-hidden="true"></i></a>
                                    <?php } elseif (! empty($attachment['external']) && $attachment['external'] == 'gdrive') { ?>
                                    <a href="<?= e($href_url); ?>"
                                        target="_blank" class="open-in-external" data-toggle="tooltip"
                                        data-title="<?= _l('open_in_google'); ?>"><i
                                            class="fa-brands fa-google" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <div class="<?php if ($is_image) {
                                        echo 'preview-image';
                                    } elseif (! $isHtml5Video) {
                                        echo 'task-attachment-no-preview';
                                    } ?>">
                                        <?php if (! $isHtml5Video) { ?>
                                        <a href="<?= ! $externalPreview ? $href_url : $externalPreview; ?>"
                                            target="_blank"
                                            <?php if ($is_image) { ?>
                                            data-lightbox="task-attachment"
                                            <?php } ?> class="<?php if ($isHtml5Video) {
                                                echo 'video-preview';
                                            } ?>">
                                            <?php } ?>
                                            <?php if ($is_image) { ?>
                                            <img src="<?= e($img_url); ?>"
                                                class="img img-responsive">
                                            <?php } elseif ($isHtml5Video) { ?>
                                            <video width="100%" height="100%"
                                                src="<?= site_url('download/preview_video?path=' . protected_file_url_by_path($path) . '&type=' . $attachment['filetype']); ?>"
                                                controls>
                                                Your browser does not support the video tag.
                                            </video>
                                            <?php } else { ?>
                                            <i
                                                class="<?= get_mime_class($attachment['filetype']); ?>"></i>
                                            <?= e($attachment['file_name']); ?>
                                            <?php } ?>
                                            <?php if (! $isHtml5Video) { ?>
                                        </a>
                                        <?php } ?>
                                    </div>
                                    <div class="clearfix"></div>
                                </li>
                            </ul>
                        </div>
                        <?php
                                                 $attachments_data[$attachment['id']] = ob_get_contents();
                            if ($attachment['task_comment_id'] != 0) {
                                $comments_attachments[$attachment['task_comment_id']][$attachment['id']] = $attachments_data[$attachment['id']];
                            }
                            ob_end_clean();
                            echo $attachments_data[$attachment['id']];
                            $i++; ?>
                        <?php
                        } ?>
                    </div>
                    <?php if (($i - 1) > $show_more_link_task_attachments) { ?>
                    <div id="show-more-less-task-attachments-col">
                        <a href="#" class="task-attachments-more"
                            onclick="slideToggle('.task_attachments_wrapper .task-attachment-col-more', task_attachments_toggle); return false;"><?= _l('show_more'); ?></a>
                        <a href="#" class="task-attachments-less hide"
                            onclick="slideToggle('.task_attachments_wrapper .task-attachment-col-more', task_attachments_toggle); return false;"><?= _l('show_less'); ?></a>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <?php } ?>
            <?php if (! empty($view_task->description)) { ?>
            <hr />
            <h4 class="bold">
                <?= _l('task_view_description'); ?>
            </h4>
            <div class="tc-content">
                <?= $view_task->description; ?>
            </div>
            <?php } ?>
            <?php if ($project->settings->upload_on_tasks == 1) { ?>
            <hr />
            <?= form_open_multipart(site_url('clients/project/' . $project->id), ['class' => 'dropzone mtop15', 'id' => 'task-file-upload']); ?>
            <input type="file" name="file" multiple class="hide" />
            <input type="hidden" name="task_id"
                value="<?= e($view_task->id); ?>" class="hide" />
            <?= form_close(); ?>
            <div class="tw-flex tw-justify-end tw-items-center tw-space-x-2 mtop15">
                <button class="gpicker" data-on-pick="taskFileGoogleDriveSave">
                    <i class="fa-brands fa-google" aria-hidden="true"></i>
                    <?= _l('choose_from_google_drive'); ?>
                </button>
                <div id="dropbox-chooser-task"></div>
            </div>
            <?php } ?>
            <?php if ($project->settings->view_task_comments == 1) { ?>
            <hr />
            <h4 class="bold mbot15">
                <?= _l('task_view_comments'); ?>
            </h4>
            <?php
                        if ($project->settings->comment_on_tasks == 1) {
                            echo form_open($this->uri->uri_string());
                            echo form_hidden('action', 'new_task_comment');
                            echo form_hidden('taskid', $view_task->id); ?>
            <textarea name="content" rows="5" class="form-control mtop15"></textarea>
            <button type="submit" class="btn btn-primary mtop10 pull-right"
                data-loading-text="<?= _l('wait_text'); ?>"
                autocomplete="off"><?= _l('task_single_add_new_comment'); ?></button>
            <div class="clearfix"></div>
            <?= form_close();
                        } ?>
            <?php
                        if (count($view_task->comments) > 0) {
                            echo '<div id="task-comments">';

                            foreach ($view_task->comments as $comment) { ?>
            <div class="mbot10 mtop10 task-comment"
                data-commentid="<?= e($comment['id']); ?>"
                id="comment_<?= e($comment['id']); ?>">
                <?php if ($comment['staffid'] != 0) { ?>
                <?= staff_profile_image($comment['staffid'], [
                    'staff-profile-image-small',
                    'media-object img-circle pull-left mright10',
                ]); ?>
                <?php } else { ?>
                <img src="<?= e(contact_profile_image_url($comment['contact_id'])); ?>
" class="client-profile-image-small media-object img-circle pull-left mright10">
                <?php } ?>
                <div class="media-body">
                    <?php if ($comment['staffid'] != 0) { ?>
                    <span
                        class="bold"><?= e($comment['staff_full_name']); ?></span><br />
                    <?php } else { ?>
                    <span class="pull-left bold">
                        <?= e(get_contact_full_name($comment['contact_id'])); ?></span>
                    <br />
                    <?php } ?>
                    <small
                        class="mtop10 text-muted"><?= e(_dt($comment['dateadded'])); ?></small><br />
                    <?php if ($comment['contact_id'] != 0) { ?>
                    <?php
                              $comment_added = strtotime($comment['dateadded']);
                        $minus_1_hour        = strtotime('-1 hours');
                        if (get_option('client_staff_add_edit_delete_task_comments_first_hour') == 0 || (get_option('client_staff_add_edit_delete_task_comments_first_hour') == 1 && $comment_added >= $minus_1_hour)) { ?>
                    <a href="#"
                        onclick="remove_task_comment(<?= e($comment['id']); ?>); return false;"
                        class="pull-right">
                        <i class="fa fa-times text-danger"></i>
                    </a>
                    <a href="#"
                        onclick="edit_task_comment(<?= e($comment['id']); ?>); return false;"
                        class="pull-right mright5">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                    <div data-edit-comment="<?= e($comment['id']); ?>"
                        class="hide">
                        <textarea rows="5"
                            class="form-control mtop10 mbot10"><?= clear_textarea_breaks($comment['content']); ?></textarea>
                        <button type="button" class="btn btn-primary pull-right"
                            onclick="save_edited_comment(<?= e($comment['id']); ?>)">
                            <?= _l('submit'); ?>
                        </button>
                        <button type="button" class="btn btn-default pull-right mright5"
                            onclick="cancel_edit_comment(<?= e($comment['id']); ?>)">
                            <?= _l('cancel'); ?>
                        </button>
                    </div>
                    <?php } ?>
                    <?php } ?>
                    <div class="comment-content"
                        data-comment-content="<?= $comment['id']; ?>">
                        <?php
                           if ($comment['file_id'] != 0 && $project->settings->view_task_attachments == 1) {
                               $comment['content'] = str_replace('[task_attachment]', '<br />' . $attachments_data[$comment['file_id']], $comment['content']);
                               // Replace lightbox to prevent loading the image twice
                               $comment['content'] = str_replace('data-lightbox="task-attachment"', 'data-lightbox="task-attachment-comment-' . $comment['id'] . '"', $comment['content']);
                           } elseif (count($comment['attachments']) > 0 && isset($comments_attachments[$comment['id']])) {
                               $comment_attachments_html = '';

                               foreach ($comments_attachments[$comment['id']] as $comment_attachment) {
                                   $comment_attachments_html .= trim($comment_attachment);
                               }
                               $comment['content'] = str_replace('[task_attachment]', '<div class="clearfix"></div>' . $comment_attachments_html, $comment['content']);
                               // Replace lightbox to prevent loading the image twice
                               $comment['content'] = str_replace('data-lightbox="task-attachment"', 'data-lightbox="task-comment-files-' . $comment['id'] . '"', $comment['content']);
                               $comment['content'] .= '<div class="clearfix"></div>';
                           }
                                echo check_for_links($comment['content']); ?>
                    </div>
                </div>
                <hr />
            </div>
            <?php }
                            }
                ?>
        </div>
        <?php } ?>
    </div>
</div>
</div>
<script>
    var task_comment_temp = window.location.href.split('#');
    if (task_comment_temp[1]) {
        var task_comment_id = task_comment_temp[task_comment_temp.length - 1];
        $('html,body').animate({
                scrollTop: $('#' + task_comment_id).offset().top
            },
            'slow');
    }
</script>