<link rel="stylesheet" type="text/css" id="tailwind-css" href="<?= base_url('assets/builds/tailwind.css') ?>">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<?php
$ci = &get_instance();
echo form_open($ci->uri->uri_string(), array('id' => 'form', 'class' => 'tw-form-horizontal disable-on-submit')); ?>

<div class="tw-max-w-4xl tw-w-full tw-mx-auto tw-my-6">

    <div id="logo" class="tw-py-2 tw-px-2 tw-h-[63px] tw-flex tw-items-center">
        <?php echo get_company_logo(get_admin_uri() . '/', '!tw-mt-0 tw-block tw-mx-auto') ?>
    </div>

    <div class="tw-bg-white tw-rounded tw-px-4 tw-py-6 tw-border tw-border-solid tw-border-neutral-200">
        <?php
        $current_step = 1;
        if ($current_step == 1) {
            $errorList = array();
            $error = false;
            $path = APPPATH . 'config';
            $app_config = $path . '/app-config.php';
            $database_config = $path . '/database.php';
            $dir = 'application/config';
            $style = "class='label label-danger m-2 font-size-15'  style='padding: 15px;font-size: 14px'";

            if (!is_writable($path)) {
                $error = true;
                $errorList[] = "<span $style > $dir is not writable. Make $dir - Permissions 0755</span>";
            }
            if (!is_writable($app_config)) {
                $error = true;
                $errorList[] = "<span $style > $dir/app-config.php is not writable. Make $dir/app-config.php writable - Permissions 0755</span>";
            }
            if (!is_writable($database_config)) {
                $error = true;
                $errorList[] = "<span $style >$dir/database.php is not writable. Make $dir/database.php writable - Permissions 0755</span>";
            }
            if (!$error) {
                $current_step = 2;
            }
        }

        if (count($errorList) > 0) {
            echo implode("<br/>", $errorList);
        }
        ?>
        <?php echo '<input type="hidden" name="step" value="' . $current_step . '">'; ?>
        <div class="row">
            <div class="col-md-12 m-2">
                <input type="submit" id="next" value="<?= ('Reload') ?>" name="next"
                       class="btn btn-primary pull-right">
            </div>
        </div>
    </div>
</div>
