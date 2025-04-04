<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="_hidden_inputs _filters _tasks_filters">
    <?php
    $tasks_filter_assignees = $this->misc_model->get_tasks_distinct_assignees();
hooks()->do_action('tasks_filters_hidden_html');
echo form_hidden('my_tasks', (staff_cant('view', 'tasks') ? 'true' : ''));
echo form_hidden('my_following_tasks');
echo form_hidden('billable');
echo form_hidden('billed');
echo form_hidden('not_billed');
echo form_hidden('not_assigned');
echo form_hidden('due_date_passed');
echo form_hidden('upcoming_tasks');
echo form_hidden('recurring_tasks');
echo form_hidden('today_tasks');

// Related task filter - used in customer profile
echo form_hidden('tasks_related_to');

if (staff_can('view', 'tasks')) {
    foreach ($tasks_filter_assignees as $tf_assignee) {
        echo form_hidden('task_assigned_' . $tf_assignee['assigneeid']);
    }
}

foreach ($task_statuses as $status) {
    $val = 'true';
    if ($status['filter_default'] == false) {
        $val = '';
    }
    echo form_hidden('task_status_' . $status['id'], $val);
}

?>
</div>

<div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data" data-toggle="tooltip"
    data-title="<?= _l('filter_by'); ?>">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa fa-filter" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu width300">
        <li>
            <a href="#" data-cview="all"
                onclick="dt_custom_view('','<?= e($view_table_name); ?>',''); return false;">
                <?= _l('task_list_all'); ?>
            </a>
        </li>
        <li class="divider"></li>
        <?php foreach ($task_statuses as $status) { ?>
        <li class="clear-all-prevent<?php if ($status['filter_default'] == true) {
            echo ' active';
        } ?>">
            <a href="#"
                data-cview="task_status_<?= e($status['id']); ?>"
                onclick="dt_custom_view('task_status_<?= e($status['id']); ?>','<?= e($view_table_name); ?>','task_status_<?= e($status['id']); ?>'); return false;">
                <?= e($status['name']); ?>
            </a>
        </li>
        <?php } ?>
        <li class="divider"></li>
        <li class="filter-group" data-filter-group="group-date">
            <a href="#" data-cview="today_tasks"
                onclick="dt_custom_view('today_tasks','<?= e($view_table_name); ?>','today_tasks'); return false;">
                <?= _l('todays_tasks'); ?>
            </a>
        </li>
        <li class="filter-group" data-filter-group="group-date">
            <a href="#" data-cview="due_date_passed"
                onclick="dt_custom_view('due_date_passed','<?= e($view_table_name); ?>','due_date_passed'); return false;">
                <?= _l('task_list_duedate_passed'); ?>
            </a>
        </li>
        <li class="filter-group" data-filter-group="group-date">
            <a href="#" data-cview="upcoming_tasks"
                onclick="dt_custom_view('upcoming_tasks','<?= e($view_table_name); ?>','upcoming_tasks'); return false;">
                <?= _l('upcoming_tasks'); ?>
            </a>
        </li>
        <li class="divider"></li>
        <li class="filter-group <?= staff_cant('view', 'tasks') ? ' active' : ''; ?>"
            data-filter-group="assigned-follower-not-assigned">
            <a href="#" data-cview="my_tasks"
                onclick="dt_custom_view('my_tasks','<?= e($view_table_name); ?>','my_tasks'); return false;">
                <?= _l('tasks_view_assigned_to_user'); ?>
            </a>
        </li>
        <li class="filter-group" data-filter-group="assigned-follower-not-assigned">
            <a href="#" data-cview="my_following_tasks"
                onclick="dt_custom_view('my_following_tasks','<?= e($view_table_name); ?>','my_following_tasks'); return false;">
                <?= _l('tasks_view_follower_by_user'); ?>
            </a>
        </li>
        <?php if (staff_can('view', 'tasks')) { ?>
        <li class="filter-group" data-filter-group="assigned-follower-not-assigned">
            <a href="#" data-cview="not_assigned"
                onclick="dt_custom_view('not_assigned','<?= e($view_table_name); ?>','not_assigned'); return false;">
                <?= _l('task_list_not_assigned'); ?>
            </a>
        </li>
        <?php } ?>
        <?php if (staff_can('create', 'tasks') || staff_can('edit', 'tasks')) { ?>
        <li>
            <a href="#" data-cview="recurring_tasks"
                onclick="dt_custom_view('recurring_tasks','<?= e($view_table_name); ?>','recurring_tasks'); return false;">
                <?= _l('recurring_tasks'); ?>
            </a>
        </li>
        <?php } ?>
        <?php if (staff_can('create', 'invoices')) { ?>
        <li class="divider"></li>
        <li class="filter-group" data-filter-group="group-billable">
            <a href="#" data-cview="billable"
                onclick="dt_custom_view('billable','<?= e($view_table_name); ?>','billable'); return false;">
                <?= _l('task_billable'); ?>
            </a>
        </li>
        <li class="filter-group" data-filter-group="group-billable">
            <a href="#" data-cview="billed"
                onclick="dt_custom_view('billed','<?= e($view_table_name); ?>','billed'); return false;">
                <?= _l('task_billed'); ?>
            </a>
        </li>
        <li class="filter-group" data-filter-group="group-billable">
            <a href="#" data-cview="not_billed"
                onclick="dt_custom_view('not_billed','<?= e($view_table_name); ?>','not_billed'); return false;">
                <?= _l('task_billed_no'); ?>
            </a>
        </li>
        <?php } ?>
        <?php if (staff_can('view', 'tasks')) { ?>
        <?php if (count($tasks_filter_assignees)) { ?>
        <div class="clearfix"></div>
        <li class="divider"></li>
        <li class="dropdown-submenu pull-left">
            <a href="#"
                tabindex="-1"><?= _l('filter_by_assigned'); ?></a>
            <ul class="dropdown-menu dropdown-menu-left">
                <?php foreach ($tasks_filter_assignees as $as) { ?>
                <li>
                    <a href="#"
                        data-cview="task_assigned_<?= e($as['assigneeid']); ?>"
                        onclick="dt_custom_view(<?= e($as['assigneeid']); ?>,'<?= e($view_table_name); ?>','task_assigned_<?= e($as['assigneeid']); ?>'); return false;"><?= e($as['full_name']); ?></a>
                </li>
                <?php } ?>
            </ul>
        </li>
        <?php } ?>

        <?php } ?>

    </ul>
</div>