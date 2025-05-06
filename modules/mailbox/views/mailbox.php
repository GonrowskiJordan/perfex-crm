<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
    <div class="content" style="padding-top:50px;">
	    <div class="row">
            <div class="col-md-2">
                <div class="panel_s mbot50">
                    <div class="">
                        <a href="<?php echo admin_url().'mailbox/compose'; ?>" class="btn btn-danger display-block">
                            <i class="fa fa-edit"></i>
                            <?php echo _l('mailbox_compose'); ?>
                        </a>
                    </div>
                </div>

                <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked customer-tabs" role="tablist">
                    <li class="<?php if ('inbox' == $group) { echo 'active '; } ?>mail_tab_<?php echo $group; ?>">
                        <a data-group="inbox" href="<?php echo admin_url('mailbox?group=inbox'); ?>">
                            <i class="fa fa-inbox menu-icon" aria-hidden="true"></i>
                            <?php echo _l('mailbox_inbox'); ?>
                            <?php
                                $num_unread = total_rows(db_prefix().'mail_inbox', ['read' => '0', 'to_staff_id' => get_staff_user_id(), 'trash' => '0']);
                                if ($num_unread > 0) { ?>
                                    <span class="badge menu-badge bg-danger"><?php echo $num_unread; ?></span>
                                <?php
                                }  ?>
                            <?php
                                $num_read = total_rows(db_prefix().'mail_inbox', ['read' => '1', 'to_staff_id' => get_staff_user_id(), 'trash' => '0']);
                            ?>
                            <span class="badge menu-badge bg-neutral"><?php echo $num_read; ?></span>
                        </a>
                    </li>
                    <li class="<?php if ('starred' == $group) { echo 'active '; } ?>mail_tab_<?php echo $group; ?>">
                        <a data-group="starred" href="<?php echo admin_url('mailbox?group=starred'); ?>">
                            <i class="fa fa-star menu-icon orange" aria-hidden="true"></i>
                            <?php echo _l('mailbox_starred'); ?>
                        </a>
                    </li>
                    <li class="<?php if ('sent' == $group) { echo 'active '; } ?>mail_tab_<?php echo $group; ?>">
                        <a data-group="sent" href="<?php echo admin_url('mailbox?group=sent'); ?>">
                            <i class="fa fa-envelope menu-icon" aria-hidden="true"></i>
                            <?php echo _l('mailbox_sent'); ?>
                        </a>
                    </li>
                    <li class="<?php if ('important' == $group) { echo 'active '; } ?>mail_tab_<?php echo $group; ?>">
                        <a data-group="important" href="<?php echo admin_url('mailbox?group=important'); ?>">
                            <i class="fa fa-bookmark menu-icon red" aria-hidden="true"></i>
                            <?php echo _l('mailbox_important'); ?>
                        </a>
                    </li>
                    <li class="<?php if ('draft' == $group) { echo 'active ';  } ?>mail_tab_<?php echo $group; ?>">
                        <a data-group="draft" href="<?php echo admin_url('mailbox?group=draft'); ?>">
                            <i class="fa fa-file menu-icon" aria-hidden="true"></i>
                            <?php echo _l('mailbox_draft'); ?>
                        </a>
                    </li>
                    <li class="<?php if ('trash' == $group) { echo 'active ';  } ?>mail_tab_<?php echo $group; ?>">
                        <a data-group="trash" href="<?php echo admin_url('mailbox?group=trash'); ?>">
                            <i class="fa fa-trash menu-icon" aria-hidden="true"></i>
                            <?php echo _l('mailbox_trash'); ?>
                        </a>
                    </li>
                    <li class="<?php if ('config' == $group) { echo 'active '; } ?>mail_tab_<?php echo $group; ?>">
                        <a data-group="trash" href="<?php echo admin_url('mailbox?group=config'); ?>">
                            <i class="fa fa-cogs menu-icon" aria-hidden="true"></i>
                            <?php echo _l('mailbox_config'); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-10">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tab-content">
                            <h4 class="customer-profile-group-heading">
                                <?php if ('detail' == $group) {
                                    echo $title;
                                } else {
                                    echo _l('mailbox_' . $group);
                                }
                                ?>
                            </h4>
                            <?php if ('compose' != $group && 'config' != $group) {?>
                                <div class="horizontal-scrollable-tabs preview-tabs-top">
                                    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                                    <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                                    <div class="horizontal-tabs">
                                        <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                                            <?php if ('inbox' == $group || 'starred' == $group || 'important' == $group || ('detail' == $group && isset($type) && 'outbox' != $type)) {?>
                                                <li role="presentation" data-toggle="tooltip" title="" class="tab-separator" data-original-title="<?php echo _l('mailbox_add_star'); ?>">
                                                    <a href="Javascript:void(0)" aria-controls="tab_emails_tracking" role="tab" data-toggle="tab" onclick="update_mass('<?php echo $group; ?>','starred',0);window.location.reload(); return false;">
                                                        <i class="fa fa-star orange" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                                <li role="presentation" data-toggle="tooltip" title="" class="tab-separator" data-original-title="<?php echo _l('mailbox_remove_star'); ?>">
                                                    <a href="Javascript:void(0)" aria-controls="tab_emails_tracking" role="tab" data-toggle="tab" onclick="update_mass('<?php echo $group; ?>','starred',1);window.location.reload(); return false;">
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                                <li role="presentation" data-toggle="tooltip" title="" class="tab-separator" data-original-title="<?php echo _l('mailbox_mark_as_important'); ?>">
                                                    <a href="Javascript:void(0)" aria-controls="tab_emails_tracking" role="tab" data-toggle="tab" onclick="update_mass('<?php echo $group; ?>','important',0);window.location.reload(); return false;">
                                                        <i class="fa fa-bookmark green" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                                <li role="presentation" data-toggle="tooltip" title="" class="tab-separator" data-original-title="<?php echo _l('mailbox_mark_as_not_important'); ?>">
                                                    <a href="Javascript:void(0)" aria-controls="tab_emails_tracking" role="tab" data-toggle="tab" onclick="update_mass('<?php echo $group; ?>','important',1);window.location.reload(); return false;">
                                                        <i class="fa fa-bookmark" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                                <li role="presentation" data-toggle="tooltip" title="" class="tab-separator" data-original-title="<?php echo _l('mailbox_mark_as_unread'); ?>">
                                                    <a href="Javascript:void(0)" aria-controls="tab_emails_tracking" role="tab" data-toggle="tab" onclick="update_mass('<?php echo $group; ?>','read',1);window.location.reload(); return false;">
                                                        <i class="fa fa-envelope orange" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                                <li role="presentation" data-toggle="tooltip" title="" class="tab-separator" data-original-title="<?php echo _l('mailbox_mark_as_read'); ?>">
                                                    <a href="Javascript:void(0)" aria-controls="tab_emails_tracking" role="tab" data-toggle="tab" onclick="update_mass('<?php echo $group; ?>','read',0);window.location.reload(); return false;">
                                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <li role="presentation" data-toggle="tooltip" title="" class="tab-separator" data-original-title="<?php echo _l('mailbox_delete'); ?>">
                                                <a href="Javascript:void(0)" aria-controls="tab_emails_tracking" role="tab" data-toggle="tab" onclick="update_mass('<?php echo $group; ?>','trash',1,'<?php if ('draft' == $group) { echo 'outbox'; } else { echo 'inbox'; } ?>');window.location.reload(); return false;">
                                                    <i class="fa fa-trash red" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <?php if ('detail' == $group) {?>
                                                <li role="presentation" data-toggle="tooltip" title="" class="tab-separator" data-original-title="<?php echo _l('mailbox_reply'); ?>">
                                                    <a href="<?php echo admin_url().'mailbox/reply/'.$mailbox->id.'/reply/'.$type; ?>">
                                                        <i class="fa fa-mail-reply" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                                <li role="presentation" data-toggle="tooltip" title="" class="tab-separator" data-original-title="<?php echo _l('mailbox_reply_all'); ?>">
                                                    <a href="<?php echo admin_url().'mailbox/reply/'.$mailbox->id.'/replyall/'.$type; ?>">
                                                        <i class="fa fa-mail-reply-all" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                                <li role="presentation" data-toggle="tooltip" title="" class="tab-separator" data-original-title="<?php echo _l('mailbox_forward'); ?>">
                                                    <a href="<?php echo admin_url().'mailbox/reply/'.$mailbox->id.'/forward/'.$type; ?>">
                                                        <i class="fa fa-mail-forward" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="tab-content">
                                <?php if ('compose' == $group && !isset($type)) {
                                    $this->load->view('mailbox/mailbox_compose');
                                } else if ('compose' == $group && 'reply' == $type) {
                                    $this->load->view('mailbox/mailbox_reply');
                                } else if ('detail' == $group && 'inbox' == $type) {
                                    $this->load->view('mailbox/mailbox_detail');
                                } else if ('detail' == $group && 'outbox' == $type) {
                                    $this->load->view('mailbox/mailbox_detail_outbox');
                                } else if ('config' == $group) {
                                    $this->load->view('mailbox/mailbox_config');
                                } else { ?>
                                    <?php
                                        $table_data = [];
                                        $obj        = [
                                            'name'    => _l('mailbox_from'),
                                            'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-from'],
                                        ];
                                        if ('sent' == $group) {
                                            $obj = [
                                                'name'    => _l('mailbox_to'),
                                                'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-to'],
                                            ];
                                        }
                                        $_table_data = [
                                            '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="mailbox"><label></label></div>',
											[
                                                'name'    => _l('mailbox_tag_heading'),
                                                'th_attrs'=> ['class'=>'toggleable p-3', 'id'=>'th-mailbox-tag', 'style'=>'width: 50px;'],
											],
                                            $obj,
											[
                                                'name'    => _l('mailbox_subject'),
                                                'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-subject'],
                                            ],
                                            [
                                                'name'    => _l('mailbox_body'),
                                                'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-body', 'style'=>'width: 200px;'],
                                            ],
                                            [
                                                'name'    => _l('assigned_customers'),
                                                'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-template', 'style'=>'width: 100px;'],
                                            ]
                                        ];
                                        if ('sent' == $group || 'draft' == $group) {
                                            $_table_data[] = [
                                                'name'    => _l('mailbox_scheduled_at'),
                                                'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-scheduled-at'],
                                            ];
                                        }
                                        $_table_data[] = [
                                            'name'    => _l('mailbox_date'),
                                            'th_attrs'=> ['class'=>'toggleable', 'id'=>'th-mailbox-date'],
                                        ];
                                        foreach ($_table_data as $_t) {
                                            array_push($table_data, $_t);
                                        }

                                        $table_data = hooks()->apply_filters('mailbox_table_columns', $table_data);

                                        render_datatable($table_data, 'mailbox', [], [
                                            'data-last-order-identifier' => 'mailbox',
                                            'data-default-order'         => get_table_last_order('mailbox'),
                                        ]);
                                     ?>
                                <?php } ?>                          
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script type="text/javascript">
	"use strict";

    $(function() {
        init_btn_with_tooltips();
        init_tabs_scrollable();
        var webmailTableNotSortable = [0];
        initDataTable('.table-mailbox', admin_url + 'mailbox/table/<?php echo $group; ?>', 'undefined', webmailTableNotSortable, 'undefined', [5, 'desc']);

        appValidateForm($('#mailbox-config-form'), {
            email: 'required',
            mail_password: 'required',
        });
        
        $("body").on("change", ".mail-tag", function (event, state) {
            let mail_id = $(this).data('id');
            let mail_type = $(this).data('type');
            let tag_id = $(this).find('option:selected').data('id');
            if (typeof tag_id === 'undefined') {
                tag_id = 0;
            }
            $.ajax({
                url: admin_url + 'mailbox/update_mail_tag/' + mail_id + '/' + tag_id + '/' + mail_type
            }).done(function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    alert_float('success', response.message);
                }
            }).fail(function(error) {
                alert_float('danger', JSON.parse(error.responseText));
            });
        });
        
        $("body").on("change", ".mail-template", function (event, state) {
            let mail_id = $(this).data('id');
            let mail_type = $(this).data('type');
            let template_id = $(this).find('option:selected').data('id');
            if (typeof template_id === 'undefined') {
                template_id = 0;
            }
            $.ajax({
                url: admin_url + 'mailbox/update_mail_template/' + mail_id + '/' + template_id + '/' + mail_type
            }).done(function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    alert_float('success', response.message);
                }
            }).fail(function(error) {
                alert_float('danger', JSON.parse(error.responseText));
            });
        });
    });
</script>
</body>
</html>