<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Mailbox Model.
 */
class Mailbox_model extends App_Model
{
    /**
     * Controler __construct function to initialize options.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new ticket to database.
     *
     * @param mixed $data  ticket $_POST data
     * @param mixed $admin If admin adding the ticket passed staff id
     */
    public function add($data, $staff_id, $ob_id = null)
    {
        $outbox_id                      = '';
        $outbox                         = [];
        $outbox['sender_staff_id']      = $staff_id;
        $outbox['to']                   = $data['to'];
        $outbox['cc']                   = $data['cc'];
        $outbox['sender_name']          = get_staff_full_name($staff_id);
        $outbox['subject']              = _strip_tags($data['subject']);
        $outbox['body']                 = _strip_tags($data['body']);
        $outbox['body']                 = nl2br_save_html($outbox['body']);
        $outbox['date_sent']            = date('Y-m-d H:i:s');
        $outbox['tagid']                = $data['tagid'];
        $outbox['templateid']           = $data['templateid'];
        $outbox['scheduled_at']         = to_sql_date($data['scheduled_at'], true);
        $outbox['scheduled_status']     = $data['scheduled_at'] ? "Scheduled" : "";
        if (isset($data['reply_from_id'])) {
            $outbox['reply_from_id'] = $data['reply_from_id'];
        }
        if (isset($data['reply_type'])) {
            $outbox['reply_type'] = $data['reply_type'];
        }
        if (isset($data['sendmail']) && 'draft' == $data['sendmail']) {
            $outbox['draft']      =  1;
            $this->db->insert(db_prefix().'mail_outbox', $outbox);

            return true;
        }
        if (isset($ob_id)) {
            $outbox['draft'] = 0;
            $this->db->where('id', $ob_id);
            $this->db->update(db_prefix().'mail_outbox', $outbox);
            $outbox_id = $ob_id;
        } else {
            $this->db->insert(db_prefix().'mail_outbox', $outbox);
            $outbox_id = $this->db->insert_id();
        }
        $inbox                       = [];
        $inbox['from_staff_id']      = $staff_id;
        $inbox['to']                 = $data['to'];
        $inbox['cc']                 = $data['cc'];
        $inbox['sender_name']        = get_staff_full_name($staff_id);
        $inbox['subject']            = _strip_tags($data['subject']);
        $inbox['body']               = _strip_tags($data['body']);
        $inbox['body']               = nl2br_save_html($inbox['body']);
        $inbox['date_received']      = date('Y-m-d H:i:s');
        $inbox['folder']             = 'inbox';
        $inbox['from_email']         = get_staff_email_by_id($staff_id);
        $inbox['tagid']              = $data['tagid'];
        $inbox['templateid']         = $data['templateid'];

        $array_send_to = [];
        $array_to      = explode(';', $data['to']);
        if (isset($array_to) && count($array_to) > 0) {
            foreach ($array_to as $value) {
                $array_send_to[$value] = $value;
            }
        }
        $array_cc = explode(';', $data['cc']);
        if (isset($array_cc) && count($array_cc) > 0) {
            foreach ($array_cc as $value) {
                $array_send_to[$value] = $value;
            }
        }

        $array_inbox_id = [];
        foreach ($array_send_to as $value) {
            $to = get_staff_id_by_email(trim($value));
            if ($to > 0) {
                $d_inbox                = $inbox;
                $d_inbox['to_staff_id'] = $to;
                $this->db->insert(db_prefix().'mail_inbox', $d_inbox);
                $inbox_id         = $this->db->insert_id();
                $array_inbox_id[] = $inbox_id;
            }
        }
        $attachments = [];
        if ($outbox_id > 0) {
            if (count($array_inbox_id) > 0) {
                foreach ($array_inbox_id as $inbox_id) {
                    $attachments = handle_mail_attachments($inbox_id, 'inbox', 'attachments', 'copy');
                    if ($attachments) {
                        $this->insert_mail_attachments_to_database($attachments, $inbox_id, 'inbox');
                    }
                }
            }

            $attachments = handle_mail_attachments($outbox_id, 'outbox');
            if ($attachments) {
                $this->insert_mail_attachments_to_database($attachments, $outbox_id, 'outbox');
            }
        }

        // Send email
        if (strlen(get_option('smtp_host')) > 0 && strlen(get_option('smtp_password')) > 0 && strlen(get_option('smtp_username')) > 0) {
            $ci = &get_instance();
            $ci->email->initialize();
            $ci->load->library('email');
            $ci->email->clear(true);
            $ci->email->from($inbox['from_email'], $inbox['sender_name']);
            $ci->email->to(str_replace(';', ',', $data['to']));
            if (isset($data['cc']) && strlen($data['cc']) > 0) {
                $ci->email->cc($data['cc']);
            }
            $ci->email->subject($inbox['subject']);
            $ci->email->message($data['body']);
            foreach ($attachments as $attachment) {
                $attachment_url = module_dir_url(MAILBOX_MODULE).'uploads/outbox/'.$outbox_id.'/'.$attachment['file_name'];
                $ci->email->attach($attachment_url);
            }
            $ci->email->send(true);
        }

        return true;
    }

