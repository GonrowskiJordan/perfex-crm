<?php

# Version 2.3.0

$lang['survey_submit']                                    = 'Skicka';
$lang['new_survey']                                       = 'Ny Undersökning';
$lang['surveys']                                          = 'Undersökningar';
$lang['survey']                                           = 'Undersökning';
$lang['survey_lowercase']                                 = 'undersökning';
$lang['survey_no_mail_lists_selected']                    = 'Inga mailinglistor valda';
$lang['survey_send_success_note']                         = 'All Enkät e-post(%s) kommer skickas via CRON';
$lang['survey_result']                                    = 'Enkät resultat: %s';
$lang['question_string']                                  = 'Fråga';
$lang['question_field_string']                            = 'Fält';
$lang['survey_list_view_tooltip']                         = 'Visa undersökning';
$lang['survey_list_view_results_tooltip']                 = 'Visa resultat';
$lang['survey_add_edit_subject']                          = 'Undersökningens Ämne';
$lang['survey_add_edit_email_description']                = 'Enkät beskrivning (Epost Beskrivning)';
$lang['survey_include_survey_link']                       = 'Inkludera enkätlänk i beskrivningen';
$lang['survey_available_mail_lists_custom_fields']        = 'Finns anpassade fält från e-postlistor';
$lang['survey_mail_lists_custom_fields_tooltip']          = 'Anpassade fält kan användas för e-post redaktör.';
$lang['survey_add_edit_short_description_view']           = 'Enkät kort beskrivning (Se beskrivning)';
$lang['survey_add_edit_from']                             = 'Från (visas i e-post)';
$lang['survey_add_edit_redirect_url']                     = 'Enkät omdirigeringsadress';
$lang['survey_add_edit_red_url_note']                     = 'När användaren avsluta undersökningen var skakunden hamna, omdirigeras till (lämna tomt för denna webbadress)';
$lang['survey_add_edit_disabled']                         = 'Avaktiverad';
$lang['survey_add_edit_only_for_logged_in']               = 'Endast för inloggade deltagare (personal, kunderna)';
$lang['send_survey_string']                               = 'Skicka undersökning';
$lang['survey_send_mail_list_clients']                    = 'Kunder';
$lang['survey_send_mail_list_staff']                      = 'Personal';
$lang['survey_send_mail_lists_string']                    = 'Mail listor';
$lang['survey_send_mail_lists_note_logged_in']            = 'Obs: Om du skickar enkäten till mailinglistor för endast inloggade deltagare, måste den vara omarkerad. ehee ja ja bättre menings uppbyggnad kan ni komma på på denna beskrivning';
$lang['survey_send_string']                               = 'Skicka';
$lang['survey_send_to_total']                             = 'Skicka till totalt %s epostadresser';
$lang['survey_send_till_now']                             = 'Tills nu';
$lang['survey_send_finished']                             = 'Undersökningen har skickat färdigt: %s';
$lang['survey_added_to_queue']                            = 'Denna undersökning läggs till i CRON kö på %s';
$lang['survey_questions_string']                          = 'Fråga';
$lang['survey_insert_field']                              = 'Infoga fält';
$lang['survey_field_checkbox']                            = 'Checkbox';
$lang['survey_field_radio']                               = 'Radio';
$lang['survey_field_input']                               = 'Inmatningsfält';
$lang['survey_field_textarea']                            = 'Textfält';
$lang['survey_question_required']                         = 'Obligatoriskt';
$lang['survey_question_only_for_preview']                 = 'Endast för förhandsvisning';
$lang['survey_create_first']                              = 'Du måste skapa undersökningen först då kommer du att kunna sätta in frågorna.';
$lang['survey_dt_name']                                   = 'Namn';
$lang['survey_dt_total_questions']                        = 'Totalt Frågor';
$lang['survey_dt_total_participants']                     = 'Totalt Deltagare';
$lang['survey_dt_date_created']                           = 'Skapat datum';
$lang['survey_dt_active']                                 = 'Aktiv';
$lang['survey_text_questions_results']                    = 'Resultat Textfrågor';
$lang['survey_view_all_answers']                          = 'Visa alla svar';
$lang['settings_survey_send_emails_per_cron_run']         = 'Hur många e-post i timmen (Cron) ';
$lang['settings_survey_send_emails_per_cron_run_tooltip'] = 'Det här alternativet används när du skickar undersökningar. Survey cron kommer att skicka X e-postmeddelanden per timme. Vissa webbhotell har gräns för att skicka e-post per timme.';
$lang['settings_cron_surveys']                            = 'Undersökningar';
$lang['survey_no_questions']                              = 'Denna undersökning har inga frågor ännu.';
$lang['survey_send_to_lists']                             = 'Undersökning - skicka lista';
$lang['survey_send_notice']                               = 'Eposten kommer skickas via CRON JOB per timme.';
$lang['survey_customers_all']                             = 'Alla kunder';
$lang['mail_lists']                                       = 'Maillista';
$lang['mail_list']                                        = 'MailLista';
$lang['new_mail_list']                                    = 'Ny MailLista';
$lang['mail_list_lowercase']                              = 'maillista';
$lang['custom_field_deleted_success']                     = 'Anpassat fält raderade';
$lang['custom_field_deleted_fail']                        = 'Problem att radera Anpassat fält';
$lang['email_removed_from_list']                          = 'Epost togs bort Från listan';
$lang['email_remove_fail']                                = 'Epost togs ej bort Från listan';
$lang['staff_mail_lists']                                 = 'Personal sändlista';
$lang['clients_mail_lists']                               = 'Kunder sändlista';
$lang['mail_list_total_imported']                         = 'Totalt inporterade epostadresser: %s';
$lang['mail_list_total_duplicate']                        = 'Antal dubletter: %s';
$lang['mail_list_total_failed_to_insert']                 = 'Emails failed to insert: %s';
$lang['mail_list_total_invalid']                          = 'Invalid email address: %s';
$lang['cant_edit_mail_list']                              = 'You cant edit this list, this list is populated automatically';
$lang['mail_list_add_edit_name']                          = 'Mail List Name';
$lang['mail_list_add_edit_customfield']                   = 'Add custom field';
$lang['mail_lists_view_email_email_heading']              = 'Email';
$lang['mail_lists_view_email_date_heading']               = 'Date Added';
$lang['add_new_email_to']                                 = 'Add New Email to %s';
$lang['import_emails_to']                                 = 'Import Emails to %s';
$lang['mail_list_new_email_edit_add_label']               = 'Email';
$lang['mail_list_import_file']                            = 'Import File';
$lang['mail_list_available_custom_fields']                = 'Available Custom Fields';
$lang['submit_import_emails']                             = 'Import Emails';
$lang['btn_import_emails']                                = 'Import Emails (Excel)';
$lang['btn_add_email_to_list']                            = 'Add Email to This List';
$lang['mail_lists_dt_list_name']                          = 'List Name';
$lang['mail_lists_dt_datecreated']                        = 'Date Created';
$lang['mail_lists_dt_creator']                            = 'Creator';
$lang['email_added_to_mail_list_successfully']            = 'Email added to list';
$lang['email_is_duplicate_mail_list']                     = 'Email already exists in this list';
