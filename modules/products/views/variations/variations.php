<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s " id="TableData">
          <div class="panel-body">
            <?php if (has_permission('products', '', 'create')) { ?>
              <a href="<?php echo admin_url('products/variations/add'); ?>" class="btn btn-info pull-left display-block">
                <?php echo _l('new_variation'); ?>
              </a>
            <?php } ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12" id="panel">
           <div class="panel_s">
              <div class="panel-body">
                <?php
                $table_data = [
                  _l('variation_name'),
                  _l('variation_values'),
                  ];
                  render_datatable($table_data, 'variations'); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  <?php init_tail(); ?>
<script type="text/javascript">
  $(function(){
    initDataTable('.table-variations', window.location.href, 'undefined', 'undefined', '');
  });
</script>
