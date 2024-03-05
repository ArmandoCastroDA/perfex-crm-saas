<?php
$company_id = !empty($subs_info) ? $subs_info->companies_id : '';
echo form_open(base_url('proceedPayment'), array('id' => 'checkoutPayment', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <h4 class="panel-title"><?= _l('payment') . '  ' . _l('summery') . ' - ' ?> <span
                    id="package_name"><?= $package_info->name ?></span>
        </h4>
    </div>
    <div class="panel-body form-horizontal">
        <div class=" mb-lg ">
            <div class="col-lg-7 col-md-7 ">
                <div class="">
                    <input type="hidden" name="companies_id" id="company_id"
                           value="<?= !empty($subs_info) ? $subs_info->companies_id : '' ?>">
                    <div class="form-group">
                        <label for="discount_type"
                               class="control-label"><?= _l('select') . ' ' . _l('package') ?>
                            <span class="required">*</span></label>
                        <div class="">
                            <select name="package_id" onchange="get_package_info(this.value)"
                                    class="form-control m0"
                                    data-width="100%"
                                    data-none-selected-text="<?php echo lang('select') . ' ' . _l('package'); ?>"
                                    data-live-search="true">
                                <?php
                                if (!empty($all_packages)) {
                                    foreach ($all_packages as $v_package) {
                                        ?>
                                        <option <?php
                                        if (isset($package_info)) {
                                            if ($package_info->id == $v_package->id) {
                                                echo 'selected';
                                            }
                                        } ?> value="<?php echo $v_package->id; ?>"
                                             data-subtext="<?php echo lang('monthly') . ': ' . display_money($v_package->monthly_price) . ' ' . _l('quarterly') . ': ' . display_money($v_package->lifetime_price) . ' ' . _l('yearly') . ': ' . display_money($v_package->yearly_price) . ' ' . strip_tags(mb_substr(!empty($c_pricing->description) ? $c_pricing->description : '', 0, 200)) . '...'; ?>"><?php echo $v_package->name; ?></option>
                                    <?php } ?>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div id="billing_cycle">

                    </div>

                    <div class="row">
                        <div class="panel panel-custom">
                            <!-- Default panel contents -->
                            <div class="panel-heading">
                                <strong><?= _l('select') . ' ' . _l('payment_method') ?></strong>
                            </div>
                            <div class="panel-body">
                                <?php
                                foreach ($payment_modes as $mode) {
                                    if (!is_numeric($mode['id']) && !empty($mode['id'])) {
                                        if (!is_payment_mode_allowed_for_saas($mode['id'])) {
                                            continue;
                                        }
                                        ?>
                                        <div class="radio radio-success online-payment-radio">
                                            <input type="radio" value="<?php echo $mode['id']; ?>"
                                                   required
                                                   id="pm_<?php echo $mode['id']; ?>" name="paymentmode">
                                            <label for="pm_<?php echo $mode['id']; ?>"><?php echo $mode['name']; ?></label>
                                        </div>
                                        <?php if (!empty($mode['description'])) { ?>
                                            <div class="mbot15">
                                                <?php echo $mode['description']; ?>
                                            </div>
                                        <?php }
                                    }
                                } ?>
                            </div>
                        </div>
                        <div class="">
                            <div class="checkbox mt-lg mb-lg pull-left">
                                <input type="checkbox" required name="i_have_read_agree">
                                <label>
                                </label>

                                <strong class="required"><?= _l('i_have_read_agree') ?></strong>
                                <a target="_blank" href="<?= base_url('front/terms-conditions') ?>"><?= _l('tos') ?></a>
                                <strong class="required"><?= _l('and') ?></strong>
                                <a target="_blank"
                                   href="<?= base_url('front/privacy-policy') ?>"><?= _l('privacy') ?></a>

                            </div>
                            <div class="col-md-3 row pull-right ">
                                <button type="submit"
                                        class="btn btn-success btn-block btn-lg"><?= _l('checkout') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5" id="package_info">

            </div>
        </div>

    </div>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    'use strict';
    // check package_id is empty or not by name
    $(document).ready(function () {
        var package_id = $('[name="package_id"]').val();
        // if package_id is not empty then trigger onchange event
        if (package_id != '') {
            get_package_info(package_id, '<?= $frequency?>_price', '<?= $company_id?>');
        }
    });

    function get_package_info(package_id, package_type = 'monthly_price', company_id = '') {
        // check input mark_paid is checked or not
        var is_coupon = $('input[name="is_coupon"]').is(":checked");
        // if company_id is empty then get from input
        if (company_id === '') {
            company_id = $('#company_id').val();
        }

        $.ajax({
            type: 'POST',
            url: '<?= base_url('get_package_info') ?>',
            data: {package_id, package_type, company_id},
            dataType: "json",
            success: function (result) {
                $('#billing_cycle').html(result.package_form_group);
                $('#package_info').html(result.package_details);
                $('#package_name').html(result.package_info.name);
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
            }
        });
    }
</script>
