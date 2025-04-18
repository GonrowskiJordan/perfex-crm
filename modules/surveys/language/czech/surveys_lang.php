<?php

# Version 2.3.0
$lang['survey_submit']                                    = 'Odeslat';
$lang['new_survey']                                       = 'Nový průzkum';
$lang['surveys']                                          = 'Průzkumy';
$lang['survey']                                           = 'Průzkum';
$lang['survey_lowercase']                                 = 'průzkum';
$lang['survey_no_mail_lists_selected']                    = 'Nebyl zvolen seznam emailů';
$lang['survey_send_success_note']                         = 'Všechny emaily průzkumu(%s) budou odeslány pomocí CRONu';
$lang['survey_result']                                    = 'Výsledky průzkumu: %s';
$lang['question_string']                                  = 'Otázka';
$lang['question_field_string']                            = 'Pole';
$lang['survey_list_view_tooltip']                         = 'Zobrazit průzkum';
$lang['survey_list_view_results_tooltip']                 = 'Zobrazit výsledky';
$lang['survey_add_edit_subject']                          = 'Předmět průzkumu';
$lang['survey_add_edit_email_description']                = 'Popis průzkumu (Popis emailu)';
$lang['survey_include_survey_link']                       = 'Připojit odkaz na průzkum v popisu';
$lang['survey_available_mail_lists_custom_fields']        = 'Dostupná vlastní pole ze seznamu emailů';
$lang['survey_mail_lists_custom_fields_tooltip']          = 'Vlastní pole mohou být použita pro editor emailu.';
$lang['survey_add_edit_short_description_view']           = 'Krátký popis průzkumu (Zobrazit popis)';
$lang['survey_add_edit_from']                             = 'Od (zobrazeno v emailu)';
$lang['survey_add_edit_redirect_url']                     = 'URL přesměrování průzkumu';
$lang['survey_add_edit_red_url_note']                     = 'Kam bude uživatel přesměrován po dokončení průzkumu (ponechte prázdné pro přesměrování na tuto stránku)';
$lang['survey_add_edit_disabled']                         = 'Zakázáno';
$lang['survey_add_edit_only_for_logged_in']               = 'Pouze pro přihlášené (zaměstnanci,zákazníci)';
$lang['send_survey_string']                               = 'Poslat průzkum';
$lang['survey_send_mail_list_clients']                    = 'Zákazníci';
$lang['survey_send_mail_list_staff']                      = 'Zaměstnanci';
$lang['survey_send_mail_lists_string']                    = 'Seznamy mailů';
$lang['survey_send_mail_lists_note_logged_in']            = 'Poznámka: Pokud odesíláte průzkum přes seznamy mailů, odškrtněte Pouze pro přihlášené uživatele.';
$lang['survey_send_string']                               = 'Odeslat';
$lang['survey_send_to_total']                             = 'Odeslat na celkem %s emailů';
$lang['survey_send_till_now']                             = 'Doposud';
$lang['survey_send_finished']                             = 'Odeslání průzkumu dokončeno: %s';
$lang['survey_added_to_queue']                            = 'Tento průzkum je přidán do fronty cornu %s';
$lang['survey_questions_string']                          = 'Otázka';
$lang['survey_insert_field']                              = 'Vložit pole';
$lang['survey_field_checkbox']                            = 'Zakškrtávací pole';
$lang['survey_field_radio']                               = 'Radio';
$lang['survey_field_input']                               = 'Pole zadávání';
$lang['survey_field_textarea']                            = 'Textová oblast';
$lang['survey_question_required']                         = 'Povinné';
$lang['survey_question_only_for_preview']                 = 'Pouze pro náhled';
$lang['survey_create_first']                              = 'Nejdřív musíte vytvořit průzkum, poté budete moci vložit otázky.';
$lang['survey_dt_name']                                   = 'Jméno';
$lang['survey_dt_total_questions']                        = 'Celkem otázek';
$lang['survey_dt_total_participants']                     = 'Celkem účastníků';
$lang['survey_dt_date_created']                           = 'Datum vytvoření';
$lang['survey_dt_active']                                 = 'Aktivní';
$lang['survey_text_questions_results']                    = 'Výsledky textových otázek';
$lang['survey_view_all_answers']                          = 'Prohlédnout všechny odpovědi';
$lang['settings_survey_send_emails_per_cron_run']         = 'Kolik emailů odesílat za hodinu';
$lang['settings_survey_send_emails_per_cron_run_tooltip'] = 'Tato možnost se využívá při odesílání průzkumů. Cron odešle X emailů za hodinu. Některé hostingy mají limit pro odesílání emailů.';
$lang['settings_cron_surveys']                            = 'Průzkumy';
$lang['survey_no_questions']                              = 'Tento průzkum zatím nemá žádné dotazy.';
$lang['survey_send_to_lists']                             = 'Seznamy pro odeslání průzkumu';
$lang['survey_send_notice']                               = 'Emaily budou odeslány pomocí CRONu.';
$lang['survey_customers_all']                             = 'Všichni klienti';
$lang['mail_lists']                                       = 'Seznamy emailů';
$lang['mail_list']                                        = 'Seznam emailů';
$lang['new_mail_list']                                    = 'Nový seznam emailů';
$lang['mail_list_lowercase']                              = 'mail list';
$lang['custom_field_deleted_success']                     = 'Vlastní pole odstraněno';
$lang['custom_field_deleted_fail']                        = 'Chyba odstraňování vlastního pole';
$lang['email_removed_from_list']                          = 'Email odstraněn ze seznamu';
$lang['email_remove_fail']                                = 'Failed to remove email from list';
$lang['staff_mail_lists']                                 = 'Seznam emailů zaměstnanců';
$lang['clients_mail_lists']                               = 'Seznam emailů klientů';
$lang['mail_list_total_imported']                         = 'Celkem iportováno emailů: %s';
$lang['mail_list_total_duplicate']                        = 'Celkem duplikováno emailů: %s';
$lang['mail_list_total_failed_to_insert']                 = 'Neúspěšně vložených emailů: %s';
$lang['mail_list_total_invalid']                          = 'Neplatných emailových adres: %s';
$lang['cant_edit_mail_list']                              = 'Tento seznam je vytvářen automaticky a nelze ho upravit';
$lang['mail_list_add_edit_name']                          = 'Název seznamu emailů';
$lang['mail_list_add_edit_customfield']                   = 'Přidat vlastní pole';
$lang['mail_lists_view_email_email_heading']              = 'Email';
$lang['mail_lists_view_email_date_heading']               = 'Datum přidání';
$lang['add_new_email_to']                                 = 'Přidat nový email do %s';
$lang['import_emails_to']                                 = 'Importovat emaily do %s';
$lang['mail_list_new_email_edit_add_label']               = 'Email';
$lang['mail_list_import_file']                            = 'Soubor importu';
$lang['mail_list_available_custom_fields']                = 'Dostupná vlastní pole';
$lang['submit_import_emails']                             = 'Importovat emaily';
$lang['btn_import_emails']                                = 'Importovat emaily (Excel)';
$lang['btn_add_email_to_list']                            = 'Přidat email do tohoto seznamu';
$lang['mail_lists_dt_list_name']                          = 'Název seznamu';
$lang['mail_lists_dt_datecreated']                        = 'Datum vytvoření';
$lang['mail_lists_dt_creator']                            = 'Vytvořil';
$lang['email_added_to_mail_list_successfully']            = 'Email přidán do seznamu';
$lang['email_is_duplicate_mail_list']                     = 'Email v tomto seznamu již existuje';
