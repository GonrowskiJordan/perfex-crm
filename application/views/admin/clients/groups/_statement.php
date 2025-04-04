<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-5">
    <div class="text-right">
        <h4 class="tw-my-0 tw-font-semibold"><?php echo _l('account_summary'); ?></h4>
        <p class="text-muted"><?php echo e(_l('statement_from_to', [$from, $to])); ?></p>
        <hr />
        <table class="table statement-account-summary">
            <tbody>
                <tr>
                    <td class="text-left"><?php echo _l('statement_beginning_balance'); ?>:</td>
                    <td><?php echo e(app_format_money($statement['beginning_balance'], $statement['currency'])); ?></td>
                </tr>
                <tr>
                    <td class="text-left"><?php echo _l('invoiced_amount'); ?>:</td>
                    <td><?php echo e(app_format_money($statement['invoiced_amount'], $statement['currency'])); ?></td>
                </tr>
                <tr>
                    <td class="text-left"><?php echo _l('amount_paid'); ?>:</td>
                    <td><?php echo e(app_format_money($statement['amount_paid'], $statement['currency'])); ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-left"><b><?php echo _l('balance_due'); ?></b>:</td>
                    <td><?php echo e(app_format_money($statement['balance_due'], $statement['currency'])); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="col-md-12">
    <div class="text-center bold padding-10">
        <?php echo _l('customer_statement_info', [$from, $to]); ?>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><b><?php echo _l('statement_heading_date'); ?></b></th>
                    <th><b><?php echo _l('statement_heading_details'); ?></b></th>
                    <th class="text-right"><b><?php echo _l('statement_heading_amount'); ?></b></th>
                    <th class="text-right"><b><?php echo _l('statement_heading_payments'); ?></b></b></th>
                    <th class="text-right"><b><?php echo _l('statement_heading_balance'); ?></b></b></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo e($from); ?></td>
                    <td><?php echo _l('statement_beginning_balance'); ?></td>
                    <td class="text-right">
                        <?php echo e(app_format_money($statement['beginning_balance'], $statement['currency'], true)); ?>
                    </td>
                    <td></td>
                    <td class="text-right">
                        <?php echo e(app_format_money($statement['beginning_balance'], $statement['currency'], true)); ?>
                    </td>
                </tr>
                <?php
       $tmpBeginningBalance = $statement['beginning_balance'];
       foreach ($statement['result'] as $data) { ?>
                <tr>
                    <td><?php echo e(_d($data['date'])); ?></td>
                    <td>
                        <?php
            if (isset($data['invoice_id'])) {
                echo _l('statement_invoice_details', ['<a href="' . admin_url('invoices/list_invoices/' . $data['invoice_id']) . '" target="_blank">' . e(format_invoice_number($data['invoice_id'])) . '</a>', e(_d($data['duedate']))]);
            } elseif (isset($data['payment_id'])) {
                echo _l('statement_payment_details', ['<a href="' . admin_url('payments/payment/' . $data['payment_id']) . '" target="_blank">' . '#' . $data['payment_id'] . '</a>', e(format_invoice_number($data['payment_invoice_id']))]);
            } elseif (isset($data['credit_note_id'])) {
                echo _l('statement_credit_note_details', ['<a href="' . admin_url('credit_notes/list_credit_notes/' . $data['credit_note_id']) . '" target="_blank">' . e(format_credit_note_number($data['credit_note_id'])) . '</a>']);
            } elseif (isset($data['credit_id'])) {
                echo _l(
                    'statement_credits_applied_details',
                    [
              '<a href="' . admin_url('credit_notes/list_credit_notes/' . $data['credit_applied_credit_note_id']) . '" target="_blank">' . e(format_credit_note_number($data['credit_applied_credit_note_id'])) . '</a>',
              e(app_format_money($data['credit_amount'], $statement['currency'], true)),
              e(format_invoice_number($data['credit_invoice_id'])),
            ]
                );
            } elseif (isset($data['credit_note_refund_id'])) {
                echo e(_l('statement_credit_note_refund', format_credit_note_number($data['refund_credit_note_id'])));
            }
          ?>
                    </td>
                    <td class="text-right">
                        <?php
          if (isset($data['invoice_id'])) {
              echo e(app_format_money($data['invoice_amount'], $statement['currency'], true));
          } elseif (isset($data['credit_note_id'])) {
              echo e(app_format_money($data['credit_note_amount'], $statement['currency'], true));
          }
          ?>
                    </td>
                    <td class="text-right">
                        <?php
          if (isset($data['payment_id'])) {
              echo e(app_format_money($data['payment_total'], $statement['currency'], true));
          } elseif (isset($data['credit_note_refund_id'])) {
              echo e(app_format_money($data['refund_amount'], $statement['currency'], true));
          }
          ?>
                    </td>
                    <td class="text-right">
                        <?php
          if (isset($data['invoice_id'])) {
              $tmpBeginningBalance = ($tmpBeginningBalance + $data['invoice_amount']);
          } elseif (isset($data['payment_id'])) {
              $tmpBeginningBalance = ($tmpBeginningBalance - $data['payment_total']);
          } elseif (isset($data['credit_note_id'])) {
              $tmpBeginningBalance = ($tmpBeginningBalance - $data['credit_note_amount']);
          } elseif (isset($data['credit_note_refund_id'])) {
              $tmpBeginningBalance = ($tmpBeginningBalance + $data['refund_amount']);
          }
          if (!isset($data['credit_id'])) {
              echo e(app_format_money($tmpBeginningBalance, $statement['currency'], true));
          }
          ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot class="statement_tfoot">
                <tr>
                    <td colspan="3" class="text-right">
                        <b><?php echo _l('balance_due'); ?></b>
                    </td>
                    <td class="text-right" colspan="2">
                        <b><?php echo e(app_format_money($statement['balance_due'], $statement['currency'])); ?></b>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>