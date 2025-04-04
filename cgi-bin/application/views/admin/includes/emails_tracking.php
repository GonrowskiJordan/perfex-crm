<?php defined('BASEPATH') or exit('No direct script access allowed');
if (count($tracked_emails) === 0) {
    echo '<h4 class="tw-m-0 tw-text-base tw-font-medium tw-text-neutral-500">' . _l('no_tracked_emails_sent') . '</h4>';
} else {
    ?>
<h4 class="tw-m-0 tw-text-lg tw-font-medium tw-text-neutral-600">
    <?= _l('tracked_emails_sent'); ?></h4>
<div class="table-responsive">
    <table class="table ">
        <thead>
            <tr>
                <th><b><?= _l('tracked_email_date'); ?></b>
                </th>
                <th><b><?= _l('tracked_email_subject'); ?></b>
                </th>
                <th><b><?= _l('tracked_email_to'); ?></b>
                </th>
                <th><b><?= _l('tracked_email_opened'); ?></b>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($tracked_emails as $email) { ?>
            <tr>
                <td>
                    <?= e(_dt($email['date'])); ?>
                </td>
                <td>
                    <?= e($email['subject']); ?>
                </td>
                <td>
                    <?= e($email['email']); ?>
                </td>
                <td>
                    <?php if ($email['opened'] == 1) {
                        echo '<span class="label label-success">
                     <i class="fa-regular fa-clock text-has-action tw-mr-1" data-toggle="tooltip" data-title="' . e(_dt($email['date_opened'])) . '"></i> ' . _l('tracked_email_opened') . '</span>';
                    } else {
                        echo '<span class="label label-danger">' . _l('tracked_email_not_opened') . '</span>';
                    }
                ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
} ?>