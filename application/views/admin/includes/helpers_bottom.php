<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php include_once APPPATH . 'views/admin/includes/modals/post_likes.php'; ?>
<?php include_once APPPATH . 'views/admin/includes/modals/post_comment_likes.php'; ?>
<div id="event"></div>
<div id="newsfeed" class="animated fadeIn hide" <?php if ($this->session->flashdata('newsfeed_auto')) {
    echo 'data-newsfeed-auto';
} ?>>
</div>
<!-- Task modal view -->
<div class="modal fade task-modal-single" id="task-modal" tabindex="-1" role="dialog"
  aria-labelledby="myLargeModalLabel">
  <div
    class="modal-dialog <?= get_option('task_modal_class'); ?>">
    <div class="modal-content data">

    </div>
  </div>
</div>

<!--Add/edit task modal-->
<div id="_task"></div>

<!-- Lead Data Add/Edit-->
<div class="modal fade lead-modal" id="lead-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div
    class="modal-dialog <?= get_option('lead_modal_class'); ?>">
    <div class="modal-content data">

    </div>
  </div>
</div>

<div id="timers-logout-template-warning" class="hide">
  <h3 class="bold">
    <?= _l('timers_started_confirm_logout'); ?>
  </h3>
  <hr />
  <a href="<?= admin_url('authentication/logout'); ?>"
    class="btn btn-danger">
    <?= _l('confirm_logout'); ?>
  </a>
</div>

<!--Lead convert to customer modal-->
<div id="lead_convert_to_customer"></div>

<!--Lead reminder modal-->
<div id="lead_reminder_modal"></div>