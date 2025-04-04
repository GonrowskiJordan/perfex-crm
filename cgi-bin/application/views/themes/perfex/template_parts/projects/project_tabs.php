<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="horizontal-scrollable-tabs preview-tabs-top panel-full-width-tabs">
    <div class="scroller arrow-left tw-mt-px"><i class="fa fa-angle-left"></i></div>
    <div class="scroller arrow-right tw-mt-px"><i class="fa fa-angle-right"></i></div>
    <div class="horizontal-tabs">
        <ul class="nav nav-tabs nav-tabs-horizontal no-margin" role="tablist">
            <li role="presentation" class="active project_tab_overview">
                <a data-group="project_overview"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_overview'); ?>"
                    role="tab">
                    <i class="fa fa-th menu-icon" aria-hidden="true"></i>
                    <?= _l('project_overview'); ?></a>
            </li>

            <?php hooks()->do_action('after_customers_area_project_overview_tab', $project); ?>

            <?php if ($project->settings->view_tasks == 1 && $project->settings->available_features['project_tasks'] == 1) { ?>
            <li role="presentation" class="project_tab_tasks">
                <a data-group="project_tasks"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_tasks'); ?>"
                    role="tab">
                    <i class="fa-regular fa-check-circle menu-icon" aria-hidden="true"></i>
                    <?= _l('tasks'); ?></a>
            </li>
            <?php } ?>

            <?php if ($project->settings->view_timesheets == 1 && $project->settings->available_features['project_timesheets'] == 1) { ?>
            <li role="presentation" class="project_tab_timesheets">
                <a data-group="project_timesheets"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_timesheets'); ?>"
                    role="tab">
                    <i class="fa-regular fa-clock menu-icon" aria-hidden="true"></i>
                    <?= _l('project_timesheets'); ?></a>
            </li>
            <?php } ?>

            <?php if ($project->settings->view_milestones == 1 && $project->settings->available_features['project_milestones'] == 1) { ?>
            <li role="presentation" class="project_tab_milestones">
                <a data-group="project_milestones"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_milestones'); ?>"
                    role="tab">
                    <i class="fa fa-rocket menu-icon" aria-hidden="true"></i>
                    <?= _l('project_milestones'); ?></a>
            </li>
            <?php } ?>

            <?php if ($project->settings->available_features['project_files'] == 1) { ?>
            <li role="presentation" class="project_tab_files">
                <a data-group="project_files"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_files'); ?>"
                    role="tab">
                    <i class="fa-solid fa-file menu-icon" aria-hidden="true"></i>
                    <?= _l('project_files'); ?></a>
            </li>
            <?php } ?>

            <?php if ($project->settings->available_features['project_discussions'] == 1) { ?>
            <li role="presentation" class="project_tab_discussions">
                <a data-group="project_discussions"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_discussions'); ?>"
                    role="tab">
                    <i class="fa-regular fa-message menu-icon" aria-hidden="true"></i>
                    <?= _l('project_discussions'); ?></a>
            </li>
            <?php } ?>

            <?php if ($project->settings->view_gantt == 1 && $project->settings->available_features['project_gantt'] == 1) { ?>
            <li role="presentation" class="project_tab_gantt">
                <a data-group="project_gantt"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_gantt'); ?>"
                    role="tab">
                    <i class="fa-solid fa-chart-gantt menu-icon" aria-hidden="true"></i>
                    <?= _l('project_gant'); ?></a>
            </li>
            <?php } ?>

            <?php if (has_contact_permission('support') && $project->settings->available_features['project_tickets'] == 1) { ?>
            <li role="presentation" class="project_tab_tickets">
                <a data-group="project_tickets"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_tickets'); ?>"
                    role="tab">
                    <i class="fa fa-life-ring menu-icon" aria-hidden="true"></i>
                    <?= _l('project_tickets'); ?></a>
            </li>
            <?php } ?>

            <?php if (has_contact_permission('contracts') && $project->settings->available_features['project_contracts'] == 1) { ?>
            <li role="presentation" class="project_tab_contracts">
                <a data-group="project_contracts"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_contracts'); ?>"
                    role="tab">
                    <i class="fa-solid fa-file-contract menu-icon" aria-hidden="true"></i>
                    <?= _l('contracts'); ?></a>
            </li>
            <?php } ?>

            <?php if (has_contact_permission('proposals') && $project->settings->available_features['project_proposals'] ?? null == 1) { ?>
            <li role="presentation" class="project_tab_proposals">
                <a data-group="project_proposals"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_proposals'); ?>"
                    role="tab">
                    <i class="fa fa-file-text menu-icon" aria-hidden="true"></i>
                    <?= _l('proposals'); ?></a>
            </li>
            <?php } ?>

            <?php if (has_contact_permission('estimates') && $project->settings->available_features['project_estimates'] == 1) { ?>
            <li role="presentation" class="project_tab_estimates">
                <a data-group="project_estimates"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_estimates'); ?>"
                    role="tab">
                    <i class="fa-regular fa-file menu-icon" aria-hidden="true"></i>
                    <?= _l('estimates'); ?>
                </a>
            </li>
            <?php } ?>

            <?php if (has_contact_permission('invoices') && $project->settings->available_features['project_invoices'] == 1) { ?>
            <li role="presentation" class="project_tab_invoices">
                <a data-group="project_invoices"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_invoices'); ?>"
                    role="tab">
                    <i class="fa-solid fa-file-invoice menu-icon" aria-hidden="true"></i>
                    <?= _l('project_invoices'); ?></a>
            </li>
            <?php } ?>

            <?php if ($project->settings->view_activity_log == 1 && $project->settings->available_features['project_activity'] == 1) { ?>
            <li role="presentation" class="project_tab_activity">
                <a data-group="project_activity"
                    href="<?= site_url('clients/project/' . $project->id . '?group=project_activity'); ?>"
                    role="tab">
                    <i class="fa-regular fa-file-lines menu-icon" aria-hidden="true"></i>
                    <?= _l('project_activity'); ?></a>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>