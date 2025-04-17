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
                type: "POST",
                url: admin_url + "mailbox/get_recipients/",
                data: { keyword: receipt_keyword },
                success: function(response) {
                    let receipts = JSON.parse(response);
                    let recipients_html = '';
                    for (let receipt_index = 0; receipt_index < receipts.length; receipt_index++) {
                        recipients_html += '<div class="receipt" data-email="' + receipts[receipt_index].email + '">' + receipts[receipt_index].email + '(' + receipts[receipt_index].firstname + ' ' + receipts[receipt_index].lastname + ' - ' + receipts[receipt_index].company + ')</div>';
                    }
                    if ($(receipt_el).closest('.email-autocomplete').find('.receipts').length) {
                        $(receipt_el).closest('.email-autocomplete').find('.receipts').html(recipients_html);
                        if (recipients_html) {
                            $(receipt_el).closest('.email-autocomplete').find('.receipts').addClass('active');
                        }
                    } else {
                        $(receipt_el).closest('.email-autocomplete').append('<div class="receipts' + (recipients_html ? ' active' : '') + '">' + recipients_html + '</div>');
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

function init_tinymce_editor(selector, content = '') {
    tinymce.remove(selector);
    if (!content) {
        content = $(selector).val();
    }
    $(selector).val(content);
    tinymce.init({
        branding: false,
        promotion: false,
        selector: selector,
        browser_spellcheck: true,
        cache_suffix: '?v=' + app.version,
        height: 250,
        min_height: 250,
        statusbar: false,
        theme: "silver",
        paste_block_drop: true,
        language: app.tinymce_lang || 'en',
        relative_urls: false,
        entity_encoding: "raw",
        autoresize_bottom_margin: 25,
        valid_elements: "+*[*]",
        valid_children: "+body[style], +style[type]",
        remove_script_host: false,
        removed_menuitems: "newdocument restoredraft",
        forced_root_block: "p",
        autosave_restore_when_empty: false,
        font_size_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
        table_default_styles: {
          width: "100%",
        },
        plugins: ["advlist", "autoresize", "autosave", "lists", "link", "image", "codesample", "visualblocks", "code", "fullscreen", "media", "save", "table", ],
        toolbar: "fontfamily fontsize | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | image link | bullist numlist | restoredraft",
        contextmenu: "link image | paste copy",
        file_picker_callback: elFinderBrowser,
        setup: function(editor) {
            editor.on('init', function() {
                editor.setContent(content);
            });
        }
    });
}

function check_email_template() {
    let mailbox_templateid = $('#mailbox-compose-form [name="templateid"]').val() ? $('#mailbox-compose-form [name="templateid"]').val() : '';
    if (mailbox_templateid) {
        $.ajax({
            type: 'GET',
            url: admin_url + '/mailbox/get_email_template/' + mailbox_templateid,
        }).done(function(response) {
            response = JSON.parse(response);

            init_tinymce_editor('#mailbox-compose-form [name="body"]', response.message);
        }).fail(function(error) {
            alert_float('danger', JSON.parse(error.responseText));
        });
    } else {
        init_tinymce_editor('#mailbox-compose-form [name="body"]');
    }
}

$('#mailbox-compose-form [name="templateid"]').change(function() {
    check_email_template();
});

function check_reply_template() {
    let mailbox_templateid = $('#mailbox-auto-reply-form [name="replyid"]').val() ? $('#mailbox-auto-reply-form [name="replyid"]').val() : '';
    if (mailbox_templateid) {
        $.ajax({
            type: 'GET',
            url: admin_url + '/mailbox/get_email_template/' + mailbox_templateid,
        }).done(function(response) {
            response = JSON.parse(response);

            init_tinymce_editor('#mailbox-auto-reply-form [name="body"]', response.message);
        }).fail(function(error) {
            alert_float('danger', JSON.parse(error.responseText));
        });
    } else {
        init_tinymce_editor('#mailbox-auto-reply-form [name="body"]');
    }
}

function mailboxAutoReplyFormHandler(form) {
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

        if ($.fn.DataTable.isDataTable('.table-mailbox-auto-replies')) {
            $('.table-mailbox-auto-replies').DataTable().ajax.reload(null, false);
        }

        $('#autoReplyModal').modal('hide');
    }).fail(function(error) {
        alert_float('danger', JSON.parse(error.responseText));
    });

    return false;
}

function validate_mailbox_auto_reply_form() {
    appValidateForm('#mailbox-auto-reply-form', {
        name: 'required',
        pattern: 'required',
    }, mailboxAutoReplyFormHandler);
}

function view_mailbox_auto_reply(auto_reply_id) {
    if (!auto_reply_id || typeof auto_reply_id == 'undefined') {
        auto_reply_id = '';
    }
    requestGet('mailbox/form_auto_reply/' + auto_reply_id).done(function(response) {
        $('#autoReplyModal .modal-content').html(response);
        $('#autoReplyModal').modal({
            show: true,
            backdrop: 'static'
        });

        $('body').off('shown.bs.modal', '#mailbox-auto-reply-form');

        $('body').on('shown.bs.modal', '#mailbox-auto-reply-form', function() {
            if (auto_reply_id == '') {
                $('#mailbox-auto-reply-form').find('input[name="name"]').focus();
            }
        });

        init_selectpicker();
        init_datepicker();
        custom_fields_hyperlink();
        validate_mailbox_auto_reply_form();

        $('#mailbox-auto-reply-form [name="replyid"]').change(function() {
            check_reply_template();
        });
        check_reply_template();
    }).fail(function(error) {
        var response = JSON.parse(error.responseText);

        alert_float('danger', response.message);
    });
}

function unassgin_customer(client_id, mail_id, type='inbox') {
    var data = {};
    data.client_id = client_id;
    data.mail_id = mail_id;
    data.type = type;
    $.post(admin_url + 'mailbox/unassign_customers', data).done(function(response) {
        response = JSON.parse(response);
        if (response.success === true || response.success == 'true') {
            alert_float('success', response.message);
            window.location.reload();
        } else {
            alert_float('warning', response.message);
        }
    });
}

$(document).ready(function() {
    enable_email_autocomplete();

    if ($('.table-mailbox-clients').length) {
       initDataTable('.table-mailbox-clients', admin_url + 'mailbox/table_client_emails/' + mailbox_client_id, undefined, [0], 'undefined', [2, 'asc']);
    }
});