<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'mail_auto_replies.name',
    db_prefix() . 'mail_auto_replies.pattern',
    'receive_templates.name as receive_name',
    db_prefix() . 'mail_auto_replies.subject',
    'reply_templates.name as reply_name',
    db_prefix() . 'mail_auto_replies.active',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'mail_auto_replies';

$join = [
    'LEFT JOIN ' . db_prefix() . 'emailtemplates as receive_templates ON ' . 'receive_templates.emailtemplateid = ' . db_prefix() . 'mail_auto_replies.receiveid',
    'LEFT JOIN ' . db_prefix() . 'emailtemplates as reply_templates ON ' . 'reply_templates.emailtemplateid = ' . db_prefix() . 'mail_auto_replies.replyid'
];
$where = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'mail_auto_replies.id',
    db_prefix() . 'mail_auto_replies.name',
    db_prefix() . 'mail_auto_replies.pattern',
    db_prefix() . 'mail_auto_replies.subject',
    db_prefix() . 'mail_auto_replies.active'
]);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $nameRow = '<a href="#"  class="tw-font-medium" onclick="view_mailbox_auto_reply(' . $aRow['id'] . '); return false;">' . e($aRow['name']) . '</a>';
    $nameRow .= '<div class="row-options">';
    $nameRow .= '<a href="#" onclick="view_mailbox_auto_reply(' . $aRow['id'] . '); return false;">' . _l('edit') . '</a>';
    $nameRow .= ' | <a href="' . admin_url('mailbox/delete_auto_reply/' . $aRow['id']) . '" class="_delete">' . _l('delete') . '</a>';
    $nameRow .= '</div>';
    $row[] = $nameRow;
    
    $row[] = '<span>'.$aRow['pattern'].'</span>';
    $row[] = '<span>'.$aRow['receive_name'].'</span>';
    $row[] = '<span>'.$aRow['subject'].'</span>';
    $row[] = '<span>'.$aRow['reply_name'].'</span>';
    
    $outputActive = '<div class="onoffswitch">
        <input type="checkbox"' . ' data-switch-url="' . admin_url() . 'mailbox/change_auto_reply_status" name="onoffswitch" class="onoffswitch-checkbox" id="t_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '"' . ($aRow['active'] == 1 ? ' checked' : '') . '>
        <label class="onoffswitch-label" for="t_' . $aRow['id'] . '"></label>
    </div>';
    $outputActive .= '<span class="hide">' . ($aRow['active'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
    $row[] = $outputActive;

    $output['aaData'][] = $row;
}