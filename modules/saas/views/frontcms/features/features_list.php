<section class="bg-invoice d-table w-100 bg-primary">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-12 text-center">
                <div class="pages-heading title-heading">
                    <?php $features_heading_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'features'),false) ?>
                    <h2 class="text-white title-dark"><?= !empty($features_heading_info->title) ? $features_heading_info->title : 'Features designed for you' ?></h2>
                    <p class="text-white-50 para-desc mb-0 mx-auto"><?= !empty($features_heading_info->description) ? $features_heading_info->description : 'We believe we have created the most efficient SaaS landing page for your users. Landing page with features that will convince you to use it for your SaaS business.' ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section pt-5 bg-light">
    <div class="container">
        <div class="row">
            <?php $features_card_info = get_old_result('tbl_saas_all_section_area', array('type' => 'features', 'status' => 1));
            foreach ($features_card_info as $key => $v_card_info) {
                ?>
                <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                    <div class="card features feature-clean explore-feature p-4 px-md-3 border-0 rounded-md shadow text-center">
                        <div class="icons text-primary text-center mx-auto">
                            <?php if (!empty($v_card_info->image ?: '')) { ?>
                                <img style="width: 45px; height:45px"
                                     src="<?= base_url() . $v_card_info->image ? $v_card_info->image : '' ?>" alt="">
                            <?php } else { ?>
                                <i class="<?= $v_card_info->icons ?: '' ?> d-block rounded h3 mb-0"></i>
                            <?php } ?>
                        </div>

                        <div class="card-body p-0 content">
                            <h5 class="mt-4"><a href="javascript:void(0)"
                                                class="title text-dark"><?= $v_card_info->title ?: '' ?></a>
                            </h5>
                            <p class="text-muted"><?= $v_card_info->description ?: '' ?></p>

                            <a href="<?= $v_card_info->link ?: '' ?>"
                               class="text-primary"><?= $v_card_info->name ?: '' ?> <i
                                        class="uil uil-angle-right-b align-middle"></i></a>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
    <?php
    $row_revers = '';
    $ms_lg_4 = '';
    $all_features_collaborates = get_old_result('tbl_saas_all_section_area', array('type' => 'features_collaborate', 'status' => 1));
    if (!empty($all_features_collaborates)) {
        foreach ($all_features_collaborates as $key => $features_collaborates) {
            if ($key % 2 == 0) {
                $row_revers = '';
                $ms_lg_4 = 'ms-lg-4';

            } else {
                $row_revers = 'flex-row-reverse';
                $ms_lg_4 = '';
            }
            ?>
            <div class="container mt-100 mt-60">
                <div class="row align-items-center <?= $row_revers ?>">

                    <div class="col-lg-5 col-md-6 float-end">
                        <img src="<?= base_url() ?><?= $features_collaborates->image ?: '' ?>"
                             class="img-fluid rounded-md shadow-lg" alt="">
                    </div>

                    <div class="col-lg-7 col-md-6 mt-4 pt-2 mt-sm-0 pt-sm-0 ">
                        <div class="section-title text-md-start text-center <?= $ms_lg_4 ?>">
                            <h4 class="title mb-4"><?= $features_collaborates->title ?: '' ?></h4>
                            <p class="text-muted mb-0 para-desc"><?= $features_collaborates->description ?: '' ?></p>

                            <div class="d-flex align-items-center text-start mt-4 pt-2">
                                <?php if (!empty($features_collaborates->icons)) { ?>
                                    <div class="text-primary h4 mb-0 me-3 p-3 rounded-md shadow bg-white">
                                        <i class="<?= $features_collaborates->icons ?: '' ?>"></i>
                                    </div>
                                <?php } ?>

                                <?php if (!empty($features_collaborates->name)) { ?>
                                    <div class="flex-1">
                                        <a href="<?= $features_collaborates->link ?: '#' ?>"
                                           class="text-dark h6"><?= $features_collaborates->name ?: '' ?></a>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="d-flex align-items-center text-start mt-4">
                                <?php if (!empty($features_collaborates->icons_2)) { ?>
                                    <div class="text-primary h4 mb-0 me-3 p-3 rounded-md shadow bg-white">
                                        <i class="<?= $features_collaborates->icons_2 ?: '' ?>"></i>
                                    </div>
                                <?php } ?>

                                <?php if (!empty($features_collaborates->button_name_2)) { ?>
                                    <div class="flex-1">
                                        <a href="<?= $features_collaborates->button_link_2 ?: '#' ?>"
                                           class="text-dark h6"><?= $features_collaborates->button_name_2 ?: '' ?></a>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="d-flex align-items-center text-start mt-4">
                                <?php
                                if ($features_collaborates->icons_3) { ?>
                                    <div class="text-primary h4 mb-0 me-3 p-3 rounded-md shadow bg-white">
                                        <i class="<?= $features_collaborates->icons_3 ?: '' ?>"></i>
                                    </div>
                                <?php }
                                if ($features_collaborates->button_name_3) { ?>
                                    <div class="flex-1">
                                        <a href="<?= $features_collaborates->button_link_3 ?: '#' ?>"
                                           class="text-dark h6"><?= $features_collaborates->button_name_3 ?: '' ?></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php }
    }
    ?>
</section>