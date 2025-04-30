<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="clearfix mtop20"></div>

<div class="">
    <div class="email-media">
        <div class="media mt-0">
            <?php echo staff_profile_image($mailbox->from_staff_id, ['mr-2 rounded-circle']); ?>
            
            <div class="media-body">
                <div class="float-right d-md-flex fs-15">
                    <small class="mr-2"><?php echo _dt($mailbox->date_received); ?></small>
                    <?php
                        $starred       = 'fa-star text-dark';
                        $msg_starred   = _l('mailbox_add_star');
                        $important     = 'fa-bookmark text-dark';
                        $msg_important = _l('mailbox_mark_as_important');
                        if (1 == $mailbox->stared) {
                            $starred = 'fa-star orange';
                            $msg_starred = _l('mailbox_remove_star');
                        }
                        if (1 == $mailbox->important) {
                            $important     = 'fa fa-bookmark green';
                            $msg_important = _l('mailbox_mark_as_not_important');
                        }
                    ?>
                    <small class="mr-2 cursor" onclick="update_field('detail','starred',<?php echo $mailbox->stared; ?>,<?php echo $mailbox->id; ?>);"><i class="fa <?php echo $starred; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $msg_starred; ?>"></i></small>
                    <small class="mr-2 cursor" onclick="update_field('detail','important',<?php echo $mailbox->important; ?>,<?php echo $mailbox->id; ?>);"><i class="fa <?php echo $important; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $msg_important; ?>"></i></small>
                    <small class="mr-2 cursor"><a href="<?php echo admin_url().'mailbox/reply/'.$mailbox->id.'/reply'; ?>"><i class="fa fa-reply text-dark" data-toggle="tooltip" title="" data-original-title="<?php echo _l('mailbox_reply'); ?>"></i></a></small>
                </div>
                <div class="media-title text-dark font-weight-semiblod"><?php echo $mailbox->sender_name; ?> <span class="text-muted">( <?php echo $mailbox->from_email; ?> )</span></div>
                <p class="mb-0 font-weight-semiblod">To: <?php echo $mailbox->to; ?></p>
                <p class="mb-0 font-weight-semiblod">Cc: <?php echo $mailbox->cc; ?></p>
            </div>
        </div>
    </div>
    <div class="email-body">
        <p><?php echo $mailbox->body; ?></p>
        <hr>
        <?php if ($mailbox->has_attachment > 0) { ?>
            <div class="email-attch">
                <p><?php echo _l('mailbox_file_attachment'); ?></p>
                <div class="emai-img">
                    <div class="">
                        <?php foreach ($attachments as $attachment) {
                            $attachment_url = module_dir_url(MAILBOX_MODULE).'uploads/'.$type.'/'.$mailbox->id.'/'.$attachment['file_name'];  ?>
                            <div class="mbot15 row" data-attachment-id="<?php echo $attachment['id']; ?>">
                                <div class="col-md-8">
                                    <div class="mbpull-left"><i class="<?php echo get_mime_class($attachment['file_type']); ?>"></i></div>
                                    <a href="<?php echo $attachment_url; ?>" target="_blank"><?php echo $attachment['file_name']; ?></a>
                                    <br />
                                    <small class="text-muted"> <?php echo $attachment['file_type']; ?></small>
                                </div>
                            </div>

                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="pull-left">
	    <select class="btn btn-default mail-tag mbot10" style="color :#000;" data-id="<?php echo $mailbox->id; ?>" data-type="inbox">
            <option></option>
            <?php foreach (get_mail_tags() as $mailbox_tag) { ?>
                <option style="text-align: left; ext-indent: 0;" data-id="<?php echo $mailbox_tag['id'] ?>" <?php echo ($mailbox_tag['id'] == $mailbox->tagid ? 'selected' : '') ?>><?php echo $mailbox_tag['name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="pull-right">
        <a class="btn btn-info mbot10" type="button" data-toggle="modal" data-target="#customers_item_modal"><i class="fa fa-user"></i> <?php echo _l('assign_customers'); ?></a>
        <a class="btn btn-danger mbot10" type="button" data-toggle="modal" href="<?= admin_url('mailbox/insert_task_data?email_subject=' . urlencode($mailbox->subject) . '&email_body=' . urlencode($mailbox->body)); ?>"><i class="fa fa-tasks"></i> <?php echo _l('assign_task'); ?></a>
        <button class="btn btn-success mbot10" type="button" data-toggle="modal" data-target="#sales_item_modal"><i class="fa fa-bullhorn"></i> <?php echo _l('assign_to_leads'); ?></button>
        <button class="btn btn-danger mbot10" type="button" data-toggle="modal" data-target="#ticket_item_modal"><i class="fa fa-life-ring"></i> <?php echo _l('assign_to_tickets'); ?></button>
        <a href="<?php echo admin_url().'mailbox/reply/'.$mailbox->id.'/reply'; ?>" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-warning mbot10">
            <i class="fa fa-reply"></i></i> <?php echo _l('mailbox_reply'); ?>
        </a>
        <a href="<?php echo admin_url().'mailbox/reply/'.$mailbox->id.'/forward'; ?>" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-info mbot10">
            <i class="fa fa-share"></i>
            <?php echo _l('mailbox_forward'); ?>
        </a>
    </div>
</div>

<script>
    var mailid = <?php echo $mailbox->id; ?>;
    var mailtype = '<?php echo $type; ?>';
</script>

<div class="modal fade" id="customers_item_modal" tabindex="-1" role="dialog" aria-labelledby="customersItemModalLabel">
    <?php echo form_open_multipart(admin_url().'mailbox/assign_customers', ['id'=>'customer_assign_form']); ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="customersItemModalLabel">
                    <span class="edit-title"><?php echo _l('assign_customers'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning affect-warning hide">
                            <?php echo _l('changing_items_affect_warning'); ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div id="customers">
                                        <?php
                                            $selected = mailbox_get_client_companies($mailbox_id, 'inbox', false);
                                            if (is_admin()) {
                                                echo render_select_with_input_group('select_customers[]', $clients, ['userid', 'company'], 'select_customers', $selected, '<div class="input-group-btn"></div>', ['multiple' => true, 'data-actions-box' => true, 'required' => true], [], '', '', false);
                                            } else {
                                                echo render_select('select_customers[]', $clients, ['userid', 'company'], 'select_customers', $selected, ['multiple' => true, 'data-actions-box' => true, 'required' => true], [], '', '', false);
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="mailbox_id" value="<?php echo $mailbox_id ?>" />
                        <input type="hidden" name="type" value="inbox" />
                        <div class="clearfix mbot15"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<div class="modal fade" id="sales_item_modal" tabindex="-1" role="dialog" aria-labelledby="salesItemModalLabel">
    <?php echo form_open_multipart(admin_url().'mailbox/conversationLead_inbox', ['id'=>'lead_assign_form']); ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="salesItemModalLabel">
                    <span class="edit-title"><?php echo _l('assign_lead'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning affect-warning hide">
                            <?php echo _l('changing_items_affect_warning'); ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div id="leads">
                                        <?php
                                            $selected = mailbox_get_leads($mailbox_id, 'inbox', false);
                                            if (is_admin() || get_option('staff_members_create_inline_customer_groups') == '1') {
                                                echo render_select_with_input_group('select_lead[]', $leads, ['id', 'name'], 'select_lead', $selected, '<div class="input-group-btn"><a href="#" class="btn btn-default" data-toggle="modal" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a></div>', ['multiple' => true, 'data-actions-box' => true, 'required' => true], [], '', '', false);
                                            } else {
                                                echo render_select('select_lead[]', $leads, ['id', 'name'], 'select_lead', $selected, ['multiple' => true, 'data-actions-box' => true, 'required' => true], [], '', '', false);
                                            }
                                        ?>  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="mailbox_id" value="<?php echo $mailbox_id ?>" >
                        <div class="clearfix mbot15"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<div class="modal fade" id="customer_group_modal" tabindex="-1" role="dialog" aria-labelledby="customerGroupModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="customerGroupModalLabel">
                    <span class="edit-title"><?php echo _l('customer_group_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('add_new', _l('lead_lowercase')); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/leads/lead', ['id' => 'customer-group-modal']); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_input('name', 'lead_group_name'); ?>
						<?php echo render_input('email', 'lead_group_email'); ?>
                        <?php echo form_hidden('id'); ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="description" value="" >
            <input type="hidden" name="address" value="" >
            <input type="hidden" name="assigned" value="" >
            <input type="hidden" name="status" value="1">
            <input type="hidden" name="source" value="1">
            <div class="modal-footer">
                <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button group="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        appValidateForm($('#customer-group-modal'), {
        name: 'required',
            email: {
                required: true,
                email: true
            }
        }, manage_customer_groups);
        $('#customer_group_modal').on('show.bs.modal', function(e) {
            var invoker = $(e.relatedTarget);
            var group_id = $(invoker).data('id');
            $('#customer_group_modal .add-title').removeClass('hide');
            $('#customer_group_modal .edit-title').addClass('hide');
            $('#customer_group_modal input[name="id"]').val('');
            $('#customer_group_modal input[name="name"]').val('');
            // is from the edit button
            if (typeof(group_id) !== 'undefined') {
                $('#customer_group_modal input[name="id"]').val(group_id);
                $('#customer_group_modal .add-title').addClass('hide');
                $('#customer_group_modal .edit-title').removeClass('hide');
                $('#customer_group_modal input[name="name"]').val($(invoker).parents('tr').find('td').eq(0).text());
            }
        });
    });
    function manage_customer_groups(form) {
        var data = $(form).serialize();
        var url = form.action;
        var formData = new URLSearchParams(data);
        var nameValue = formData.get('name');
        $.post(url, data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                if ($.fn.DataTable.isDataTable('.table-customer-groups')) {
                    $('.table-customer-groups').DataTable().ajax.reload();
                }
                if ($('body').hasClass('dynamic-create-groups') && typeof(response.id) != 'undefined') {
                    console.log(data);
                    var groups = $('select[name="select_lead[]"]');
                    groups.prepend('<option value="'+response.id+'">'+nameValue+'</option>');
                    groups.selectpicker('refresh');
                }
                alert_float('success', response.message);
            }
            $('#customer_group_modal').modal('hide');
        });
        return false;
    }
</script>

<div class="modal fade" id="ticket_item_modal" tabindex="-1" role="dialog" aria-labelledby="ticketItemModalLabel">
    <?php echo form_open_multipart(admin_url().'mailbox/conversationTicket_inbox', ['id'=>'ticket_assign_form']); ?>
    <input type="hidden" id="emailBodyInput" name="email_message">
    <input type="hidden" name="subject" value="<?php echo $mailbox->subject; ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ticketItemModalLabel">
                    <span class="edit-title"><?php echo _l('assign_ticket'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning affect-warning hide">
                            <?php echo _l('changing_items_affect_warning'); ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div id="tickets">
                                        <?php
                                            $selected = [];
                                            echo render_select('select_customer[]', $contacts, ['id', 'name'], 'select_customer', $selected, ['multiple' => false, 'data-actions-box' => true, 'required' => true], [], '', '', false);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="mailbox_id" value="<?php echo $mailbox_id ?>" >
                        <div class="clearfix mbot15"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<div class="modal fade" id="task_item_modal" tabindex="-1" role="dialog" aria-labelledby="taskItemModalLabel">
    <?php echo form_open_multipart(admin_url().'mailbox/conversationTask_inbox', ['id'=>'task_assign_form']); ?>
    <input type="hidden" id="emailBodyInput" name="email_message">
    <input type="hidden" name="subject" value="<?php echo $mailbox->subject; ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="taskItemModalLabel">
                    <span class="edit-title"><?php echo _l('assign_task'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning affect-warning hide">
                            <?php echo _l('changing_items_affect_warning'); ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div id="tasks">
                                        <select id="clientid" name="select_customer[]" multiple="false" data-live-search="true" data-width="100%" class="ajax-search<?php if (isset($invoice) && empty($invoice->clientid)) {echo ' customer-removed';} ?>" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                            <?php $selected = (isset($invoice) ? $invoice->clientid : '');

                                            if ($selected == '') {
                                                $selected = (isset($customer_id) ? $customer_id: '');
                                            }

                                            if ($selected != '') {
                                                $rel_data = get_relation_data('customer',$selected);
                                                $rel_val = get_relation_values($rel_data,'customer');
                                                echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="mailbox_id" value="<?php echo $mailbox_id ?>" >
                        <div class="clearfix mbot15"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>