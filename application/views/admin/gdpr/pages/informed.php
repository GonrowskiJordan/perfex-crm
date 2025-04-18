<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="no-mtop">
    <?= _l('gdpr_right_to_be_informed'); ?>
    <small>
        <a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-to-be-informed/"
            target="_blank"><?= _l('learn_more'); ?></a>
    </small>
</h4>
<hr class="hr-panel-separator" />
<?php render_yes_no_option('gdpr_enable_terms_and_conditions', 'Enable Terms & Conditions for registration and customers portal'); ?>
<hr />
<?php render_yes_no_option('gdpr_enable_terms_and_conditions_lead_form', 'Enable Terms & Conditions for web to lead forms'); ?>
<hr />
<?php render_yes_no_option('gdpr_enable_terms_and_conditions_ticket_form', 'Enable Terms & Conditions for ticket form'); ?>
<hr />
<?php render_yes_no_option('gdpr_show_terms_and_conditions_in_footer', 'Show Terms & Conditions in customers area footer'); ?>
<hr />
<?php render_yes_no_option('gdpr_enable_terms_and_conditions_estimate_request_form', 'Enable Terms & Conditions for estimate request forms'); ?>
<hr class="hr-panel-separator" />
<p class="">
    <?= _l('terms_and_conditions') ?>
    <br />
    <a href="<?= terms_url(); ?>"
        target="_blank"><?= terms_url(); ?></a>
</p>
<?= render_textarea('settings[terms_and_conditions]', '', get_option('terms_and_conditions'), [], [], '', 'tinymce'); ?>
<hr />
<p class="">
    <i class="fa-regular fa-circle-question" data-toggle="tooltip"
        data-title="You may want to include the privacy policy in your terms and condtions content."></i> Privacy Policy
    <br />
    <a href="<?= privacy_policy_url(); ?>"
        target="_blank"><?= privacy_policy_url(); ?></a>
</p>
<?= render_textarea('settings[privacy_policy]', '', get_option('privacy_policy'), [], [], '', 'tinymce'); ?>