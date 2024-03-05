<?php $features_heading_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'review_heading'),false);
$features_card_info = get_old_result('tbl_saas_all_section_area', array('type' => 'reviews', 'status' => 1));
?>
<section class="section bg-light"
         style="background: url('<?= base_url() ?>/modules/saas/assets/images/shape2.png') center center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h6>
                        <?= (!empty($features_heading_info->name) ? $features_heading_info->name : '') ?>
                    </h6>
                    <h4 class="title mb-4">
                        <?= (!empty($features_heading_info->title) ? $features_heading_info->title : '') ?>
                    </h4>
                    <p class="text-muted para-desc mx-auto mb-0">
                        <?= (!empty($features_heading_info->description) ? $features_heading_info->description : '') ?>
                    </p>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row justify-content-center">
            <div class="col-lg-12 mt-4">
                <div class="tns-outer" id="tns1-ow">
                    <div id="tns1-mw" class="tns-ovh">
                        <div class="tns-inner" id="tns1-iw">
                            <div class="tiny-three-item  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal"
                                 id="tns1" style="transform: translate3d(-33.3333%, 0px, 0px);">
                                <?php

                                foreach ($features_card_info as $key => $v_card_info) {
                                    $rating = (!empty($v_card_info->title_2) ? min($v_card_info->title_2, 5) : 0);
                                    ?>


                                    <div class="tiny-slide tns-item" id="tns1-item_<?php echo $key; ?>"
                                         aria-hidden="true" tabindex="-1">
                                        <div class="d-flex client-testi m-1">
                                            <img src="<?= (!empty($v_card_info->image) ? base_url($v_card_info->image) : '') ?>"
                                                 class="avatar avatar-small client-image rounded shadow" alt="">
                                            <div class="card flex-1 content p-3 shadow rounded position-relative">
                                                <ul class="list-unstyled mb-0">

                                                    <?php
                                                    for ($i = 1; $i <= $rating; $i++) {
                                                        ?>
                                                        <li class="list-inline-item"><i
                                                                    class="text-warning mdi <?= ($i == $rating ? 'mdi-star-half' : 'mdi-star') ?>"></i>
                                                        </li>
                                                        <?php
                                                    }

                                                    ?>

                                                </ul>
                                                <p class="text-muted mt-2">
                                                    <?= (!empty($v_card_info->description) ? '" ' . $v_card_info->description . ' "' : '') ?>
                                                </p>
                                                <h6 class="text-primary">
                                                    <?= (!empty($v_card_info->title) ? ' - ' . $v_card_info->title : '') ?>
                                                    <?php
                                                    if (!empty($v_card_info->designation)) {
                                                        echo '<small class="text-muted">' . $v_card_info->designation . '</small>';
                                                    }
                                                    ?>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>

                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</section>