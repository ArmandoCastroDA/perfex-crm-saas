<div class="form-group">
    <?php
    // replace _price to offer into $type string
    if (empty($front)){
    ?>
    <label for="field-1" class="control-label"><?= _l('billing_cycle') ?>
        <span class="required">*</span></label>

    <div class="">
        <?php
        }
        echo form_dropdown('billing_cycle', $options, $type, 'style="width:100%" id="billing_cycle" onchange="get_package_info(' . $package_info->id . ',this.value)" class="form-control"'); ?>
        <small><?= _l('plan_renews') ?> <span
                    class="text-danger"><?= $renew_date; ?></span>
            @

            <?php
            if (!empty($package_info->$other)) {
                echo '<del>', display_money($package_info->$type, default_currency()), '</del>' . ' ' . display_money($package_info->$other);
            } else {
                echo display_money($package_info->$type, default_currency());
            }
            ?>
            /<?= _l($type_title) ?></small>
    </div>
</div>
<input type="hidden" name="expired_date" value="<?= $renew_date ?>">
<?php if (empty($company_id) && empty($front) && empty(subdomain())) { ?>
    <div class="form-group ">
        <div class="checkbox">
            <input type="checkbox" name="mark_paid" value="1">
            <label class=""><?= _l('direct_payment') ?>
            </label>
        </div>
    </div>
<?php } ?>
<div class="mark_as_paid hidden" style="display: none">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_coupon" value="1" id="is_coupon">
        <label class="form-check-label"><?= _l('i_have_a_coupon') ?></label>
    </div>
</div>

<div class="mark_as_paid coupon_code_area " style="display: none">
    <div class="form-group  mt-2 mb-2" id="coupon_code_area">
        <div class="input-group">
            <input type="text" class="form-control" id="coupon_code" name="coupon_code" value=""
                   placeholder="<?= _l('enter_coupon_code') ?>">
            <span class="input-group-btn">
            <button id="btn_coupon_code" type="button"
                    class="btn btn-primary btnSubmit"><?= _l('apply') ?></button>
            </span>
        </div>
        <span class="text-danger" id="discount_error"></span>
    </div>
    <div id="applied_discount"></div>
    <div class="form-group">
        <label for="field-1" class="control-label sub_total_text"><?= _l('amount') ?>
            <span class="required">*</span></label>

        <div class="">
            <input type="text" class="form-control" id="sub_total" name="amount" readonly
                   value="<?php
                   if (!empty($package_info->$other)) {
                       echo $package_info->$other;
                   } else {
                       echo $package_info->$type;
                   } ?>">
        </div>
    </div>
    <div id="final_amount"></div>
    <?php if (empty($company_id) && empty($front) && empty(subdomain())) { ?>
        <div class="form-group">
            <label for="payment_date" class="control-label"><?= _l('payment_date') ?>
                <span class="required">*</span></label>

            <div class="">
                <div class="input-group date">
                    <input type="text" class="form-control datepicker" name="payment_date"
                           value="<?= date('Y-m-d') ?>">
                    <div class="input-group-addon">
                        <a href="#"><i class="fa fa-calendar"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="field-1" class="control-label"><?= _l('payment_method') ?>
                <span class="required">*</span></label>

            <div class="">
                <select name="payment_method" class="form-control select_box" style="width: 100%">
                    <option value=""><?= _l('dropdown_non_selected_tex') ?></option>
                    <?php
                    $this->load->model('payment_modes_model');
                    $payment_modes = $this->payment_modes_model->get('', [
                        'expenses_only !=' => 1,
                    ]);
                    if (!empty($payment_modes)) {
                        foreach ($payment_modes as $mode) {
                            ?>
                            <option value="<?= $mode['id'] ?>"><?= $mode['name'] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="field-1" class="control-label"><?= _l('notes') ?></label>

            <div class="">
                <textarea class="form-control" name="notes"></textarea>
            </div>
        </div>

    <?php } ?>
    <script type="text/javascript">
        'use strict';
        $(document).ready(function () {
            $('input[name="mark_paid"]').on('change', function () {
                if ($(this).prop('checked')) {
                    $('.mark_as_paid').show();
                } else {
                    $('.mark_as_paid').hide();
                }
            });
            $('input[name="is_coupon"]').on('change', function () {
                if ($(this).prop('checked')) {
                    $('.coupon_code_area').show();
                } else {
                    $('.coupon_code_area').hide();
                }
            });
            $('#btn_coupon_code').on('click', function () {
                var coupon_code = $('#coupon_code').val();
                var formData = {
                    'coupon_code': $('#coupon_code').val(),
                    'billing_cycle': '<?= $type ?>',
                    'package_id': "<?= $package_info->id ?>",
                    'email': $('#check_email').val(),
                };
                if (coupon_code == '') {
                    alert('<?= _l('coupon_code_required') ?>');
                    return false;
                }
                $.ajax({
                    type: "post",
                    url: "<?= base_url() ?>check_coupon_code",
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
            });
        });

    </script>


