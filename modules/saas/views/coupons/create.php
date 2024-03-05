<div class="panel_s">
    <div class="panel-heading">
        <h4 class="panel-title"><?php echo _l('new_coupon'); ?></h4>
    </div>

    <div class="panel-body">
        <?php
        if (!empty($coupon_info)) {
            $id = $coupon_info->id;
        } else {
            $id = null;
        }
        echo form_open(base_url('saas/coupons/save_coupon/' . $id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>

        <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?= _l('name') ?>
                <span class="required">*</span></label>

            <div class="col-sm-5">
                <input required type="text" name="name"
                       placeholder="<?= _l('enter') . ' ' . _l('name') ?>"
                       class="form-control" value="<?php
                if (!empty($coupon_info->name)) {
                    echo $coupon_info->name;
                }
                ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?= _l('code') ?>
                <span class="required">*</span></label>

            <div class="col-sm-5">
                <div class="input-group">
                    <input required type="text" id="coupon_code" name="code"
                           placeholder="<?= _l('enter') . ' ' . _l('code') ?>"
                           class="form-control" value="<?php
                    $this->load->helper('string');
                    if (!empty($coupon_info)) {
                        echo $coupon_info->code;
                    } else {
                        echo(random_string('alnum', 8));
                    }
                    ?>"/>
                    <div class="input-group-addon ">
                        <a href="#" id="gen_coupon" class="">
                            <i class="fa fa-refresh"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?= _l('amount') ?>
                <span class="required">*</span></label>

            <div class="col-sm-5">
                <div class="input-group">
                    <input required data-parsley-type="number" type="text" name="amount"
                           placeholder="<?= _l('enter') . ' ' . _l('amount') ?>"
                           class="form-control br0" value="<?php
                    if (isset($coupon_info->amount)) {
                        echo $coupon_info->amount;
                    }
                    ?>"/>
                    <div class="input-group-addon tw-py-0 tw-px-0 tw-border-0">
                        <select name="type" class="selectpicker" data-width="100%">
                            <option value="1" <?php
                            if (isset($coupon_info)) {
                                if ($coupon_info->type == '1') {
                                    echo 'selected';
                                }
                            } ?>><?php echo _l('percentage'); ?></option>
                            <option value="0" <?php if (isset($coupon_info)) {
                                if ($coupon_info->type == '0') {
                                    echo 'selected';
                                }
                            } ?>><?php echo _l('flat'); ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label
                    class="col-lg-3 col-md-3 col-sm-3 control-label"><?= _l('end_date') ?></label>
            <div class="col-lg-5 col-md-5 col-sm-5">
                <div class="input-group">
                    <input required type="text" name="end_date"
                           class="form-control datepicker"
                           value="<?php
                           if (!empty($coupon_info->end_date)) {
                               echo $coupon_info->end_date;
                           } else {
                               echo date('Y-m-d');
                           }
                           ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                    <div class="input-group-addon">
                        <a href="#"><i class="fa fa-calendar"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="discount_type"
                   class="control-label col-sm-3"><?= _l('select') . ' ' . _l('package') ?><span
                        class="required">*</span></label>
            <div class="col-sm-5">
                <select name="package_id" class="selectpicker" data-width="100%">
                    <option value="0"><?= _l('all') . ' ' . _l('package') ?></option>
                    <?php
                    $all_pricing = get_order_by('tbl_saas_packages', null, 'sort', true);
                    if (!empty($all_pricing)) {
                        foreach ($all_pricing as $pricing) {
                            ?>
                            <option value="<?php echo $pricing->id; ?>" <?php
                            if (isset($coupon_info)) {
                                if ($coupon_info->package_id == $pricing->id) {
                                    echo 'selected';
                                }
                            } ?>><?php echo $pricing->name; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>
        <?php
        $package_type = get_active_frequency();
        ?>

        <div class="form-group">
            <label for="discount_type"
                   class="control-label col-sm-3"><?= _l('select') . ' ' . _l('package_type') ?><span
                        class="required">*</span></label>
            <div class="col-sm-5">
                <select name="package_type" class="selectpicker" data-width="100%">
                    <?php foreach ($package_type as $value) {
                        $key = $value['name'];
                        ?>
                        <option value="<?php echo $key; ?>" <?php
                        if (isset($coupon_info)) {
                            if ($coupon_info->package_type == $key) {
                                echo 'selected';
                            }
                        } ?>><?php echo $value['label']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="field-1"
                   class="col-sm-3 control-label"><?= _l('showing_on_pricing') ?></label>

            <div class="col-sm-2">
                <div class="checkbox">
                    <input type="checkbox" value="Yes"
                        <?php if (!empty($coupon_info->show_on_pricing) && $coupon_info->show_on_pricing == 'Yes') {
                            echo 'checked';
                        } ?>
                           name="show_on_pricing">
                    <label for="show_on_pricing">

                    </label>
                </div>

            </div>

            <div class="col-sm-2">
                <div class="checkbox">
                    <input
                        <?= (!empty($coupon_info->status) && $coupon_info->status == 'active' || empty($coupon_info) ? 'checked' : ''); ?>
                            class="select_one" type="checkbox" name="status" value="active">
                    <label for="status">
                        <?= _l('active') ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="discount_type" class="control-label col-sm-3"></label>
            <div class="col-sm-4">
                <button type="submit" id="sbtn" name="sbtn" value="1"
                        class="btn btn-block btn-success"><?= _l('save') ?></button>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    'use strict';
    // click to generate coupon code and random string
    $(document).ready(function () {
        $('#gen_coupon').on('click', function (e) {
            // generate coupon code randomly
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for (var i = 0; i < 8; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            $('#coupon_code').val(text);
        });
    })
</script>
