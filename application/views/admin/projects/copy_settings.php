<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Copy Project -->
<div class="modal fade" id="copy_project" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?= form_open(admin_url('projects/copy/' . (isset($project) ? $project->id : '')), ['id' => 'copy_form', 'data-copy-url' => admin_url('projects/copy/')]); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <?= _l('copy_project'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" class="copy" name="tasks" id="c_tasks" checked>
                            <label
                                for="c_tasks"><?= _l('tasks'); ?></label>
                        </div>
                        <div class="checkbox checkbox-primary mleft10 tasks-copy-option">
                            <input type="checkbox" name="tasks_include_checklist_items"
                                id="tasks_include_checklist_items" checked>
                            <label
                                for="tasks_include_checklist_items"><small><?= _l('copy_project_task_include_check_list_items'); ?></small></label>
                        </div>
                        <div class="checkbox checkbox-primary mleft10 tasks-copy-option">
                            <input type="checkbox" name="task_include_assignees" id="task_include_assignees" checked>
                            <label
                                for="task_include_assignees"><small><?= _l('copy_project_task_include_assignees'); ?></small></label>
                        </div>
                        <div class="checkbox checkbox-primary mleft10 tasks-copy-option">
                            <input type="checkbox" name="task_include_followers"
                                id="copy_project_task_include_followers" checked>
                            <label
                                for="copy_project_task_include_followers"><small><?= _l('copy_project_task_include_followers'); ?></small></label>
                        </div>
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" name="milestones" id="c_milestones" checked>
                            <label
                                for="c_milestones"><?= _l('project_milestones'); ?></label>
                        </div>
                        <div class="checkbox checkbox-primary">
                            <input type="checkbox" name="members" id="c_members" class="copy" checked>
                            <label
                                for="c_members"><?= _l('project_members'); ?></label>
                        </div>
                        <hr />
                        <div class="copy-project-tasks-status-wrapper">
                            <p class="bold">
                                <?= _l('copy_project_tasks_status'); ?>
                            </p>
                            <?php foreach ($task_statuses as $cp_task_status) { ?>
                            <div class="radio radio-primary">
                                <input type="radio" name="copy_project_task_status"
                                    value="<?= e($cp_task_status['id']); ?>"
                                    id="cp_task_status_<?= e($cp_task_status['id']); ?>"
                                    <?php if ($cp_task_status['id'] == '1') {
                                        echo ' checked';
                                    } ?>>
                                <label
                                    for="cp_task_status_<?= e($cp_task_status['id']); ?>"><?= e($cp_task_status['name']); ?></label>
                            </div>
                            <?php } ?>
                            <hr />
                        </div>
                        <?= render_input('name', 'project_name', (isset($project) ? $project->name : '')); ?>
                        <div class="form-group">
                            <label
                                for="clientid_copy_project"><?= _l('project_customer'); ?></label>
                            <select id="clientid_copy_project" name="clientid_copy_project" data-live-search="true"
                                data-width="100%" class="ajax-search"
                                data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= render_date_input('start_date', 'project_start_date', _d(date('Y-m-d'))); ?>
                            </div>
                            <div class="col-md-6">
                                <?= render_date_input('deadline', 'project_deadline'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                    data-dismiss="modal"><?= _l('close'); ?></button>
                <button type="submit" data-form="#copy_form" autocomplete="off"
                    data-loading-text="<?= _l('wait_text'); ?>"
                    class="btn btn-primary"><?= _l('copy_project'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
        <?= form_close(); ?>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Copy Project end -->
<script>
    // Copy project modal and set url if ID is passed manually eq from project list area
    function copy_project(id, el) {

        $('#copy_project').modal('show');

        if (typeof(id) != 'undefined') {
            $('#copy_form').attr('action', $('#copy_form').data('copy-url') + id);
        }

        if (typeof el != 'undefined') {
            let name = $(el).data('name');
            $('#copy_form input[name="name"]').val(name);
        }

        appValidateForm($('#copy_form'), {
            name: 'required',
            start_date: 'required',
            clientid_copy_project: 'required',
        });

        var copy_members = $('#c_members');
        var copy_tasks = $('input[name="tasks"].copy');
        var copy_assignees_and_followers = $(
            'input[name="task_include_assignees"],input[name="task_include_followers"]');

        copy_members.off('change');
        copy_tasks.off('change');
        copy_assignees_and_followers.off('change');

        copy_members.on('change', function() {
            if (!$(this).prop('checked')) {
                copy_assignees_and_followers.prop('checked', false)
            }
        });

        copy_tasks.on('change', function() {
            var checked = $(this).prop('checked');
            if (checked) {

                var copy_assignees = $('input[name="task_include_assignees"]').prop('checked');
                var copy_followers = $('input[name="task_include_followers"]').prop('checked');

                if (copy_assignees || copy_followers) {
                    $('input[name="members"].copy').prop('checked', true);
                }

                $('.copy-project-tasks-status-wrapper').removeClass('hide');
                $('.tasks-copy-option').removeClass('hide');

            } else {
                $('.copy-project-tasks-status-wrapper').addClass('hide');
                $('.tasks-copy-option').addClass('hide');
            }
        });

        copy_assignees_and_followers.on('change', function() {
            var checked = $(this).prop('checked');
            if (checked == true) {
                $('input[name="members"].copy').prop('checked', true);
            }
        });
    }
</script>