<?php
if (!empty($company_info)) {
    $id = $company_info->id;
    $frequency = $company_info->frequency;
} else {
    $id = null;
    $frequency = null;
}
echo form_open(base_url('saas/companies/save_companies/' . $id), array('id' => 'new_company_form', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form'));
$done_server_settings = get_option('done_server_settings');
if (empty($done_server_settings)) {
    ?>

    <div class="alert alert-danger">
        <h4><strong>Need to Configuring your Server! </strong></h4>
        <p>
            I have seen you did not configure your server yet.
            click <a href="<?= base_url('saas/settings/index/server_settings') ?>">here to configure your server.</a>
            if you any help Please follow the instructions below to configure your server
            properly in <a href="https://docs.coderitems.com/perfectsaas/#configure_server"
                           target="_blank"> how to configure server</a>
        </p>
        <hr/>
        <a href="<?php echo saas_url('companies/dismiss_server_settings_notice'); ?>" class="alert-link">I have done it!
            Don't
            show this message again</a>
    </div>
<?php } ?>

<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
    <?php echo $title; ?>
    <button type="submit" id="btn_companies"
            class="btn btn-sm btn-primary mt-sm pull-right row"><?= _l('save_changes') ?></button>
</h4>
<div class="panel_s">
    <div class="panel-body">
        <div class="row mb-lg ">

            <div class="col-lg-7 col-md-7 ">
                <div class="">
                    <div class="form-group">
                        <label for="field-1" class=" control-label"><?= _l('account') ?>
                            <span class="text-danger">*</span></label>

                        <div class="">
                            <input name="domain" required class="form-control main_domain" id="domain"
                                   value="<?= (!empty($company_info) ? $company_info->domain : '') ?>"
                                   placeholder="<?= _l('choose_a_domain') ?> *"
                                   autocomplete="off"
                                   type="text">
                            <small class="new_error text-danger" id="domain_error"></small>
                            <span class="help-block domain_showed"
                                  style="display: none"><?= _l('your_app_URL_will_be') ?> <strong
                                        id="sub_domain" class=""></strong></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="discount_type"
                               class="control-label"><?= _l('select') . ' ' . _l('package') ?>
                            <span class="text-danger">*</span></label>
                        <div class="">
                            <select name="package_id" onchange="get_package_info(this.value)"
                                    class="selectpicker m0"
                                    data-width="100%"
                                    required
                                    data-none-selected-text="<?php echo lang('select') . ' ' . _l('package'); ?>"
                                    data-live-search="true">
                                <option value=""></option>
                                <?php
                                $active_frequency = get_active_frequency(true);
                                if (!empty($all_packages)) {
                                    foreach ($all_packages as $v_package) {
                                        $sub_text = '';
                                        if (!empty($active_frequency)) {
                                            foreach ($active_frequency as $name => $v_frequency) {
                                                $sub_text .= $v_frequency . ': ' . display_money($v_package->$name) . ' ';
                                            }
                                        }
                                        $sub_text .= strip_tags(mb_substr(!empty($v_package->description) ? $v_package->description : '', 0, 200)) . '...';
                                        ?>
                                        <option <?php
                                        if (isset($company_info)) {
                                            if ($company_info->package_id == $v_package->id) {
                                                echo 'selected';
                                            }
                                        } ?> value="<?php echo $v_package->id; ?>"
                                             data-subtext="<?php echo $sub_text ?>"><?php echo $v_package->name; ?></option>
                                    <?php } ?>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div id="billing_cycle">

                    </div>
                    <div class="form-group">
                        <label for="field-1" class="control-label"><?= _l('name') ?>
                            <span class="text-danger">*</span></label>

                        <div class="">
                            <input name="name" required class="form-control"
                                   value="<?= (!empty($company_info) ? $company_info->name : '') ?>"
                                   placeholder="<?= _l('name') ?> *"
                                   type="text">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="control-label"><?= _l('email') ?>
                            <span class="text-danger">*</span>
                        </label>

                        <div class="">
                            <input name="email"
                                   value="<?= (!empty($company_info) ? $company_info->email : '') ?>"
                                   id="check_email" class="form-control" required
                                   placeholder="<?= _l('email') ?> *"
                                   autocomplete="off"
                                   type="email">
                            <small class="new_error text-danger" id="email_error"></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-1" class="control-label"><?= _l('password') ?></label>
                        <div class="">
                            <?php
                            if (!empty($company_info)) { ?>
                                <a data-toggle="modal" data-target="#myModal"
                                   href="<?= base_url() ?>saas/companies/reset_password/<?= $company_info->id ?>"
                                   class="btn btn-xs btn-primary"><?= _l('reset_password') ?></a>
                            <?php } else { ?>
                                <div class="input-group">
                                    <input name="password"
                                           autocomplete="off"
                                           value="<?= (!empty($company_info) ? ($company_info->password) : '') ?>"
                                           id="password" class="form-control" required
                                           placeholder="<?= _l('password') ?> *"
                                           type="password">
                                    <div class="input-group-addon">
                                        <a href="javascript:void(0);" id="show_password"><i class="fa fa-eye"></i></a>
                                    </div>
                                </div>
                            <?php }
                            ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="field-1" class="control-label"><?= _l('mobile') ?></label>
                        <div class="">
                            <input name="mobile" class="form-control"
                                   value="<?= (!empty($company_info) ? $company_info->mobile : '') ?>"
                                   placeholder="<?= _l('mobile') ?>"
                                   type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="control-label"><?= _l('address') ?></label>
                        <div class="">
                            <input name="address" class="form-control"
                                   value="<?= (!empty($company_info) ? $company_info->address : '') ?>"
                                   placeholder="<?= _l('address') ?>"
                                   type="text">
                        </div>
                    </div>
                    <?php $countries = get_all_countries();
                    $customer_default_country = get_option('customer_default_country');
                    $selected = (isset($company_info) ? $company_info->country : $customer_default_country);
                    echo render_select('country', $countries, ['country_id', ['short_name']], 'country', $selected, ['data-none-selected-text' => _l('dropdown_non_selected_tex')]);
                    ?>
                    <div class="form-group">
                        <label for="field-1"
                               class="control-label"><?= _l('select_timezone') ?></label>
                        <div class="">
                            <select name="timezone" data-live-search="true" id="timezone" class="selectpicker"
                                    data-width="100%"
                                    required
                                    data-none-selected-text="Select system timezone">
                                <option value=""></option>
                                <?php foreach (app\services\Timezones::get() as $key => $timezones) { ?>
                                    <optgroup label="<?php echo $key; ?>">
                                        <?php foreach ($timezones as $timezone) { ?>
                                            <option value="<?php echo $timezone; ?>"
                                                <?php if (!empty($company_info->timezone) && $timezone == $company_info->timezone) {
                                                    echo 'selected';
                                                } ?>
                                            ><?php echo $timezone; ?></option>
                                        <?php } ?>
                                    </optgroup>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1"
                               class="control-label"><?= _l('select_language') ?></label>
                        <div class="">
                            <select name="language" class="selectpicker" data-width="100%" required
                                    data-none-selected-text="Select language"
                            >
                                <option><?= _l('select_language') ?></option>
                                <?php foreach ($this->app->get_available_languages() as $user_lang) { ?>
                                    <option value="<?= $user_lang ?>" <?= (!empty($company_info->language) && $company_info->language == $user_lang || get_option('saas_active_language') == $user_lang ? 'selected' : NULL) ?>><?= ucfirst($user_lang) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                </div>
            </div>
            <div class="col-lg-5 col-md-5" id="package_info">

            </div>
        </div>
    </div>
</div>
<?php echo form_close();
$default_url = preg_replace('#^https?://#', '', rtrim(companyBaseUrl(), '/'));
?>

<script type="text/javascript">
    'use strict';
    // check package_id is empty or not by name
    $(document).ready(function () {
        $("#new_company_form").appFormValidator();

        var package_id = $('[name="package_id"]').val();
        // if package_id is not empty then trigger onchange event
        if (package_id != '') {
            get_package_info(package_id, '<?= $frequency?>_price', '<?= $id?>');
        }
    });
    // click to show_password with icon
    $(document).on('keyup', '.main_domain', function () {
        var sub_domain = $(this).val();
        // remove space,special character,dot from sub_domain and replace with -
        sub_domain = sub_domain.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9]/g, '-').replace(/\./g, '');
        // subdomain should be lowercase
        sub_domain = sub_domain.toLowerCase();

        var main_domain = "<?= $default_url?>";
        // remove www from main_domain
        main_domain = main_domain.replace('www.', '');
        var http = "<?= (isset($_SERVER['HTTPS']) ? "https://" : "http://")?>";
        $('#sub_domain').html(http + sub_domain + '.' + main_domain);
        var domainDiv = $('.domain_showed');
        if ($(this).val() == "") {
            domainDiv.css("display", "none");
        } else {
            domainDiv.css("display", "block");
        }
        check_already_exists('domain', sub_domain);
    });
    // check_email_exists
    $(document).on('keyup', '#check_email', function () {
        var email = $(this).val();
        check_already_exists('email', email);
    });

    function check_already_exists(type, value) {
        $.ajax({
            type: "POST",
            url: "<?= base_url()?>saas/gb/check_already_exists",
            data: {type, value},
            dataType: "json",
            success: function (data) {
                if (data.status == 'error') {
                    $('#' + type + '_error').html(data.message);
                    $('#btn_companies').attr('disabled', true);
                } else {
                    $('#btn_companies').attr('disabled', false);
                    $('#' + type + '_error').html('');
                }
            }
        });
    }

    $('#show_password').on('click', function () {
        // check the password field type if it is password field then set the attribute to text
        if ($('#password').attr('type') == 'password') {
            $('#password').attr('type', 'text');
            $('#show_password i').removeClass('fa-eye');
            $('#show_password i').addClass('fa-eye-slash');
        } else {
            // if the password field type is text then set the attribute to password
            $('#password').attr('type', 'password');
            $('#show_password i').removeClass('fa-eye-slash');
            $('#show_password i').addClass('fa-eye');
        }
    });

    function get_package_info(package_id, package_type = 'monthly_price', company_id = '<?= $id?>') {
        // check input mark_paid is checked or not
        var mark_paid = $('input[name="mark_paid"]').is(":checked");
        var is_coupon = $('input[name="is_coupon"]').is(":checked");
        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>saas/gb/get_package_info',
            data: {package_id, package_type, company_id},
            dataType: "json",
            success: function (result) {
                $('#billing_cycle').html(result.package_form_group);
                $('#package_info').html(result.package_details);
                if (mark_paid) {
                    $('.mark_as_paid').css('display', 'block');
                    $('input[name="mark_paid"]').prop('checked', true);
                    if (is_coupon) {
                        $('.coupon_code_area').show();
                        $('input[name="is_coupon"]').prop('checked', true);

                        var coupon_code = $('#coupon_code').val();
                        if (coupon_code != '') {
                            var formData = {
                                'coupon_code': $('#coupon_code').val(),
                                'billing_cycle': $('[name="billing_cycle"]').val(),
                                'package_id': $('[name="mark_paid"]').val(),
                                'email': $('#check_email').val(),
                            };
                            $.ajax({
                                type: "post",
                                url: "<?= base_url() ?>saas/gb/check_coupon_code",
                                data: formData, // our data object
                                dataType: 'json', // what type of data do we expect back from the server
                                success: function (data) {
                                    if (data.success == true) {
                                        $('#applied_discount').html(data.applied_discount);
                                        $('#sub_total').val(data.sub_total_input);
                                        $('.sub_total_text').html('<?= _l('sub_total') ?>');
                                        $('#final_amount').html(data.total_amount);
                                    } else {
                                        $('#discount_error').html(data.message);
                                    }
                                }
                            });
                        }
                    }
                } else {
                    $('.mark_as_paid').css('display', 'none');
                }
            }
        });
    }
</script>