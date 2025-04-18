<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons">
                    <?php if (staff_can('create', 'subscriptions')) { ?>
                    <a href="<?= admin_url('subscriptions/create'); ?>"
                        class="btn btn-primary pull-left display-block">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?= _l('new_subscription'); ?>
                    </a>
                    <?php } ?>
                    <div id="vueApp" class="tw-inline pull-right tw-ml-0 sm:tw-ml-1.5 rtl:tw-mr-1.5 rtl:tw-ml-0">
                        <app-filters id="<?= $table->id(); ?>"
                            view="<?= $table->viewName(); ?>"
                            :saved-filters="<?= $table->filtersJs(); ?>"
                            :available-rules="<?= $table->rulesJs(); ?>">
                        </app-filters>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="panel_s tw-mt-2 sm:tw-mt-4">
                    <div class="panel-body">
                        <h4 class="tw-mt-0 tw-font-bold tw-text-lg">
                            <i class="fa-brands fa-stripe" aria-hidden="true"></i>
                            <?= _l('subscriptions_summary'); ?>
                        </h4>

                        <div
                            class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-8 tw-gap-2 tw-mt-2 sm:tw-mt-4">
                            <?php foreach (subscriptions_summary() as $summary) { ?>
                            <div
                                class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center lg:last:tw-border-r-0">
                                <span class="tw-font-semibold ltr:tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    <?= e($summary['total']); ?>
                                </span>
                                <span
                                    style="color:<?= e($summary['color']); ?>">
                                    <?= _l('subscription_' . $summary['id']); ?>
                                </span>
                            </div>
                            <?php } ?>
                        </div>
                        <hr class="hr-panel-separator" />
                        <div class="panel-table-full">
                            <?php hooks()->do_action('before_subscriptions_table'); ?>
                            <?php
                                $this->load->view('admin/subscriptions/table_html', [
                                    'url' => admin_url('subscriptions/table'),
                                ]);
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>

</html>