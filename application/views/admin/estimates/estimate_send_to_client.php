<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade email-template"
	data-editor-id=".<?= 'tinymce-' . $estimate->id; ?>"
	id="estimate_send_to_client_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<?= form_open('admin/estimates/send_to_email/' . $estimate->id); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close close-send-template-modal"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">
					<span
						class="edit-title"><?= _l('estimate_send_to_client_modal_heading'); ?></span>
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<?php
                            if ($template_disabled) {
                                echo '<div class="alert alert-danger">';
                                echo 'The email template <b><a href="' . admin_url('emails/email_template/' . $template_id) . '" class="alert-link" target="_blank">' . $template_system_name . '</a></b> is disabled. Click <a href="' . admin_url('emails/email_template/' . $template_id) . '" class="alert-link" target="_blank">here</a> to enable the email template in order to be sent successfully.';
                                echo '</div>';
                            }
$selected = [];
$contacts = $this->clients_model->get_contacts($estimate->clientid, ['active' => 1, 'estimate_emails' => 1]);

foreach ($contacts as $contact) {
    array_push($selected, $contact['id']);
}
if (count($selected) == 0) {
    echo '<p class="text-danger">' . _l('sending_email_contact_permissions_warning', _l('customer_permission_estimate')) . '</p><hr />';
}
echo render_select('sent_to[]', $contacts, ['id', 'email', 'firstname,lastname'], 'invoice_estimate_sent_to_email', $selected, ['multiple' => true], [], '', '', false);
?>
						</div>
						<?= render_input('cc', 'CC'); ?>
						<hr />
						<div class="checkbox checkbox-primary">
							<input type="checkbox" name="attach_pdf" id="attach_pdf" checked>
							<label
								for="attach_pdf"><?= _l('estimate_send_to_client_attach_pdf'); ?></label>
						</div>
						<h5 class="bold">
							<?= _l('estimate_send_to_client_preview_template'); ?>
						</h5>
						<hr />
						<?= render_textarea('email_template_custom', '', $template->message, [], [], '', 'tinymce-' . $estimate->id); ?>
						<?= form_hidden('template_name', $template_name); ?>
					</div>
				</div>

				<?php if (count($estimate->attachments) > 0) { ?>
				<hr />
				<div class="row">
					<div class="col-md-12">
						<h5 class="bold no-margin">
							<?= _l('include_attachments_to_email'); ?>
						</h5>
						<hr />
						<?php foreach ($estimate->attachments as $attachment) { ?>
						<div class="checkbox checkbox-primary">
							<input type="checkbox"
								<?= ! empty($attachment['external']) ? 'disabled' : ''; ?>
							value="<?= e($attachment['id']); ?>"
							name="email_attachments[]">
							<label for="">
								<a
									href="<?= site_url('download/file/sales_attachment/' . $attachment['attachment_key']); ?>">
									<?= e($attachment['file_name']); ?>
								</a>
							</label>
						</div>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
			</div>
			<div class="modal-footer">
				<button type="button"
					class="btn btn-default close-send-template-modal"><?= _l('close'); ?></button>
				<button type="submit" autocomplete="off"
					data-loading-text="<?= _l('wait_text'); ?>"
					class="btn btn-primary"><?= _l('send'); ?></button>
			</div>
		</div>
		<?= form_close(); ?>
	</div>
</div>