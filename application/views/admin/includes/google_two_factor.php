<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="google_auth">
    <div class="row mbot25">
        <div class="col-sm-12">
            <?= _l('google_2fa_scan_qr_guide') ?>
        </div>
        <div class="col-md-8 col-md-offset-2 google_auth_qr">
            <figure class="text-center tw-mt-8">
                <img src="data:image/png;base64, <?= e($qrURL); ?>" alt="QR Image">
                <figcaption>
                    <strong class="bold"><?= e($secret); ?></strong>
                    <br />
                    <small
                        class="text-muted"><?= _l('google_2fa_manul_input_secret') ?></small>
                </figcaption>
            </figure>
        </div>
    </div>
    <div class="col-12">
        <?= form_hidden('secret', $secret); ?>
        <br />
    </div>
</div>
<div class="form-group">
    <label for="google_auth_code"
        class="control-label"><?= _l('google_authentication_code'); ?></label>
    <div class="input-group">
        <input type="number" id="google_auth_code" name="google_auth_code" class="form-control" required>
        <div class="input-group-btn">
            <button class="btn btn-block btn-success" type="button"
                onclick="verify_g2fa()"><?= _l('verify') ?></button>
        </div>
    </div>
</div>
</div>
<script>
    function verify_g2fa() {
        var code = $('#google_auth_code').val();
        var secret = $('input[name="secret"]').val();

        $('#submit_2fa').prop("disabled", true);
        $.post(admin_url + '/staff/verify_google_two_factor', {
            code: code,
            secret: secret,
        }, function(response) {
            if (response.status == 'success') {
                $('#submit_2fa').prop("disabled", false);
                alert_float('success', response.message);
            } else {
                $('#submit_2fa').prop("disabled", true);
                alert_float('danger', response.message);
            }
        });
    }
</script>