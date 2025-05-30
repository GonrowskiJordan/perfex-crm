<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="sync_data_proposal_data"
  data-rel-type="<?= e($rel_type); ?>" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          <?= _l('sync_data'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if ($rel_type == 'lead') {
            $lang_key = 'lead_lowercase';
        } else {
            $lang_key = 'client_lowercase';
        }?>
        <p>
          <?= e(_l('proposal_sync_1_info', [_l($lang_key), _l($lang_key)])); ?>
        </p>
        <p>
          <?= e(_l('proposal_sync_2_info', _l($lang_key))); ?>
        </p>
        <?= render_textarea('address', 'proposal_address', $related->address); ?>
        <div class="row">
          <div class="col-md-6">
            <?= render_input('city', 'billing_city', $related->city); ?>
          </div>
          <div class="col-md-6">
            <?= render_input('state', 'billing_state', $related->state); ?>
          </div>
          <div class="col-md-6">
            <?php $countries = get_all_countries(); ?>
            <?= render_select('country', $countries, ['country_id', ['short_name'], 'iso2'], 'billing_country', $related->country); ?>
          </div>
          <div class="col-md-6">
            <?= render_input('zip', 'billing_zip', $related->zip); ?>
          </div>
        </div>
        <?= render_input('phone', 'proposal_phone', $related->phonenumber); ?>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary"
          onclick="sync_proposals_data(<?= e($rel_id); ?>,'<?= e($rel_type); ?>');"><?= _l('sync_now'); ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->