<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$company_name = ConfigItems('saas_companyname') ? ConfigItems('saas_companyname') : 'Perfect SaaS';
$affiliate_commission_amount = ConfigItems('affiliate_commission_amount') ? ConfigItems('affiliate_commission_amount') : 0;
$affiliate_commission_type = ConfigItems('affiliate_commission_type') == 'percentage' ? '%' : 'credits';
$affiliate_rule = ConfigItems('affiliate_rule') == 'only_first_subscription' ? 'first subscription' : 'each subscriptions';
$min_amount = ConfigItems('minimum_payout_amount') ? ConfigItems('minimum_payout_amount') : 0;
$referralUrl = base_url('register?via=' . $user->referral_link);
$referral = base_url('register?via=');
?>
<style type="text/css">
    .d-flex {
        display: flex !important;
    }

    .justify-content-between {
        justify-content: space-between !important;
    }

    .align-items-center {
        align-items: center !important;
    }
</style>

<div class="col-lg-12">
    <div class="">
        <p class="text-muted para-desc mx-auto">
            <?= _l('affiliate_program_description', $company_name) ?>
            <span
                    class="text-success fs-14"><?= $affiliate_commission_amount ?> <?= $affiliate_commission_type ?>
                    </span>
            <?= _l('affiliate_program_description_2', $affiliate_rule) ?>

            <a href="<?= BaseUrl('affiliate') ?>" class="">
                <?= _l('more_about_affiliate_program') ?>
            </a>
        </p>

        <div class="">
            <p class="text-muted mb-0">
                <?= _l('your_affiliate_link_is') ?>
            </p>
            <?= form_open(base_url('affiliate/settings'), ['id' => 'affiliate-link-form']) ?>
            <div class="input-group hide tw-mb-6 "
                 id="affiliate_link_edit">
                <div class="input-group-addon">
                    <span class="input-group-text p-2">
                                        <?= $referral ?>
                                    </span>
                </div>

                <input type="text" class="form-control" name="referral_link"
                       style="    height: 36px;"
                       value="<?= $user->referral_link ?>">
                <div class="input-group-addon" style="background: none;padding:0">
                    <button class="btn btn-warning" type="submit">
                        <?= _l('update') ?>
                    </button>
                    <button class="btn btn-primary" type="button"
                            onclick="cancelEditAffiliateLink()">
                        <?= _l('cancel') ?>
                    </button>
                </div>
            </div>
        </div>
        <?= form_close() ?>

        <div class="input-group tw-mb-6 " id="affiliate_link">
            <input type="text" class="form-control"
                   id="affiliate-link"
                   style="    height: 36px;"
                   onfocus="this.select();" onmouseup="return false;"
                   value="<?= $referralUrl ?>" readonly>

            <div class="input-group-addon" style="background: none;padding:0">
                <button class="btn btn-warning btn-sm ml-0 mr-0" type="button"
                        onclick="copyToClipboard('<?= $referralUrl ?>');">
                    <?= _l('copy') ?>
                </button>
                <button class="btn btn-primary btn-sm" type="button"
                        onclick="editAffiliateLink()">
                    <?= _l('edit') ?>
                </button>
            </div>
        </div>
    </div>


    <div class="row">
        <?php
        foreach ($states as $state) { ?>

            <div class="col-md-3 col-xl-3">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="d-flex">
                            <div class="flex-grow-1"><p
                                        class="text-muted fw-medium"><?= _l($state['name']); ?></p>
                                <h4 class="mb-0">
                                    <?php echo $state['count']; ?></h4></div>
                            <div class="avatar-sm  align-self-center ms-3">
                                    <span
                                            class="avatar-title bg-<?= $state['color']; ?> rounded-circle"><i
                                                class="<?= $state['icon']; ?>"></i>
                                            </span></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="row mt-5">
        <div class="col-sm-6">
            <div class="panel_s">
                <div class="panel-body">
                    <?php $this->load->view('affiliates/user/commissionsTable') ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel_s">
                <div class="panel-body">
                    <?php $this->load->view('affiliates/user/payoutsTable') ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    'use strict';

    // click to data-bs-toggle="modal" data-bs-target="#LoginForm" redirect to affiliate/payouts
    $(document).on('click', '.btn-primary[data-bs-target="#LoginForm"]', function () {
        window.location.href = '<?= BaseUrl('affiliate/payouts') ?>';
    });

    function copyToClipboard(text) {
        var dummy = document.createElement('input');
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);
        alert("Copied to clipboard");
    }

    function editAffiliateLink() {
        // remove hide class from affiliate_link_edit id and add hide class to affiliate_link id
        document.getElementById("affiliate_link_edit").classList.remove("hide");
        document.getElementById("affiliate_link").classList.add("hide");
    }

    function cancelEditAffiliateLink() {
        // remove hide class from affiliate_link id and add hide class to affiliate_link_edit id
        document.getElementById("affiliate_link").classList.remove("hide");
        document.getElementById("affiliate_link_edit").classList.add("hide");
    }
</script>