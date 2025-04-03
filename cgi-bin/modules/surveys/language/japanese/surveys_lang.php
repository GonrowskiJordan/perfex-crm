<?php

# Version 2.3.0

$lang['survey_submit']                                    = '提出する';
$lang['new_survey']                                       = '新しい調査';
$lang['surveys']                                          = '調査';
$lang['survey']                                           = '調査';
$lang['survey_lowercase']                                 = '調査';
$lang['survey_no_mail_lists_selected']                    = 'メールリストは選択されていません';
$lang['survey_send_success_note']                         = '全ての調査メール (%s) はCRONを通して送られる';
$lang['survey_result']                                    = '調査の結果: %s';
$lang['question_string']                                  = '質問';
$lang['question_field_string']                            = 'フィールド';
$lang['survey_list_view_tooltip']                         = '調査を一覧する';
$lang['survey_list_view_results_tooltip']                 = '結果を一覧する';
$lang['survey_add_edit_subject']                          = '調査を行う';
$lang['survey_add_edit_email_description']                = ' 調査記述（メール記述）';
$lang['survey_include_survey_link']                       = '調査リンク記述を含む';
$lang['survey_available_mail_lists_custom_fields']        = 'メールリストからのカスタムフィールドを利用できます';
$lang['survey_mail_lists_custom_fields_tooltip']          = 'カスタムフィールドはメール編集者から使用可能です';
$lang['survey_add_edit_short_description_view']           = '調査短記述 (説明を一覧せよ)';
$lang['survey_add_edit_from']                             = '～から（メールに表示されている）';
$lang['survey_add_edit_redirect_url']                     = '調査をURLに変える';
$lang['survey_add_edit_red_url_note']                     = '利用者が調査を終えた時どこへ転換するか（このurlサイトに空白を残すこと）';
$lang['survey_add_edit_disabled']                         = '機能不可';
$lang['survey_add_edit_only_for_logged_in']               = 'ログインした参加者のみ（スタッフ、顧客）';
$lang['send_survey_string']                               = ' 調査を送る';
$lang['survey_send_mail_list_clients']                    = '顧客';
$lang['survey_send_mail_list_staff']                      = 'スタッフ';
$lang['survey_send_mail_lists_string']                    = 'メールリスト';
$lang['survey_send_mail_lists_note_logged_in']            = 'NB: メールリストの中のログインした参加者のみに調査を送っている場合は、チェックを入れないでおく必要があります';
$lang['survey_send_string']                               = '送る';
$lang['survey_send_to_total']                             = '全ての %s メールを送信する';
$lang['survey_send_till_now']                             = '今まで';
$lang['survey_send_finished']                             = '調査送信完了: %s';
$lang['survey_added_to_queue']                            = 'この調査は %s のcron queueに加わっている';
$lang['survey_questions_string']                          = '質問';
$lang['survey_insert_field']                              = 'フィールドを加える';
$lang['survey_field_checkbox']                            = 'チェックボックス';
$lang['survey_field_radio']                               = 'ラジオ';
$lang['survey_field_input']                               = 'フィールドをインプットする';
$lang['survey_field_textarea']                            = 'テキスト領域';
$lang['survey_question_required']                         = '必須の';
$lang['survey_question_only_for_preview']                 = 'プレビューのみ';
$lang['survey_create_first']                              = 'まず調査を作成してから質問を加える事ができます';
$lang['survey_dt_name']                                   = '名前';
$lang['survey_dt_total_questions']                        = '総質問';
$lang['survey_dt_total_participants']                     = '合計参加者';
$lang['survey_dt_date_created']                           = '日付作成';
$lang['survey_dt_active']                                 = 'アクティブな';
$lang['survey_text_questions_results']                    = '本文質問の結果';
$lang['survey_view_all_answers']                          = '全ての回答を見る';
$lang['settings_survey_send_emails_per_cron_run']         = 'どのくらいのメールを毎時送信するか';
$lang['settings_survey_send_emails_per_cron_run_tooltip'] = 'このオプションは調査を送る時に使われる。この調査クローンは毎時Xメールとして送信される。いくつかのホスティングプロバイダーは毎時送信するメールに制限があります。';
$lang['settings_cron_surveys']                            = '調査';
$lang['survey_no_questions']                              = 'この調査は質問をまだ追加していません';
$lang['survey_send_to_lists']                             = '調査を送るリスト';
$lang['survey_send_notice']                               = '毎時 CRON JOB としてメールは送られる';
$lang['survey_customers_all']                             = '全ての顧客';
$lang['mail_lists']                                       = 'メールリスト';
$lang['mail_list']                                        = 'メールリスト';
$lang['new_mail_list']                                    = '新しいメールリスト';
$lang['mail_list_lowercase']                              = 'メールリスト';
$lang['custom_field_deleted_success']                     = '税関のフィールドの削除';
$lang['custom_field_deleted_fail']                        = '税関のフィールドの問題削除';
$lang['email_removed_from_list']                          = 'リストからメール削除';
$lang['email_remove_fail']                                = 'Failed to remove email from list';
$lang['staff_mail_lists']                                 = 'スタッフのメールリスト';
$lang['clients_mail_lists']                               = 'クライアントのメールリスト';
$lang['mail_list_total_imported']                         = 'インポートされた全てのメール: %s';
$lang['mail_list_total_duplicate']                        = '総重複メール: %s';
$lang['mail_list_total_failed_to_insert']                 = '書き込みに失敗したメール: %s';
$lang['mail_list_total_invalid']                          = '無効なメールアドレス: %s';
$lang['cant_edit_mail_list']                              = 'このリストは自動的に変化するため、リストの編集はできません';
$lang['mail_list_add_edit_name']                          = 'メールリスト名';
$lang['mail_list_add_edit_customfield']                   = '税関のフィールド追加';
$lang['mail_lists_view_email_email_heading']              = 'メール';
$lang['mail_lists_view_email_date_heading']               = '日付追加';
$lang['add_new_email_to']                                 = '%s に新しいメールの追加';
$lang['import_emails_to']                                 = '%s にメールをインポートする';
$lang['mail_list_new_email_edit_add_label']               = 'メール';
$lang['mail_list_import_file']                            = 'ファイルをインポートする';
$lang['mail_list_available_custom_fields']                = '有効な税関のフィールド';
$lang['submit_import_emails']                             = 'メールをインポートする';
$lang['btn_import_emails']                                = 'メールをインポートする (Excel)';
$lang['btn_add_email_to_list']                            = 'このリストにメールを追加';
$lang['mail_lists_dt_list_name']                          = 'ネームリスト';
$lang['mail_lists_dt_datecreated']                        = '日付作成';
$lang['mail_lists_dt_creator']                            = 'クリエイター';
$lang['email_added_to_mail_list_successfully']            = 'リストにメールを追加';
$lang['email_is_duplicate_mail_list']                     = 'リストに既に存在するメール';
