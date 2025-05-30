<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-md-12 mtop10">
        <?= form_open_multipart('', ['id' => 'task-form']); ?>
        <?= form_hidden('action', 'new_task'); ?>
        <?= form_hidden('task_id', $task->id); ?>
        <h2 class="tw-text-xl tw-font-semibold tw-mt-0" id="task-edit-heading">
            <?= e($task->name); ?>
        </h2>
        <div class="form-group">
            <label for="name">
                <?= _l('task_add_edit_subject'); ?>
            </label>
            <input type="text" name="name" id="name" class="form-control" required
                value="<?= e($task->name); ?>">
        </div>
        <div class="row">
            <div
                class="col-md-<?= $project->settings->view_milestones == 1 ? '6' : '12'; ?>">
                <div class="form-group">
                    <label for="priority" class="control-label">
                        <?= _l('task_add_edit_priority'); ?>
                    </label>
                    <select name="priority" class="selectpicker" id="priority" data-width="100%"
                        data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                        <option value="1" <?= $task->priority == 1 ? 'selected' : ''; ?>><?= _l('task_priority_low'); ?>
                        </option>
                        <option value="2" <?= $task->priority == 2 ? 'selected' : ''; ?>><?= _l('task_priority_medium'); ?>
                        </option>
                        <option value="3" <?= $task->priority == 3 ? 'selected' : ''; ?>><?= _l('task_priority_high'); ?>
                        </option>
                        <option value="4" <?= $task->priority == 4 ? 'selected' : ''; ?>><?= _l('task_priority_urgent'); ?>
                        </option>
                        <?php hooks()->apply_filters('task_priorities_select', $task); ?>
                    </select>
                </div>
            </div>
            <?php if ($project->settings->view_milestones == 1) { ?>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="milestone">
                        <?= _l('task_milestone'); ?>
                    </label>
                    <select name="milestone" id="milestone" class="selectpicker" data-width="100%"
                        data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                        <option value=""></option>
                        <?php foreach ($milestones as $milestone) { ?>
                        <option
                            value="<?= e($milestone['id']); ?>"
                            <?php if ($milestone['id'] == $task->milestone) {
                                echo ' selected';
                            } ?>>
                            <?= e($milestone['name']); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <?php } else {
                echo form_hidden('milestone', $task->milestone);
            } ?>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= render_date_input('startdate', 'task_add_edit_start_date', _d($task->startdate), ['required' => true]); ?>
            </div>
            <div class="col-md-6">
                <?= render_date_input('duedate', 'task_add_edit_due_date', $task->duedate, $project->deadline ? ['data-date-end-date' => $project->deadline] : []); ?>
            </div>
        </div>
        <?php if ($project->settings->view_team_members == 1) { ?>
        <div class="form-group">
            <label for="assignees">
                <?= _l('task_single_assignees_select_title'); ?>
            </label>
            <select class="selectpicker" multiple="true" name="assignees[]" id="assignees" data-width="100%"
                data-live-search="true">
                <?php foreach ($members as $member) { ?>
                <option
                    value="<?= e($member['staff_id']); ?>"
                    <?= $this->tasks_model->is_task_assignee($member['staff_id'], $task->id) ? 'selected' : ''; ?>>
                    <?= e(get_staff_full_name($member['staff_id'])); ?>
                </option>
                <?php } ?>
            </select>
        </div>
        <?php } ?>
        <div class="form-group">
            <label for="description">
                <?= _l('task_add_edit_description'); ?>
            </label>
            <textarea name="description" id="description" rows="10"
                class="form-control"><?= clear_textarea_breaks($task->description); ?></textarea>
        </div>
        <?= render_custom_fields('tasks', $task->id, ['show_on_client_portal' => 1]); ?>
        <button type="submit"
            class="btn btn-primary pull-right"><?= _l('submit'); ?></button>
        <?= form_close(); ?>
    </div>
</div>
<script>
    $(function() {
        $("#name").on('paste keyup', function() {
            $('#task-edit-heading').html($(this).val());
        });
    });
</script>