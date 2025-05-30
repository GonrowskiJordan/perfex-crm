<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade estimate-pipeline" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="close_modal_manually('.estimate-pipeline'); return false;"
                    aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <?= e(format_estimate_number($id)); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= $estimate; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                    onclick="close_modal_manually('.estimate-pipeline'); return false;"><?= _l('close'); ?></button>
            </div>
        </div>
    </div>
</div>