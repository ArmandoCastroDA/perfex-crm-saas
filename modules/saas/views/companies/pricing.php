<div class=" clearfix">
    <div class="column col-md-7 col-sm-6 col-xs-12">
        <div class="panel panel-custom">
            <div class="panel-heading">
                <?= _l('sign_up') . ' ' . _l('for') . ' <span id="package_name">' ?></span></strong>
            </div>
            <div class="panel-body">
                <div class="contact-form default-form">

                    <div class="">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <input name="domain" required class="form-control main_domain" min="3" id="domain"
                                       value=""
                                       placeholder="<?= _l('choose_a_domain') ?> *" type="text">
                                <small class="new_error" id="new_error"></small>
                                <span class="help-block domain_showed"
                                      style="display: none"><?= _l('your_app_URL_will_be') ?> <strong
                                            id="sub_domain" class=""></strong></span>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <input name="name" required class="form-control" value=""
                                       placeholder="<?= _l('name') ?> *"
                                       type="text">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <select name="country" class="form-control select_box"
                                        data-width="100%">
                                    <option value=""><?= _l('select_package') ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <input name="website" required class="form-control" value=""
                                       placeholder="<?= _l('website') ?> *"
                                       type="text">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <input name="email" id="check_email" class="form-control" required
                                       placeholder="<?= _l('email') ?> *"
                                       type="email">
                                <small class="new_error" id="email_error"></small>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <input name="password" required class="form-control"
                                       placeholder="<?= _l('password') ?> *"
                                       type="password">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <input name="mobile" value="" class="form-control" required
                                       placeholder="<?= _l('mobile') ?> *"
                                       type="text">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <select name="country" class="form-control select_box"
                                        data-width="100%">
                                    <optgroup label="Default Country">
                                        <option
                                                value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                                    </optgroup>
                                    <optgroup label="<?= _l('other_countries') ?>">
                                        <?php if (!empty($countries)): foreach ($countries as $country): ?>
                                            <option
                                                    value="<?= $country->value ?>" <?= (!empty($company_info->country) && $company_info->country == $country->value ? 'selected' : NULL) ?>><?= $country->value ?>
                                            </option>
                                        <?php
                                        endforeach;
                                        endif;
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <select name="logo_or_icon" class="form-control">
                                <option><?= _l('select_currency') ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <select name="logo_or_icon" class="form-control">
                                <option><?= _l('select_timezone') ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <select name="logo_or_icon" class="form-control">
                                <option><?= _l('select_language') ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">

                            <div class="checkbox-inline c-checkbox">
                                <label>
                                    <input
                                        <?= (!empty($package_info->status) && $package_info->status == 'published' || empty($package_info) ? 'checked' : ''); ?>
                                            class="select_one" type="checkbox" name="status" value="published">
                                    <span class="fa fa-check"></span> <?= _l('published') ?>
                                </label>
                            </div>

                            <div class="checkbox-inline c-checkbox">
                                <label>
                                    <input
                                        <?= (!empty($package_info->status) && $package_info->status == 'unpublished' ? 'checked' : ''); ?>
                                            class="select_one" type="checkbox" name="status"
                                            value="unpublished">
                                    <span class="fa fa-check"></span> <?= _l('unpublished') ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail w-210">
                                    <?php if (config_item('company_logo') != '') : ?>
                                        <img src="<?php echo base_url() . config_item('company_logo'); ?>">
                                    <?php else: ?>
                                        <img src="<?= base_url('uploads/default_avatar.jpg') ?>"
                                             alt="Please Connect Your Internet">
                                    <?php endif; ?>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail w-210"></div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">
                                            <input type="file" name="company_logo" value="upload"
                                                   data-buttonText="<?= _l('choose_file') ?>" id="myImg"/>
                                            <span class="fileinput-exists"><?= _l('change') ?></span>
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists"
                                           data-dismiss="fileinput"><?= _l('remove') ?></a>

                                </div>

                                <div id="valid_msg" style="color: #e11221"></div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <div class="pull-left">
                                <button type="submit" id="new_company"
                                        class="btn btn-blue"><?= _l('sign_up') ?></button>
                            </div>
                            <div class="pull-right">
                                <p class="pt-lg tw-text-black"><?= _l('already_have_an_account') ?>
                                    <a href="<?= base_url() ?>login"
                                       class="btn btn-blue"><?= _l('sign_in') ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo form_close(); ?>
    <div id="package_details">
        <?php $this->load->view('saas/package_details', array('plan_info' => $plan_info)) ?>
    </div>
