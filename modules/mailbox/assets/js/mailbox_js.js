/**
 * Update email status 
 */
function update_field(group, action, value, mail_id, type='inbox') {
    var data = {};
    data.group = group;
    data.action = action;
    data.value = value;
    data.id = mail_id;
    data.type = type;
    if (group == 'detail') {
        data.type = mailtype; 
    }
    $.post(admin_url + 'mailbox/update_field', data).done(function(response) {
        response = JSON.parse(response);
        if (response.success === true || response.success == 'true') {
            alert_float('success', response.message);
            if (group == 'detail') {
                window.location.reload();
            } else {
                reload_mailbox_tables();
            }
        } else {
            alert_float('warning', response.message);
        }
    });
}

/**
 * Reload mailbox datagrid
 * @return 
 */
function reload_mailbox_tables() {
    var av_tasks_tables = ['.table-mailbox'];
    $.each(av_tasks_tables, function(i, selector) {
        if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().ajax.reload(null, false);
        }
    });
}

/**
 * Update multi-email 
 */
function update_mass(group, action, value, type = "inbox") {
    if (group == 'detail') {
        update_field(group, action, value, mailid, type);
    } else {
        if (confirm_delete()) {
            var table_mailbox = $('.table-mailbox');
            var rows = table_mailbox.find('tbody tr');
            var lstid = '';
            $.each(rows, function() {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') === true) {
                    lstid = lstid + checkbox.val() + ',';
                }
            });
            update_field(group, action, value, lstid, type);
        }
    }
}

function enable_email_autocomplete() {
    /**
     * Auto Complete for the receipt email
     */
    $('.email-autocomplete input').keyup(function() {
        let receipt_el = this;
        let receipt_keyword = $(receipt_el).val();
        if (receipt_keyword) {
            $.ajax({
                url: admin_url + "mailbox/get_recipients/" + $(receipt_el).val(),
                type: "GET",
                success: function(response) {
                    let receipts = JSON.parse(response);
                    let recipients_html = '';
                    for (let receipt_index = 0; receipt_index < receipts.length; receipt_index++) {
                        recipients_html += '<div class="receipt" data-email="' + receipts[receipt_index].email + '">' + receipts[receipt_index].email + '(' + receipts[receipt_index].firstname + ' ' + receipts[receipt_index].lastname + ' - ' + receipts[receipt_index].company + ')</div>';
                    }
                    if ($(receipt_el).closest('.email-autocomplete').find('.receipts').length) {
                        $(receipt_el).closest('.email-autocomplete').find('.receipts').html(recipients_html).addClass('active');
                    } else {
                        $(receipt_el).closest('.email-autocomplete').append('<div class="receipts active">' + recipients_html + '</div>');
                    }
                    $('.email-autocomplete .receipts .receipt').click(function() {
                        $('.email-autocomplete input').val($(this).data('email'));
                    });
                }
            });
        } else {
            if ($(receipt_el).closest('.email-autocomplete').find('.receipts').length) {
                $(receipt_el).closest('.email-autocomplete').find('.receipts').html('');
            } else {
                $(receipt_el).closest('.email-autocomplete').append('<div class="receipts"></div>');
            }
        }
    });
    $('.email-autocomplete input').focus(function() {
        if ($('.email-autocomplete .receipts').length) {
            $('.email-autocomplete .receipts').addClass('active');
        }
    });
    $('.email-autocomplete input').blur(function() {
        if ($('.email-autocomplete .receipts').length) {
            setTimeout(function() {
                $('.email-autocomplete .receipts').removeClass('active');
            }, 500);
        }
    });
}

    

$('.mail-tag').change(function() {
    let mail_id = $(this).data('id');
    let tag_id = $(this).find('option:selected').data('id');
    $.ajax({
        type: 'POST',
        data: {id: mail_id, tag_id: tag_id},
        contentType: false,
        cache: false,
        processData: false,
        url: '/admin/mainbox/change_mail_tag'
    }).done(function(response) {
        response = JSON.parse(response);
        if (response.success) {
            alert_float('success', response.message);
        }
    }).fail(function(error) {
        alert_float('danger', JSON.parse(error.responseText));
    });
});
function view_contact(contact_id) {
    if (!contact_id || typeof contact_id == 'undefined') {
        contact_id = '';
    }
    requestGet('mailbox/form_contact/' + contact_id).done(function(response) {
        $('#contactModal .modal-content').html(response);
        $('#contactModal').modal({
            show: true,
            backdrop: 'static'
        });

        $('body').off('shown.bs.modal', '#contact');

        $('body').on('shown.bs.modal', '#contact', function() {
            if (contact_id == '') {
                $('#contact').find('input[name="firstname"]').focus();
            }
        });

        init_selectpicker();
        init_datepicker();
        custom_fields_hyperlink();
        validate_contact_form();
    }).fail(function(error) {
        var response = JSON.parse(error.responseText);

        alert_float('danger', response.message);
    });
}

