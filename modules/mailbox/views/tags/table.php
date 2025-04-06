<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'mail_tags.name',
    db_prefix() . 'mail_tags.active',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'mail_tags';

$join = [];
$where = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id','name','active']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $nameRow = '<a href="#"  class="tw-font-medium" onclick="view_mailbox_tag(' . $aRow['id'] . '); return false;">' . e($aRow['name']) . '</a>';
    $nameRow .= '<div class="row-options">';
    $nameRow .= '<a href="#" onclick="view_mailbox_tag(' . $aRow['id'] . '); return false;">' . _l('edit') . '</a>';
    $nameRow .= ' | <a href="' . admin_url('mailbox/delete_tag/' . $aRow['id']) . '" class="_delete">' . _l('delete') . '</a>';
    $nameRow .= '</div>';
    $row[] = $nameRow;
    
    $outputActive = '<div class="onoffswitch">
        <input type="checkbox"' . ' data-switch-url="' . admin_url() . 'mailbox/change_tag_status" name="onoffswitch" class="onoffswitch-checkbox" id="t_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '"' . ($aRow['active'] == 1 ? ' checked' : '') . '>
        <label class="onoffswitch-label" for="t_' . $aRow['id'] . '"></label>
    </div>';
    $outputActive .= '<span class="hide">' . ($aRow['active'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
    $row[] = $outputActive;
    
    $output['aaData'][] = $row;
}