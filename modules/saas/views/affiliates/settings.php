<?php
echo form_open(admin_url('saas/affiliates/settings'), ['id' => 'settings-form']);
?>
<div class="panel_s">
    <div class="panel-heading">
        <h4 class="">
            <?php echo _l('affiliates') . ' ' . _l('commission') . ' ' . _l('settings'); ?>
        </h4>
    </div>
    <!-- ************** general *************-->
    <div class="panel-body">
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox checkbox-primary">
                    <input type="checkbox"
                           name="enable_affiliate" <?php if (get_option('enable_affiliate') == 'TRUE') {
                        echo 'checked';
                    }
                    ?>
                           id="enable_affiliate">
                    <label>
                        <?php echo _l('enable_affiliate'); ?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <?php echo _l('commission') . ' ' . _l('amount'); ?>
                </label>
                <div class="input-group">
                    <div class="input-group-addon"><span class="input-group-text pointer" id="commission_type">
                                <?php if (get_option('affiliate_commission_type') == 'percentage') {
                                    echo '%';
                                } else {
                                    echo '$';
                                } ?>
                            </span></div>
                    <input name="affiliate_commission_amount"
                           value="<?php echo get_option('affiliate_commission_amount'); ?>"
                           id="affiliate_commission_amount" type="text" class="form-control"
                    >
                </div>
            </div>
            <?php
            $rules = [
                'no_payment_required' => _l('no_payment_required_will_get_commission_according_to_affiliate_rule'),
                'only_first_subscription_payment' => _l('only_first_subscription_payment'),
                'every_payment_of_the_subscription' => _l('every_payment_of_the_subscription'),
            ];
            ?>

            <div class="form-group">
                <label for="payment_rules_for_affiliates">
                    <?php echo _l('payment_rules_for_affiliates'); ?>
                </label>
                <select name="payment_rules_for_affiliates" id="payment_rules_for_affiliates"
                        class="selectpicker" data-width="100%"
                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                    <?php foreach ($rules as $key => $rule) { ?>
                        <option
                            <?= (get_option('payment_rules_for_affiliates') == $key) ? 'selected' : '' ?>
                                value="<?php echo $key; ?>"><?php echo $rule; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>
                    <?php echo _l('withdrawal') . ' ' . _l('payment') . ' ' . _l('method'); ?>
                </label>
                <select class="selectpicker"
                        data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                        name="withdrawal_payment_method[]" id="withdrawal_payment_method"
                        data-actions-box="true" multiple="true"
                        data-width="100%"
                        data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                    <?php foreach ($payment_modes as $mode) {
                        $selected = '';
                        $inv_modes = get_option('withdrawal_payment_method');
                        if (isset($inv_modes)) {
                            if ($inv_modes) {
                                $inv_modes = unserialize($inv_modes);
                                if (is_array($inv_modes)) {
                                    foreach ($inv_modes as $_allowed_payment_mode) {
                                        if ($_allowed_payment_mode == $mode['id']) {
                                            $selected = ' selected';
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($mode['selected_by_default'] == 1) {
                                $selected = ' selected';
                            }
                        } ?>
                        <option value="<?php echo $mode['id']; ?>" <?php echo $selected; ?>>
                            <?php echo $mode['name']; ?></option>
                        <?php
                    } ?>
                </select>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group clearfix tw-mt-2">
                <label for="affiliate_commission_type" class="control-label pull-left tw-mr-4">
                    <?php echo _l('commission') . ' ' . _l('type'); ?>
                </label>
                <div class="pull-left">
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" name="affiliate_commission_type"
                               class="commission_type"
                            <?= (get_option('affiliate_commission_type') == 'fixed') ? 'checked' : '' ?>
                               value="fixed" checked
                        >
                        <label><?= _l('fixed') ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" name="affiliate_commission_type"
                               class="commission_type"
                            <?= (get_option('affiliate_commission_type') == 'percentage') ? 'checked' : '' ?>
                               value="percentage">
                        <label><?= _l('percentage') ?></label>
                    </div>
                </div>
            </div>
            <?php
            $rules = [
                'only_first_subscription' => _l('only_first_subscription'),
                'every_subscription' => _l('every_subscription'),
            ];
            ?>
            <div class="form-group">
                <label for="payment_rules_for_affiliates">
                    <?php echo _l('affiliate_rule'); ?>
                </label>
                <select name="affiliate_rule" id="affiliate_rule"
                        class="selectpicker" data-width="100%"
                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                    <?php foreach ($rules as $key => $rule) { ?>
                        <option
                            <?php echo (get_option('affiliate_rule') == $key) ? 'selected' : '' ?>
                                value="<?php echo $key; ?>"><?php echo $rule; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>
                    <?php echo _l('minimum') . ' ' . _l('payout') . ' ' . _l('amount'); ?>
                </label>
                <div class="input-group">
                    <div class="input-group-addon"><span class="input-group-text pointer">$</span></div>
                    <input name="minimum_payout_amount"
                           value="<?php echo get_option('minimum_payout_amount'); ?>"
                           id="minimum_payout_amount" type="text" class="form-control"
                    >
                </div>
            </div>

            <div class="btn-bottom-toolbar text-right">
                <button type="submit" class="btn btn-primary">
                    <?php echo _l('settings_save'); ?>
                </button>
            </div>

        </div>
    </div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    'use strict';
    $(document).ready(function () {
        $('input[name="affiliate_commission_type"]').on('click', function () {
            var commission_type = $(this).val();
            if (commission_type == 'percentage') {
                $('#commission_type').text('%');
            } else {
                $('#commission_type').text('$');
            }
        });
    });
</script>
