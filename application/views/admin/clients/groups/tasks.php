<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading">
    <?= _l('tasks'); ?></h4>
<?php if (isset($client)) {
    init_relation_tasks_table(['data-new-rel-id' => $client->userid, 'data-new-rel-type' => 'customer']);
} ?>