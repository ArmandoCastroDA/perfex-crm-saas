<?php
if (!empty($package_info)) {
    $id = $package_info->id;
} else {
    $id = null;
}
echo form_open(base_url('saas/packages/save_packages/' . $id), array('id' => 'new_package_form', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>

<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
    <?php echo $title; ?>
    <button type="submit" class="btn btn-sm btn-primary mt-sm pull-right row tw-ml-3"><?= _l('save_changes') ?></button>
    <?php
    if (!empty($package_info)) {
        ?>
        <button type="submit"
                name="update_all_company_packages"
                value="1"
                class="btn btn-sm btn-primary mt-sm pull-right tw-mr-3"><?= _l('update_all_company_packages') ?></button>
        <?php
    }
    ?>
</h4>

<div class="row mb-lg ">
    <div class="col-lg-6 col-md-6 br pv">
        <div class="">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="">
                        <div class="form-group">
                            <label for="field-1" class="control-label"><?= _l('name') ?>
                                <span class="text-danger">*</span></label>

                            <div class="">
                                <input required type="text" name="name"
                                       placeholder="<?= _l('enter') . ' ' . _l('package') . ' ' . _l('name') ?>"
                                       class="form-control" value="<?php
                                if (!empty($package_info->name)) {
                                    echo $package_info->name;
                                }
                                ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field-1" class="control-label"><?= _l('price') ?>
                                <span class="text-danger">*</span></label>
                            <div class="row tw-mb-1 ">
                                <?php
                                $active_frequency = get_active_frequency(true);
                                $class = '';
                                // if $active_frequency is 3 the class will be col-sm-4
                                if (count($active_frequency) == 3) {
                                    $class = 'col-sm-4';
                                } elseif (count($active_frequency) == 2) {
                                    $class = 'col-sm-6';
                                } elseif (count($active_frequency) == 1) {
                                    $class = 'col-sm-12';
                                }
                                if (!empty($active_frequency)) {
                                    foreach ($active_frequency as $name => $frequency) { ?>
                                        <div class="<?= $class ?>">
                                            <input required data-parsley-type="number" type="text"
                                                   name="<?= $name ?>"
                                                   placeholder="<?= $frequency ?>"
                                                   class="form-control"
                                                   value="<?php
                                                   if (!empty($package_info->$name)) {
                                                       echo $package_info->$name;
                                                   }
                                                   ?>"/>
                                        </div>
                                    <?php }
                                }
                                ?>
                            </div>

                            <small class="text-muted d-block">
                                <?= _l('saas_price_placeholder') ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="field-1" class="control-label"><?= _l('trial_period') ?>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="">
                                <input required type="text" name="trial_period"
                                       placeholder="<?= _l('enter') . ' ' . _l('trial_period') ?>"
                                       class="form-control" value="<?php
                                if (!empty($package_info->trial_period)) {
                                    echo $package_info->trial_period;
                                }
                                ?>"/>
                            </div>
                        </div>

                        <div class="form-group mbot15<?= count($payment_modes) > 0 ? ' select-placeholder' : ''; ?>">
                            <label for="allowed_payment_modes"
                                   class="control-label"><?php echo _l('allowed_payment_modes'); ?></label>
                            <br/>
                            <?php if (count($payment_modes) > 0) { ?>
                                <select class="selectpicker"
                                        data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                                        name="allowed_payment_modes[]" data-actions-box="true" multiple="true"
                                        data-width="100%"
                                        data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <?php foreach ($payment_modes as $mode) {
                                        $selected = '';
                                        if (isset($package_info)) {
                                            if ($package_info->allowed_payment_modes) {
                                                $inv_modes = unserialize($package_info->allowed_payment_modes);
                                                if (is_array($inv_modes)) {
                                                    foreach ($inv_modes as $_allowed_payment_mode) {
                                                        if ($_allowed_payment_mode == $mode['id']) {
                                                            $selected = ' selected';
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            if (!empty($mode['selected_by_default']) && $mode['selected_by_default'] == 1) {
                                                $selected = ' selected';
                                            }
                                        } ?>
                                        <option value="<?php echo $mode['id']; ?>" <?php echo $selected; ?>>
                                            <?php echo $mode['name']; ?></option>
                                        <?php
                                    } ?>
                                </select>
                            <?php } else { ?>
                                <p class="tw-text-neutral-500">
                                    <?php echo _l('invoice_add_edit_no_payment_modes_found'); ?>
                                </p>
                                <a class="btn btn-primary btn-sm" href="<?php echo admin_url('paymentmodes'); ?>">
                                    <?php echo _l('new_payment_mode'); ?>
                                </a>
                            <?php } ?>
                        </div>
                        <div class="form-group mbot15<?= count($modules) > 0 ? ' select-placeholder' : ''; ?>">
                            <label for="allowed_modules"
                                   class="control-label"><?php echo _l('allowed_modules'); ?>

                            </label>
                            <br/>
                            <?php if (count($modules) > 0) { ?>
                                <select class="selectpicker"
                                        data-toggle="<?php echo $this->input->get('allowed_modules'); ?>"
                                        name="modules[]" data-actions-box="true" multiple="true"
                                        data-width="100%"
                                        data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <?php foreach ($modules as $module) {
                                        if ($module['system_name'] == 'saas') {
                                            continue;
                                        }
                                        $module_title = moduleTitle($module);
                                        $selected = '';
                                        if (isset($package_info)) {
                                            if ($package_info->modules) {
                                                $inv_modes = unserialize($package_info->modules);
                                                if (is_array($inv_modes)) {
                                                    foreach ($inv_modes as $_allowed_module) {
                                                        if ($_allowed_module == $module['system_name']) {
                                                            $selected = ' selected';
                                                        }
                                                    }
                                                }
                                            }
                                        } ?>
                                        <option value="<?php echo $module['system_name']; ?>" <?php echo $selected; ?>>
                                            <?php echo $module_title; ?></option>
                                        <?php
                                    } ?>
                                </select>
                            <?php } else { ?>
                                <p class="tw-text-neutral-500">
                                    <?php echo _l('allowed_modules'); ?>
                                </p>
                            <?php } ?>
                        </div>
                        <?php
                        $themes = get_theme_list();
                        ?>
                        <div class="form-group mbot15<?= count($themes) > 0 ? ' select-placeholder' : ''; ?>">
                            <label for="allowed_modules"
                                   class="control-label"><?php echo _l('allowed_themes'); ?></label>
                            <br/>
                            <?php if (count($themes) > 0) { ?>
                                <select class="selectpicker"
                                        data-toggle="<?php echo $this->input->get('allowed_themes'); ?>"
                                        name="allowed_themes[]" data-actions-box="true" multiple="true"
                                        data-width="100%"
                                        data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <?php foreach ($themes as $key => $theme) {
                                        $themeName = basename($theme);
                                        $selected = '';
                                        if (isset($package_info)) {
                                            if ($package_info->allowed_themes) {
                                                $inv_modes = unserialize($package_info->allowed_themes);
                                                if (is_array($inv_modes) && !empty($inv_modes)) {
                                                    in_array($themeName, $inv_modes) ? $selected = ' selected' : '';
                                                }
                                            }
                                        } ?>
                                        <option value="<?php echo $themeName; ?>" <?php echo $selected; ?>>
                                            <?php echo ucfirst($themeName); ?></option>
                                        <?php
                                    } ?>
                                </select>
                            <?php } else { ?>
                                <p class="tw-text-neutral-500">
                                    <?php echo _l('allowed_themes'); ?>
                                </p>
                            <?php } ?>
                        </div>
                        <?php
                        $default_modules = get_default_modules();
                        ?>
                        <div class="form-group mbot15<?= count($default_modules) > 0 ? ' select-placeholder' : ''; ?>">
                            <label for="disabled_modules"
                                   class="control-label"><?php echo _l('disabled_modules'); ?></label>
                            <br/>
                            <?php if (count($default_modules) > 0) { ?>
                                <select class="selectpicker"
                                        data-toggle="<?php echo $this->input->get('disabled_modules'); ?>"
                                        name="disabled_modules[]" data-actions-box="true" multiple="true"
                                        data-width="100%"
                                        data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <?php foreach ($default_modules as $key => $modules) {
                                        $selected = '';
                                        if (isset($package_info)) {
                                            if ($package_info->disabled_modules) {
                                                $disabled_modules = unserialize($package_info->disabled_modules);
                                                if (is_array($disabled_modules) && !empty($disabled_modules)) {
                                                    in_array($modules, $disabled_modules) ? $selected = ' selected' : '';
                                                }
                                            }
                                        } ?>
                                        <option value="<?php echo $modules; ?>" <?php echo $selected; ?>>
                                            <?php echo ucfirst(str_replace('_', ' ', $modules)); ?></option>
                                        <?php
                                    } ?>
                                </select>
                            <?php } else { ?>
                                <p class="tw-text-neutral-500">
                                    <?php echo _l('disabled_modules'); ?>
                                </p>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <?php
                            $packageinfo = '';
                            if (!empty($package_info)) {
                                $packageinfo = $package_info;
                            }
                            echo saas_packege_field('checkbox', $packageinfo);
                            ?>
                        </div>

                        <div class="row   tw-mb-1 ">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <input type="checkbox" value="Yes"
                                            <?php if (!empty($package_info) && $package_info->recommended == 'Yes') {
                                                echo 'checked';
                                            } ?> name="recommended">
                                        <label><?= _l('recommended') ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">

                                <div class="form-group">
                                    <div class="checkbox checkbox-success">
                                        <input
                                            <?= (!empty($package_info->status) && $package_info->status == 'published' || empty($package_info) ? 'checked' : ''); ?>
                                                class="select_one" type="checkbox" name="status" value="published">
                                        <label>
                                            <?= _l('published') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $value = (isset($package_info) ? $package_info->description : ''); ?>
                        <?php echo render_textarea('description', _l('description'), $value); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 ">
        <div class="row">
            <div class="panel_s">
                <div class="panel-body ">
                    <div class="row">
                        <?php
                        if (empty($package_info)) {
                            $package_info = '';
                        }
                        echo saas_packege_field('text', $package_info);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>
<script type="text/javascript">
    'use strict';
    // click to additional_price checkbox disabled according to data-name attribute value in the input name
    $(document).on('click', '.additional_price', function () {
        var name = $(this).attr('data-name');
        if ($(this).is(':checked')) {
            $('input[name="' + name + '"]').prop('disabled', false);
        } else {
            $('input[name="' + name + '"]').prop('disabled', true);
        }
    });
    $(function () {
        $("#new_package_form").appFormValidator();
    });
</script>