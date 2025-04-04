<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons tw-mb-3 sm:tw-mb-5">
                    <div class="row">
                        <div class="col-md-8">
                            <?php if (staff_can('create', 'proposals')) { ?>
                            <a href="<?= admin_url('proposals/proposal'); ?>"
                                class="btn btn-primary pull-left new">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?= _l('new_proposal'); ?>
                            </a>
                            <?php } ?>
                            <a href="<?= admin_url('proposals/pipeline/' . $switch_pipeline); ?>"
                                class="btn btn-default mleft5 pull-left" data-toggle="tooltip" data-placement="top"
                                data-title="<?= _l('switch_to_list_view'); ?>">
                                <i class="fa-solid fa-table-list"></i>
                            </a>
                        </div>
                        <div class="col-md-4" data-toggle="tooltip" data-placement="top"
                            data-title="<?= _l('search_by_tags'); ?>">
                            <?= render_input('search', '', '', 'search', ['data-name' => 'search', 'onkeyup' => 'proposals_pipeline();', 'placeholder' => _l('search_proposals')], [], 'no-margin') ?>
                            <?= form_hidden('sort_type'); ?>
                            <?= form_hidden('sort', (get_option('default_proposals_pipeline_sort') != '' ? get_option('default_proposals_pipeline_sort_type') : '')); ?>
                        </div>
                    </div>
                </div>

                <div class="animated mtop5 fadeIn">
                    <?= form_hidden('proposalid', $proposalid); ?>
                    <div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="kanban-leads-sort">
                                    <span
                                        class="bold"><?= _l('proposals_pipeline_sort'); ?>:
                                    </span>
                                    <a href="#" onclick="proposal_pipeline_sort('datecreated'); return false"
                                        class="datecreated">
                                        <?php if (get_option('default_proposals_pipeline_sort') == 'datecreated') {
                                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_proposals_pipeline_sort_type')) . '"></i> ';
                                        } ?><?= _l('proposals_sort_datecreated'); ?>
                                    </a>
                                    |
                                    <a href="#" onclick="proposal_pipeline_sort('date'); return false" class="date">
                                        <?php if (get_option('default_proposals_pipeline_sort') == 'date') {
                                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_proposals_pipeline_sort_type')) . '"></i> ';
                                        } ?><?= _l('proposals_sort_proposal_date'); ?>
                                    </a>
                                    |
                                    <a href="#" onclick="proposal_pipeline_sort('pipeline_order');return false;"
                                        class="pipeline_order">
                                        <?php if (get_option('default_proposals_pipeline_sort') == 'pipeline_order') {
                                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_proposals_pipeline_sort_type')) . '"></i> ';
                                        } ?><?= _l('proposals_sort_pipeline'); ?>
                                    </a>
                                    |
                                    <a href="#" onclick="proposal_pipeline_sort('open_till');return false;"
                                        class="open_till">
                                        <?php if (get_option('default_proposals_pipeline_sort') == 'open_till') {
                                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_proposals_pipeline_sort_type')) . '"></i> ';
                                        } ?><?= _l('proposals_sort_open_till'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="proposals-pipeline">
                                <div class="container-fluid">
                                    <div id="kan-ban"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="proposal">
</div>
<?php $this->load->view('admin/includes/modals/sales_attach_file'); ?>
<?php init_tail(); ?>
<div id="convert_helper"></div>
<script>
    $(function() {
        proposals_pipeline();
    });
</script>
</body>

</html>