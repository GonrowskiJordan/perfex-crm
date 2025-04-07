<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'mail_outbox.to',
    db_prefix() . 'mail_outbox.sender_name',
    db_prefix() . 'mail_outbox.subject',
    db_prefix() . 'mail_outbox.date_sent',
    db_prefix() . 'mail_outbox.scheduled_at',
    db_prefix() . 'mail_tags.id as tag_id',
    db_prefix() . 'mail_tags.name as tag_name',
    db_prefix() . 'emailtemplates.emailtemplateid as template_id',
    db_prefix() . 'emailtemplates.name as template_name',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'mail_outbox';

$join = [
    'LEFT JOIN ' . db_prefix() . 'mail_tags ON ' . db_prefix() . 'mail_tags.id = ' . db_prefix() . 'mail_outbox.tagid',
    'LEFT JOIN ' . db_prefix() . 'emailtemplates ON ' . db_prefix() . 'emailtemplates.emailtemplateid = ' . db_prefix() . 'mail_outbox.templateid'
];
$where = [];
array_push($where, 'AND trash = 0');
if ($group == 'draft') {
    array_push($where, ' AND draft = 1');
} else {
    array_push($where, ' AND draft = 0');
}
array_push($where, ' AND sender_staff_id = '.get_staff_user_id());
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'mail_outbox.id',
    db_prefix() . 'mail_outbox.has_attachment',
    db_prefix() . 'mail_outbox.stared',
    db_prefix() . 'mail_outbox.important',
    db_prefix() . 'mail_outbox.body',
    db_prefix() . 'mail_outbox.subject',
    db_prefix() . 'mail_outbox.date_sent',
    db_prefix() . 'mail_outbox.scheduled_at'
]);

$mail_tags = get_mail_tags();

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $starred = "fa-star";    
    $msg_starred = _l('mailbox_add_star');
    $important = "fa-bookmark";
    $msg_important = _l('mailbox_mark_as_important');
    if ($aRow['stared'] == 1) {
        $starred = "fa-star orange";
        $msg_starred = _l('mailbox_remove_star');
    }
    if ($aRow['important'] == 1) {
        $important = "fa fa-bookmark green";
        $msg_important = _l('mailbox_mark_as_not_important');        
    }
    $has_attachment = "";
    if ($aRow['has_attachment'] > 0) {
        $has_attachment = '<i class="fa fa-paperclip pull-right" data-toggle="tooltip" title="'._l('mailbox_file_attachment').'" data-original-title="fa-paperclip"></i>';
    }
    if ($group == "draft") {
        $type = "outbox"; 
    } else {
        $type = "inbox";
    }

    $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>                
                <a class="btn btnIcon" data-toggle="tooltip" title="" data-original-title="'. _l('mailbox_delete').'" onclick="update_field(\''.$group.'\',\'trash\',1,'.$aRow['id'].',\''.$type.'\');"><i class="fa fa-trash"></i></a>';
    
    $content = '<a href="'.admin_url().'mailbox/outbox/'.$aRow['id'].'">';
    if ($group == 'draft') {
        $content = '<a href="'.admin_url().'mailbox/compose/'.$aRow['id'].'">';
    }
    $row[] = $content.'<span>'.$aRow[db_prefix() . 'mail_outbox.to'].'</span></a>';
    $row[] = $content.'<span>'.$aRow['subject'].($has_attachment?' - </span><span class="text-muted">'.clear_textarea_breaks(text_limiter($aRow['body'],2,'...')).'</span>'.$has_attachment:'').'</a>';    
    $mail_tag_content = '<select class="mail-tag" data-id="'.$aRow['id'].'" data-type="outbox">';
    $mail_tag_content .= '<option></option>';
    foreach ($mail_tags as $mail_tag) {
        $mail_tag_content .= '<option data-id="'.$mail_tag['id'].'"'.($mail_tag['id']==$aRow['tag_id']?'selected':'').'>'.$mail_tag['name'].'</option>';
    }
    $mail_tag_content .= '</select>';
    $row[] = $mail_tag_content;
    $row[] = $content.'<span>'.$aRow['template_name'].'</span></a>';
    $row[] = $content.'<span>'._dt($aRow['scheduled_at']).'</span></a>';
    $row[] = $content.'<span>'._dt($aRow['date_sent']).'</span></a>';

    $output['aaData'][] = $row;
}