    /**
     * Insert mail attachments to database.
     *
     * @param array $attachments array of attachment
     * @param mixed $mail_id
     */
    public function insert_mail_attachments_to_database($attachments, $mail_id, $type = 'inbox')
    {
        foreach ($attachments as $attachment) {
            $attachment['mail_id']  = $mail_id;
            $attachment['type']     = $type;
            $this->db->insert(db_prefix().'mail_attachment', $attachment);
            $this->db->where('id', $mail_id);
            $this->db->update(db_prefix().'mail_'.$type, [
                'has_attachment' => 1,
            ]);
        }
    }

    /**
     * Get detail email by id and type.
     *
     * @param int    $id
     * @param string $type
     *
     * @return row
     */
    public function get($id, $type='inbox')
    {
        $this->db->where('id', $id);

        return $this->db->get(db_prefix().'mail_'.$type)->row();
    }

    /**
     * Update email status.
     *
     * @param int    $group
     * @param string $action
     * @param int    $value
     * @param int    $mail_id
     * @param string $type
     *
     * @return bool
     */
    public function update_field($group, $action, $value, $mail_id, $type = 'inbox')
    {
        if ('starred' == $action) {
            $action = 'stared';
        }
        $arr_id = explode(',', $mail_id);
        foreach ($arr_id as $id) {
            if (strlen(trim($id)) > 0) {
                if (('trash' == $group || 'sent' == $group) && 'trash' == $action) {
                    if ('sent' == $group) {
                        $type = 'outbox';
                    }
                    $this->db->where('id', $id);
                    $this->db->delete(db_prefix().'mail_'.$type);

                    $this->db->where('mail_id', $id);
                    $file = $this->db->get(db_prefix().'mail_attachment')->result_array();
                    foreach ($file as $f) {
                        $path           = MAILBOX_MODULE_UPLOAD_FOLDER.'/'.$type.'/'.$id.'/'.$f['file_name'];
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                    $this->db->where('mail_id', $id);
                    $this->db->where('type', $type);
                    $this->db->delete(db_prefix().'mail_attachment');
                } else {
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix().'mail_'.$type, [
                        $action => $value,
                    ]);
                }
            }
        }
        return true;
    }

    /**
     * Get email attachments.
     *
     * @param int    $mail_id
     * @param string $type
     *
     * @return array
     */
    public function get_mail_attachment($mail_id, $type='inbox')
    {
        $this->db->where('mail_id', $mail_id);
        $this->db->where('type', $type);

        return $this->db->get(db_prefix().'mail_attachment')->result_array();
    }

    /**
     * Update email configuration.
     *
     * @param array $data
     * @param int   $staff_id
     *
     * @return bool
     */
    public function update_config($data, $staff_id)
    {
        unset($data['email']);
        $this->db->where('staffid', $staff_id);
        $this->db->update(db_prefix().'staff', $data);

        return true;
    }

    /**
     * Clients Data.
     *
     */
    public function select_client()
    {
        $this->db->select('*');
        $data = $this->db->get(db_prefix().'clients')->result_array();
        return $data;
    }

    /**
     * Leads Data.
     *
     */
    public function select_lead()
    {
        $this->db->select('*');
        $this->db->where("lost", 0);
        $this->db->where("junk", 0);
        $data = $this->db->get(db_prefix().'leads')->result_array();
        return $data;
    }

