<section class="section pt-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <?php $features_creatives_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'creatives'),false) ?>

                <div class="section-title text-center mb-4 pb-2">
                    <h4 class="title mb-4"><?= $features_creatives_info->title ?></h4>

                    <p class="text-muted para-desc mb-0 mx-auto"><?= $features_creatives_info->description ?></p>
                </div>

            </div>
        </div>

        <div class="row">
            <?php $creatives_card_info = get_old_result('tbl_saas_all_section_area', array('type' => 'creatives'));
            foreach ($creatives_card_info as $key => $v_card_info) {
                ?>
                <div class="col-lg-4 col-md-4 col-12 mt-4 pt-2">
                    <div class="card explore-feature border-0 rounded text-center bg-white">
                        <div class="card-body py-5">
                            <div class="icon rounded-circle shadow-lg d-inline-block fs-22"><i
                                        class="<?= $v_card_info->icons ?: '-' ?>"></i></div>

                            <div class="content mt-3">
                                <h5><a class="title text-dark"
                                       href="#"><?= $v_card_info->name ?: '-' ?></a></h5>

                                <p class="text-muted small"><?= $v_card_info->designation ?: '-' ?></p>
                            </div>
                            <div class="progress-box mt-4">
                                <h6 class="title text-muted"
                                    style="text-align: initial;"><?= $v_card_info->title ?: '-' ?></h6>
                                <div class="progress">
                                    <div class="progress-bar position-relative bg-primary"
                                         style="width:<?= $v_card_info->color ? $v_card_info->color : '-' ?>%;">
                                        <div class="progress-value d-block text-muted h6"><?= $v_card_info->color ?: '-' ?>
                                            %
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-box mt-4">
                                <h6 class="title text-muted"
                                    style="text-align: initial;"><?= $v_card_info->title_2 ?: '-' ?></h6>
                                <div class="progress">
                                    <div class="progress-bar position-relative bg-primary"
                                         style="width:<?= $v_card_info->color_2 ?: '-' ?>%;">
                                        <div class="progress-value d-block text-muted h6"><?= $v_card_info->color_2 ?: '-' ?>
                                            %
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php
$this->load->view('frontcms/discovers/discovers');
?>