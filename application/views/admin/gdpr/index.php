<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <?php if (! is_gdpr()) { ?>
         <div class="panel_s">
            <div class="panel-body">
               <div class="col-md-12 text-center">
                  <h4>
                     <?= _l('gdpr_not_enabled'); ?>
                  </h4>
                  <a href="<?= admin_url('gdpr/enable'); ?>"
                     class="btn btn-primary"><?= _l('enable_gdpr'); ?></a>
               </div>
            </div>
         </div>
         <?php } else { ?>
         <?php if ($save == true) { ?>
         <?= form_open(admin_url('gdpr/save?page=' . $page)); ?>
         <?php } ?>
         <div class="col-md-3">
            <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
               <li<?php if ($page == 'general') {
                   echo ' class="active"';
               } ?>>
                  <a
                     href="<?= admin_url('gdpr/index?page=general'); ?>"><?= _l('settings_group_general'); ?></a>
                  </li>
                  <li<?php if ($page == 'portability') {
                      echo ' class="active"';
                  } ?>>
                     <a
                        href="<?= admin_url('gdpr/index?page=portability'); ?>"><?= _l('gdpr_right_to_data_portability'); ?></a>
                     </li>
                     <li<?php if ($page == 'forgotten') {
                         echo ' class="active"';
                     } ?>>
                        <a
                           href="<?= admin_url('gdpr/index?page=forgotten'); ?>"><?= _l('gdpr_right_to_erasure'); ?></a>
                        </li>
                        <li<?php if ($page == 'informed') {
                            echo ' class="active"';
                        } ?>>
                           <a
                              href="<?= admin_url('gdpr/index?page=informed'); ?>"><?= _l('gdpr_right_to_be_informed'); ?></a>
                           </li>
                           <li<?php if ($page == 'rectification') {
                               echo ' class="active"';
                           } ?>>
                              <a
                                 href="<?= admin_url('gdpr/index?page=rectification'); ?>"><?= _l('gdpr_right_of_access'); ?>/<?= _l('gdpr_right_to_rectification'); ?></a>
                              </li>
                              <li<?php if ($page == 'consent') {
                                  echo ' class="active"';
                              } ?>>
                                 <a
                                    href="<?= admin_url('gdpr/index?page=consent'); ?>"><?= _l('gdpr_consent'); ?></a>
                                 </li>
            </ul>
         </div>
         <div class="col-md-9">
            <div class="panel_s">
               <div class="panel-body">
                  <?php hooks()->do_action('before_admin_gdpr_settings'); ?>
                  <?php $this->load->view('admin/gdpr/pages/' . $page); ?>
               </div>
            </div>
         </div>
         <?php if ($save == true) { ?>
         <div class="btn-bottom-toolbar text-right">
            <button type="submit"
               class="btn btn-primary"><?= _l('save'); ?></button>
         </div>
         <?= form_close(); ?>
         <?php } ?>
         <?php } ?>
      </div>
   </div>
</div>
<div id="page-tail"></div>
<?php init_tail(); ?>
<script>
   $(function() {
      $('.removalStatus').on('change', function(e) {
         var id = $(this).attr('data-id');
         var val = $(this).selectpicker('val');

         // Event is invoked twice? Second is jQuery object
         if (typeof(val) != 'string') {
            return;
         }
         requestGet('gdpr/change_removal_request_status/' + id + '/' + val);
      });
   });
</script>
</body>

</html>