<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s mtop25">
    <div class="panel-body">
        <div class="col-md-12">
            <?php if (is_client_logged_in()) { ?>
            <a href="<?= site_url('clients/gdpr'); ?>"
                class="btn btn-default pull-right">
                <?= _l('client_go_to_dashboard'); ?>
            </a>
            <?php } ?>
            <h1 class="mbot20">
                <?= e(hooks()->apply_filters('consent_public_page_heading', get_option('companyname'))); ?>
            </h1>
            <div class="tc-content mbot20">
                <?= get_option('gdpr_consent_public_page_top_block'); ?>
            </div>
        </div>
        <?= form_open();

foreach ($purposes as $purpose) { ?>
        <div class="col-md-12">
            <div class="gdpr-purpose">
                <div class="row">
                    <div class="col-md-9">
                        <h3 class="gdpr-purpose-heading">
                            <?= e($purpose['name']); ?>
                            <?php if (! empty($purpose['consent_last_updated'])) { ?>
                            <small
                                class="text-muted"><?= e(_l('consent_last_updated', _dt($purpose['consent_last_updated']))); ?></small>
                            <?php } ?>
                        </h3>
                    </div>
                    <div class="col-md-3 text-right">
                        <?php if ($purpose['consent_given'] == '1') { ?>
                        <i class="fa fa-check fa-2x text-success" aria-hidden="true"></i>
                        <?php } else { ?>
                        <i class="fa fa-remove fa-2x text-danger" aria-hidden="true"></i>
                        <?php } ?>
                    </div>
                    <div class="col-md-12">
                        <?php
             if (! empty($purpose['opt_in_purpose_description']) && ! empty($purpose['consent_given'])) { ?>
                        <p class="no-mbot mtop10">
                            <?= e($purpose['opt_in_purpose_description']); ?>
                        </p>
                        <?php } elseif (! empty($purpose['description']) && empty($purpose['consent_given'])) { ?>
                        <p class="no-mbot mtop10">
                            <?= e($purpose['description']); ?>
                        </p>
                        <?php } ?>
                        <hr />
                        <div class="mtop15">
                            <?php
                if (empty($purpose['consent_given'])) { ?>
                            <div class="radio radio-inline">
                                <input type="radio" value="opt-in"
                                    id="opt_in_<?= e($purpose['id']); ?>"
                                    name="action[<?= e($purpose['id']); ?>]">
                                <label
                                    for="opt_in_<?= e($purpose['id']); ?>"><?= _l('gdpr_consent_agree'); ?></label>
                            </div>
                            <?php if (empty($purpose['last_action_is_opt_out'])) { ?>
                            <div class="radio radio-inline">
                                <input type="radio" value="opt-out"
                                    id="opt_out_<?= e($purpose['id']); ?>"
                                    name="action[<?= e($purpose['id']); ?>]">
                                <label
                                    for="opt_out_<?= e($purpose['id']); ?>"><?= _l('gdpr_consent_disagree'); ?></label>
                            </div>
                            <?php } ?>
                            <?php } else { ?>
                            <div class="radio radio-inline">
                                <input type="radio" value="opt-out"
                                    id="opt_out_<?= e($purpose['id']); ?>"
                                    name="action[<?= e($purpose['id']); ?>]">
                                <label
                                    for="opt_out_<?= e($purpose['id']); ?>"><?= _l('gdpr_consent_disagree'); ?></label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
} ?>
        <div class="col-md-12">
            <button type="submit"
                class="btn btn-primary"><?= _l('update_consent'); ?></button>
        </div>
        <?= form_close(); ?>
    </div>
</div>