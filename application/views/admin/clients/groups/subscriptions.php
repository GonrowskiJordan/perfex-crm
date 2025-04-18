<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($client)) { ?>
<h4 class="customer-profile-group-heading">
    <?= _l('subscriptions'); ?></h4>
<?php if (staff_can('create', 'subscriptions')) { ?>
<a href="<?= admin_url('subscriptions/create?customer_id=' . $client->userid); ?>"
    class="btn btn-primary mbot15<?= $client->active == 0 ? ' disabled' : ''; ?>">
    <i class="fa-regular fa-plus tw-mr-1"></i>
    <?= _l('new_subscription'); ?>
</a>
<?php }
$this->load->view('admin/subscriptions/table_html', [
    'url' => admin_url('subscriptions/table?client_id=' . $client->userid),
]);
} ?>