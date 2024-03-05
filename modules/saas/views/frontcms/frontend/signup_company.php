<?php
echo form_open(base_url('signed_up'), array('id' => 'contact-form', 'autocomplete' => "off", 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form', 'method' => 'post', 'accept-charset' => 'utf-8'));

?>
<div class="card panel-custom">
    <div class="card-header">
        <span class="panel-title"><?php echo
            _l('signup'); ?> -
            <span class="package-name"><?php echo $package->name; ?></span>
        </span>
        <button type="button" class="btn btn-icon btn-close float-end" data-bs-dismiss="modal" id="close-modal"
                aria-label="Close">
            <i class="uil uil-times fs-4 text-dark"></i></button>
    </div>
    <div class="card-body">
        <div class="row mb-lg form-horizontal">
            <div class="column col-md-6 col-sm-6 col-xs-12 ">

                <div class="form-group mb-3">
                    <input name="domain" required class="form-control main_domain" id="domain"
                           autocomplete="nope"
                           aria-autocomplete="none"
                           value="<?= (!empty($company_info) ? $company_info->domain : '') ?>"
                           placeholder="<?= _l('choose_a_domain') ?> *"
                           type="text">
                    <small class="new_error text-danger" id="domain_error"></small>
                    <?php
                    if (get_option('saas_server_wildcard') == 'on') {
                        ?>
                        <span class="help-block domain_showed text-danger"
                              style="display: none"><?= _l('your_app_URL_will_be') ?> <strong
                                    id="sub_domain" class=""></strong></span>
                        <?php
                    }
                    ?>
                    <span class="text-danger">
                        <?= form_error('domain'); ?>
                    </span>

                </div>
                <div class="form-group mb-3">
                    <select name="package_id" onchange="get_package_info(this.value)" class="form-control"
                            style="width: 100%">
                        <option value=""><?= _l('select') . ' ' . _l('package') ?></option>
                        <?php
                        $all_packages = $this->saas_model->get_packages();
                        if (!empty($all_packages)) {
                            foreach ($all_packages as $v_package) {
                                ?>
                                <option <?php
                                if (isset($package_id)) {
                                    if ($package_id == $v_package->id) {
                                        echo 'selected';
                                    }
                                } ?> value="<?php echo $v_package->id; ?>"><?php echo $v_package->name; ?></option>
                            <?php } ?>
                            <?php
                        }
                        ?>
                    </select>
                    <span class="text-danger">
                        <?= form_error('package_id'); ?>
                    </span>
                </div>
                <div id="billing_cycle">

                </div>
                <div class="form-group mb-3 mt-3">

                    <input name="name" required class="form-control"
                           value="<?= (!empty($company_info) ? $company_info->name : '') ?>"
                           placeholder="<?= _l('name') ?> *"
                           type="text">
                    <span class="text-danger">
                        <?= form_error('name'); ?>
                    </span>

                </div>

                <div class="form-group mb-3">
                    <input name="email"
                           value="<?= (!empty($company_info) ? $company_info->email : '') ?>"
                           id="check_email" class="form-control" required
                           placeholder="<?= _l('email') ?> *"
                           type="email">
                    <small class="new_error text-danger" id="email_error"></small>
                    <span class="text-danger">
                        <?= form_error('email'); ?>
                    </span>
                </div>


                <div class="form-group mb-3">

                    <input name="mobile" class="form-control"
                           value="<?= (!empty($company_info) ? $company_info->mobile : '') ?>"
                           placeholder="<?= _l('mobile') ?>"
                           type="text">
                </div>
                <div class="form-group mb-3">
                    <input name="address" class="form-control"
                           value="<?= (!empty($company_info) ? $company_info->address : '') ?>"
                           placeholder="<?= _l('address') ?>"
                           type="text">
                </div>
                <div class="form-group mb-3">

                    <select name="country" class="form-control select_box"
                            style="width: 100%">
                        <?php
                        $countries = get_all_countries();
                        if (!empty($countries)): foreach ($countries as $key => $country): ?>
                            <option
                                    value="<?= $country['country_id'] ?>" <?= (!empty($company_info->country) && $company_info->country == $country['short_name'] || get_option('company_country') == $country['short_name'] ? 'selected' : NULL) ?>><?= $country['short_name'] ?>
                            </option>
                        <?php
                        endforeach;
                        endif;
                        ?>
                    </select>
                </div>
                <div class="d-flex row mb-0 mt-4" style="margin-bottom: 0!important;">
                    <div class="col-md-4 justify-content-start">
                        <button type="submit" id="btn_companies"
                                class="btn btn-primary pricing-btn secondary-btn"><?= _l('register') ?>
                        </button>
                    </div>
                    <div class="col-md-8 justify-content-end">
                        <p class="pt-lg text-end"><?= _l('already_have_an_account') ?>
                            <a href="<?= base_url('company/login') ?>"
                               class="me-2 btn btn-secondary"><?= _l('sign_in') ?></a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-5 col-md-5" id="package_info">

            </div>
        </div>
    </div>
</div>
<?php
if (empty($register)) {
    ?>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('close') ?></button>
    </div>
    <?php
}
echo form_close();
$default_url = preg_replace('#^https?://#', '', rtrim(companyBaseUrl(), '/'));
?>
<script type="text/javascript">
    'use strict';
    // click to show_password with icon
    // document.on keyup
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
        const url = http + sub_domain + '.' + main_domain;
        $('#sub_domain').html(url);
        var domainDiv = $('.domain_showed');
        if ($(this).val() === "") {
            // remove style display none
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

    // check package_id is empty or not by name
    $(document).ready(function () {
        var package_id = '<?php echo $package_id; ?>';
        // if package_id is not empty then trigger onchange event
        if (package_id != '') {
            get_package_info(package_id, 'monthly_price');
        }
    });

    function get_package_info(package_id, package_type = 'monthly_price', company_id = '') {
        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>saas/gb/get_package_info',
            data: {package_id, package_type, company_id, front: true},
            dataType: "json",
            success: function (result) {
                $('.package-name').html(result.package_info.name);
                $('#billing_cycle').html(result.package_form_group);
                $('#package_info').html(result.package_details);
            }
        });
    }
</script>