<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?= form_hidden('project_id', $project->id);
echo '<div class="clearfix"></div>';
if (((get_option('access_tickets_to_none_staff_members') == 1 && ! is_staff_member()) || is_staff_member())) {
    echo '<a href="' . admin_url('tickets/add?project_id=' . $project->id) . '" class="tw-mb-4 btn btn-primary"> <i class="fa-regular fa-plus tw-mr-1"></i>' . _l('new_ticket') . '</a>';
}

?>
<div class="panel_s panel-table-full">
    <div class="panel-body">
        <?= AdminTicketsTableStructure('tickets-table'); ?>
    </div>
</div>