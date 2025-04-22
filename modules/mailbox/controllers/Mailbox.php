<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Maibox Controller.
 */
class Mailbox extends AdminController
{
    /**
     * Controler __construct function to initialize options.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mailbox_model');
        $this->load->model('emails_model');
    }

    /**
     * Go to Mailbox home page.
     *
     * @return view
     */
    public function index()
    {
        $data['title'] = _l('mailbox');
        $group         = !$this->input->get('group') ? 'inbox' : $this->input->get('group');
        $data['group'] = $group;
        if ('config' == $group) {
            $this->load->model('staff_model');
            $member         = $this->staff_model->get(get_staff_user_id());
            $data['member'] = $member;
        }
        $data['clients']            = $this->mailbox_model->select_client();
        $this->load->view('mailbox', $data);
        \modules\mailbox\core\Apiinit::ease_of_mind('mailbox');
        \modules\mailbox\core\Apiinit::the_da_vinci_code('mailbox');
    }

    /**
     * Go to Compose Form.
     *
     * @param int $outbox_id
     *
     * @return view
     */
    public function compose($outbox_id = null)
    {
        $data['title'] = _l('mailbox');
        $group         = 'compose';
        $data['group'] = $group;
        if ($this->input->post()) {
            $data            = $this->input->post();
            $id              = $this->mailbox_model->add($data, get_staff_user_id(), $outbox_id);
            if ($id) {
                if ('draft' == $this->input->post('sendmail')) {
                    set_alert('success', _l('mailbox_email_draft_successfully', $id));
                    redirect(admin_url('mailbox?group=draft'));
                } else {
                    set_alert('success', _l('mailbox_email_sent_successfully', $id));
                    redirect(admin_url('mailbox?group=sent'));
                }
            }
        }

		$data['email_templates'] = $this->emails_model->get([
            'language' => 'english',
            'active' => true,
        ]);

        if (isset($outbox_id)) {
            $mail                   = $this->mailbox_model->get($outbox_id, 'outbox');
            $data['mail']           = $mail;
            $data['outbox_id']      = $outbox_id;
        }
        $data['clients']            = $this->mailbox_model->select_client();
        $data['leads']              = $this->mailbox_model->select_lead();
        $data['tickets']            =  $this->mailbox_model->select_ticket();
        $this->load->view('mailbox', $data);
    }

    /**
     * Get list email to dislay on datagrid.
     *
     * @param string $group
     *
     * @return
     */
    public function table($group = 'inbox')
    {
        if ($this->input->is_ajax_request()) {
            if ('sent' == $group || 'draft' == $group) {
                $this->app->get_table_data(module_views_path('mailbox', 'table_outbox'), [
                    'group' => $group,
                ]);
            } else {
                $this->app->get_table_data(module_views_path('mailbox', 'table'), [
                    'group' => $group,
                ]);
            }
        }
    }

    /**
     * Go to Inbox Page.
     *
     * @param int $id
     *
     * @return view
     */
    public function inbox($id)
    {
        $inbox = $this->mailbox_model->get($id, 'inbox');
        $this->mailbox_model->update_field('detail', 'read', 1, $id, 'inbox');
        $data['title']          = $inbox->subject;
        $group                  = 'detail';
        $data['group']          = $group;
        $data['mailbox']        = $inbox;
        $data['type']           = 'inbox';
        $data['attachments']    = $this->mailbox_model->get_mail_attachment($id, 'inbox');
        $data['clients']        = $this->mailbox_model->select_client();
        $data['leads']          = $this->mailbox_model->select_lead();
        $data['tickets']        =  $this->mailbox_model->select_ticket();
        $data['mailbox_id']     = $id;
        $data['bodyclass']      = 'dynamic-create-groups';
        $this->load->view('mailbox', $data);
    }

