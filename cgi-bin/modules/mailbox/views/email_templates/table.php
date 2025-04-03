<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'emailtemplateid as id',
    'name',
    'subject',
    'slug',
    'type',
    'active',
];

$sIndexColumn = 'emailtemplateid';
$sTable       = db_prefix() . 'emailtemplates';

$join = [];
$where = [' AND ' . db_prefix() . 'emailtemplates.type="mailbox" AND ' . db_prefix() . 'emailtemplates.language="english"'];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['emailtemplateid','name','subject','slug','type','active']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $nameRow = '<a href="#"  class="tw-font-medium" onclick="view_mailbox_email_template(' . $aRow['id'] . '); return false;">' . e($aRow['name']) . '</a>';
    $nameRow .= '<div class="row-options">';
    $nameRow .= '<a href="#" onclick="view_mailbox_email_template(' . $aRow['id'] . '); return false;">' . _l('edit') . '</a>';
    $nameRow .= ' | <a href="' . admin_url('mailbox/delete_email_template/' . $aRow['id']) . '" class="_delete">' . _l('delete') . '</a>';
    $nameRow .= '</div>';
    $row[] = $nameRow;
    $row[] = $aRow['subject'];
    $row[] = $aRow['slug'];

    $outputActive = '<div class="onoffswitch">
        <input type="checkbox"' . ' data-switch-url="' . admin_url() . 'mailbox/change_email_template_status" name="onoffswitch" class="onoffswitch-checkbox" id="t_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '"' . ($aRow['active'] == 1 ? ' checked' : '') . '>
        <label class="onoffswitch-label" for="t_' . $aRow['id'] . '"></label>
    </div>';
    $outputActive .= '<span class="hide">' . ($aRow['active'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
    $row[] = $outputActive;
    
    $output['aaData'][] = $row;
}