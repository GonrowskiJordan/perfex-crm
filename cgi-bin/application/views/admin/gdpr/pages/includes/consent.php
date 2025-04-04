<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="consentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <?= form_open('', ['id' => 'consentForm']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">New consent purpose</h4>
      </div>
      <div class="modal-body">
        <?php $value = (isset($purpose) ? $purpose->name : '');
$attrs               = [];
if (isset($purpose) && $purpose->total_usage > 0) {
    $attrs['disabled'] = true;
}
?>
        <?= render_input('name', 'Name / Purpose', $value, 'text', $attrs); ?>
        <?php $value = (isset($purpose) ? $purpose->description : ''); ?>
        <?= render_textarea('description', 'Description', $value, ['placeholder' => 'Briefly describe the purpose of this consent. Eq. for what the data will be used.', 'rows' => 10]); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div><!-- /.modal-content -->
    <?= form_close(); ?>
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->