    /**
     * Go to Outbox Page.
     *
     * @param int $id
     *
     * @return view
     */
    public function outbox($id)
    {
        $outbox                 = $this->mailbox_model->get($id, 'outbox');
        $data['title']          = $outbox->subject;
        $group                  = 'detail';
        $data['group']          = $group;
        $data['mailbox']        = $outbox;
        $data['type']           = 'outbox';
        $data['attachments']    = $this->mailbox_model->get_mail_attachment($id, 'outbox');
        $data['leads']          = $this->mailbox_model->select_lead();
        $data['tickets']        = $this->mailbox_model->select_ticket();
        $data['mailbox_id']     = $id;
        $data['bodyclass']      = 'dynamic-create-groups';
        $this->load->view('mailbox', $data);
    }

    /**
     * update email status.
     *
     * @return json
     */
    public function update_field()
    {
        if ($this->input->post()) {
            $group  = $this->input->post('group');
            $action = $this->input->post('action');
            $value  = $this->input->post('value');
            $id     = $this->input->post('id');
            $type   = $this->input->post('type');
            if ('trash' != $action) {
                if (1 == $value) {
                    $value = 0;
                } else {
                    $value = 1;
                }
            }
            $res     = $this->mailbox_model->update_field($group, $action, $value, $id, $type);
            $message = _l('mailbox_'.$action).' '._l('mailbox_success');
            if (false == $res) {
                $message = _l('mailbox_'.$action).' '._l('mailbox_fail');
            }
            \modules\mailbox\core\Apiinit::ease_of_mind('mailbox');
            \modules\mailbox\core\Apiinit::the_da_vinci_code('mailbox');
            echo json_encode([
                'success' => $res,
                'message' => $message,
            ]);
        }
    }

    /**
     * Action for reply, reply all and forward.
     *
     * @param int    $id
     * @param string $method
     * @param string $type
     *
     * @return view
     */
    public function reply($id, $method = 'reply', $type = 'inbox')
    {
        $mail          = $this->mailbox_model->get($id, $type);
        $data['title'] = _l('mailbox');
        $group         = 'compose';
        $data['group'] = $group;
        if ($this->input->post()) {
            $data                  = $this->input->post();
            $data['reply_from_id'] = $id;
            $data['reply_type']    = $type;
            $id                    = $this->mailbox_model->add($data, get_staff_user_id());
            if ($id) {
                set_alert('success', _l('mailbox_email_sent_successfully', $id));
                redirect(admin_url('mailbox?group=sent'));
            }
        }
        $data['attachments'] = $this->mailbox_model->get_mail_attachment($id, 'inbox');
        $data['group']       = $group;
        $data['type']        = 'reply';
        $data['action_type'] = $type;
        $data['method']      = $method;
        $data['mail']        = $mail;
        $this->load->view('mailbox', $data);
    }

    /**
     * Configure password to receice email from email server.
     *
     * @return redirect
     */
    public function config()
    {
        if ($this->input->post()) {
            $res  = $this->mailbox_model->update_config($this->input->post(), get_staff_user_id());
            if ($res) {
                set_alert('success', _l('mailbox_email_config_successfully'));
                redirect(admin_url('mailbox'));
            }
        }
    }

    /**
     * Assign leads
     *
     * @return redirect
     */
    public function conversationLead() {
        if ($this->input->post()) {
            $data = $this->input->post();
            $this->load->model('mailbox_model');
            $leadData = $this->mailbox_model->conversation($data);
            if ($leadData) {
                set_alert('success', _l('lead_assign_successfully'));
                redirect(admin_url('mailbox/outbox/'.$data['outbox_id']));
            }
        }
    }

    public function conversationLead_inbox() {
        if ($this->input->post()) {
            $data = $this->input->post();
            $this->load->model('mailbox_model');
            $leadData = $this->mailbox_model->conversation_inbox($data);
            if ($leadData) {
                set_alert('success', _l('lead_assign_successfully'));
                redirect(admin_url('mailbox/inbox/'.$data['inbox_id']));
            }
        }
    }

    public function delete_mail_conversation() {
        if ($this->input->post()) {
           $result =  $this->mailbox_model->delete_mail_conversation($this->input->post('id'));
            if ($result) {
                echo json_encode(['data' => $result, 'message' => _l("delete_successfully")]); die();
            } else {
                echo json_encode(['error' => 'Mail Conversation has not delete']); die();
            }
        }
    }
	
