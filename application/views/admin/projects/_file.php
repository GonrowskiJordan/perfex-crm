<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade _project_file" tabindex="-1" role="dialog" data-toggle="modal">
   <div class="modal-dialog full-screen-modal" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" onclick="close_modal_manually('._project_file'); return false;">
               <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title"><?= e($file->subject); ?></h4>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6 border-right project_file_area">
                  <?php if ($file->staffid == get_staff_user_id() || staff_can('create', 'projects')) { ?>
                  <?= render_input('file_subject', 'project_discussion_subject', $file->subject, 'text', ['onblur' => 'update_file_data(' . $file->id . ')']); ?>
                  <?= render_textarea('file_description', 'project_discussion_description', $file->description, ['onblur' => 'update_file_data(' . $file->id . ')']); ?>
                  <hr />
                  <?php } else { ?>
                  <?php if (! empty($file->description)) { ?>
                  <p class="bold">
                     <?= _l('project_discussion_description'); ?>
                  </p>
                  <div class="tw-text-neutral-500">
                     <?= process_text_content_for_display($file->description); ?>
                  </div>
                  <hr />
                  <?php } ?>
                  <?php } ?>
                  <?php if (! empty($file->external) && $file->external == 'dropbox') { ?>
                  <a href="<?= e($file->external_link); ?>"
                     target="_blank" class="btn btn-primary mbot20">
                     <i class="fa fa-dropbox" aria-hidden="true"></i>
                     <?= _l('open_in_dropbox'); ?>
                  </a>
                  <br />
                  <?php } elseif (! empty($file->external) && $file->external == 'gdrive') { ?>
                  <a href="<?= e($file->external_link); ?>"
                     target="_blank" class="btn btn-primary mbot20">
                     <i class="fa-brands fa-google" aria-hidden="true"></i>
                     <?= _l('open_in_google'); ?>
                  </a>
                  <br />
                  <?php } ?>
                  <?php $path    = PROJECT_ATTACHMENTS_FOLDER . $file->project_id . '/' . $file->file_name; ?>
                  <?php $fileUrl = base_url('uploads/projects/' . $file->project_id . '/' . $file->file_name); ?>
                  <a href="<?= e($fileUrl); ?>" target="_blank"
                     class="btn btn-primary mbot20"
                     download="<?= e($file->original_file_name); ?>">
                     <i class="fa fa-file-download" aria-hidden="true"></i>
                     <?= _l('download'); ?>
                  </a>
                  <br />
                  <?php if (is_image($path)) { ?>
                  <img src="<?= e($fileUrl); ?>"
                     class="img img-responsive">
                  <?php } elseif (! empty($file->external) && ! empty($file->thumbnail_link) && $file->external == 'dropbox') { ?>
                  <img
                     src="<?= optimize_dropbox_thumbnail($file->thumbnail_link); ?>"
                     class="img img-responsive">
                  <?php
                  } elseif (strpos($file->filetype, 'pdf') !== false && empty($file->external)) { ?>
                  <iframe src="<?= e($fileUrl); ?>" height="100%"
                     width="100%" frameborder="0"></iframe>
                  <?php
                  } elseif (is_html5_video($path)) { ?>
                  <video width="100%" height="100%"
                     src="<?= site_url('download/preview_video?path=' . protected_file_url_by_path($path) . '&type=' . $file->filetype); ?>"
                     controls>
                     Your browser does not support the video tag.
                  </video>
                  <?php
                  } elseif (is_markdown_file($path) && $previewMarkdown = markdown_parse_preview($path)) {
                      echo $previewMarkdown;
                  } else {
                      if (empty($file->external)) {
                          echo '<a href="' . site_url('uploads/projects/' . $file->project_id . '/' . $file->file_name) . '" download="' . e($file->original_file_name) . '">' . e($file->original_file_name) . '</a>';
                      } else {
                          echo '<a href="' . $file->external_link . '" target="_blank">' . e($file->original_file_name) . '</a>';
                      }
                      echo '<p class="text-muted">' . _l('no_preview_available_for_file') . '</p>';
                  } ?>
               </div>
               <div class="col-md-6 project_file_discusssions_area">
                  <div id="project-file-discussion" class="tc-content"></div>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default"
               onclick="close_modal_manually('._project_file'); return false;"><?= _l('close'); ?></button>
         </div>
      </div>
      <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php $discussion_lang = get_project_discussions_language_array(); ?>
<script>
   var discussion_id = '<?= e($file->id); ?>';
   var discussion_user_profile_image_url =
      '<?= e($discussion_user_profile_image_url); ?>';
   var current_user_is_admin = '<?= is_admin(); ?>';
   $('body').find('._project_file').modal({
      show: true,
      backdrop: 'static',
      keyboard: false
   });
</script>