    public function conversation($data) {
        foreach($data['select_lead'] as $value) {
            $lead_mail = [];
            $lead_mail['outbox_id'] = $data['outbox_id'];
            $lead_mail['lead_id'] = $value;
            $this->db->select('*');
            $this->db->where("outbox_id", $data['outbox_id']);
            $this->db->where("lead_id", $value);
            $select_data = $this->db->get(db_prefix().'mail_conversation')->result_array();
            if (empty($select_data)) {
               $this->db->insert(db_prefix().'mail_conversation', $lead_mail);
               $mail_conversation_id = $this->db->insert_id();
            }   
        }
        return true;
    }

    public function conversation_inbox($data) {
        foreach($data['select_lead'] as $value) {
            $lead_mail = [];
            $lead_mail['inbox_id'] = $data['inbox_id'];
            $lead_mail['lead_id'] = $value;
            $this->db->select('*');
            $this->db->where("inbox_id", $data['inbox_id']);
            $this->db->where("lead_id", $value);
            $select_data = $this->db->get(db_prefix().'mail_conversation')->result_array();
            if (empty($select_data)) {
               $this->db->insert(db_prefix().'mail_conversation', $lead_mail);
               $mail_conversation_id = $this->db->insert_id();
            } 
        }
        return true;
    }

