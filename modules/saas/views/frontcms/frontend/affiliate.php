<?php
$company_name = get_option('saas_companyname') ? get_option('saas_companyname') : 'Perfect SaaS';
$affiliate_commission_amount = get_option('affiliate_commission_amount') ? get_option('affiliate_commission_amount') : 0;
$affiliate_commission_type = get_option('affiliate_commission_type') == 'percentage' ? '%' : 'credits';
$affiliate_rule = get_option('affiliate_rule') == 'only_first_subscription' ? 'first subscription' : 'each subscriptions';
$min_amount = get_option('minimum_payout_amount') ? get_option('minimum_payout_amount') : 0;
?>
<section class="bg-invoice d-table w-100 bg-primary">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-12 text-center">
                <div class="pages-heading title-heading">
                    <h2 class="text-white title-dark"><?= $company_name ?>
                        Earn $$$</h2>
                    <p class="text-white-50 para-desc mb-0 mx-auto">
                        We are happy to announce our affiliate program. You can earn <span
                                class="text-success fs-14"><?= $affiliate_commission_amount ?> <?= $affiliate_commission_type ?></span> <?= $affiliate_rule ?>
                        of your referrals
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="bg-invoice d-table w-100 bg-light">
    <div class="row my-md-5 pt-md-3 my-4 pt-2 pb-lg-4 justify-content-center border-bottom">
        <div class="col-12 text-center">
            <div class="section-title">
                <h4 class="title mb-4">
                    <?= $company_name . ' ' . _l('affiliate_program') ?>
                </h4>
                <p class="text-muted para-desc mx-auto">
                    <?= _l('affiliate_program_front_description') ?>

                </p>
                <a href="<?= base_url('affiliate/become') ?>" class="btn btn-primary mt-4">
                    <?= _l('become_an_affiliate') ?>
                </a>
            </div>
        </div><!--end col-->
    </div>


    <div class="row my-md-5 pt-md-3 my-4 pt-2 pb-lg-4 justify-content-center ">
        <div class="col-12 text-center">
            <div class="section-title">
                <h4 class="title mb-4">
                    <?= _l('how_it_works') ?>
                </h4>
                <p class="text-muted para-desc mx-auto">
                    <?= _l('affiliate_program_description', $company_name) ?>
                    <span
                            class="text-success fs-14"><?= $affiliate_commission_amount ?> <?= $affiliate_commission_type ?>
                    </span>
                    <?= _l('affiliate_program_description_2', $affiliate_rule) ?>
                </p>
                <a href="<?= base_url('affiliate/become') ?>" class="btn btn-primary mt-4">
                    <?= _l('become_an_affiliate') ?>
                </a>
            </div>
        </div><!--end col-->
    </div>
    <div class="col-sm-6 m-auto">
        <div class="card rounded border-0 shadow ms-lg-5 justify-content-center align-items-center">
            <div class="card-body">
                <div class="content pt-2">
                    <p class="text-muted para-desc mb-4 ">
                        <?= _l('affiliate_program_rules') ?>
                    </p>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-1"><span class="text-primary h5 me-2"><i
                                        class="uil uil-check-circle align-middle"></i></span>
                            Minimum payout threshold is $<?= $min_amount ?> . Get payed for very
                            conversion.
                        </li>
                        <li class="mb-1"><span class="text-primary h5 me-2"><i
                                        class="uil uil-check-circle align-middle"></i></span>
                            <?= $affiliate_commission_amount ?> <?= $affiliate_commission_type ?> commission rate
                            on <?= $affiliate_rule ?>
                        </li>
                        <li class="mb-1"><span class="text-primary h5 me-2"><i
                                        class="uil uil-check-circle align-middle"></i></span>
                            Commissions are cleared after 40 days.
                        </li>
                        <li class="mb-1"><span class="text-primary h5 me-2"><i
                                        class="uil uil-check-circle align-middle"></i></span>
                            The payment will be made via
                            <?php
                            $payment_modes = $this->saas_model->get_payment_modes();
                            $inv_modes = get_option('withdrawal_payment_method');
                            if (isset($inv_modes)) {
                                $inv_modes = unserialize($inv_modes);
                            } else {
                                $inv_modes = [];
                            }
                            if (!empty($payment_modes)) {
                                foreach ($payment_modes as $mode) {
                                    if (!empty($inv_modes) && in_array($mode['id'], $inv_modes)) {
                                        echo $mode['name'] . ', ';
                                    }
                                }
                            }
                            ?>
                        </li>
                        <li class="mb-1"><span class="text-primary h5 me-2"><i
                                        class="uil uil-check-circle align-middle"></i></span>
                            PPC campaigns are not allowed.
                        </li>
                        <li class="mb-1"><span class="text-primary h5 me-2"><i
                                        class="uil uil-check-circle align-middle"></i></span>
                            Self-referrals are not allowed and will not be commissioned.
                        </li>
                        <li class="mb-1">
                            <span class="text-primary h5 me-2"><i
                                        class="uil uil-check-circle align-middle"></i></span>
                            <?= $company_name ?> reserves the right to change the terms of the affiliate program at
                            any time due to changing market conditions, risk of fraud, or any other factors we deem
                            relevant.
                        </li>
                    </ul>
                </div>
            </div>
            <a href="<?= base_url('affiliate/become') ?>" class="btn btn-primary mb-4 align-items-center">
                <?= _l('become_an_affiliate') ?>
            </a>
        </div>
    </div>
</section>