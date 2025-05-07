<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php if (isset($consent_purposes)) { ?>
                            <div class="row mbot15">
                                <div class="col-md-3 contacts-filter-column">
                                    <div class="select-placeholder">
                                        <select name="custom_view" title="<?=_l('gdpr_consent'); ?>" id="custom_view" class="selectpicker" data-width="100%">
                                            <option value=""></option>
                                            <?php foreach ($consent_purposes as $purpose) { ?>
                                                <option value="consent_<?= e($purpose['id']); ?>">
                                                    <?= e($purpose['name']); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="inline-block new-contact-wrapper" data-title="<?= _l('customer_contact_person_only_one_allowed'); ?>">
                            <a href="#" onclick="view_contact(); return false;" class="btn btn-primary new-contact mbot15">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?= _l('new_contact'); ?>
                            </a>
                        </div>

                        <div class="clearfix"></div>

                        <?php
                            $table_data = [_l('client_firstname') , _l('client_lastname') ];
                            if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1')
                            {
                                array_push($table_data, ['name' => _l('gdpr_consent') . ' (' . _l('gdpr_short') . ')', 'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'], ]);
                            }
                            $table_data = array_merge($table_data, [_l('client_email') , _l('clients_list_company') , _l('client_phonenumber') , _l('contact_position') , _l('clients_list_last_login') , ['name' => _l('contact_active') , 'th_attrs' => ['class' => 'text-center']], ]);
                            $custom_fields = get_custom_fields('contacts', ['show_on_table' => 1]);
                            foreach ($custom_fields as $field)
                            {
                                array_push($table_data, ['name' => $field['name'], 'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1], ]);
                            }
                            render_datatable($table_data, 'all-contacts');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>

<div id="consent_data"></div>

<script>
    function contactFormHandler(form) {
        $('#contactModal input[name="is_primary"]').prop('disabled', false);

        $("#contactModal input[type=file]").each(function() {
            if ($(this).val() === "") {
                $(this).prop('disabled', true);
            }
        });

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
                if (typeof(response.is_individual) != 'undefined' && response.is_individual) {
                    $('.new-contact').addClass('disabled');
                    if (!$('.new-contact-wrapper')[0].hasAttribute('data-toggle')) {
                        $('.new-contact-wrapper').attr('data-toggle', 'tooltip');
                    }
                }
            }

            if ($.fn.DataTable.isDataTable('.table-contacts')) {
                $('.table-contacts').DataTable().ajax.reload(null, false);
            } else if ($.fn.DataTable.isDataTable('.table-all-contacts')) {
                $('.table-all-contacts').DataTable().ajax.reload(null, false);
            }

            if (response.proposal_warning && response.proposal_warning != false) {
                $('body').find('#contact_proposal_warning').removeClass('hide');
                $('body').find('#contact_update_proposals_emails').attr('data-original-email', response.original_email);
                $('#contactModal').animate({
                    scrollTop: 0
                }, 800);
            } else {
                $('#contactModal').modal('hide');
            }
        }).fail(function(error) {
            alert_float('danger', JSON.parse(error.responseText));
        });

        return false;
    }
    
    function validate_contact_form() {
        appValidateForm('#contact-form', {
            firstname: 'required',
            lastname: 'required',
            password: {
                required: {
                    depends: function(element) {
                        var $sentSetPassword = $('input[name="send_set_password_email"]');

                        if ($('#contact input[name="contactid"]').val() == '' && $sentSetPassword.prop('checked') == false) {
                            return true;
                        }
                    }
                }
            },
            email: {
                <?php if (hooks()->apply_filters('contact_email_required', 'true') === 'true') { ?>
                    required: true,
                <?php } ?>

                email: true,
                // Use this hook only if the contacts are not logging into the customers area and you are not using support tickets piping.
                <?php if (hooks()->apply_filters('contact_email_unique', 'true') === 'true') { ?>

                remote: {
                    url: admin_url + "misc/contact_email_exists",
                    type: 'post',
                    data: {
                        email: function() {
                            return $('#contact input[name="email"]').val();
                        },
                        userid: function() {
                            return $('body').find('input[name="contactid"]').val();
                        }
                    }
                }
                <?php } ?>
            }
       }, contactFormHandler);
    }

    $(function() {
        var optionsHeading = [];

        var allContactsServerParams = {
            "custom_view": "[name='custom_view']",
        }

        <?php if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1') { ?>
            optionsHeading.push($('#th-consent').index());
        <?php } ?>

        _table_api = initDataTable('.table-all-contacts', window.location.href, optionsHeading, optionsHeading,
        allContactsServerParams, [0, 'asc']);

        if (_table_api) {
            <?php if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1') { ?>
                _table_api.on('draw', function() {
                    var tableData = $('.table-all-contacts').find('tbody tr');
                    $.each(tableData, function() {
                        $(this).find('td:eq(2)').addClass('bg-neutral');
                    });
                });

                $('select[name="custom_view"]').on('change', function() {
                    _table_api.ajax.reload().columns.adjust().responsive.recalc();
                });
            <?php } ?>
        }
    });
</script>
</body>
</html>