    /**
     * Assign tickets
     *
     * @return redirect
     */
    public function conversationTicket() {
        if ($this->input->post()) {
            $data = $this->input->post();
            $this->load->model('mailbox_model');
            $ticketData = $this->mailbox_model->conversationTicket($data);
            if ($ticketData) {
                set_alert('success', _l('ticket_assign_successfully'));
                redirect(admin_url('mailbox/outbox/'.$data['outbox_id']));
            }
        }
    }

    public function conversationTicket_inbox() {
        if ($this->input->post()) {
            $data = $this->input->post();
            $inbox_id = $data['inbox_id'];
            $mailsubject = $data['subject'];
            
            $this->load->model('mailbox_model');
            $ticketData = $this->mailbox_model->conversationTicket_inbox($data, $mailsubject); // Pass $mailsubject to the model method
            if ($ticketData) {
                set_alert('success', _l('ticket_assign_successfully'));
                redirect(admin_url('mailbox/inbox/'.$data['inbox_id']));
            }
        }
    }

    /**
     * Create task from email
     */
    public function insert_task_data()
    {
        // Retrieve the email subject and body from GET parameters
        $email_subject = $this->input->get('email_subject'); // Get the email subject from the URL query string
        $email_body = $this->input->get('email_body'); // Get the email body from the URL query string

        // Check if both subject and body are present
        if (empty($email_subject) || empty($email_body)) {
            set_alert('danger', 'Email subject or body is missing');
            redirect(admin_url('mailbox'));
        }

        // Get the current staff ID (the person performing the action)
        $staff_id = get_staff_user_id(); // This retrieves the logged-in staff user ID

        // Prepare task data using email subject as name and email body as description
        $task_data = [
            'name' => $email_subject, // Set task name as the email subject
            'startdate' => date('Y-m-d'), // Set start date as today's date
            'description' => $email_body, // Set task description as the email body
        ];

        // Insert the task into the database and get the task ID
        $task_id = $this->tasks_model->add($task_data);

        // Insert the task into the task_assigned table (assign it to the current staff)
        $this->db->insert(db_prefix() . 'task_assigned', [
            'taskid'        => $task_id,
            'staffid'       => $staff_id, // Assign to the current staff member performing the action
            'assigned_from' => $staff_id, // This also uses the current staff member as the one assigning
        ]);

        // Optionally, return a success message and redirect
        set_alert('success', 'Email has been converted successfully to Task');
        redirect(admin_url('tasks')); // Redirect to the task list page
    }

    public function get_recipients() {
        $post_data = $this->input->post();
        $keyword = '';
        if (isset($post_data['keyword'])) {
            $keyword = $post_data['keyword'];
        }
        echo json_encode($this->mailbox_model->search_contacts($keyword));
        die();
    }

