<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-md-12">
        <?= form_open_multipart('clients/company', ['id' => 'company-profile-form']); ?>
        <!-- Required hidden field -->
        <?= form_hidden('company_form', true); ?>
        <h4 class="tw-mt-0 tw-font-bold tw-text-lg tw-text-neutral-700 section-text section-heading-company-profile">
            <?= _l('clients_profile_heading'); ?>
        </h4>
        <div class="panel_s">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group company-profile-company-group">
                            <label for="company"
                                class="control-label"><?= _l('clients_company'); ?></label>
                            <?php
                        $company_val = $client->company;
if (! empty($company_val)) {
    // Check if is realy empty client company so we can set this field to empty
    // The query where fetch the client auto populate firstname and lastname if company is empty
    if (is_empty_customer_company($client->userid)) {
        $company_val = '';
    }
}
?>
                            <input type="text" class="form-control" name="company"
                                value="<?= set_value('company', $company_val); ?>">
                            <?= form_error('company'); ?>
                        </div>
                        <?php if (get_option('company_requires_vat_number_field') == 1) { ?>
                        <div class="form-group company-profile-vat-group">
                            <label for="vat"
                                class="control-label"><?= _l('clients_vat'); ?></label>
                            <input type="text" class="form-control" name="vat"
                                value="<?= e($client->vat); ?>">
                        </div>
                        <?php } ?>
                        <div class="form-group company-profile-phone-group">
                            <label
                                for="phonenumber"><?= _l('clients_phone'); ?></label>
                            <input type="text" class="form-control" name="phonenumber" id="phonenumber"
                                value="<?= e($client->phonenumber); ?>">
                        </div>
                        <div class="form-group company-profile-website-group">
                            <label class="control-label"
                                for="website"><?= _l('client_website'); ?></label>
                            <input type="text" class="form-control" name="website" id="website"
                                value="<?= e($client->website); ?>">
                        </div>
                        <div class="form-group company-profile-country-group">
                            <label
                                for="lastname"><?= _l('clients_country'); ?></label>
                            <select
                                data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>"
                                data-live-search="true" name="country" class="form-control" id="country">
                                <option value=""></option>
                                <?php foreach (get_all_countries() as $country) { ?>
                                <?php
        $selected = '';
                                    if ($client->country == $country['country_id']) {
                                        echo $selected = true;
                                    }
                                    ?>
                                <option
                                    value="<?= e($country['country_id']); ?>"
                                    <?= set_select('country', $country['country_id'], $selected); ?>>
                                    <?= e($country['short_name']); ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group company-profile-city-group">
                            <label
                                for="city"><?= _l('clients_city'); ?></label>
                            <input type="text" class="form-control" name="city" id="city"
                                value="<?= e($client->city); ?>">
                        </div>
                        <div class="form-group company-profile-address-group">
                            <label
                                for="address"><?= _l('clients_address'); ?></label>
                            <textarea name="address" id="address" class="form-control"
                                rows="4"><?= clear_textarea_breaks($client->address); ?></textarea>
                        </div>
                        <div class="form-group company-profile-zip-group">
                            <label
                                for="zip"><?= _l('clients_zip'); ?></label>
                            <input type="text" class="form-control" name="zip" id="zip"
                                value="<?= e($client->zip); ?>">
                        </div>
                        <div class="form-group company-profile-state-group">
                            <label
                                for="state"><?= _l('clients_state'); ?></label>
                            <input type="text" class="form-control" name="state" id="state"
                                value="<?= e($client->state); ?>">
                        </div>
                        <?php if (! is_language_disabled()) { ?>
                        <div class="form-group company-profile-language-group">
                            <label for="default_language"
                                class="control-label"><?= _l('localization_default_language'); ?>
                            </label>
                            <select
                                data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>"
                                name="default_language" id="default_language" class="form-control selectpicker">
                                <option value="" <?php if ($client->default_language == '') {
                                    echo 'selected';
                                } ?>><?= _l('system_default_string'); ?>
                                </option>
                                <?php foreach ($this->app->get_available_languages() as $availableLanguage) {
                                    $selected = '';
                                    if ($client->default_language == $availableLanguage) {
                                        $selected = 'selected';
                                    } ?>
                                <option
                                    value="<?= e($availableLanguage); ?>"
                                    <?= e($selected); ?>>
                                    <?= e(ucfirst($availableLanguage)); ?>
                                </option>
                                <?php
                                } ?>
                            </select>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-12 custom-fields">
                        <?= render_custom_fields('customers', $client->userid, ['show_on_client_portal' => 1]); ?>
                    </div>
                    <?php if (get_option('allow_primary_contact_to_view_edit_billing_and_shipping') == 1 && is_primary_contact()) { ?>
                    <div class="col-md-12">
                        <h3 class="company-profile-billing-shipping-heading">
                            <?= _l('billing_shipping'); ?>
                        </h3>
                        <hr class="no-mbot" />
                    </div>
                    <div class="col-md-6">
                        <?php $countries = get_all_countries(); ?>
                        <h4 class="mbot15 mtop20 company-profile-billing-address-heading">
                            <?= _l('billing_address'); ?>
                        </h4>
                        <div class="form-group company-profile-billing-street-group">
                            <label
                                for="billing_street"><?= _l('billing_street'); ?></label>
                            <textarea name="billing_street" id="billing_street" class="form-control"
                                rows="4"><?= clear_textarea_breaks($client->billing_street); ?></textarea>
                        </div>
                        <div class="form-group company-profile-billing-city-group">
                            <label
                                for="billing_city"><?= _l('billing_city'); ?></label>
                            <input type="text" class="form-control" name="billing_city" id="billing_city"
                                value="<?= e($client->billing_city); ?>">
                        </div>
                        <div class="form-group company-profile-billing-state-group">
                            <label
                                for="billing_state"><?= _l('billing_state'); ?></label>
                            <input type="text" class="form-control" name="billing_state" id="billing_state"
                                value="<?= e($client->billing_state); ?>">
                        </div>
                        <div class="form-group company-profile-billing-zip-group">
                            <label
                                for="billing_zip"><?= _l('billing_zip'); ?></label>
                            <input type="text" class="form-control" name="billing_zip" id="billing_zip"
                                value="<?= e($client->billing_zip); ?>">
                        </div>
                        <div class="form-group company-profile-billing-country-group">
                            <label
                                for="billing_country"><?= _l('billing_country'); ?></label>
                            <select
                                data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>"
                                name="billing_country" id="billing_country" class="form-control">
                                <option value=""></option>
                                <?php foreach ($countries as $country) { ?>
                                <option
                                    value="<?= e($country['country_id']); ?>"
                                    <?php if ($client->billing_country == $country['country_id']) {
                                        echo ' selected';
                                    } ?>><?= e($country['short_name']); ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h4 class="mbot15 mtop20 company-profile-shipping-address-heading">
                            <?= _l('shipping_address'); ?>
                        </h4>
                        <div class="form-group company-profile-shipping-street-group">
                            <label
                                for="shipping_street"><?= _l('shipping_street'); ?></label>
                            <textarea name="shipping_street" id="shipping_street" class="form-control"
                                rows="4"><?= clear_textarea_breaks($client->shipping_street); ?></textarea>
                        </div>
                        <div class="form-group company-profile-shipping-city-group">
                            <label
                                for="shipping_city"><?= _l('shipping_city'); ?></label>
                            <input type="text" class="form-control" name="shipping_city" id="shipping_city"
                                value="<?= e($client->shipping_city); ?>">
                        </div>
                        <div class="form-group company-profile-shipping-state-group">
                            <label
                                for="shipping_state"><?= _l('shipping_state'); ?></label>
                            <input type="text" class="form-control" name="shipping_state" id="shipping_state"
                                value="<?= e($client->shipping_state); ?>">
                        </div>
                        <div class="form-group company-profile-shipping-zip-group">
                            <label
                                for="shipping_zip"><?= _l('shipping_zip'); ?></label>
                            <input type="text" class="form-control" name="shipping_zip" id="shipping_zip"
                                value="<?= e($client->shipping_zip); ?>">
                        </div>
                        <div class="form-group company-profile-shipping-country-group">
                            <label
                                for="shipping_country"><?= _l('shipping_country'); ?></label>
                            <select
                                data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>"
                                name="shipping_country" id="shipping_country" class="form-control">
                                <option value=""></option>
                                <?php foreach ($countries as $country) { ?>
                                <option
                                    value="<?= e($country['country_id']); ?>"
                                    <?php if ($client->shipping_country == $country['country_id']) {
                                        echo ' selected';
                                    } ?>><?= e($country['short_name']); ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php if ($contact->is_primary == 1) { ?>
            <div class="panel-footer text-right company-profile-save-section">
                <button type="submit" class="btn btn-primary company-profile-save">
                    <?= _l('clients_edit_profile_update_btn'); ?>
                </button>
            </div>
            <?php } ?>
        </div>
        <?= form_close(); ?>
    </div>
</div>