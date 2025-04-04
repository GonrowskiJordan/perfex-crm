<?php

# Version 2.3.0
$lang['survey_submit']                                    = 'Inleveren';
$lang['new_survey']                                       = 'Nieuwe enquête';
$lang['surveys']                                          = 'Enquêtes';
$lang['survey']                                           = 'Enquête';
$lang['survey_lowercase']                                 = 'enquête';
$lang['survey_no_mail_lists_selected']                    = 'Geen maillijst geselecteerd';
$lang['survey_send_success_note']                         = 'Alle enquêtemails (%s) zullen verzonden worden via cronjob';
$lang['survey_result']                                    = 'Resultaat voor enquête: %s';
$lang['question_string']                                  = 'Vraag';
$lang['question_field_string']                            = 'Veld';
$lang['survey_list_view_tooltip']                         = 'Bekijk enquête';
$lang['survey_list_view_results_tooltip']                 = 'Bekijk resultaten';
$lang['survey_add_edit_subject']                          = 'Enquête onderwerp';
$lang['survey_add_edit_email_description']                = 'Enquête beschrijving (voor email)';
$lang['survey_include_survey_link']                       = 'Enquête link toevoegen aan beschrijving';
$lang['survey_available_mail_lists_custom_fields']        = 'Beschikbare aangepaste velden van mailing lijst';
$lang['survey_mail_lists_custom_fields_tooltip']          = 'Aangepaste velden kunnen gebruikt worden voor mailing editor.';
$lang['survey_add_edit_short_description_view']           = 'Enquête beschrijving (voor enquête )';
$lang['survey_add_edit_from']                             = 'Van (in email weergegeven)';
$lang['survey_add_edit_redirect_url']                     = 'Enquête doorsturing';
$lang['survey_add_edit_red_url_note']                     = 'Waar gebruikers na het invoeren van de enquête naar doorgestuurd worden. (Laat leeg voor deze site URL)';
$lang['survey_add_edit_disabled']                         = 'Uitgeschakeld';
$lang['survey_add_edit_only_for_logged_in']               = 'Alleen voor ingelogde deelnemers (medewerkers, klanten)';
$lang['send_survey_string']                               = 'Enquête versturen';
$lang['survey_send_mail_list_clients']                    = 'Klanten';
$lang['survey_send_mail_list_staff']                      = 'Medewerker';
$lang['survey_send_mail_lists_string']                    = 'Maillijsten';
$lang['survey_send_mail_lists_note_logged_in']            = 'Let op: als je de enquête verstuurt naar maillijsten, moet \'alleen voor ingelogde gebruikers\' niet zijn aangevinkt.';
$lang['survey_send_string']                               = 'Versturen';
$lang['survey_send_to_total']                             = 'Stuur naar totaal %s emails';
$lang['survey_send_till_now']                             = 'Tot nu toe';
$lang['survey_send_finished']                             = 'Enquête versturen klaar: %s';
$lang['survey_added_to_queue']                            = 'Deze enquête word toegevoegd aan de cron queue op %s';
$lang['survey_questions_string']                          = 'Vragen';
$lang['survey_insert_field']                              = 'Veld toevoegen';
$lang['survey_field_checkbox']                            = 'Selectievakjes';
$lang['survey_field_radio']                               = 'Meerkeuze';
$lang['survey_field_input']                               = 'Korte tekst';
$lang['survey_field_textarea']                            = 'Lange tekst';
$lang['survey_question_required']                         = 'Verplicht';
$lang['survey_question_only_for_preview']                 = 'Alleen voor preview';
$lang['survey_create_first']                              = 'Je moet eerst de enquête maken, daarna kan je de vragen toevoegen.';
$lang['survey_dt_name']                                   = 'Naam';
$lang['survey_dt_total_questions']                        = 'Aantal vragen';
$lang['survey_dt_total_participants']                     = 'Aantal deelnemers';
$lang['survey_dt_date_created']                           = 'Aangemaakt ';
$lang['survey_dt_active']                                 = 'Actief';
$lang['survey_text_questions_results']                    = 'Tekst antwoorden op vragen';
$lang['survey_view_all_answers']                          = 'Bekijk alle antwoorden';
$lang['settings_survey_send_emails_per_cron_run']         = 'Hoeveel emails per uur te verzenden';
$lang['settings_survey_send_emails_per_cron_run_tooltip'] = 'Deze optie wordt gebruikt wanneer je enquêtes verstuurd. De enquête cronjob gaat elke 5 minuten, dus je kan kiezen hoeveel emails je elke 5 minuten stuurt.';
$lang['settings_cron_surveys']                            = 'Enquêtes';
$lang['survey_no_questions']                              = 'Deze enquête heeft nog geen vragen.';
$lang['survey_send_to_lists']                             = 'Enquête verstuurd lijsten';
$lang['survey_send_notice']                               = 'Emails worden ieder uur door cronjob verzonden.';
$lang['survey_customers_all']                             = 'Alle klanten';
$lang['mail_lists']                                       = 'Maillijsten';
$lang['mail_list']                                        = 'Maillijst';
$lang['new_mail_list']                                    = 'Nieuwe maillijst';
$lang['mail_list_lowercase']                              = 'maillijst';
$lang['custom_field_deleted_success']                     = 'Aangepast veld verwijderd';
$lang['custom_field_deleted_fail']                        = 'Probleem bij het verwijderen van aangepast veld';
$lang['email_removed_from_list']                          = 'Email adres verwijderd van maillijst';
$lang['email_remove_fail']                                = 'Probleem bij het verwijderen van het email adres';
$lang['staff_mail_lists']                                 = 'Medewerkers maillijst';
$lang['clients_mail_lists']                               = 'Klanten maillijst';
$lang['mail_list_total_imported']                         = 'Totale emails geïmport: %s';
$lang['mail_list_total_duplicate']                        = 'Totale dubbele emails: %s';
$lang['mail_list_total_failed_to_insert']                 = 'Emails onsuccesvol toegevoegd: %s';
$lang['mail_list_total_invalid']                          = 'Ongeldig email adres: %s';
$lang['cant_edit_mail_list']                              = 'Je kan deze lijst niet bewerken, deze lijst wordt automatisch bijgehouden.';
$lang['mail_list_add_edit_name']                          = 'Maillijst titel';
$lang['mail_list_add_edit_customfield']                   = 'Aangepast veld toevoegen';
$lang['mail_lists_view_email_email_heading']              = 'Email';
$lang['mail_lists_view_email_date_heading']               = 'Datum';
$lang['add_new_email_to']                                 = 'Nieuw email adres toevoegen aan %s';
$lang['import_emails_to']                                 = 'Importeer emails naar %s';
$lang['mail_list_new_email_edit_add_label']               = 'Email';
$lang['mail_list_import_file']                            = 'Importeer document';
$lang['mail_list_available_custom_fields']                = 'Beschikbare aangepaste velden';
$lang['submit_import_emails']                             = 'Importeer emails';
$lang['btn_import_emails']                                = 'Importeer emails (Excel)';
$lang['btn_add_email_to_list']                            = 'Email aan deze lijst toevoegen';
$lang['mail_lists_dt_list_name']                          = 'Lijst titel';
$lang['mail_lists_dt_datecreated']                        = 'Datum';
$lang['mail_lists_dt_creator']                            = 'Door';
$lang['email_added_to_mail_list_successfully']            = 'Email toegevoegd aan lijst';
$lang['email_is_duplicate_mail_list']                     = 'Email bestaat al in deze lijst';
