<section class="bg-invoice d-table w-100 bg-primary">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-12 text-center">
                <div class="pages-heading title-heading">
                    <h2 class="text-white title-dark"><?= !empty(get_option('saas_front_pricing_title')) ? get_option('saas_front_pricing_title') : 'Our Pricing Rates' ?></h2>
                    <p class="text-white-50 para-desc mb-0 mx-auto"><?= !empty(get_option('saas_front_pricing_description')) ? get_option('saas_front_pricing_description') : 'Start working with Perfect SaaS that can provide everything you need to generate awareness, drive traffic, connect.' ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$billing_cycle = get_active_frequency();
?>

<section class="section pt-3">
    <div class="container ">
        <div class="row align-items-center">
            <div class="col-12 mt-4 pt-2">
                <div class="text-center">
                    <ul class="nav nav-pills rounded-pill justify-content-center d-inline-block border py-1 px-2"
                        id="pills-tab" role="tablist">
                        <?php

                        foreach ($billing_cycle as $key => $value) {
                            $offer = '';
                            $coupons = get_coupon_by_package_type($value['name']);
                            if (!empty($coupons) && $coupons->package_type == $value['name']) {
                                // coupon type 1 is percentage and 2 is fixed amount
                                if ($coupons->type == 1) {
                                    $offer = $coupons->amount . '%' . ' ' . _l('off');
                                } else {
                                    $offer = $coupons->amount . ' ' . _l('off');
                                }
                            }
                            ?>
                            <button type="button"
                                    onclick="get_price(this)"
                                    class="btn get_price <?= $value['class'] ?> rounded-pill"
                                    name="<?= $value['value'] ?>"><?= _l($value['name']) ?>
                                <?php
                                if (!empty($offer)) {
                                    ?>
                                    <span class="badge rounded-pill bg-danger"><?= $offer ?></span>
                                    <?php
                                }
                                ?>
                            </button>
                            <?php
                        }
                        ?>
                    </ul>
                </div>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="Month" role="tabpanel" aria-labelledby="Monthly">
                        <div class="row">
                            <?php
                            $symbol = get_base_currency()->symbol;
                            $all_packages = $this->saas_model->get_packages();
                            if ($all_packages) {
                                foreach ($all_packages as $package) {
                                    // check if coupon is there and package type is monthly then apply coupon
                                    $package = apply_coupon($package);


                                    ?>
                                    <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                                        <div class="card pricing-rates business-rate shadow border-0 rounded <?= $package->recommended == 'Yes' ? '' : ' bg-light' ?>">
                                            <?php
                                            if ($package->recommended == 'Yes') {
                                                ?>
                                                <div class="ribbon ribbon-right ribbon-warning overflow-hidden"><span
                                                            class="text-center d-block shadow small h6">Best</span>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <div class="card-body">
                                                <h6 class="py-2 px-4 d-inline-block bg-soft-primary h6 mb-4 text-primary rounded-lg"><?= $package->name ?></h6>
                                                <?php
                                                if (!empty($package->monthly_offer)) {
                                                    ?>
                                                    <div class="d-flex monthly_price">
                                                        <span class="price h1 mb-0"><?= display_money($package->monthly_offer) ?></span>
                                                        <span class="h4 align-self-end mb-1">/mo</span>
                                                    </div>
                                                    <del class="d-flex monthly_price  mb-4">
                                                        <span class="price mb-0"><?= display_money($package->monthly_price) ?></span>
                                                        <span class="align-self-end mb-1">/mo</span>
                                                    </del>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div class="d-flex mb-4 monthly_price">
                                                        <span class="price h1 mb-0"><?= display_money(display_money($package->monthly_price)) ?></span>
                                                        <span class="h4 align-self-end mb-1">/mo</span>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                                <?php
                                                if (!empty($package->yearly_offer)) {
                                                    ?>
                                                    <del class="d-flex yearly_price d-none">
                                                        <span class="price mb-0"><?= display_money($package->yearly_price) ?></span>
                                                        <span class="align-self-end mb-1">/yr</span>
                                                    </del>
                                                    <div class="d-flex mb-4 yearly_price d-none">
                                                        <span class="price h1 mb-0"><?= display_money($package->yearly_offer) ?></span>
                                                        <span class="h4 align-self-end mb-1">/yr</span>
                                                    </div>

                                                    <?php
                                                } else {
                                                    ?>
                                                    <div class="d-flex mb-4 yearly_price d-none">
                                                        <span class="price h1 mb-0"><?= display_money($package->yearly_price) ?></span>
                                                        <span class="h4 align-self-end mb-1">/yr</span>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <div class="d-flex mb-4 lifetime_price d-none">
                                                    <span class="price h1 mb-0"><?= display_money($package->lifetime_price) ?></span>
                                                    <span class="h4 align-self-end mb-1">/lt</span>
                                                </div>


                                                <ul class="list-unstyled mb-0 ps-0">
                                                    <?= saas_packege_list($package, 6, true) ?>
                                                </ul>


                                                <div class="d-flex  align-center justify-content-center">
                                                    <span onclick="packageDetails(<?= $package->id ?>)"
                                                          style="cursor: pointer"
                                                          class="text-center text-primary mt-2"><?= lang('see_details') ?></span>
                                                </div>
                                                <div class="d-flex align-center justify-content-center">
                                                    <?php
                                                    $saas_buy_now_page = ConfigItems('saas_buy_now_page');
                                                    if (!empty($saas_buy_now_page) && $saas_buy_now_page == 'new_page') { ?>
                                                        <a href="<?= base_url('register/' . $package->id) ?>"
                                                           class="btn btn-primary mt-3"><?= _l('buy_now') ?></a>
                                                    <?php } else { ?>
                                                        <button type="button" value="<?= $package->id ?>"
                                                                name="package_id"
                                                                class="btn btn-primary mt-3"
                                                                onclick="choosePlan(<?= $package->id ?>)">
                                                            <?= _l('buy_now') ?>
                                                        </button>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            }
                            ?>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

</section>
<div class="modal fade" id="package_details_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header">
                <span class="modal-title" id="myModalLabel"><?= lang('package_details') ?></span>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"
                        aria-label="Close">
                    <i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <div class="modal-body " id="package_details">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('close') ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-xl" id="modal-xl" tabindex="-1" role="dialog"
     aria-labelledby="bd-example-modal-xl">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content rounded shadow border-0" id="modal-xl-con">


        </div>

    </div>
</div>


<script type="text/javascript">
    'use strict';

    function get_price(value) {
        var price = value.name;
        $('.get_price').removeClass('btn-primary');
        $(value).addClass('btn-primary');
        $('.monthly_price').addClass('d-none');
        $('.yearly_price').addClass('d-none');
        $('.lifetime_price').addClass('d-none');
        $('.' + price).removeClass('d-none');
    }

    // get package details by ajax and show in modal
    function packageDetails(package_id) {
        $.ajax({
            url: "<?= base_url() ?>saas/gb/get_package_info/",
            type: "POST",
            data: {package_id: package_id, front: true},
            dataType: "json",
            success: function (data) {
                $('#package_details').html(data.package_details);
                $('#package_details_modal').modal('show');

            }
        });
    }

    function choosePlan(package_id) {
        var formData = {
            'package_id': package_id,
        };
        $.ajax({
            url: "<?= base_url() ?>saas/gb/signup_company/",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (data) {
                $('#modal-xl-con').html(data.subview);
                $('#modal-xl').modal('show');
            }
        });
    }

    function getPackageData(package_id, only_details = false) {
        $.ajax({
            url: "<?= base_url() ?>saas/gb/get_package_info/",
            type: "POST",
            data: {package_id: package_id},
            dataType: "json",
            success: function (data) {
                if (only_details) {
                    $('#package_details').html(data.package_details);
                    $('#package_details_modal').modal('show');
                } else {
                    $('#modal-xl-con').html(data.package_details);
                    $('#modal-xl').modal();
                }

            }
        });
    }
</script>