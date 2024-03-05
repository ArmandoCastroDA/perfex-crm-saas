<!DOCTYPE html>
<html dir="<?php echo is_rtl(true) ? 'rtl' : 'ltr'; ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <?php
    $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
    $base_url .= '://' . $_SERVER['HTTP_HOST'];
    $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    $base_url = preg_replace('/install.*/', '', $base_url);
    ?>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title><?= get_option('saas_companyname') ?></title>

    <?php app_external_form_header($form); ?>
    <style>
        body,
        html {
            font-size: 16px;
        }

        body {
            font-family: "Inter", sans-serif;
            background: #f8fafc;
        }

        body > * {
            font-size: 14px;
        }

        #loading {
            position: fixed;
            width: 100%;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 9999;
            display: none;
        }

        @-webkit-keyframes spin {
            from {
                -webkit-transform: rotate(0deg);
            }
            to {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        #loading::after {
            content: '';
            display: block;
            position: absolute;
            left: 48%;
            top: 40%;
            width: 40px;
            height: 40px;
            border-style: solid;
            border-color: black;
            border-top-color: transparent;
            border-width: 4px;
            border-radius: 50%;
            -webkit-animation: spin .8s linear infinite;
            animation: spin .8s linear infinite;
        }
    </style>

</head>

<body>


<div class="tw-max-w-4xl tw-w-full tw-mx-auto tw-my-6">
    <div class="logo tw-mt-5 tw-mb-5 tw-p-3 tw-inline-block tw-w-full">
        <img src="<?php echo saas_logo() ?>"
             class="tw-block tw-mx-auto">
    </div>
    <nav aria-label="Progress">
        <ol role="list"
            class="tw-divide-y tw-divide-solid tw-divide-neutral-200 tw-rounded-md tw-border tw-border-solid tw-border-neutral-200 md:tw-flex md:tw-divide-y-0 tw-mb-4 tw-bg-white">
            <?php foreach ($steps as $stepIdx => $step) { ?>
                <li class="tw-relative md:tw-flex md:tw-flex-1">
                    <?php if ($step['status'] === 'complete') { ?>
                        <div class="tw-flex tw-w-full tw-items-center">
                <span class="tw-flex tw-items-center tw-px-5 tw-py-4 tw-text-sm tw-font-medium">
                    <span
                            class="tw-flex tw-h-7 tw-w-7 tw-flex-shrink-0 tw-items-center tw-justify-center tw-rounded-full <?= count($steps) === $current_step && $step['id'] === $current_step ? 'tw-bg-success-600' : 'tw-bg-primary-600'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="tw-h-5 w-5 tw-text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                    </span>
                    <span class="tw-ml-2.5 tw-text-sm tw-font-medium tw-text-neutral-900">
                        <?= $step['name']; ?>
                    </span>
                </span>
                        </div>
                    <?php } elseif ($step['status'] === 'current') { ?>
                        <div class="tw-flex tw-items-center tw-px-5 tw-py-4 tw-text-sm tw-font-medium"
                             aria-current="step">
                <span
                        class="tw-flex tw-h-7 tw-w-7 tw-flex-shrink-0 tw-items-center tw-justify-center tw-rounded-full tw-border-2 tw-border-solid tw-border-primary-600">
                    <span class="tw-text-primary-600">
                        <?= $step['id']; ?>
                    </span>
                </span>
                            <span class="tw-ml-2.5 tw-text-sm tw-font-medium tw-text-primary-600">
                    <?= $step['name']; ?>
                </span>
                        </div>
                    <?php } else { ?>
                        <div class="tw-flex tw-items-center">
                <span class="tw-flex tw-items-center tw-px-5 tw-py-4 tw-text-sm tw-font-medium">
                    <span
                            class="tw-flex tw-h-7 tw-w-7 tw-flex-shrink-0 tw-items-center tw-justify-center tw-rounded-full tw-border-2 tw-border-solid tw-border-neutral-300">
                        <span class="tw-text-neutral-500">
                            <?= $step['id']; ?>
                        </span>
                    </span>
                    <span class="tw-ml-2.5 tw-text-sm tw-font-medium tw-text-neutral-500">
                        <?= $step['name']; ?>
                    </span>
                </span>
                        </div>
                    <?php } ?>
                    <?php if ($stepIdx !== count($steps) - 1) { ?>
                        <!-- Arrow separator for lg screens and up -->
                        <div class="tw-absolute tw-top-0 tw-right-0 tw-hidden tw-h-full tw-w-5 md:tw-block"
                             aria-hidden="true">
                            <svg class="tw-h-full tw-w-full tw-text-neutral-300" viewBox="0 0 22 80" fill="none"
                                 preserveAspectRatio="none">
                                <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor"
                                      stroke-linejoin="round"/>
                            </svg>
                        </div>
                    <?php } ?>
                </li>
            <?php } ?>
        </ol>
    </nav>

    <div class="tw-bg-white tw-rounded tw-px-4 tw-py-6 tw-border tw-border-solid tw-border-neutral-200 demo">
        <?php if (isset($error) && $error != '') { ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php } ?>
        <?php echo form_open($this->uri->uri_string(), array('id' => 'form', 'class' => 'tw-form-horizontal disable-on-submit')); ?>

        <?php echo '<input type="hidden" name="step" value="' . $current_step . '">'; ?>
        <?php
        if ($current_step == 1) { ?>


        <div id='loading'></div>

        <div class="row ">
            <div class="col-md-6">
                <div class="form-group">
                    <label class=" control-label"><?= _l('activation_token') ?>
                        <span class="text-danger">*</span>
                    </label>
                    <div class="mb-0">
                        <input type="text" class="form-control" id="activation_token"
                               required
                               value="<?= (!empty($activation_token) ? $activation_token : '') ?>"
                               placeholder="<?= _l('enter_placeholder', lang('activation_token')) ?>"
                               name="activation_token"

                        >
                        <small class="text-danger"
                               id="activation_token_error"><?= (!empty($activation_token_error) ? $activation_token_error : '') ?>
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class=" control-label"><?= _l('email') ?></label>
                    <div class="">
                        <input type="text" class="form-control" id="check_email"
                               value="<?= (!empty($user_name) ? $user_name : '') ?>"
                               placeholder="<?= _l('enter_placeholder', lang('username')) ?>"
                               name="email">
                        <small class="text-danger"
                               id="username_error"><?= (!empty($username_error) ? $username_error : '') ?></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="firstname" class="control-label"><?= _l('first_name') ?>
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" name="firstname" id="firstname" required
                    >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="lastname" class="control-label"><?= _l('last_name') ?>
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" name="lastname" id="lastname" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class=" control-label"><?= _l('password') ?>
                        <span class="text-danger">*</span>
                    </label>
                    <div class="">
                        <input type="text" class="form-control" id="password" value="123456"
                               placeholder="<?= _l('enter_placeholder', lang('password')) ?>"
                               name="password">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="timezone"
                           class="control-label"><?= _l('settings_localization_default_timezone') ?></label>
                    <select name="timezone" data-live-search="true" id="timezone" class="form-control" required
                            data-none-selected-text="Select system timezone">
                        <option value=""></option>
                        <?php foreach (get_timezones_list() as $key => $timezones) { ?>
                            <optgroup label="<?php echo $key; ?>">
                                <?php foreach ($timezones as $timezone) { ?>
                                    <option value="<?php echo $timezone; ?>" <?php if (get_option('saas_default_timezone') == $timezone) {
                                        echo 'selected';
                                    } ?>><?php echo $timezone; ?></option>
                                <?php } ?>
                            </optgroup>
                        <?php } ?>
                    </select>
                </div>
            </div>

        </div>

        <div class="row">
            <?php if (show_recaptcha() && $form->recaptcha == 1) { ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>">
                        </div>
                        <div id="recaptcha_response_field" class="text-danger"></div>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-6">
                <div class="form-group">
                    <label class=" control-label"><?= _l('fresh_database') ?></label>
                    <div class="">
                        <div class="checkbox">
                            <input type="checkbox" name="fresh_database" checked>
                            <label></label>
                            <small class="text-danger"
                                   id="password_error">if you uncheck it you will install it with sample
                                data</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <input type="submit" id="next" value="<?= _l('Done') ?>" name="next"
                       class="btn btn-primary pull-right">
            </div>
        </div>
    </div>
    <?php }
    if ($current_step == 2) {
        $base_url = get_option('default_url');
        $wildcard = get_option('saas_server_wildcard');
        if (!empty($wildcard)) {
            $base_url = companyUrl($company_info->domain);
        }
        // check slash exist or not last of the url
        // if not exist then add slash
        if (substr($base_url, -1) != '/') {
            $base_url .= '/';
        }
        ?>
        <h4 class="bold">Installation successful!</h4>
        <p>Please login as administrator at <a
                    href="<?php echo $base_url; ?>admin"

                    target="_blank"><?php echo $base_url; ?>admin</a></p>
        <h3><b>Remember:</b></h3>

        <ul class="list-unstyled ">

            <h4 class="bold">Administrators/staff members must login at : <a class="text-danger"
                                                                             href="<?php echo $base_url . 'admin'; ?>"
                                                                             target="_blank"><?php echo $base_url . 'admin'; ?></a>
            </h4>
            <h4 class="bold">Customers contacts must login at : <a class="text-danger"
                                                                   href="<?php echo $base_url . 'login'; ?>"
                                                                   target="_blank"><?php echo $base_url . 'login'; ?></a>
            </h4>
            <?php
            if (empty($wildcard)) {
                ?>
                <h4 class="bold">Account : <?= $company_info->domain ?></h4>
            <?php }
            ?>
            <h4 class="bold">Email : <?= $_POST['email'] ?></h4>
            <h4 class="bold">Password : <?= $_POST['password'] ?></h4>

        </ul>

        <hr/>

    <?php }
    ?>
    <?php echo form_close(); ?>
</div>


<?php app_external_form_footer($form); ?>

<script type="text/javascript">

    // on ready
    $(function () {
        // check activation token
        check_activation_token();
    });


    $('#activation_token').on("change", function () {
        check_activation_token();
    });

    function check_activation_token() {
        var activation_token = $('#activation_token').val();
        var base_url = "<?= base_url()?>";
        var url = null;
        var id = null;
        var value = null;
        var btn;
        if (activation_token) {
            id = 'activation_token_error';
            btn = 'next';
            url = 'check_existing_activation_token_new';
            value = activation_token;
        }
        if (url != null && activation_token != null) {
            // add loading
            $('#loading').show();
            $.ajax({
                url: base_url + url,
                type: "POST",
                data: {
                    name: value,
                },
                dataType: 'json',
                success: function (res) {
                    // remove loading
                    $('#loading').hide();
                    if (res.error) {
                        $("#" + id).html(res.error);
                        $("#" + btn).attr("disabled", "disabled");
                        return;
                    } else {
                        $("#firstname").val(res.first_name);
                        $("#lastname").val(res.last_name);
                        $("#check_email").val(res.email);
                        $("#" + id).empty();
                        $("#" + btn).removeAttr("disabled");
                        return;
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    // remove loading
                    $('#loading').hide();
                    $("#" + id).html(xhr.responseText);
                    $("#" + btn).attr("disabled", "disabled");
                    return;
                }
            });
        }
    }

    $(function () {
        $('select').selectpicker();
        $('#installForm').on('submit', function (e) {
            $('#installBtn').prop('disabled', true);
            $('#installBtn').text('Please wait...');
        });

        setTimeout(function () {
            $('.sql-debug-alert').slideUp();
        }, 4000);
    });
</script>
</body>
</html>

