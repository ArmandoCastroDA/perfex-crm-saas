<section class="bg-invoice d-table w-100 bg-primary">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-12 text-center">
                <div class="pages-heading title-heading">
                    <?php $features_heading_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'gallery_heading'),false) ?>
                    <h2 class="text-white title-dark"><?= !empty($features_heading_info->title) ? $features_heading_info->title : 'Our Gallery' ?></h2>
                    <p class="text-white-50 para-desc mb-0 mx-auto"><?= !empty($features_heading_info->description) ? $features_heading_info->description : 'In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content.' ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section pt-5 bg-light">
    <div class="container">
        <div class="row mt-4">
            <?php $gallery_he_info = get_old_order_by('tbl_saas_all_section_area', array('type' => 'gallery', 'status' => 1), null, null, 1);
            ?>

            <div class="col-md-6 p-2 ">
                <div class="gallery feature-primary feature-clean position-relative overflow-hidden rounded-md">
                    <img src="<?= base_url() ?><?= $gallery_he_info[0]->image ?: '' ?>"
                         class="img-fluid" alt="">
                    <div class="bg-overlay bg-linear-gradient-2"></div>
                    <div class="position-absolute bottom-0 end-0 start-0 m-4 mt-0">
                        <a href="<?= $gallery_he_info[0]->link ?: '' ?>"
                           class="d-flex justify-content-between align-items-center">
                            <span>
                                <span class="d-block title text-white title-dark fs-5 fw-semibold"><?= $gallery_he_info[0]->title ?: '' ?></span>
                                <span class="fs-6 text-white-50 d-block"><?= $gallery_he_info[0]->color ?: '' ?> Items</span>
                            </span>

                            <i class="uil uil-arrow-up-right text-white title-dark fs-4"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <?php $gallery_card_info = get_old_order_by('tbl_saas_all_section_area', array('type' => 'gallery', 'status' => 1), null, null, 6);
                    foreach ($gallery_card_info as $key => $v_card_info) {
                        if ($key++) {
                            # code...
                            ?>
                            <div class="col-6">
                                <div class="row">

                                    <div class="col-12 p-2">
                                        <div class="gallery feature-primary feature-clean position-relative overflow-hidden rounded-md">
                                            <img src="<?= base_url() ?><?= $v_card_info->image ?: '' ?>"
                                                 class="img-fluid" alt="">
                                            <div class="bg-overlay bg-linear-gradient-2"></div>
                                            <div class="position-absolute bottom-0 end-0 start-0 m-2 m-md-4 mt-0">
                                                <a href="<?= $v_card_info->link ?: '' ?>"
                                                   class="d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <span class="d-block title text-white title-dark fs-5 fw-semibold"><?= $v_card_info->title ?: '' ?></span>
                                                        <span class="fs-6 text-white-50 d-block"><?= $v_card_info->color ?: '' ?> Items</span>
                                                    </span>

                                                    <i class="uil uil-arrow-up-right text-white title-dark fs-4"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        <?php }
                    } ?>
                </div>
            </div>
            <div class="col-12 mt-4">
                <div class="text-center">
                    <a href="" class="btn btn-link primary fw-semibold mb-0">See More Categories <span
                                class="h5 mb-0 ms-1"><i class="uil uil-arrow-right align-middle"></i></span></a>
                </div>
            </div>
        </div>
    </div>
</section>