    public function delete_mail_conversation($id) {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'mail_conversation');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;    
    }	
	
    /**
     * Tickets Data.
     *
     */
    public function select_ticket()
    {
        $this->db->select('*');
		$this->db->where("status", 5);
        $data = $this->db->get(db_prefix().'tickets')->result_array();

        return $data;
    }
	
    public function conversationTicket($data) {
        foreach($data['select_ticket'] as $value) {
            $ticket_mail = [];
            $ticket_mail['outbox_id'] = $data['outbox_id'];
            $ticket_mail['ticket_id'] = $value;
            $this->db->select('*');
            $this->db->where("outbox_id", $data['outbox_id']);
            $this->db->where("ticket_id", $value);
            $select_data = $this->db->get(db_prefix().'mail_conversation')->result_array();
            if (empty($select_data)) {
               $this->db->insert(db_prefix().'mail_conversation', $ticket_mail);
               $mail_conversation_id = $this->db->insert_id();
            }   
        }
        return true;
    }

	public function conversationTicket_inbox($data, $mailsubject) {
		$selected_customers = $this->input->post('select_customer');
		$email_message = $data['email_message'];
		foreach ($selected_customers as $ticketuserid) {
			$ticket_mail = [];
			$ticket_mail['subject'] = $mailsubject; // Use the passed $mailsubject variable
			$ticket_mail['message'] = $_POST['email_message'];
			$ticket_mail['status'] = 1;
			$ticket_mail['department'] = 1;
			$ticket_mail['priority'] = 2;
			$this->db->select('id');
			$this->db->where('userid', $ticketuserid);
			$thecontactid = $this->db->get(db_prefix().'contacts')->row();
			$ticket_mail['contactid'] = $thecontactid->id;
			$ticket_mail['userid'] = $ticketuserid;
			$ticket_mail['date'] = date("Y-m-d H:i:s");
			$ticket_mail['assigned'] = 1;
			$this->db->insert(db_prefix().'tickets', $ticket_mail);
			$ticket_id = $this->db->insert_id();
		}
		
		return true;
	}

    public function search_contacts($search = '', $where = [])
	{
        $this->db->select('contacts.id,contacts.userid,contacts.is_primary,contacts.firstname,contacts.lastname,contacts.email,clients.company');
        $this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.userid = ' . db_prefix() . 'contacts.userid', 'left');
        $this->db->join(db_prefix() . 'countries', '' . db_prefix() . 'countries.country_id = ' . db_prefix() . 'clients.country', 'left');

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != ''))
        {
            $this->db->where($where);
        }
        if ($search)
        {
            $this->db->where("LOWER(firstname) LIKE '%" . strtolower($search) . "%'");
            $this->db->or_where("LOWER(lastname) LIKE '%" . strtolower($search) . "%'");
            $this->db->or_where("LOWER(email) LIKE '%" . strtolower($search) . "%'");
        }
        
        return $this->db->get(db_prefix() . 'contacts')->result_array();
    }

    /**
     * Get mailbox tags
     * @param  mixed $tag_id
     * @param  array $where       perform where query
     * @param  array $whereIn     perform whereIn query
     * @return array
     */
    public function get_tags($tag_id = '', $where = ['active' => 1], $whereIn = [])
    {
        $this->db->where($where);
        if ($tag_id != '') {
            $this->db->where('id', $tag_id);
        }
        foreach ($whereIn as $key => $values) {
            if (is_string($key) && is_array($values)) {
                $this->db->where_in($key, $values);
            }
        }
        return $this->db->get(db_prefix() . 'mail_tags')->result_array();
    }

    /**
     * Get single mailbox tag
     * @param  mixed $id tag id
     * @return object
     */
    public function get_tag($id = '')
    {
        $this->db->where('id', $id);
        return $this->db->get(db_prefix() . 'mail_tags')->row();
    }

    /**
     * Add new mailbox tag
     * @param array  $data               $_POST data
     */
    public function add_tag($data)
    {
        if (isset($data['custom_fields'])) {
            $custom_fields           = $data['custom_fields'];
            unset($data['custom_fields']);
        }
        $data['name']                       = trim($data['name']);
        $data                               = hooks()->apply_filters('before_create_mailbox_tag', $data);
        $this->db->insert(db_prefix() . 'mail_tags', $data);
        $tag_id = $this->db->insert_id();
        if ($tag_id) {
            if (isset($custom_fields)) {
                handle_custom_fields_post($tag_id, $custom_fields);
            }
            log_activity('Mailbox Tag Created [ID: ' . $tag_id . ']');
            hooks()->do_action('mailbox_tag_created', $tag_id);
            return $tag_id;
        }
        return false;
    }

    /**
     * Update mailbox tag data
     * @param  array  $data           $_POST data
     * @param  mixed  $id             tag id
     * @return mixed
     */
    public function update_tag($data, $id)
    {
        $affectedRows   = 0;
        $tag            = $this->get_tag($id);
        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }
        $data = hooks()->apply_filters('before_update_mailbox_tag', $data, $id);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'mail_tags', $data);
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            hooks()->do_action('mailbox_tag_updated', $id, $data);
        }
        if ($affectedRows > 0) {
            log_activity('Mailbox Tag Updated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Change mailbox tag status
     * @param  mixed $id        tag id
     * @param  mixed $status    tag status
     * @return object
     */
    public function change_tag_status($id, $status)
    {
        $status = hooks()->apply_filters('change_tag_status', $status, $id);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'mail_tags', ['active' => $status, ]);
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('mailbox_tag_status_changed', ['id' => $id, 'status' => $status, ]);
            log_activity('Mailbox Tag Status Changed [TagID: ' . $id . ' Status(Active/Inactive): ' . $status . ']');
            return true;
        }
        return false;
    }

    /**
     * Update mailbox tag
     * @param  mixed $id        mail id
     * @param  mixed $tag_id    tag id
     * @param  mixed $type      type
     * @return object
     */
    public function update_mail_tag($id, $tag_id, $type = 'outbox')
    {
        if ($tag_id) {
            $this->db->where('id', $tag_id);
            $email_tag = $this->db->get(db_prefix() . 'mail_tags')->row();
        } else {
            $tag_id = null;
        }
        $this->db->where('id', $id);
        $email = $this->db->get(db_prefix() . 'mail_' . $type)->row();
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'mail_' . $type, ['tagid' => $tag_id, ]);
        $affectedRows = 0;
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            hooks()->do_action('mailbox_tag_changed', ['id' => $email->id, 'tagid' => $tag_id, ]);
            log_activity('Email Tag Changed [ID: ' . $email->id . (isset($email_tag) && $email_tag ? ' Tag: ' . $email_tag->name : '' ) . ']');
        }

        return $affectedRows ? true : false;
    }

    /**
     * Delete mailbox tag
     * @param  mixed $id        tag id
     * @return object
     */
    public function delete_tag($id)
    {
        $affectedRows = 0;
        hooks()->do_action('before_mailbox_tag_deleted', $id);
        $last_activity = get_last_system_activity_id();
        if ($id) {
            $this->db->where('id', $id);
        }
        $this->db->delete(db_prefix() . 'mail_tags');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            hooks()->do_action('after_mailbox_tag_deleted', $id);
            // Delete activity log caused by delete customer function
            if ($last_activity) {
                $this->db->where('id >', $last_activity->id);
                $this->db->delete(db_prefix() . 'activity_log');
            }
            log_activity('Mailbox Tag Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Update Email Template
     * @param array  $data      $_POST data
     * @param  mixed $id        email template id
     * @return object
     */
    public function update_email_template($data, $id)
    {
        $this->db->where('emailtemplateid', $id);
        $email_template = $this->db->get(db_prefix() . 'emailtemplates')->row();

        $affectedRows = 0;
        $this->db->where('emailtemplateid', $id);
        $this->db->update(db_prefix() . 'emailtemplates', $data);
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            if ($affectedRows > 0) {
                $last_activity = get_last_system_activity_id();
                hooks()->do_action('after_mailbox_email_template_updated', $id);
                // Delete activity log caused by delete customer function
                if ($last_activity) {
                    $this->db->where('id >', $last_activity->id);
                    $this->db->delete(db_prefix() . 'activity_log');
                }
                log_activity('Email Template Updated [' . $email_template->name . ']');
            }
        }

        return true;
    }

    /**
     * Update Email Template Status
     * @param  mixed $id        email template id
     * @param  mixed $status    email template status
     * @return object
     */
    public function update_email_template_status($id, $status)
    {
        $this->db->where('emailtemplateid', $id);
        $email_template = $this->db->get(db_prefix() . 'emailtemplates')->row();

        $this->db->where('slug', $email_template->slug);
        $email_templates = $this->db->get(db_prefix() . 'emailtemplates')->result_array();

        $affectedRows = 0;
        foreach ($email_templates as $email_template) {
            $this->db->where('emailtemplateid', $email_template['emailtemplateid']);
            $this->db->update(db_prefix() . 'emailtemplates', ['active' => $status, ]);
            if ($this->db->affected_rows() > 0) {
                $affectedRows++;
                hooks()->do_action('mailbox_email_template_status_changed', ['id' => $email_template['emailtemplateid'], 'status' => $status, ]);
                log_activity('Email Template Status Changed [TagID: ' . $email_template['emailtemplateid'] . ' Status(Active/Inactive): ' . $status . ']');
            }
        }

        return $affectedRows ? true : false;
    }

    /**
     * Update mailbox template
     * @param  mixed $id        mail id
     * @param  mixed $tag_id    template id
     * @param  mixed $type      type
     * @return object
     */
    public function update_mail_template($id, $template_id, $type = 'outbox')
    {
        if ($template_id) {
            $this->db->where('emailtemplateid', $template_id);
            $email_template = $this->db->get(db_prefix() . 'emailtemplates')->row();
        } else {
            $template_id = null;
        }
        $this->db->where('id', $id);
        $email = $this->db->get(db_prefix() . 'mail_' . $type)->row();
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'mail_' . $type, ['templateid' => $template_id, ]);
        $affectedRows = 0;
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            hooks()->do_action('mailbox_template_changed', ['id' => $email->id, 'templateid' => $template_id, ]);
            log_activity('Email Template Changed [ID: ' . $email->id . (isset($email_template) && $email_template ? ' Template: ' . $email_template->name : '' ) . ']');
        }

        return $affectedRows ? true : false;
    }

    /**
     * Delete Email Template
     * @param  mixed $id        email template id
     * @return object
     */
    public function delete_email_templates($id)
    {
        $this->db->where('emailtemplateid', $id);
        $email_template = $this->db->get(db_prefix() . 'emailtemplates')->row();

        $this->db->where('slug', $email_template->slug);
        $email_templates = $this->db->get(db_prefix() . 'emailtemplates')->result_array();

        $affectedRows = 0;
        foreach ($email_templates as $email_template) {
            hooks()->do_action('before_mailbox_email_template_deleted', $email_template['emailtemplateid']);
            $last_activity = get_last_system_activity_id();
            $this->db->where('emailtemplateid', $email_template['emailtemplateid']);
            $this->db->delete(db_prefix() . 'emailtemplates');
            if ($this->db->affected_rows() > 0) {
                $affectedRows++;
            }
            if ($affectedRows > 0) {
                hooks()->do_action('after_mailbox_email_template_deleted', $email_template['emailtemplateid']);
                // Delete activity log caused by delete customer function
                if ($last_activity) {
                    $this->db->where('id >', $last_activity->id);
                    $this->db->delete(db_prefix() . 'activity_log');
                }
                log_activity('Email Template Deleted [ID: ' . $email_template['emailtemplateid'] . ']');
            }
        }

        return $affectedRows ? true : false;
    }

    /**
     * Get auto replies
     * @param  mixed $tag_id
     * @param  array $where       perform where query
     * @param  array $whereIn     perform whereIn query
     * @return array
     */
    public function get_auto_replies($tag_id = '', $where = ['active' => 1], $whereIn = [])
    {
        $this->db->where($where);
        if ($tag_id != '') {
            $this->db->where('id', $tag_id);
        }
        foreach ($whereIn as $key => $values) {
            if (is_string($key) && is_array($values)) {
                $this->db->where_in($key, $values);
            }
        }
        return $this->db->get(db_prefix() . 'mail_auto_replies')->result_array();
    }

    /**
     * Get single mailbox auto reply
     * @param  mixed $id auto reply id
     * @return object
     */
    public function get_auto_reply($id = '')
    {
        $this->db->where('id', $id);
        return $this->db->get(db_prefix() . 'mail_auto_replies')->row();
    }

    /**
     * Add new mailbox auto reply
     * @param array  $data               $_POST data
     */
    public function add_auto_reply($data)
    {
        if (isset($data['custom_fields'])) {
            $custom_fields           = $data['custom_fields'];
            unset($data['custom_fields']);
        }
        $data['name']                       = trim($data['name']);
        $data                               = hooks()->apply_filters('before_create_mailbox_auto_reply', $data);
        $this->db->insert(db_prefix() . 'mail_auto_replies', $data);
        $auto_reply_id = $this->db->insert_id();
        if ($auto_reply_id) {
            if (isset($custom_fields)) {
                handle_custom_fields_post($auto_reply_id, $custom_fields);
            }
            log_activity('Mailbox Auto Reply Created [ID: ' . $auto_reply_id . ']');
            hooks()->do_action('mailbox_auto_reply_created', $auto_reply_id);
            return $auto_reply_id;
        }
        return false;
    }

    /**
     * Update mailbox auto reply data
     * @param  array  $data           $_POST data
     * @param  mixed  $id             auto reply id
     * @return mixed
     */
    public function update_auto_reply($data, $id)
    {
        $affectedRows   = 0;
        $auto_reply     = $this->get_auto_reply($id);
        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }
        $data = hooks()->apply_filters('before_update_mailbox_auto_reply', $data, $id);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'mail_auto_replies', $data);
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            hooks()->do_action('mailbox_auto_reply_updated', $id, $data);
        }
        if ($affectedRows > 0) {
            log_activity('Mailbox Auto Reply Updated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Change mailbox auto reply status
     * @param  mixed $id        auto reply id
     * @param  mixed $status    auto reply status
     * @return object
     */
    public function change_auto_reply_status($id, $status)
    {
        $status = hooks()->apply_filters('change_auto_reply_status', $status, $id);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'mail_auto_replies', ['active' => $status, ]);
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('mailbox_auto_reply_status_changed', ['id' => $id, 'status' => $status, ]);
            log_activity('Mailbox Auto Reply Status Changed [ID: ' . $id . ' Status(Active/Inactive): ' . $status . ']');
            return true;
        }
        return false;
    }

    public function assign_customers($data) {
        foreach($data['select_customers'] as $value) {
            $customer_mail = [];
            $customer_mail[$data['type'] . '_id'] = $data['mailbox_id'];
            $customer_mail['client_id'] = $value;
            $this->db->select('*');
            $this->db->where($data['type'] . "_id", $data['mailbox_id']);
            $this->db->where("client_id", $value);
            $select_data = $this->db->get(db_prefix().'mail_clients')->result_array();
            if (empty($select_data)) {
               $this->db->insert(db_prefix().'mail_clients', $customer_mail);
               $mail_customer_id = $this->db->insert_id();
            }   
        }
        return true;
    }

    public function unassign_customers($data) {
        $this->db->where($data['type'] . '_id', $data['mail_id']);
        $this->db->where('client_id', $data['client_id']);
        $this->db->delete(db_prefix().'mail_clients');
        return true;
    }
}