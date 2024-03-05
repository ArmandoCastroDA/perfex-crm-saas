<!-- Slider-area Start -->
<link type="text/css" id="theme-opt" rel="stylesheet"
      href="<?= module_dir_url(SaaS_MODULE) ?>assets/css/swiper.min.css">


<section class="d-table w-100 home-slider position-relative" id="home">
    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $slider_info = get_old_result('tbl_saas_front_slider', array('status' => 1));
            if (!empty($slider_info)) {
                foreach ($slider_info as $key => $sliders) {
                    if ($sliders->slider_bg != '') {
                        $slider_bg = $sliders->slider_bg;
                    } else {
                        $slider_bg = '';
                    }
                    if ($sliders->slider_img != '') {
                        $slider_img = $sliders->slider_img;
                    } else {
                        $slider_img = '';
                    }
                    ?>
                    <div class="carousel-item <?php if ($key == 0) {
                        echo 'active';
                    } ?>" data-bs-interval="300000">
                        <div class="bg-home-75vh d-flex align-items-center"
                             style="background: url('<?php echo base_url() . $slider_bg; ?>') center center;">
                            <div class="bg-overlay"></div>
                            <div class="container">
                                <div class="row mt-5">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="title-heading mb-md-5 pb-md-5">
                                            <h4 class="text-white-50 "><?php if ($sliders->subtitle != '') {
                                                    echo($sliders->subtitle);
                                                } ?></h4>
                                            <?php if ($sliders->title != '') { ?>

                                                <h4 class="text-white mb-3 heading title-dark">
                                                    <?php echo($sliders->title); ?>
                                                </h4>
                                            <?php } ?>
                                            <?php if ($sliders->description != '') { ?>
                                                <p class="para-desc text-white-50">
                                                    <?php echo trim(strip_tags($sliders->description)); ?>
                                                </p>
                                            <?php } ?>

                                            <div class="mt-4 pt-2">
                                                <?php if ($sliders->button_text_1 != '' || $sliders->button_text_2 != '') { ?>
                                                    <div class="slider-button">
                                                        <?php if ($sliders->button_text_1 != '' || $sliders->button_link_1 != '') { ?>
                                                            <a href="<?= !empty($sliders->button_link_1) ? $sliders->button_link_1 : '#' ?>"
                                                               class="btn btn-light mb-3"
                                                               style="margin-right: 20px;">
                                                                <?php if ($sliders->button_icon_1 != '') { ?>
                                                                    <i class="<?= $sliders->button_icon_1 ?>"></i>
                                                                <?php } ?>
                                                                <?= !empty($sliders->button_text_1) ? $sliders->button_text_1 : '' ?>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($sliders->button_text_2 != '' || $sliders->button_link_2 != '') { ?>
                                                            <a href="<?= !empty($sliders->button_link_2) ? $sliders->button_link_2 : '#' ?>"
                                                               class="btn btn-primary slider-btn mb-3">
                                                                <?php if ($sliders->button_icon_2 != '') { ?>
                                                                    <i class="<?= $sliders->button_icon_2 ?>"></i>
                                                                <?php } ?>
                                                                <?= !empty($sliders->button_text_2) ? $sliders->button_text_2 : '' ?>
                                                            </a>

                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <img class="slider-img" src="<?php echo base_url() . $slider_img; ?>"
                                             alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>