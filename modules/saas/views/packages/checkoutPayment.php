<?php
$id = null;
$frequency = 'monthly';
echo form_open(base_url('saas/gb/update_company_packages/' . $id), array('id' => 'checkoutPayment', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <h4 class="panel-title"><?= _l('payment') . '  ' . _l('summery') . ' - ' ?> <span
                    id="plan_name"><?= $package_info->name ?></span></h4>
    </div>
    <div class="panel-body form-horizontal">
        <div class="">
            <div class="col-lg-7 col-md-7 ">
                <div class="">
                    <?php
                    $super_admin = super_admin_access();
                    if (!empty($super_admin)) { ?>
                        <div class="form-group">
                            <label for="discount_type"
                                   class="control-label "><?= _l('select') . ' ' . _l('company') ?>
                                <span
                                        class="required">*</span></label>
                            <div class="">
                                <select name="companies_id" class="selectpicker m0"
                                        required
                                        data-width="100%"
                                        data-none-selected-text="<?php echo lang('select') . ' ' . _l('company'); ?>"
                                        data-live-search="true">
                                    <option value=""></option>
                                    <?php
                                    $all_subscriber = get_old_order_by('tbl_saas_companies', array('for_seed' => NULL), 'id');
                                    if (!empty($all_subscriber)) {
                                        foreach ($all_subscriber as $v_subscriber) { ?>
                                            <option value="<?php echo $v_subscriber->id; ?>"
                                                    data-subtext="<?php echo lang('domain') . ':' . $v_subscriber->domain . ' ' . _l('status') . ':' . _l($v_subscriber->status) . ' ' . _l('trial_period') . ':' . _l($v_subscriber->is_trial) . '...'; ?>"><?php echo $v_subscriber->name . '(' . $v_subscriber->email . ')'; ?></option>
                                        <?php } ?>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <?php } else {
                        ?>
                        <input type="hidden" name="company_id" id="company_id"
                               value="<?= !empty($subs_info) ? $subs_info->id : '' ?>">
                    <?php } ?>
                    <div class="form-group">
                        <label for="discount_type"
                               class="control-label "><?= _l('select') . ' ' . _l('package') ?>
                            <span class="required">*</span></label>
                        <div class="">
                            <select name="package_id" onchange="get_package_info(this.value)"
                                    class="selectpicker m0"
                                    data-width="100%"
                                    required
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

                </div>
                <div class="col-md-3 pull-right ">
                    <button type="submit" class="btn btn-success btn-block btn-lg"><?= _l('update') ?></button>
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
        $("#checkoutPayment").appFormValidator();
        var package_id = $('[name="package_id"]').val();
        // if package_id is not empty then trigger onchange event
        if (package_id != '') {
            get_package_info(package_id, '<?= $frequency?>_price');
        }
    });

    function get_package_info(package_id, package_type = 'monthly_price', company_id = '') {
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
