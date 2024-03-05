<section class="py-5 border-bottom border-top bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <?php $features_heading_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'brand_heading'),false);
            ?>
            <div class="col-12 text-center">
                <h5>
                    <?= (!empty($features_heading_info->title) ? $features_heading_info->title : '') ?>
                </h5>
                <?= (!empty($features_heading_info->description) ? '<p class="text-muted para-desc mx-auto mb-0">' . $features_heading_info->description . '</p>' : '') ?>
            </div><!--end col-->
        </div><!--end row-->
        <div class="row mt-5 justify-content-center">
            <div class="tns-outer" id="tns2-ow">
                <div id="tns2-mw" class="tns-ovh">
                    <div class="tns-inner" id="tns2-iw">
                        <div class="tiny-six-item  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal"
                             id="tns2" style="transform: translate3d(-33.3333%, 0px, 0px);">

                            <?php $features_card_info = get_old_result('tbl_saas_all_section_area', array('type' => 'brands', 'status' => 1));
                            foreach ($features_card_info as $key => $v_card_info) {
                                ?>
                                <div class="tiny-slide tns-item" id="tns2-item_<?php echo $key; ?>"
                                     aria-hidden="true" tabindex="-1">
                                    <div class="text-center">
                                        <img src="<?= (!empty($v_card_info->image) ? base_url($v_card_info->image) : '') ?>"
                                             class=" avatar avatar-ex-sm" alt="">
                                    </div><!--end col-->
                                </div><!--end col-->
                            <?php } ?>
                        </div><!--end row-->
                    </div><!--end row-->
                </div><!--end row-->
            </div><!--end row-->
        </div><!--end row-->
    </div><!--end container-->
</section>