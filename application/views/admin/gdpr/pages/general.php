<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="no-mtop">
    <?= _l('settings_group_general'); ?>
</h4>
<hr class="hr-panel-separator" />
<?php render_yes_no_option('enable_gdpr', 'Enable GDPR'); ?>
<hr />
<?php render_yes_no_option('show_gdpr_in_customers_menu', 'Show GDPR link in customers area navigation'); ?>
<hr />
<?php render_yes_no_option('show_gdpr_link_in_footer', 'Show GDPR link in customers area footer'); ?>
<hr />
<p class="">
    GDPR page top information block
</p>
<?= render_textarea('settings[gdpr_page_top_information_block]', '', get_option('gdpr_page_top_information_block'), [], [], '', 'tinymce'); ?>