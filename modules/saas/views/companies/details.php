<div class="row">
    <div class="col-sm-4" data-spy="scroll" data-offset="0" xmlns="http://www.w3.org/1999/html">
        <div class="row">
            <div class="panel panel-custom fees_payment">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <div class="panel-title">
                        <strong><?= _l('subscription_details') ?></strong>
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    $super_admin = super_admin_access();
                    $company_info->companies_id = $company_info->id;
                    if (!empty($company_info->frequency)) {
                        if ($company_info->frequency == 'monthly') {
                            $frequency = _l('mo');
                        } else if ($company_info->frequency == 'lifetime') {
                            $frequency = _l('lt');
                        } else {
                            $frequency = _l('yr');
                        }
                        $plan_name = '<a data-toggle="modal" data-target="#myModal" href="' . base_url('saas/gb/package_details/' . $company_info->company_history_id . '/1') . '">' . $company_info->package_name . ' ' . display_money($company_info->amount, default_currency()) . ' /' . $frequency . ' ' . '</a>';
                    } else {
                        $plan_name = '-';
                    }
                    $activation_code = null;
                    if (!empty($company_info->db_name) != 0) {
                        $db_name = $company_info->db_name;
                    }
                    $update = true;
                    $status = $company_info->status;
                    if ($status == 'pending') {
                        $label = 'exclamation-triangle text-info';
                        $activation_code = $company_info->activation_code;
                        $update = null;
                    } else if ($status == 'running') {
                        $label = 'check-circle text-success';
                    } else if ($status == 'expired') {
                        $label = 'lock text-danger';
                    } else if ($status == 'suspended') {
                        $label = 'ban text-warning';
                    } else {
                        $label = 'times-circle text-danger';
                    }
                    $trial = null;
                    $till_date = null;
                    $validity_date = null;
                    if ($status != 'pending') {
                        if ($company_info->trial_period != 0) {
                            $till_date = trial_period($company_info);
                            $trial = '<small class="label label-danger text-sm mt0">' . _l('trial') . '</small>';
                        } else {
                            $till_date = running_period($company_info);
                        }
                        $validity_date = date("Y-m-d", strtotime($till_date . "day"));
                    }
                    if ($validity_date < date('Y-m-d') && $status == 'running') {
                        $status = 'expired';
                        $label = 'lock text-danger';
                    }
                    ?>
                    <div class="tw-pb tw-pt tw-flex tw-justify-between">
                        <div class="">
                            <i class="fa fa-3x fa-<?= $label ?> pull-left"></i>
                            <h2 class="tw-mt-0 tw-ml-3 pull-left">
                                <?= _l($status) . ' ' . $trial ?>
                                <?php if (!empty($till_date)) { ?>
                                    <small
                                            class="tw-block text-sm fs-0-8"

                                    ><?= _l('till') . ' ' . $validity_date; ?></small>
                                <?php } ?>
                            </h2>
                        </div>
                    </div>
                    <?php
                    if (!empty($company_info->db_name) && $company_info->status == 'running') {
                        ?>
                        <div class="bb bt pb-sm pt-sm">
                            <label class="control-label"><?= _l('name') ?></label>
                            <span class="pull-right"><?= $company_info->name ?></span>
                        </div>
                        <div class="bb tw-py-2 tw-mb-1 tw-flex tw-items-center tw-justify-between">
                            <label class="control-label"><?= _l('login') ?></label>
                            <div class="pull-right">
                                <button
                                        data-company-id="<?= $company_info->companies_id ?>"
                                        data-toggle="tooltip" data-title="<?= _l('login_as_admin'); ?>"
                                        class="btn btn-primary  tw-ml-3 tw-rounded-full view-company"
                                >
                                    <?= _l('login_as_admin') ?>
                                </button>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="bb pb-sm pt-sm">
                        <label class="control-label"><?= _l('email') ?></label>
                        <span class="pull-right"><?= $company_info->email ?></span>
                    </div>


                    <?php
                    $links = all_company_url($company_info->domain);
                    foreach ($links as $lname => $link) {
                        ?>
                        <div class="bb tw-py-2 tw-mb-1 tw-flex tw-items-center tw-justify-between">
                            <label class="control-label"><?= _l($lname) . ' ' . _l('link') ?></label>
                            <div class="pull-right">
                                <a target="_blank" href="<?= $link ?>"> <?= _l('login_customer_contacts') ?></a>
                                <a href="javascript:void(0)" class="copy-link tw-ml-2" data-toggle="tooltip"
                                   title="<?= _l('copy') ?>"
                                   data-clipboard-text="<?= $link ?>"><i
                                            class="fa fa-clipboard"></i>
                                </a>
                                <br/>
                                <a target="_blank" href="<?= $link ?>admin"> <?= _l('admin_login_staff') ?></a>
                                <a href="javascript:void(0)" class="copy-link tw-ml-2" data-toggle="tooltip"
                                   title="<?= _l('copy') ?>"
                                   data-clipboard-text="<?= $link ?>admin"><i
                                            class="fa fa-clipboard"></i>
                                </a>
                            </div>
                        </div>
                    <?php }
                    ?>

                    <div class="bb pb-sm pt-sm">
                        <label class="control-label"><?= _l('package') ?></label>
                        <span class="pull-right"><?= $plan_name ?></span>
                    </div>
                    <?php if (!empty($activation_code) && empty($db_name)) { ?>
                        <div class="bb pb-sm pt-sm tw-truncate">
                            <label class="control-label pull-left tw-truncate"><?= _l('activation_token') ?></label>
                            <span class="pull-right "><?= $activation_code ?> </span>
                            <a href="javascript:void(0)" class="copy-link tw-ml-2" data-toggle="tooltip"
                               title="<?= _l('copy') ?>"
                               data-clipboard-text="<?= $activation_code ?>"><i
                                        class="fa fa-clipboard"></i>
                            </a>
                        </div>
                    <?php }
                    if (!empty($db_name) && !empty($super_admin)) { ?>
                        <div class="bb pb-sm pt-sm">
                            <label class="control-label"><?= _l('database') ?> </label>
                            <span class="pull-right"><?= $db_name ?></span>
                        </div>
                        <div class="bb pb-sm pt-sm">
                            <label class="control-label"><?= _l('restore') ?></label>
                            <span class="pull-right">
                        <a class="mr-lg"
                           href="<?= base_url('saas/companies/reset_db/' . $company_info->id . '/1') ?>"><?= _l('fresh_db') ?></a> |

                        <a href="<?= base_url('saas/companies/reset_db/' . $company_info->id) ?>"><?= _l('with_sample_db') ?></a></span>
                        </div>
                    <?php } else { ?>
                        <div class="bb pb-sm pt-sm">
                            <label class="control-label"><?= _l('setup_manually') ?></label>
                            <span class="pull-right">
                        <a class="mr-lg"
                           href="<?= base_url('saas/companies/reset_db/' . $company_info->id . '/1') ?>"><?= _l('fresh_db') ?></a> |

                        <a href="<?= base_url('saas/companies/reset_db/' . $company_info->id) ?>"><?= _l('with_sample_db') ?></a></span>
                        </div>
                    <?php } ?>
                    <div class="pb-sm pt-sm">
                        <label class="control-label"><?= _l('created_date') ?></label>
                        <span class="pull-right"><?= _dt($company_info->created_date) ?></span>
                    </div>
                    <?php
                    if (empty($super_admin)) {
                        ?>
                        <div class="pb-sm pt-sm pull-right">
                            <label class="control-label"></label>
                            <a href="<?= base_url('upgradePlan/' . $company_info->id) ?>"
                               class="btn btn-sm btn-info"><i
                                        class="fa fa-redo"></i><?= _l('upgrade') . ' ' . _l('package') ?></a>
                        </div>
                    <?php }
                    ?>
                    <div class="pb-sm pt-sm ">

                        <a data-toggle="modal" data-target="#myModal"
                           href="<?= base_url() ?>saas/companies/reset_password/<?= $company_info->id ?>"
                           class="btn btn-sm btn-info"><?= _l('reset_password') ?></a>

                        <a href="<?= saas_url('packages/customize/' . $company_info->id) ?>"
                           class="btn btn-sm btn-primary pull-right"><?= _l('customize') . ' ' . _l('package') ?>
                        </a>
                    </div>

                </div>
            </div>
            <?php if (!empty($update) && !empty($super_admin)) {
            $default_modules = get_default_modules();
            ?>
        <?php echo form_open(base_url('saas/gb/update_sub_validity/' . $company_info->id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="panel-title">
                        <strong><?= _l('update') ?></strong>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="mt0 pt0">
                        <div class="pb-sm pt-sm">
                            <label class="control-label"><?= _l('disabled_modules') ?></label>
                            <select class="selectpicker"
                                    data-toggle="<?php echo $this->input->get('disabled_modules'); ?>"
                                    name="disabled_modules[]" data-actions-box="true" multiple="true"
                                    data-width="100%"
                                    data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                <?php foreach ($default_modules as $key => $modules) {
                                    $selected = '';
                                    if (isset($company_info)) {
                                        if ($company_info->disabled_modules) {
                                            $disabled_modules = unserialize($company_info->disabled_modules);
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
                        </div>
                    </div>
                    <div class="mt0 pt0">
                        <div class="pb-sm pt-sm">
                            <label class="control-label"><?= _l('status') ?></label>
                            <select name="status" class="form-control select_box"
                                    data-width="100%">
                                <?php
                                $subs_status = array('running', 'expired', 'suspended', 'terminated');
                                if (!empty($subs_status)): foreach ($subs_status as $sb_status): ?>
                                    <option
                                            value="<?= $sb_status ?>" <?= (!empty($status) && $status == $sb_status ? 'selected' : NULL) ?>><?= _l($sb_status) ?>
                                    </option>
                                <?php
                                endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="pb-sm pt-sm">
                        <label class="control-label"><?= _l('validity') ?></label>
                        <div class="input-group">
                            <input required type="text" name="validity"
                                   placeholder="<?= _l('enter') . ' ' . _l('validity') ?>"
                                   class="form-control datepicker" value="<?php
                            if (!empty($validity_date)) {
                                echo $validity_date;
                            }
                            ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="pb-sm pt-sm">
                        <label class="control-label"><?= _l('remarks') ?></label>
                        <textarea class="form-control" name="remarks"
                                  required><?php if (!empty($company_info->remarks)) {
                                echo $company_info->remarks;
                            } ?></textarea>
                    </div>
                    <div class="pb-sm pt-sm">
                        <div class="checkbox ">
                            <input type="checkbox" <?php
                            if ($company_info->maintenance_mode == 'Yes') {
                                echo "checked=\"checked\"";
                            }
                            ?> name="maintenance_mode" id="hideShow">
                            <label><?= _l('put_on_maintenance') ?></label>
                        </div>
                    </div>
                    <div class="pb-sm pt-sm"
                         id="hideShowDiv" <?php echo ($company_info->maintenance_mode != 'Yes') ? 'style="display:none"' : '' ?>>
                        <label class="control-label"><?= _l('maintenance_mode_message') ?></label>
                        <textarea class="form-control"
                                  name="maintenance_mode_message"><?php if (!empty($company_info->maintenance_mode_message)) {
                                echo $company_info->maintenance_mode_message;
                            } ?></textarea>
                    </div>
                    <div class="pb-sm pt-sm pull-right">
                        <label class="control-label"></label>
                        <button type="submit" class="btn btn-sm btn-primary "><?= _l('update') ?></button>
                    </div>
                    <?php }
                    if (!empty($super_admin)) {
                        ?>
                        <div class="pb-sm pt-sm pull-left">
                            <label class="control-label"></label>
                            <a href="<?= base_url('saas/companies') ?>" class="btn btn-sm btn-warning"><i
                                        class="fa fa-redo pr-sm"></i><?= _l('back') ?></a>
                        </div>
                        <?php
                    }
                    if (!empty($update) && !empty($super_admin)) { ?>
                </div>
            </div>
        <?php echo form_close(); ?>
        <?php } ?>
        </div>
    </div>
    <div id="subscriptions_history">
        <!--  **************** show when print End ********************* -->
        <div class="col-sm-8 print_width">
            <?php
            $modules = (!empty($company_info->modules) ? unserialize($company_info->modules) : array());
            $moduleInfo = get_result('tbl_saas_package_module');

            ?>
            <?php echo form_open(base_url('saas/companies/update_modules/' . $company_info->id), array('enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
            <div class="row">
                <div class="col-sm-6 ">
                    <div class="panel panel-custom">
                        <!-- Default panel contents -->
                        <div class="panel-heading">
                            <div class="panel-title tw-flex tw-items-center tw-justify-between">
                                <strong><?= _l('current_modules') ?></strong>
                                <button type="submit" class="btn btn-sm btn-primary pull-right">
                                    <?= _l('delete') . ' ' . _l('modules') ?>
                                </button>
                            </div>
                        </div>
                        <!-- Table -->
                        <div class="panel-body home-activity ">
                            <?php
                            if (count($modules) > 0) { ?>
                                <div class="table-responsive tab-content">
                                    <table class="table table-striped modules" style="margin-top: 0">
                                        <thead>
                                        <tr>
                                            <th class="not_visible">
                                                <div class="checkbox mass_select_all_wrap">
                                                    <input type="checkbox" class="mass_select_all"><label></label>
                                                </div>
                                            </th>
                                            <th><?= _l('module_name') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($modules)) {
                                            foreach ($modules as $module) {
                                                $description = $this->app_modules->get($module);
                                                if (empty($description)) {
                                                    continue;
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="checkbox">
                                                            <input type="checkbox" class="module"
                                                                   name="delete_module[]"
                                                                   value="<?= $module ?>"><label></label>
                                                        </div>
                                                    </td>
                                                    <td><?php echo($description['headers']['module_name']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-info"><?= _l('no_module_found') ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 ">
                    <div class="panel panel-custom">
                        <!-- Default panel contents -->
                        <div class="panel-heading">
                            <div class="panel-title tw-flex tw-items-center tw-justify-between">
                                <strong><?= _l('add') . ' ' . _l('modules') ?></strong>
                                <button type="submit" class="btn btn-sm btn-primary pull-right">
                                    <?= _l('add') . ' ' . _l('modules') ?>
                                </button>
                            </div>
                        </div>
                        <!-- Table -->
                        <div class="panel-body home-activity ">
                            <div class="table-responsive tab-content">
                                <table class="table table-striped modules" style="margin-top: 0">
                                    <thead>
                                    <tr>
                                        <th class="not_visible">
                                            <div class="checkbox mass_select_all_wrap">
                                                <input type="checkbox" class="mass_select_all"><label></label>
                                            </div>
                                        </th>
                                        <th><?= _l('module_name') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($moduleInfo)) {
                                        foreach ($moduleInfo as $module) {
                                            if (in_array($module->module_name, $modules)) {
                                                continue;
                                            }
                                            $module_name = $module->module_name;
                                            $description = $this->app_modules->get($module->module_name);
                                            if (empty($description)) {
                                                continue;
                                            }
                                            $price = $module->price;
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="checkbox">
                                                        <input type="checkbox" class="module"
                                                               name="add_module[]"
                                                               value="<?= $module_name ?>"><label></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php echo($description['headers']['module_name']); ?>
                                                    (<?php echo display_money($module->price);; ?>)
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <div id="subscriptions_history">
        <!--  **************** show when print End ********************* -->
        <div class="col-sm-8 print_width">
            <div class="panel panel-custom">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <div class="panel-title">
                        <strong><?= _l('subscriptions') . ' ' . _l('histories') ?></strong>
                    </div>
                </div>

                <!-- Table -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped da" id="subsTable">
                            <thead>
                            <tr>
                                <th><?= _l('name') ?></th>
                                <th><?= _l('amount') ?></th>
                                <th><?= _l('created_date') ?></th>
                                <th><?= _l('validity') ?></th>
                                <th><?= _l('method') ?></th>
                                <th><?= _l('status') ?></th>
                            </tr>
                            </thead>
                            <tbody id="pricing">
                            <script>
                                $(function () {
                                    'use strict';
                                    initDataTable('#subsTable', base_url + "saas/gb/companyHistoryList/" + '<?= $company_info->id ?>', undefined, undefined, undefined);
                                });
                            </script>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!--************ Payment History End***********-->
        </div>
    </div>
    <div id="payment_history">
        <!--  **************** show when print End ********************* -->
        <div class="col-sm-8 print_width">

            <div class="panel panel-custom">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <div class="panel-title">
                        <strong><?= _l('payment') . ' ' . _l('histories') ?></strong>
                    </div>
                </div>

                <!-- Table -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped da" id="PaymentDataTables">
                            <thead>
                            <tr>
                                <th><?= _l('company_name') ?></th>
                                <th><?= _l('package_name') ?></th>
                                <th><?= _l('transaction_id') ?></th>
                                <th><?= _l('amount') ?></th>
                                <th><?= _l('payment_date') ?></th>
                                <th><?= _l('method') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <script>
                                $(function () {
                                    'use strict';
                                    initDataTable('#PaymentDataTables', base_url + "saas/gb/companyPaymentList/" + '<?= $company_info->id ?>', undefined, undefined, undefined);
                                });
                            </script>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!--************ Payment History End***********-->
        </div>
    </div>
</div>

<?php $this->load->view('saas/companies/login_as_company', ['company_info' => $company_info]) ?>

<script type="text/javascript">
    // click hideShow checkbox to show hide hideShowDiv
    'use strict';
    $('.mass_select_all').on('click', function () {
        // get table class .modules .delete_module is td input checkbox
        let selector = $(this).closest('.modules').find('.module');
        if ($(this).is(':checked')) {
            selector.prop('checked', true);
        } else {
            selector.prop('checked', false);
        }
    });

    $('#hideShow').on('click', function () {
        if ($(this).is(':checked')) {
            $('#hideShowDiv').show();
            // add required attribute to input
            $('#hideShowDiv textarea').attr('required', 'required');
        } else {
            $('#hideShowDiv').hide();
            // remove required attribute from input
            $('#hideShowDiv textarea').removeAttr('required');
        }
    });
    // click on copy-link to copy to clipboard
    $('.copy-link').on('click', function () {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(this).data('clipboard-text')).select();
        document.execCommand("copy");
        $temp.remove();
        alert_float('success', '<?= _l('copied') ?>');
    });
</script>