<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <?php $features_heading_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'features'),false) ?>

                <div class="section-title text-center mb-4 pb-2">
                    <h4 class="title mb-3"><?= $features_heading_info->title ?: '' ?></h4>

                    <p class="text-muted para-desc mx-auto mb-3"><?= $features_heading_info->description ?: '' ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php $features_card_info = get_old_order_by('tbl_saas_all_section_area', array('type' => 'features', 'status' => 1), null, null, 6);
            foreach ($features_card_info as $key => $v_card_info) {
                ?>
                <div class="col-md-4 col-12">
                    <div class="features text-center">

                        <div class="image position-relative d-inline-block">
                            <?php if (!empty($v_card_info->image ?: '')) { ?>
                                <img style="width: 45px; height:45px"
                                     src="<?= base_url() . $v_card_info->image ? $v_card_info->image : '' ?>" alt="">
                            <?php } else { ?>
                                <i class="<?= $v_card_info->icons ?: '' ?> h2 text-primary"></i>
                            <?php } ?>
                        </div>

                        <div class="content mt-4">
                            <h5><?= $v_card_info->title ?: '' ?></h5>
                            <p class="text-muted mb-3"><?= $v_card_info->description ?: '' ?></p>
                        </div>

                    </div>

                </div>
            <?php } ?>
            <div class="mt-4 pt-2 text-center">
                <a href="<?= base_url() . 'frontcms/features' ?>" class="btn btn-primary">See More <i
                            class="mdi mdi-arrow-right"></i></a>
            </div>
        </div>

    </div>
</section>

