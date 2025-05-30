<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'mailbox-compose-form']); ?>

<div class="clearfix mtop20"></div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <i class="fa fa-question-circle mbpull-left" data-toggle="tooltip" data-title="<?php echo _l('mailbox_multi_email_split'); ?>"></i>
            <?php
                $to             = '';
                $cc             = '';
                $subject        = '';
                $body           = '';
                $scheduled_at   = '';
            ?>
            <?php if (isset($mail)) {
                $to             = $mail->to;
                $cc             = $mail->cc;
                $subject        = $mail->subject;
                $body           = $mail->body;
                $scheduled_at   = $mail->scheduled_at ? $mail->scheduled_at->format('Y-m-d H:i') : '';
            }
            ?>
            <div class="email-autocomplete">
                <?php echo render_input('to', 'mailbox_to', $to); ?>
            </div>
            <?php echo render_input('cc', 'CC', $cc); ?>
            <?php echo render_input('subject', 'mailbox_subject', $subject); ?>

            <div class="form-group select-placeholder">
                <label for="templateid" class="control-label"><?= _l('email_template'); ?></label>
                <select name="templateid" data-live-search="true" id="templateid" class="form-control selectpicker" data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                    <option></option>
                    <?php foreach ($email_templates as $email_template) { ?>
                        <option value="<?= $email_template['emailtemplateid']; ?>" <?= isset($mail) && $mail->templateid == $email_template['emailtemplateid'] ? 'selected' : ''; ?>>
                            <?= e($email_template['name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            
            <?php
                $CI = &get_instance();
                $CI->db->select()->from(db_prefix().'mail_tags')->where(db_prefix().'mail_tags.active', 1);
                $mailbox_tags = $CI->db->get()->result_array();

                $CI = &get_instance();
                $CI->db->select()->from(db_prefix().'emailtemplates')->where(db_prefix().'emailtemplates.active', 1)->where(db_prefix().'emailtemplates.language', 'english');
                $email_templates = $CI->db->get()->result_array();

                $CI->db->select()->from(db_prefix().'staff')->where(db_prefix().'staff.mail_password !=', '');
                $staffs = $CI->db->get()->result_array();
                
                $myid = get_staff_user_id();
                
                $CI->db->select()->from(db_prefix().'staff')->where('staffid', $myid );
                $currentusers = $CI->db->get()->result_array();
                
                foreach ($currentusers as $currentuser) {
                    $mail_signature = $currentuser['mail_signature'];
                }
            ?>

            <hr />
            <?php 
                if ($mail_signature !== null) {
                    echo render_textarea('body', 'mailbox_body', $body.$mail_signature, [], [], '', 'tinymce tinymce-compose');
                } else {
                    echo render_textarea('body', 'mailbox_body', $body, [], [], '', 'tinymce tinymce-compose');
                }
            ?>
        </div>
        <div class="attachments">
            <div class="attachment">
                <div class="mbot15">
                    <div class="form-group">
                        <label for="attachment" class="control-label"><?php echo _l('ticket_add_attachments'); ?></label>
                        <div class="input-group">
                            <input type="file" extension="<?php echo str_replace('.', '', get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments[0]" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-success add_more_attachments p8-half" data-max="<?php echo get_option('maximum_allowed_ticket_attachments'); ?>" type="button"><i class="fa fa-plus"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group select-placeholder">
            <label for="tagid" class="control-label"><?= _l('mailbox_tag'); ?></label>
            <select name="tagid" data-live-search="true" id="tagid" class="form-control selectpicker" data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                <option></option>
                <?php foreach ($mailbox_tags as $mailbox_tag) { ?>
                    <option value="<?= $mailbox_tag['id']; ?>" <?= isset($mail) && $mail->tagid == $mailbox_tag['id'] ? 'selected' : ''; ?>>
                        <?= e($mailbox_tag['name']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        
        <?php echo render_datetime_input('scheduled_at', 'mailbox_scheduled_at', $scheduled_at); ?>

        <div class="btn-group mbpull-left">
            <a href="<?php echo admin_url().'mailbox'; ?>" class="btn btn-warning close-send-template-modal"><?php echo _l('cancel'); ?></a> 
        </div>
        <div class="pull-right">
            <?php if (!isset($mail)) { ?>
                <button type="submit" name="sendmail" value="draft" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-primary mbot10">
                    <i class="fa fa-file menu-icon"></i> <?php echo _l('mailbox_save_draft'); ?>
                </button>
            <?php } ?>
            <?php if (isset($mail) && !$mail->draft) { ?>
                <a class="btn btn-info mbot10" type="button" data-toggle="modal" data-target="#customers_item_modal"><i class="fa fa-user"></i> <?php echo _l('assign_customers'); ?></a>
                <a class="btn btn-success mbot10" type="button" data-toggle="modal" href="<?= admin_url('mailbox/insert_task_data'); ?>"><i class="fa fa-life-ring"></i> <?php echo _l('assign_task'); ?></a>
                <button class="btn btn-succes mbot10s" type="button" data-toggle="modal" data-target="#sales_item_modal"><i class="fa fa-bullhorn"></i> <?php echo _l('assign_to_leads'); ?></button>
                <button class="btn btn-danger mbot10" type="button" data-toggle="modal" data-target="#ticket_item_modal"><i class="fa fa-life-ring"></i> <?php echo _l('assign_to_tickets'); ?></button>
            <?php } ?>
            <button type="submit" name="sendmail" value="outbox" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" class="btn btn-info mbot10">
                <i class="fa fa-paper-plane menu-icon"></i>
                <?php echo _l('mailbox_send'); ?>
            </button>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

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
                                            $selected = mailbox_get_client_companies(isset($outbox_id) ? $outbox_id : '', 'outbox', false);
                                            if (is_admin()) {
                                                echo render_select_with_input_group('select_customers[]', $clients, ['userid', 'company'], 'select_customers', $selected, '<div class="input-group-btn"><a href="#" class="btn btn-default" data-toggle="modal" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a></div>', ['multiple' => true, 'data-actions-box' => true, 'required' => true], [], '', '', false);
                                            } else {
                                                echo render_select('select_customers[]', $clients, ['userid', 'company'], 'select_customers', $selected, ['multiple' => true, 'data-actions-box' => true, 'required' => true], [], '', '', false);
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="mailbox_id" value="<?php echo isset($outbox_id) ? $outbox_id : '' ?>" >
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

<div class="modal fade" id="sales_item_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <?php echo form_open_multipart(admin_url().'mailbox/conversationLead', ['id'=>'lead_assign_form']); ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
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
                                            $selected = [];
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
                        <input type="hidden" name="outbox_id" value="<?php echo isset($outbox_id) ? $outbox_id : '' ?>" >
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

<div class="modal fade" id="ticket_item_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <?php echo form_open_multipart(admin_url().'mailbox/conversationTicket', ['id'=>'ticket_assign_form']); ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
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
                                        <select id="clientid" name="select_customer[]" multiple="false" data-live-search="true" data-width="100%" class="ajax-search<?php if(isset($invoice) && empty($invoice->clientid)){echo ' customer-removed';} ?>" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                            <?php $selected = (isset($invoice) ? $invoice->clientid : '');

                                            if ($selected == '') {
                                                $selected = (isset($customer_id) ? $customer_id: '');
                                            }

                                            if ($selected != '') {
                                                $rel_data = get_relation_data('customer', $selected);
                                                $rel_val = get_relation_values($rel_data,'customer');
                                                echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                                            } ?>
							            </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="outbox_id" value="<?php echo isset($outbox_id) ? $outbox_id : '' ?>" >
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

<div class="modal fade" id="task_item_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <?php echo form_open_multipart(admin_url().'mailbox/conversationTask', ['id'=>'task_assign_form']); ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
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
							            <select id="clientid" name="select_customer[]" multiple="false" data-live-search="true" data-width="100%" class="ajax-search<?php if(isset($invoice) && empty($invoice->clientid)){echo ' customer-removed';} ?>" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
							                <?php $selected = (isset($invoice) ? $invoice->clientid : '');

                                            if ($selected == '') {
                                                $selected = (isset($customer_id) ? $customer_id: '');
                                            }

                                            if ($selected != '') {
                                                $rel_data = get_relation_data('customer', $selected);
                                                $rel_val = get_relation_values($rel_data,'customer');
                                                echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                                            } ?>
							            </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="outbox_id" value="<?php echo isset($outbox_id) ? $outbox_id : '' ?>" >
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