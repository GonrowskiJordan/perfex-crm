<?php defined('BASEPATH') or exit('No direct script access allowed');
$i = 0;

foreach ($statuses as $status) {
    $kanBan = new app\services\proposals\ProposalsPipeline($status);
    $kanBan->search($this->input->get('search'))
        ->sortBy($this->input->get('sort_by'), $this->input->get('sort'));
    if ($this->input->get('refresh')) {
        $kanBan->refresh($this->input->get('refresh')[$status] ?? null);
    }
    $proposals       = $kanBan->get();
    $total_proposals = count($proposals);
    $total_pages     = $kanBan->totalPages(); ?>
<ul class="kan-ban-col" data-col-status-id="<?= e($status); ?>"
    data-total-pages="<?= e($total_pages); ?>"
    data-total="<?= e($total_proposals); ?>">
    <li class="kan-ban-col-wrapper">
        <div
            class="panel_s panel-<?= proposal_status_color_class($status); ?> no-mbot">
            <div class="panel-heading tw-font-semibold">
                <?= format_proposal_status($status, '', false); ?>
                -
                <span class="tw-text-sm">
                    <?= $kanBan->countAll() . ' ' . _l('proposals') ?>
                </span>
            </div>
            <div class="kan-ban-content-wrapper">
                <div class="kan-ban-content">
                    <ul class="sortable<?php if (staff_can('edit', 'proposals')) {
                        echo ' status pipeline-status';
                    } ?>"
                        data-status-id="<?= e($status); ?>">
                        <?php
                          foreach ($proposals as $proposal) {
                              $this->load->view('admin/proposals/pipeline/_kanban_card', ['proposal' => $proposal, 'status' => $status]);
                          } ?>
                        <?php if ($total_proposals > 0) { ?>
                        <li class="text-center not-sortable kanban-load-more"
                            data-load-status="<?= e($status); ?>">
                            <a href="#" class="btn btn-default btn-block<?php if ($total_pages <= 1 || $kanBan->getPage() === $total_pages) {
                                echo ' disabled';
                            } ?>"
                                data-page="<?= $kanBan->getPage(); ?>"
                                onclick="kanban_load_more(<?= e($status); ?>,this,'proposals/pipeline_load_more',347,360); return false;"
                                ;><?= _l('load_more'); ?></a>
                        </li>
                        <?php } ?>
                        <li class="text-center not-sortable mtop30 kanban-empty<?php if ($total_proposals > 0) {
                            echo ' hide';
                        } ?>">
                            <h4>
                                <i class="fa-solid fa-circle-notch" aria-hidden="true"></i><br /><br />
                                <?= _l('no_proposals_found'); ?>
                            </h4>
                        </li>
                    </ul>
                </div>
            </div>
    </li>
</ul>
<?php $i++;
} ?>