</div>

<?php
$default_url = preg_replace('#^https?://#', '', rtrim(companyBaseUrl(), '/'));
?>
<script type="text/javascript">
    'use strict';
    $(document).ready(function () {
        $(".set_price").on('click', function () {
            var result = $(this).attr('result');
            if (result == 'by_currency') {
                $('.currencywise_price').removeClass('custom-bg active');
                $('.currencywise_price').addClass('btn-primary');
            }
            if (result == 'by_type') {
                $('.by_type').removeClass('custom-bg active');
                $('.by_type').addClass('btn-primary');
            }
            $(this).removeClass('btn-primary');
            $(this).addClass('custom-bg active');

            var interval_type = $('.interval_type >  .active').attr('type');
            var currencywise_price = $('.currency_type >  .active').attr('currency');

            $('#new_interval_type').val(interval_type);
            $('#new_currency_type').val(currencywise_price);

            var formData = {
                'interval_type': interval_type,
                'currencywise_price': currencywise_price,
            };
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= base_url()?>frontend/get_currencywise_price/', // the url where we want to POST
                data: formData, // our data object
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res) {
                    if (res) {
                        $('select[name="pricing_id"]').find('option').remove();
                        $.ajax({
                            url: "<?= base_url()?>admin/global_controller/get_package_details/" + res[0].frontend_pricing_id,
                            type: "GET",
                            dataType: 'json',
                            success: function (result) {
                                document.getElementById('package_name').innerHTML = result.package_name;
                                document.getElementById('package_details').innerHTML = result.package_details;
                            }
                        });
                        $.each(res, function (index, item) {
                            if (interval_type == 'annually') {
                                var amount = item.yearly + ' /' + '<?= _l('yr')?>';
                            } else {
                                var amount = item.monthly + ' /' + '<?= _l('mo')?>';
                            }
                            $('select[name="pricing_id"]').append('<option value="' + item.frontend_pricing_id + '">' + item.name + ' ' + item.currency + amount + '</option>');
                        });
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });
    })
    $(".main_domain").on('keyup', function () {
        var sub_domain = $(this).val();
        // remove space,special character,dot from sub_domain and replace with -
        sub_domain = sub_domain.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9]/g, '-').replace(/\./g, '');
        // subdomain should be lowercase
        sub_domain = sub_domain.toLowerCase();

        var main_domain = "<?= $default_url?>";
        main_domain = main_domain.replace('www.', '');

        var http = "<?= (isset($_SERVER['HTTPS']) ? "https://" : "http://")?>";
        $('#sub_domain').html(http + sub_domain + '.' + main_domain);
        var domainDiv = $('.domain_showed');
        if ($(this).val() == "") {
            domainDiv.css("display", "none");
        } else {
            domainDiv.css("display", "block");
        }
    });

    $(document).on("change", function () {
        const check_email = $('#check_email').val();
        const check_domain = $('#domain').val();
        const base_url = "<?= base_url()?>";
        let url = null;
        let id = null;
        let btn = null;
        let value = null;


        if (check_domain) {
            id = 'new_error';
            btn = 'new_company';
            url = 'check_existing_domain';
            value = check_domain;
        }
        if (check_email) {
            id = 'email_error';
            btn = 'new_company';
            url = 'check_existing_subscription_email';
            value = check_email;
        }
        if (url) {
            $.ajax({
                url: base_url + "admin/global_controller/" + url,
                type: "POST",
                data: {
                    name: value,
                },
                dataType: 'json',
                success: function (res) {
                    if (res.error) {
                        $("#" + id).html(res.error);
                        $("#" + btn).attr("disabled", "disabled");
                        return;
                    } else {
                        $("#" + id).empty();
                        $("#" + btn).removeAttr("disabled");
                        return;
                    }
                }
            });
        }
    });
</script>