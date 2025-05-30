<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="tw-mt-0 tw-font-bold tw-text-lg tw-text-neutral-700 tickets-summary-heading">
    <?= _l('projects_summary'); ?>
</h4>

<div class="tw-mb-2">
    <?php get_template_part('projects/project_summary'); ?>
</div>

<h4 class="tw-mt-0 tw-mb-3 tw-font-semibold tw-text-lg tw-text-neutral-700 section-heading section-heading-projects">
    <?= _l('clients_my_projects'); ?>
</h4>

<div class="panel_s">
    <div class="panel-body">
        <table class="table dt-table table-projects" data-order-col="2" data-order-type="desc">
            <thead>
                <tr>
                    <th class="th-project-name">
                        <?= _l('project_name'); ?>
                    </th>
                    <th class="th-project-start-date">
                        <?= _l('project_start_date'); ?>
                    </th>
                    <th class="th-project-deadline">
                        <?= _l('project_deadline'); ?>
                    </th>
                    <th class="th-project-billing-type">
                        <?= _l('project_billing_type'); ?>
                    </th>
                    <?php
                     $custom_fields = get_custom_fields('projects', ['show_on_client_portal' => 1]);

foreach ($custom_fields as $field) { ?>
                    <th><?= e($field['name']); ?>
                    </th>
                    <?php } ?>
                    <th><?= _l('project_status'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project) { ?>
                <tr>
                    <td><a
                            href="<?= site_url('clients/project/' . $project['id']); ?>"><?= e($project['name']); ?></a>
                    </td>
                    <td
                        data-order="<?= e($project['start_date']); ?>">
                        <?= e(_d($project['start_date'])); ?>
                    </td>
                    <td
                        data-order="<?= e($project['deadline']); ?>">
                        <?= e(_d($project['deadline'])); ?>
                    </td>
                    <td>
                        <?php
   if ($project['billing_type'] == 1) {
       $type_name = 'project_billing_type_fixed_cost';
   } elseif ($project['billing_type'] == 2) {
       $type_name = 'project_billing_type_project_hours';
   } else {
       $type_name = 'project_billing_type_project_task_hours';
   }
                    echo _l($type_name);
                    ?>
                    </td>
                    <?php foreach ($custom_fields as $field) { ?>
                    <td><?= get_custom_field_value($project['id'], $field['id'], 'projects'); ?>
                    </td>
                    <?php } ?>
                    <td>
                        <?php
                    $status = get_project_status_by_id($project['status']);
                    echo '<span class="label project-status-' . $status['id'] . ' tw-ml-3" style="color:' . $status['color'] . ';border:1px solid ' . adjust_hex_brightness($status['color'], 0.4) . ';background: ' . adjust_hex_brightness($status['color'], 0.04) . ';">' . e($status['name']) . '</span>';
                    ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>