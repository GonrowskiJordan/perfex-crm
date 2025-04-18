<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade _event" id="viewEvent">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?= e($event->title); ?></h4>
      </div>
      <?= form_open('admin/utilities/calendar', ['id' => 'calendar-event-form']); ?>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <?php if ($event->userid != get_staff_user_id()) { ?>
            <div class="alert alert-info">
              <?= _l('event_created_by', '<a href="' . admin_url('profile/' . $event->userid) . '" target="_blank" class="alert-link">' . e(get_staff_full_name($event->userid))) . '</a>'; ?>
            </div>
            <?php } ?>
            <?php if ($event->userid == get_staff_user_id() || is_admin()) { ?>
            <?= form_hidden('eventid', $event->eventid); ?>
            <?= render_input('title', 'utility_calendar_new_event_placeholder', $event->title); ?>
            <?= render_textarea('description', 'event_description', $event->description, ['rows' => 5]); ?>
            <?= render_datetime_input('start', 'utility_calendar_new_event_start_date', _dt($event->start), ['data-step' => 30]); ?>
            <div class="clearfix mtop15"></div>
            <?= render_datetime_input('end', 'utility_calendar_new_event_end_date', _dt($event->end), ['data-step' => 30]); ?>
            <?php if (is_email_template_active('event-notification-to-staff')) { ?>
            <div class="form-group">
              <div class="row">
                <div class="col-md-12">
                  <label
                    for="reminder_before"><?= _l('event_notification'); ?></label>
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <input type="number" class="form-control" name="reminder_before"
                      value="<?= e($event->reminder_before); ?>"
                      id="reminder_before">
                    <span class="input-group-addon"><i class="fa-regular fa-circle-question" data-toggle="tooltip"
                        data-title="<?= _l('reminder_notification_placeholder'); ?>"></i></span>
                  </div>
                </div>
                <div class="col-md-6">
                  <select name="reminder_before_type" id="reminder_before_type" class="selectpicker" data-width="100%">
                    <option value="minutes" <?php if ($event->reminder_before_type == 'minutes') {
                        echo ' selected';
                    } ?>><?= _l('minutes'); ?></option>
                    <option value="hours" <?php if ($event->reminder_before_type == 'hours') {
                        echo ' selected';
                    } ?>><?= _l('hours'); ?></option>
                    <option value="days" <?php if ($event->reminder_before_type == 'days') {
                        echo ' selected';
                    } ?>><?= _l('days'); ?></option>
                    <option value="weeks" <?php if ($event->reminder_before_type == 'weeks') {
                        echo ' selected';
                    } ?>><?= _l('weeks'); ?></option>
                  </select>
                </div>
              </div>
            </div>
            <?php } ?>
            <hr />
            <p class="bold">
              <?= _l('event_color'); ?>
            </p>
            <?php
                               $event_colors = '';
                $favourite_colors            = get_system_favourite_colors();
                $i                           = 0;

                foreach ($favourite_colors as $color) {
                    $color_selected_class = 'cpicker-small';
                    if ($color == $event->color) {
                        $color_selected_class = 'cpicker-big';
                    }
                    $event_colors .= "<div class='calendar-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ';border:1px solid ' . $color . "'></div>";
                    $i++;
                }
                echo '<div class="cpicker-wrapper">';
                echo $event_colors;
                echo '</div>';
                echo form_hidden('color', $event->color);
                ?>

            <div class="clearfix"></div>
            <hr />
            <div class="checkbox checkbox-primary">
              <input type="checkbox" name="public" id="event_public" <?php if ($event->public == 1) {
                  echo 'checked';
              } ?>>
              <label
                for="event_public"><?= _l('utility_calendar_new_event_make_public'); ?></label>
            </div>
            <?php } else { ?>
            <a
              href="<?= admin_url('profile/' . $event->userid); ?>"><?= staff_profile_image($event->userid, ['staff-profile-xs-image']); ?>
              <?= e(get_staff_full_name($event->userid)); ?></a>
            <hr />
            <h5 class="bold">
              <?= _l('event_description'); ?>
            </h5>
            <?= process_text_content_for_display($event->description); ?>
            <h5 class="bold">
              <?= _l('utility_calendar_new_event_start_date'); ?>
            </h5>
            <p><?= e($event->start); ?></p>
            <?php if (is_date($event->end)) { ?>
            <h5 class="bold">
              <?= _l('utility_calendar_new_event_end_date'); ?>
            </h5>
            <p><?= e($event->end); ?></p>
            <?php } ?>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default"
          data-dismiss="modal"><?= _l('close'); ?></button>
        <?php if ($event->userid == get_staff_user_id() || is_admin()) { ?>
        <button type="button" class="btn btn-danger"
          onclick="delete_event(<?= e($event->eventid); ?>); return false"><?= _l('delete_event'); ?></button>
        <button type="submit"
          class="btn btn-primary"><?= _l('submit'); ?></button>
        <?php } ?>
      </div>
      <?= form_close(); ?>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->