function mailboxTagFormHandler(form) {
    var formURL = $(form).attr("action");

    var formData = new FormData($(form)[0]);

    $.ajax({
        type: 'POST',
        data: formData,
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData: false,
        url: formURL
    }).done(function(response) {
        response = JSON.parse(response);
        if (response.success) {
            alert_float('success', response.message);
        }

        if ($.fn.DataTable.isDataTable('.table-mailbox-tags')) {
            $('.table-mailbox-tags').DataTable().ajax.reload(null, false);
        }

        $('#tagModal').modal('hide');
    }).fail(function(error) {
        alert_float('danger', JSON.parse(error.responseText));
    });

    return false;
}

function validate_mailbox_tag_form() {
    appValidateForm('#mailbox-tag-form', {
        name: 'required',
    }, mailboxTagFormHandler);
}

function view_mailbox_tag(tag_id) {
    if (!tag_id || typeof tag_id == 'undefined') {
        tag_id = '';
    }
    requestGet('mailbox/form_tag/' + tag_id).done(function(response) {
        $('#tagModal .modal-content').html(response);
        $('#tagModal').modal({
            show: true,
            backdrop: 'static'
        });

        $('body').off('shown.bs.modal', '#mailbox-tag-form');

        $('body').on('shown.bs.modal', '#mailbox-tag-form', function() {
            if (tag_id == '') {
                $('#mailbox-tag-form').find('input[name="name"]').focus();
            }
        });

        init_selectpicker();
        init_datepicker();
        custom_fields_hyperlink();
        validate_mailbox_tag_form();
    }).fail(function(error) {
        var response = JSON.parse(error.responseText);

        alert_float('danger', response.message);
    });
}

function mailboxEmailTemplateFormHandler(form) {
    var formURL = $(form).attr("action");

    var formData = new FormData($(form)[0]);

    $.ajax({
        type: 'POST',
        data: formData,
        mimeType: "multipart/form-data",
        contentType: false,
        cache: false,
        processData: false,
        url: formURL
    }).done(function(response) {
        response = JSON.parse(response);
        if (response.success) {
            alert_float('success', response.message);
        }

        if ($.fn.DataTable.isDataTable('.table-mailbox-email-templates')) {
            $('.table-mailbox-email-templates').DataTable().ajax.reload(null, false);
        }

        $('#emailTemplateModal').modal('hide');
    }).fail(function(error) {
        alert_float('danger', JSON.parse(error.responseText));
    });

    return false;
}

function validate_mailbox_email_template_form() {
    appValidateForm('#mailbox-email-template-form', {
        name: 'required',
    }, mailboxEmailTemplateFormHandler);
}

function view_mailbox_email_template(template_id) {
    if (!template_id || typeof template_id == 'undefined') {
        template_id = '';
    }
    requestGet('mailbox/form_email_template/' + template_id).done(function(response) {
        $('#emailTemplateModal .modal-content').html(response);
        $('#emailTemplateModal').modal({
            show: true,
            backdrop: 'static'
        });

        $('body').off('shown.bs.modal', '#mailbox-email-template-form');

        $('body').on('shown.bs.modal', '#mailbox-email-template-form', function() {
            if (template_id == '') {
                $('#mailbox-email-template-form').find('input[name="name"]').focus();
            }
        });

        init_selectpicker();
        init_datepicker();
        custom_fields_hyperlink();
        validate_mailbox_email_template_form();
        enable_email_autocomplete();
    }).fail(function(error) {
        var response = JSON.parse(error.responseText);

        alert_float('danger', response.message);
    });
}

$('#mailbox_compose_form [name="templateid"]').change(function() {
    if ($(this).val()) {
        $('#mailbox_compose_form [name="body"]').parent().hide();
    } else {
        $('#mailbox_compose_form [name="body"]').parent().show();
    }
});

$(document).ready(function() {
    enable_email_autocomplete();
});