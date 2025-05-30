<?php

# Version 2.3.0

$lang['survey_submit']                                    = 'Senden';
$lang['new_survey']                                       = 'Umfrage erstellen';
$lang['surveys']                                          = 'Umfragen';
$lang['survey']                                           = 'Umfrage';
$lang['survey_lowercase']                                 = 'Umfrage';
$lang['survey_no_mail_lists_selected']                    = 'Keine Mailing Liste ausgewählt';
$lang['survey_send_success_note']                         = 'Alle Umfrage Mails (%s) werden mittels Cron Job innerhalb von 5 Minuten versendet';
$lang['survey_result']                                    = 'Ergebnis für die Umfrage: %s';
$lang['question_string']                                  = 'Frage';
$lang['question_field_string']                            = 'Feld';
$lang['survey_list_view_tooltip']                         = 'Umfrage anzeigen';
$lang['survey_list_view_results_tooltip']                 = 'Ergebnis anzeigen';
$lang['survey_add_edit_subject']                          = 'Umfrage Titel';
$lang['survey_add_edit_email_description']                = 'Umfrage Beschreibung (E-Mail Text)';
$lang['survey_include_survey_link']                       = 'Den Umfrage-Link in die Beschreibung einfügen';
$lang['survey_available_mail_lists_custom_fields']        = 'Verfügbare benutzerdefinierte Felder aus E-Mail-Listen';
$lang['survey_mail_lists_custom_fields_tooltip']          = 'Benutzerdefinierte Felder können für E-Mail-Editor verwendet werden.';
$lang['survey_add_edit_short_description_view']           = 'Kurze Beschreibung (Angezeigte Beschreibung)';
$lang['survey_add_edit_from']                             = 'Gesendet von (Wird in der E-Mail angezeigt)';
$lang['survey_add_edit_redirect_url']                     = 'Umfrage Weiterleitungsadresse';
$lang['survey_add_edit_red_url_note']                     = 'Wohin soll der User weitergeleitet werden nach der Umfrage (Leeres Feld = Kundencenter)';
$lang['survey_add_edit_disabled']                         = 'Deaktivieren';
$lang['survey_add_edit_only_for_logged_in']               = 'Nur für angemeldete Nutzer (Mitarbeiter, Kunden)';
$lang['send_survey_string']                               = 'Umfrage senden';
$lang['survey_send_mail_list_clients']                    = 'Kunden';
$lang['survey_send_mail_list_staff']                      = 'Mitarbeiter';
$lang['survey_send_mail_lists_string']                    = 'Mailing Liste';
$lang['survey_send_mail_lists_note_logged_in']            = 'Notiz: Wenn dies an eine Mailingliste gesendet wird, muss "nur für angemeldete Nutzer" deaktiviert sein';
$lang['survey_send_string']                               = 'Senden';
$lang['survey_send_to_total']                             = 'Gesendet an %s E-Mails';
$lang['survey_send_till_now']                             = 'Bis jetzt';
$lang['survey_send_finished']                             = 'Umfrage senden beendet: %s';
$lang['survey_added_to_queue']                            = 'Diese Umfrage wurde zum Warteschlangen-Cron am %s zugefügt';
$lang['survey_questions_string']                          = 'Fragen';
$lang['survey_insert_field']                              = 'Feld einfügen';
$lang['survey_field_checkbox']                            = 'Checkbox';
$lang['survey_field_radio']                               = 'Radiofeld';
$lang['survey_field_input']                               = 'Textfeld (klein)';
$lang['survey_field_textarea']                            = 'Textfeld (groß)';
$lang['survey_question_required']                         = 'Pflichtfeld';
$lang['survey_question_only_for_preview']                 = 'Nur zur Vorschau';
$lang['survey_create_first']                              = 'Die Umfrage muss zuerst erstellt werden, um Fragen hinzufügen zu können.';
$lang['survey_dt_name']                                   = 'Name';
$lang['survey_dt_total_questions']                        = 'Gesamt Fragen';
$lang['survey_dt_total_participants']                     = 'Gesamt Teilnehmer';
$lang['survey_dt_date_created']                           = 'Datum erstellt';
$lang['survey_dt_active']                                 = 'Aktiv';
$lang['survey_text_questions_results']                    = 'Text Fragen Ergebnis';
$lang['survey_view_all_answers']                          = 'Alle Antworten anzeigen';
$lang['settings_survey_send_emails_per_cron_run']         = 'Wie viele Mails sollen pro Stunde gesendet werden (Umfragen)';
$lang['settings_survey_send_emails_per_cron_run_tooltip'] = 'Diese Option greift beim Senden von Umfragen. Der Umfrage Cron läuft alle 5 Minuten. Es kann definiert werden, wie viele Mails alle 5 Minuten gesendet werden.';
$lang['settings_cron_surveys']                            = 'Umfragen';
$lang['survey_no_questions']                              = 'Dieser Umfrage wurden bisher keine Fragen hinzugefügt.';
$lang['survey_send_to_lists']                             = 'Befragungslisten';
$lang['survey_send_notice']                               = 'E-Mails werden stündlich per CRON JOB gesendet.';
$lang['survey_customers_all']                             = 'Alle Kunden';
$lang['mail_lists']                                       = 'Mailinglisten';
$lang['mail_list']                                        = 'Mailingliste';
$lang['new_mail_list']                                    = 'Mailingliste erstellen';
$lang['mail_list_lowercase']                              = 'Mailingliste';
$lang['custom_field_deleted_success']                     = 'Kunden Bereich gelöscht';
$lang['custom_field_deleted_fail']                        = 'Problem beim Löschen des Kunden-Bereichs';
$lang['email_removed_from_list']                          = 'E-Mail von der Liste gelöscht';
$lang['email_remove_fail']                                = 'E-Mail von der Liste entfernt';
$lang['staff_mail_lists']                                 = 'Mitarbeiter Mailingliste';
$lang['clients_mail_lists']                               = 'Kunden Mailingliste';
$lang['mail_list_total_imported']                         = 'Alle importierten E-Mails: %s';
$lang['mail_list_total_duplicate']                        = 'Alle vervielfachten E-Mails: %s';
$lang['mail_list_total_failed_to_insert']                 = 'Alle zum Einsetzen verfehlten E-Mails: %s';
$lang['mail_list_total_invalid']                          = 'Ungültige E-Mail Adressen: %s';
$lang['cant_edit_mail_list']                              = 'Diese Liste kann nicht bearbeitet werden, da sie automatisch befüllt wurde.';
$lang['mail_list_add_edit_name']                          = 'Mailinglisten Name';
$lang['mail_list_add_edit_customfield']                   = 'Kunden Bereich hinzufügen';
$lang['mail_lists_view_email_email_heading']              = 'E-Mail';
$lang['mail_lists_view_email_date_heading']               = 'Datum hinzugefügt';
$lang['add_new_email_to']                                 = 'Eine neue E-Mail hinzufügen';
$lang['import_emails_to']                                 = 'Import E-Mails to %s';
$lang['mail_list_new_email_edit_add_label']               = 'E-Mail';
$lang['mail_list_import_file']                            = 'Datei importieren';
$lang['mail_list_available_custom_fields']                = 'Verfügbare Kunden Bereiche';
$lang['submit_import_emails']                             = 'E-Mails importieren';
$lang['btn_import_emails']                                = 'E-Mails (Excel) importieren';
$lang['btn_add_email_to_list']                            = 'Die E-Mail dieser Liste hinzufügen.';
$lang['mail_lists_dt_list_name']                          = 'Listenname';
$lang['mail_lists_dt_datecreated']                        = 'Datum erstellt';
$lang['mail_lists_dt_creator']                            = 'Ersteller';
$lang['email_added_to_mail_list_successfully']            = 'E-Mail zur Liste hinzugefügt';
$lang['email_is_duplicate_mail_list']                     = 'E-Mail existiert in dieser Liste bereits';
