<?php

# Version 2.3.0

$lang['survey_submit']                                    = 'Enviar';
$lang['new_survey']                                       = 'Nova Pesquisa';
$lang['surveys']                                          = 'Pesquisas';
$lang['survey']                                           = 'Pesquisa';
$lang['survey_lowercase']                                 = 'pesquisa';
$lang['survey_no_mail_lists_selected']                    = 'Nenhuma lista de envio selecionada';
$lang['survey_send_success_note']                         = 'Todos os Emails Pesquisados(%s) serão enviados através do CRON de Trabalho com um intervalo de 5 minutos';
$lang['survey_result']                                    = 'Resultado da Pesquisa: %s';
$lang['question_string']                                  = 'Questão';
$lang['question_field_string']                            = 'Campo';
$lang['survey_list_view_tooltip']                         = 'Visualizar Pesquisa';
$lang['survey_list_view_results_tooltip']                 = 'Visualizar Resultados';
$lang['survey_add_edit_subject']                          = 'Assunto da Pesquisa';
$lang['survey_add_edit_email_description']                = 'Descrição da Pesquisa (E-mail de Descrição)';
$lang['survey_include_survey_link']                       = 'Incluir o link da pesquisa na descrição';
$lang['survey_available_mail_lists_custom_fields']        = 'Campo do cliente disponível para listas de e-mails';
$lang['survey_mail_lists_custom_fields_tooltip']          = 'Os campos personalizados podem ser usados para editar e-mail. DICA: Clique no editor de e-mail e, em seguida, selecionar a partir de lista suspensa do menu para ser anexado automaticamente.';
$lang['survey_add_edit_short_description_view']           = 'Pesquisa de descrição curta (Visualizar Descrição)';
$lang['survey_add_edit_from']                             = 'De (exibido no e-mail)';
$lang['survey_add_edit_redirect_url']                     = 'Pesquisa redirecionada para URL';
$lang['survey_add_edit_red_url_note']                     = 'Quando o usuário acabar a pesquisa para onde ele será redirecionado (deixar em branco para esse site url)';
$lang['survey_add_edit_disabled']                         = 'Desativar';
$lang['survey_add_edit_only_for_logged_in']               = 'Apenas para participantes registados (staff,clientes)';
$lang['send_survey_string']                               = 'Enviar Pesquisa';
$lang['survey_send_mail_list_clients']                    = 'Clientes';
$lang['survey_send_mail_list_staff']                      = 'Staff';
$lang['survey_send_mail_lists_string']                    = 'Listas de Envio';
$lang['survey_send_mail_lists_note_logged_in']            = 'Nota: Se você está enviando uma pesquisa para as listas de envio Apenas para participantes registados precisa não estar selecionado';
$lang['survey_send_string']                               = 'Enviar';
$lang['survey_send_to_total']                             = 'Enviar para total de %s e-mails';
$lang['survey_send_till_now']                             = 'Até agora';
$lang['survey_send_finished']                             = 'Envio da pesquisa terminado: %s';
$lang['survey_added_to_queue']                            = 'Essa pesquisa foi adicionada a fila cron em %s';
$lang['survey_questions_string']                          = 'Questões';
$lang['survey_insert_field']                              = 'Inserir Campo';
$lang['survey_field_checkbox']                            = 'Caixa de Seleção';
$lang['survey_field_radio']                               = 'Rádio';
$lang['survey_field_input']                               = 'Incluir Campo';
$lang['survey_field_textarea']                            = 'Área de Texto';
$lang['survey_question_required']                         = 'Necessário';
$lang['survey_question_only_for_preview']                 = 'Apenas para previsualização';
$lang['survey_create_first']                              = 'Você precisa criar uma pesquisa primeiro então você vai poder inserir questões.';
$lang['survey_dt_name']                                   = 'Nome';
$lang['survey_dt_total_questions']                        = 'Total de Questões';
$lang['survey_dt_total_participants']                     = 'Total de Participantes';
$lang['survey_dt_date_created']                           = 'Data de Criação';
$lang['survey_dt_active']                                 = 'Ativo';
$lang['survey_text_questions_results']                    = 'Resultado das Perguntas de Texto';
$lang['survey_view_all_answers']                          = 'Visualizar outras Respostas';
$lang['settings_survey_send_emails_per_cron_run']         = 'Quantos e-mails enviar por hora';
$lang['settings_survey_send_emails_per_cron_run_tooltip'] = 'Esta opção é usada quando a pesquisa é emviada. A Pesquisa cron é realizada a cada 5 minutos. Então você pode definir quantos e-mails serão enviados a cada 5 minutos';
$lang['settings_cron_surveys']                            = 'Pesquisas';
$lang['survey_no_questions']                              = 'Esta pesquisa não tem perguntas acrescentadas ainda.';
$lang['survey_send_to_lists']                             = 'Listas de envio de pesquisa';
$lang['survey_send_notice']                               = 'Os e-mails serão enviados através do CRON JOB por hora.';
$lang['survey_customers_all']                             = 'Todos os clientes';
$lang['mail_lists']                                       = 'Listas de Envio';
$lang['mail_list']                                        = 'Lista de Envio';
$lang['new_mail_list']                                    = 'Nova Lista de Envio';
$lang['mail_list_lowercase']                              = 'lista de envio';
$lang['custom_field_deleted_success']                     = 'Campo Personalizado deletado';
$lang['custom_field_deleted_fail']                        = 'Problema ao apagar o campo personalizado';
$lang['email_removed_from_list']                          = 'E-mail removido da lista';
$lang['email_remove_fail']                                = 'Failed to remove email from list';
$lang['staff_mail_lists']                                 = 'Lista de Envio do Staff';
$lang['clients_mail_lists']                               = 'Lista de Envio dos Clientes';
$lang['mail_list_total_imported']                         = 'Total de e-mails importados: %s';
$lang['mail_list_total_duplicate']                        = 'Total de e-amils duplicados: %s';
$lang['mail_list_total_failed_to_insert']                 = 'Erro nos e-mails a inserir: %s';
$lang['mail_list_total_invalid']                          = 'Endereço de e-mail inválido: %s';
$lang['cant_edit_mail_list']                              = 'Você pode editar essa lista, essa lista é preenchida automaticamente';
$lang['mail_list_add_edit_name']                          = 'Nome da Lista de Envio';
$lang['mail_list_add_edit_customfield']                   = 'Adicionar campo personalizado';
$lang['mail_lists_view_email_email_heading']              = 'E-mail';
$lang['mail_lists_view_email_date_heading']               = 'Data Adicionada';
$lang['add_new_email_to']                                 = 'Adicionar Novo E-mail a %s';
$lang['import_emails_to']                                 = 'Importar E-mails para %s';
$lang['mail_list_new_email_edit_add_label']               = 'E-mail';
$lang['mail_list_import_file']                            = 'Importar Ficheiro';
$lang['mail_list_available_custom_fields']                = 'Campos Personalizados Disponíveis';
$lang['submit_import_emails']                             = 'Importar E-mails';
$lang['btn_import_emails']                                = 'Importar E-mails (Excel)';
$lang['btn_add_email_to_list']                            = 'Adicionar E-mail a Esta Lista';
$lang['mail_lists_dt_list_name']                          = 'Nome da Lista';
$lang['mail_lists_dt_datecreated']                        = 'Data Criada';
$lang['mail_lists_dt_creator']                            = 'Criador';
$lang['email_added_to_mail_list_successfully']            = 'E-mail adicionado a lista';
$lang['email_is_duplicate_mail_list']                     = 'E-mail já existente nessa lista';