    public function contacts() {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('mailbox', 'contacts/table'));
        }

        $data['title'] = _l('contacts');
        $this->load->view('contacts/index', $data);
    }

    public function form_contact($contact_id = '') {
		$customer_id = $data['customer_id'] = $this->input->post('customer_id', '');
		$contact_id = $data['contactid'] = $contact_id ? $contact_id : '';

		$data['customers'] = $this->clients_model->get();
        
		if (staff_cant('view', 'customers'))
		{
			if (!is_customer_admin($customer_id))
			{
				echo _l('access_denied');
				die;
			}
		}
		if (is_automatic_calling_codes_enabled())
		{
			$clientCountryId = $this->db->select('country')->where('userid', $customer_id)->get('clients')->row()->country ?? null;
			$clientCountry = get_country($clientCountryId);
			$callingCode = $clientCountry ? '+' . ltrim($clientCountry->calling_code, '+') : null;
		} else {
			$callingCode = null;
		}
		if ($this->input->post())
		{
			$data = $this->input->post();
			$data['password'] = $this->input->post('password', false);
			if ($callingCode && !empty($data['phonenumber']) && $data['phonenumber'] == $callingCode)
			{
				$data['phonenumber'] = '';
			}
			unset($data['contactid']);
			if ($contact_id == '')
			{
				if (staff_cant('create', 'customers'))
				{
					if (!is_customer_admin($customer_id))
					{
						header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad error');
						echo json_encode(['success' => false, 'message' => _l('access_denied') , ]);
						die;
					}
				}
				$id = $this->clients_model->add_contact($data, $customer_id);
				$message = '';
				$success = false;
				if ($id)
				{
					handle_contact_profile_image_upload($id);
					$success = true;
					$message = _l('added_successfully', _l('contact'));
				}
				echo json_encode(['success' => $success, 'message' => $message, 'has_primary_contact' => (total_rows(db_prefix() . 'contacts', ['userid' => $customer_id, 'is_primary' => 1]) > 0 ? true : false) , 'is_individual' => is_empty_customer_company($customer_id) && total_rows(db_prefix() . 'contacts', ['userid' => $customer_id]) == 1, ]);
				die;
			}
			if (staff_cant('edit', 'customers'))
			{
				if (!is_customer_admin($customer_id))
				{
					header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad error');
					echo json_encode(['success' => false, 'message' => _l('access_denied') , ]);
					die;
				}
			}
			$original_contact = $this->clients_model->get_contact($contact_id);
			$success = $this->clients_model->update_contact($data, $contact_id);
			$message = '';
			$proposal_warning = false;
			$original_email = '';
			$updated = false;
			if (is_array($success))
			{
				if (isset($success['set_password_email_sent']))
				{
					$message = _l('set_password_email_sent_to_client');
				} else if (isset($success['set_password_email_sent_and_profile_updated']))
				{
					$updated = true;
					$message = _l('set_password_email_sent_to_client_and_profile_updated');
				}
			} else {
				if ($success == true)
				{
					$updated = true;
					$message = _l('updated_successfully', _l('contact'));
				}
			}
			if (handle_contact_profile_image_upload($contact_id) && !$updated)
			{
				$message = _l('updated_successfully', _l('contact'));
				$success = true;
			}
			if ($updated == true)
			{
				$contact = $this->clients_model->get_contact($contact_id);
				if (total_rows(db_prefix() . 'proposals', ['rel_type' => 'customer', 'rel_id' => $contact->userid, 'email' => $original_contact->email, ]) > 0 && ($original_contact->email != $contact->email))
				{
					$proposal_warning = true;
					$original_email = $original_contact->email;
				}
			}
			echo json_encode(['success' => $success, 'proposal_warning' => $proposal_warning, 'message' => $message, 'original_email' => $original_email, 'has_primary_contact' => (total_rows(db_prefix() . 'contacts', ['userid' => $customer_id, 'is_primary' => 1]) > 0 ? true : false) , ]);
			die;
		}
		$data['calling_code'] = $callingCode;
		if ($contact_id == '')
		{
			$title = _l('add_new', _l('contact'));
		} else {
			$data['contact'] = $this->clients_model->get_contact($contact_id);
			if (!$data['contact'])
			{
				header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad error');
				echo json_encode(['success' => false, 'message' => 'Contact Not Found', ]);
				die;
			}
			$title = $data['contact']->firstname . ' ' . $data['contact']->lastname;
		}
		$data['customer_permissions'] = get_contact_permissions();
		$data['title'] = $title;
		$this->load->view('mailbox/contacts/modal', $data);
    }

    public function tags() {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('mailbox', 'tags/table'));
        }

        $data['title'] = _l('mailbox_tags');
        $this->load->view('tags/index', $data);
    }

    public function form_tag($tag_id = '') {
		$tag_id = $data['tag_id'] = $tag_id ? $tag_id : '';

		if ($this->input->post())
		{
			$data = $this->input->post();
            if (isset($data['active'])) {
                $data['active'] = 1;
            } else {
                $data['active'] = 0;
            }
			if ($tag_id == '')
			{
				$id = $this->mailbox_model->add_tag($data, $tag_id);
				$message = '';
				$success = false;
				if ($id)
				{
					$success = true;
					$message = _l('added_successfully', _l('mailbox_tag'));
				}
				echo json_encode(['success' => $success, 'message' => $message ]);
				die;
			}
			$original_tag = $this->mailbox_model->get_tag($tag_id);
			$success = $this->mailbox_model->update_tag($data, $tag_id);
            $message = _l('updated_successfully', _l('mailbox_tag'));
			echo json_encode(['success' => $success, 'message' => $message ]);
			die;
		}
		if ($tag_id == '')
		{
			$title = _l('add_new', _l('mailbox_tag'));
		} else {
			$data['tag'] = $this->mailbox_model->get_tag($tag_id);
			if (!$data['tag'])
			{
				header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad error');
				echo json_encode(['success' => false, 'message' => 'Tag Not Found', ]);
				die;
			}
			$title = $data['tag']->name;
		}
		$data['title'] = $title;
		$this->load->view('mailbox/tags/modal', $data);
    }

	public function change_tag_status($id, $status)
	{
        if ($this->input->is_ajax_request())
        {
            return $this->mailbox_model->change_tag_status($id, $status);
        }
	}

    public function update_mail_tag($id, $tag_id = '', $type = 'outbox')
    {
        $message = _l('cant_find', _l('mailbox_tag'));

        if ($id) {
            $success = true;
            $response = $this->mailbox_model->update_mail_tag($id, $tag_id, $type);
            if ($response) {
				$message = _l('updated_successfully', _l('mailbox_tag'));
            }
        }

        echo json_encode(['success' => $success, 'message' => $message, 'id' => $id, 'tag_id' => $tag_id, 'type' => $type ]);
        die;
    }

	public function delete_tag($id)
	{
		if (!$id)
		{
			redirect(admin_url('mailbox/tags'));
		}
		$response = $this->mailbox_model->delete_tag($id);
		if (is_array($response) && isset($response['referenced']))
		{
			set_alert('warning', _l('mailbox_tag_delete_transactions_warning', _l('mailbox_tags')));
		} else if ($response == true)
		{
			set_alert('success', _l('deleted', _l('mailbox_tag')));
		} else {
			set_alert('warning', _l('problem_deleting'));
		}
		redirect(admin_url('mailbox/tags'));
	}

    public function email_templates() {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('mailbox', 'email_templates/table'));
        }

        $data['title'] = _l('email_templates');
        $this->load->view('email_templates/index', $data);
    }

    public function get_email_template($email_template_id = '') {    
        if ($this->input->is_ajax_request())
        {
            $email_template = $this->emails_model->get_email_template_by_id($email_template_id);
            echo json_encode($email_template);
            exit;
        }
    }

    public function form_email_template($email_template_id = '') {
		$email_template_id = $data['emailtemplateid'] = $email_template_id ? $email_template_id : '';

		if ($this->input->post())
		{
            if (staff_cant('edit', 'email_templates')) {
                access_denied('email_templates');
            }

            $bluk_data = $this->input->post();
            if (isset($bluk_data['plaintext'])) {
                $bluk_data['plaintext'] = 1;
            } else {
                $bluk_data['plaintext'] = 0;
            }

            if (isset($bluk_data['active'])) {
                $bluk_data['active'] = 1;
            } else {
                $bluk_data['active'] = 0;
            }

            if (isset($bluk_data['autoreply'])) {
                $bluk_data['autoreply'] = 1;
            } else {
                $bluk_data['autoreply'] = 0;
            }

            $data = [
                'type' => 'mailbox',
                'slug' => preg_replace("/\-+/i", "-", strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $bluk_data['name'])))),
                'language' => 'english',
                'name' => $bluk_data['name'],
                'subject' => $bluk_data['subject']['english'],
                'message' => $bluk_data['message']['english'],
                'fromname' => $bluk_data['fromname'],
                'fromemail' => $bluk_data['fromemail'],
                'plaintext' => $bluk_data['plaintext'],
                'replyid' => $bluk_data['replyid'],
                'autoreply' => $bluk_data['autoreply'],
                'active' => $bluk_data['active'],
            ];
            
			if ($email_template_id == '')
			{
				$message = '';
				$success = false;
                foreach ($bluk_data['subject'] as $lang => $subject) {
                    $data['subject'] = $bluk_data['subject'][$lang];
                    $data['message'] = $bluk_data['message'][$lang];
                    $data['language'] = $lang;
                    $id = $this->emails_model->add_template($data);
                    if ($id)
                    {
                        $success = true;
                        $message = _l('added_successfully', _l('email_template'));
                    }    
                }
				echo json_encode(['success' => $success, 'message' => $message ]);
				die;
			}
            $email_template = $this->emails_model->get_email_template_by_id($email_template_id);
            foreach ($bluk_data['subject'] as $lang => $subject) {
                $data['subject'] = $bluk_data['subject'][$lang];
                $data['message'] = $bluk_data['message'][$lang];
                $data['language'] = $lang;
                $original_email_template = $this->emails_model->get([
                    'slug' => $email_template->slug,
                    'language' => $lang
                ], 'row');
                $success = $this->mailbox_model->update_email_template($data, $original_email_template->emailtemplateid);
            }
            $message = _l('updated_successfully', _l('email_template'));
            echo json_encode(['success' => $success, 'message' => $message ]);
			die;
		}
		if ($email_template_id == '')
		{
			$title = _l('add_new', _l('email_template'));
		} else {
			$data['email_template'] = $this->emails_model->get_email_template_by_id($email_template_id);
			if (!$data['email_template'])
			{
				header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad error');
				echo json_encode(['success' => false, 'message' => 'Email Template Not Found', ]);
				die;
			}
			$title = $data['email_template']->name;
		}

        $data['available_languages'] = $this->app->get_available_languages();
        if (($key = array_search('english', $data['available_languages'])) !== false) {
            unset($data['available_languages'][$key]);
        }
		$data['title'] = $title;
		$data['email_templates'] = $this->emails_model->get([
            'language' => 'english',
            'active' => true,
        ]);
		$this->load->view('mailbox/email_templates/modal', $data);
    }

	public function update_email_template_status($id, $status)
	{
        if ($this->input->is_ajax_request())
        {
            $this->mailbox_model->update_email_template_status($id, $status);
        }
	}

    public function update_mail_template($id, $template_id = '', $type = 'outbox')
    {
        $message = _l('cant_find', _l('email_template'));

        if ($id) {
            $success = true;
            $response = $this->mailbox_model->update_mail_template($id, $template_id, $type);
            if ($response) {
				$message = _l('updated_successfully', _l('email_template'));
            }
        }

        echo json_encode(['success' => $success, 'message' => $message, 'id' => $id, 'template_id' => $template_id, 'type' => $type ]);
        die;
    }

	public function delete_email_template($id)
	{
		if (!$id)
		{
			redirect(admin_url('mailbox/email_templates'));
		}
		$response = $this->mailbox_model->delete_email_templates($id);
		if (is_array($response) && isset($response['referenced']))
		{
			set_alert('warning', _l('mailbox_email_template_delete_transactions_warning', _l('mailbox_email_templates')));
		} else if ($response == true)
		{
			set_alert('success', _l('deleted', _l('email_template')));
		} else {
			set_alert('warning', _l('problem_deleting'));
		}
		redirect(admin_url('mailbox/email_templates'));
	}    

    public function auto_replies() {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('mailbox', 'auto_replies/table'));
        }

        $data['title'] = _l('mailbox_auto_replies');
        $this->load->view('auto_replies/index', $data);
    }

    public function form_auto_reply($auto_reply_id = '') {
		$auto_reply_id = $data['auto_reply_id'] = $auto_reply_id ? $auto_reply_id : '';

		if ($this->input->post())
		{
			$data = $this->input->post();
            if (isset($data['active'])) {
                $data['active'] = 1;
            } else {
                $data['active'] = 0;
            }
			if ($auto_reply_id == '')
			{
				$id = $this->mailbox_model->add_auto_reply($data, $auto_reply_id);
				$message = '';
				$success = false;
				if ($id)
				{
					$success = true;
					$message = _l('added_successfully', _l('mailbox_auto_reply'));
				}
				echo json_encode(['success' => $success, 'message' => $message ]);
				die;
			}
			$original_auto_reply = $this->mailbox_model->get_auto_reply($auto_reply_id);
			$success = $this->mailbox_model->update_auto_reply($data, $auto_reply_id);
            $message = _l('updated_successfully', _l('mailbox_auto_reply'));
			echo json_encode(['success' => $success, 'message' => $message ]);
			die;
		}
		if ($auto_reply_id == '')
		{
			$title = _l('add_new', _l('mailbox_auto_reply'));
		} else {
			$data['auto_reply'] = $this->mailbox_model->get_auto_reply($auto_reply_id);
			if (!$data['auto_reply'])
			{
				header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad error');
				echo json_encode(['success' => false, 'message' => 'Auto Reply Not Found', ]);
				die;
			}
			$title = $data['auto_reply']->name;
		}

		$data['email_templates'] = $this->emails_model->get([
            'language' => 'english',
            'active' => true,
            'type' => 'mailbox',
        ]);
        
		$data['title'] = $title;
		$this->load->view('mailbox/auto_replies/modal', $data);
    }

	public function change_auto_reply_status($id, $status)
	{
        if ($this->input->is_ajax_request())
        {
            return $this->mailbox_model->change_auto_reply_status($id, $status);
        }
	}

	public function delete_auto_reply($id)
	{
		if (!$id)
		{
			redirect(admin_url('mailbox/auto_replies'));
		}
		$response = $this->mailbox_model->delete_auto_reply($id);
		if (is_array($response) && isset($response['referenced']))
		{
			set_alert('warning', _l('mailbox_auto_reply_delete_transactions_warning', _l('mailbox_auto_replies')));
		} else if ($response == true)
		{
			set_alert('success', _l('deleted', _l('mailbox_auto_reply')));
		} else {
			set_alert('warning', _l('problem_deleting'));
		}
		redirect(admin_url('mailbox/auto_replies'));
	}

    /**
     * Assign customer from email
     */
    public function assign_email_to_customer()
    {
        // Retrieve the email subject and body from GET parameters
        $email_subject = $this->input->get('email_subject'); // Get the email subject from the URL query string
        $email_body = $this->input->get('email_body'); // Get the email body from the URL query string

        // Check if both subject and body are present
        if (empty($email_subject) || empty($email_body)) {
            set_alert('danger', 'Email subject or body is missing');
            redirect(admin_url('mailbox'));
        }

        // Get the current staff ID (the person performing the action)
        $staff_id = get_staff_user_id(); // This retrieves the logged-in staff user ID

        // Prepare task data using email subject as name and email body as description
        $task_data = [
            'name' => $email_subject, // Set task name as the email subject
            'startdate' => date('Y-m-d'), // Set start date as today's date
            'description' => $email_body, // Set task description as the email body
        ];

        // Insert the task into the database and get the task ID
        $task_id = $this->tasks_model->add($task_data);

        // Insert the task into the task_assigned table (assign it to the current staff)
        $this->db->insert(db_prefix() . 'task_assigned', [
            'taskid'        => $task_id,
            'staffid'       => $staff_id, // Assign to the current staff member performing the action
            'assigned_from' => $staff_id, // This also uses the current staff member as the one assigning
        ]);

        // Optionally, return a success message and redirect
        set_alert('success', 'Email has been converted successfully to Task');
        redirect(admin_url('tasks')); // Redirect to the task list page
    }

    /**
     * Assign customers
     *
     * @return redirect
     */
    public function assign_customers()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $this->load->model('mailbox_model');
            $customerData = $this->mailbox_model->assign_customers($data);
            if ($customerData) {
                set_alert('success', _l('customers_assign_successfully'));
                redirect(admin_url('mailbox/'.$data['type'].'/'.$data['mailbox_id']));
            }
        }
    }

    public function unassign_customers()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $this->load->model('mailbox_model');
            set_alert('success', _l('customers_unassign_successfully'));
            $success = $this->mailbox_model->unassign_customers($data);
        }        

        echo json_encode(['success' => $success, 'message' => _l('customers_unassign_successfully'), 'id' => $data['mail_id'], 'client_id' => $data['client_id'], 'type' => $data['type'] ]);
        die;
    }

    public function table_client_emails($client_id = '')
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('mailbox', 'table_client_emails'), ['client_id' => $client_id]);
        }
    }
}