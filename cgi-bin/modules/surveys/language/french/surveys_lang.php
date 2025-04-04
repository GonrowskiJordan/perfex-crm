<?php

# Version 2.3.0

$lang['survey_submit']                                    = 'Envoyer';
$lang['new_survey']                                       = 'Nouveau sondage';
$lang['surveys']                                          = 'Sondages';
$lang['survey']                                           = 'Sondage';
$lang['survey_lowercase']                                 = 'sondage';
$lang['survey_no_mail_lists_selected']                    = 'Aucune liste emails sélectionnée';
$lang['survey_send_success_note']                         = 'Tous les emails du sondage (%s) seront envoyés par l\'emploi du CRON Job avec un intervalle de 5 minutes';
$lang['survey_result']                                    = 'Résultat pour le sondage : %s';
$lang['question_string']                                  = 'Question';
$lang['question_field_string']                            = 'Champ';
$lang['survey_list_view_tooltip']                         = 'Voir le sondage';
$lang['survey_list_view_results_tooltip']                 = 'Voir les résultats';
$lang['survey_add_edit_subject']                          = 'Sujet';
$lang['survey_add_edit_email_description']                = 'Description (Description email)';
$lang['survey_include_survey_link']                       = 'Inclure le lien du sondage dans la description';
$lang['survey_available_mail_lists_custom_fields']        = 'Champs personnalisés disponibles à partir des listes emails';
$lang['survey_mail_lists_custom_fields_tooltip']          = 'Les champs personnalisés peuvent être utilisés pour l\'éditeur de la messagerie. Astuce: Cliquez sur l\'éditeur de messagerie, puis sélectionnez le champ dans le menu déroulant pour l\’ajouter automatiquement.';
$lang['survey_add_edit_short_description_view']           = 'Description courte du sondage (Voir la description)';
$lang['survey_add_edit_from']                             = 'De (affiché dans l\'email)';
$lang['survey_add_edit_redirect_url']                     = 'URL de redirection du sondage';
$lang['survey_add_edit_red_url_note']                     = 'Lorsque l\’utilisateur termine son sondage où doit-il être redirigé (laisser vide pour conserver cette url)';
$lang['survey_add_edit_disabled']                         = 'Désactiver';
$lang['survey_add_edit_only_for_logged_in']               = 'Seulement pour les participants inscrits (collaborateurs, clients)';
$lang['send_survey_string']                               = 'Envoyer le sondage';
$lang['survey_send_mail_list_clients']                    = 'Clients';
$lang['survey_send_mail_list_staff']                      = 'Collaborateur';
$lang['survey_send_mail_lists_string']                    = 'Listes emails';
$lang['survey_send_mail_lists_note_logged_in']            = 'Remarque: si vous envoyez des sondages dans vos mailings seuls les participants connectés doivent être décochés';
$lang['survey_send_string']                               = 'Envoyer';
$lang['survey_send_to_total']                             = 'Envoyé à %s emails';
$lang['survey_send_till_now']                             = 'Jusqu\'à maintenant';
$lang['survey_send_finished']                             = 'Sondages envoyés: %s';
$lang['survey_added_to_queue']                            = 'Ce sondage a été ajouté dans la file d\'attente cron le %s';
$lang['survey_questions_string']                          = 'Questions';
$lang['survey_insert_field']                              = 'Insérer un champ';
$lang['survey_field_checkbox']                            = 'Case à cocher';
$lang['survey_field_radio']                               = 'Radio';
$lang['survey_field_input']                               = 'Champ de texte';
$lang['survey_field_textarea']                            = 'Zone de texte';
$lang['survey_question_required']                         = 'Requis';
$lang['survey_question_only_for_preview']                 = 'Uniquement pour la prévisualisation';
$lang['survey_create_first']                              = 'Vous devez premièrement créer le sondage, alors vous serez en mesure d\'insérer les questions.';
$lang['survey_dt_name']                                   = 'Nom';
$lang['survey_dt_total_questions']                        = 'Total Questions';
$lang['survey_dt_total_participants']                     = 'Total Participants';
$lang['survey_dt_date_created']                           = 'Date de création';
$lang['survey_dt_active']                                 = 'Actif';
$lang['survey_text_questions_results']                    = 'Résultats des questions de texte';
$lang['survey_view_all_answers']                          = 'Voir toutes les réponses';
$lang['settings_survey_send_emails_per_cron_run']         = 'Nombre d\'emails à envoyer par heure (Sondages)';
$lang['settings_survey_send_emails_per_cron_run_tooltip'] = 'Cettte option est utilisée lors de l\'envoi des sondages. La tâche cron des sondages s\'exécute toutes les 5 minutes. Vous pouvez par conséquent définir le nombre d\'emails à envoyer toutes les 5 minutes';
$lang['settings_cron_surveys']                            = 'Sondages';
$lang['survey_no_questions']                              = 'Cette enquête n\'a pas de question supplémentaire encore.';
$lang['survey_send_to_lists']                             = 'Listes d\'envoi des enquêtes';
$lang['survey_send_notice']                               = 'Les e-mails seront expédiés toutes les heures via CRON JOB.';
$lang['survey_customers_all']                             = 'Tous les Clients';
$lang['mail_lists']                                       = 'Listes emails';
$lang['mail_list']                                        = 'Liste emails';
$lang['new_mail_list']                                    = 'Nouvelle liste emails';
$lang['mail_list_lowercase']                              = 'liste emails';
$lang['custom_field_deleted_success']                     = 'Champ personnalisé supprimé';
$lang['custom_field_deleted_fail']                        = 'Problème lors de la suppression du champ personnalisé';
$lang['email_removed_from_list']                          = 'E-mail supprimé de la liste';
$lang['email_remove_fail']                                = 'Failed to remove email from list';
$lang['staff_mail_lists']                                 = 'Liste email collaborateurs';
$lang['clients_mail_lists']                               = 'Liste email clients';
$lang['mail_list_total_imported']                         = 'Total emails importés: %s';
$lang['mail_list_total_duplicate']                        = 'Total emails dupliqués: %s';
$lang['mail_list_total_failed_to_insert']                 = 'Echec d\'insertion des emails: %s';
$lang['mail_list_total_invalid']                          = 'Adresse email invalide: %s';
$lang['cant_edit_mail_list']                              = 'Vous ne pouvez pas éditer cette liste, celle-ci est renseignée automatiquement';
$lang['mail_list_add_edit_name']                          = 'Nom de la liste emails';
$lang['mail_list_add_edit_customfield']                   = 'Ajouter un champ personnalisé';
$lang['mail_lists_view_email_email_heading']              = 'Email';
$lang['mail_lists_view_email_date_heading']               = 'Ajouté le';
$lang['add_new_email_to']                                 = 'Ajouter un nouvel email à %s';
$lang['import_emails_to']                                 = 'Importer des emails à %s';
$lang['mail_list_new_email_edit_add_label']               = 'Email';
$lang['mail_list_import_file']                            = 'Importer un fichier';
$lang['mail_list_available_custom_fields']                = 'Champs personnalisés disponibles';
$lang['submit_import_emails']                             = 'Importer des emails';
$lang['btn_import_emails']                                = 'Importer des emails (Excel)';
$lang['btn_add_email_to_list']                            = 'Ajouter l\'email à la liste';
$lang['mail_lists_dt_list_name']                          = 'Nom de la liste';
$lang['mail_lists_dt_datecreated']                        = 'Date de création';
$lang['mail_lists_dt_creator']                            = 'Auteur';
$lang['email_added_to_mail_list_successfully']            = 'E-mail ajouté à la liste';
$lang['email_is_duplicate_mail_list']                     = 'E-mail dèjà présent dans la liste';
