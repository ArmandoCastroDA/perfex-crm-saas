w<?php $abouts_heading_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'abouts'), false) ?>
<section class="bg-invoice d-table w-100 bg-primary">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="pages-heading title-heading">
                    <h2 class="text-white title-dark"> <?= (!empty($abouts_heading_info->title)) ? $abouts_heading_info->title : '' ?> </h2>
                    <p class="text-white-50 para-desc mb-0 mx-auto"><?= (!empty($abouts_heading_info->description)) ? $abouts_heading_info->description : '' ?></p>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div> <!--end container-->
</section>
<section class="section pt-5 bg-light">
    <div class="container">
        <?php $abouts_info = get_old_result('tbl_saas_all_section_area', array('type' => 'abouts'), false) ?>

        <div class="row align-items-center" id="counter">
            <div class="col-md-6">
                <img src="<?= base_url() ?><?= (!empty($abouts_info->image)) ? $abouts_info->image : '' ?>"
                     class="img-fluid"
                     alt=""/>
            </div>
            <div class="col-md-6 mt-4 pt-2 mt-sm-0 pt-sm-0">
                <div class="ms-lg-4">
                    <div class="d-flex mb-4">
                        <span class="text-primary h1 mb-0"><span class="counter-value display-1 fw-bold"
                                                                 data-target="<?= (!empty($abouts_info->color)) ? $abouts_info->color : '' ?>">
                                <?= (!empty($abouts_info->color)) ? $abouts_info->color : '' ?>
                            </span>+</span>
                        <span class="h6 align-self-end ms-2">
                            <?= _l('years') ?>
                            <br/>
                            <?= _l('experience') ?>
                        </span>
                    </div>
                    <div class="section-title">
                        <h4 class="title mb-4"><?= (!empty($abouts_info->title)) ? $abouts_info->title : '' ?></h4>
                        <p class="text-muted">
                            <?= (!empty($abouts_info->description)) ? $abouts_info->description : '' ?>
                        </p>
                        <a href="javascript:void(0)"
                           class="btn btn-primary mt-3"><?= (!empty($abouts_info->name)) ? $abouts_info->name : '' ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <?php $abouts_work_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'about_works'), false) ?>
            <div class="col-12">
                <div class="section-title text-center mb-4 pb-2">
                    <h6 class="text-primary"><?= (!empty($abouts_work_info->name)) ? $abouts_work_info->name : '' ?></h6>
                    <h4 class="title mb-4"><?= (!empty($abouts_work_info->title)) ? $abouts_work_info->title : '' ?></h4>
                    <p class="text-muted para-desc mx-auto mb-0">
                        <?= (!empty($abouts_work_info->description)) ? $abouts_work_info->description : '' ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mt-4 pt-2">
                <?php $abouts_discussion_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'discussion'), false) ?>

                <div class="card abouts feature-clean work-process bg-transparent process-arrow border-0 text-center">
                    <div class="icons text-primary text-center mx-auto">
                        <i class="<?= (!empty($abouts_discussion_info->icons)) ? $abouts_discussion_info->icons : '' ?> d-block rounded h3 mb-0"></i>
                    </div>

                    <div class="card-body">
                        <h5 class="text-dark"><?= (!empty($abouts_discussion_info->title)) ? $abouts_discussion_info->title : '' ?></h5>
                        <p class="text-muted mb-0">
                            <?= (!empty($abouts_discussion_info->description)) ? $abouts_discussion_info->description : '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-md-5 pt-md-3 mt-4 pt-2">
                <?php $abouts_strategy_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'strategy'), false) ?>

                <div class="card abouts feature-clean work-process bg-transparent process-arrow border-0 text-center">
                    <div class="icons text-primary text-center mx-auto">
                        <i class="<?= (!empty($abouts_strategy_info->icons)) ? $abouts_strategy_info->icons : '' ?> d-block rounded h3 mb-0"></i>
                    </div>

                    <div class="card-body">
                        <h5 class="text-dark"><?= (!empty($abouts_strategy_info->title)) ? $abouts_strategy_info->title : '' ?></h5>
                        <p class="text-muted mb-0">
                            <?= (!empty($abouts_strategy_info->description)) ? $abouts_strategy_info->description : '' ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-md-5 pt-md-5 mt-4 pt-2">
                <?php $abouts_strategy_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'reporting'), false) ?>
                <div class="card abouts feature-clean work-process bg-transparent d-none-arrow border-0 text-center">
                    <div class="icons text-primary text-center mx-auto">
                        <i class="<?= (!empty($abouts_strategy_info->icons)) ? $abouts_strategy_info->icons : '' ?> d-block rounded h3 mb-0"></i>
                    </div>

                    <div class="card-body">
                        <h5 class="text-dark"><?= (!empty($abouts_strategy_info->title)) ? $abouts_strategy_info->title : '' ?></h5>
                        <p class="text-muted mb-0">
                            <?= (!empty($abouts_strategy_info->description)) ? $abouts_strategy_info->description : '' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>
<section class="section bg-light pt-0">
    <div class="container">
        <?php
        $about_footer = get_old_result('tbl_saas_all_section_area', array('type' => 'about_footer'), false);
        ?>
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="video-solution-cta position-relative" style="z-index: 1">
                    <div class="position-relative">
                        <img src="<?= base_url() ?><?= (!empty($about_footer->image)) ? $about_footer->image : '' ?>"
                             class="img-fluid rounded-md shadow-lg" alt=""/>
                        <div class="play-icon">
                            <a href="<?= (!empty($about_footer->links)) ? $about_footer->links : '#' ?>"
                               data-type="youtube"
                               data-id="<?= (!empty($about_footer->icons_3)) ? $about_footer->icons_3 : '' ?>"
                               class="play-btn lightbox">
                                <i class="<?= (!empty($about_footer->icons)) ? $about_footer->icons : '' ?> rounded-circle bg-white shadow-lg"></i>
                            </a>
                        </div>
                    </div>
                    <div class="content mt-md-4 pt-md-2">
                        <div class="row justify-content-center">
                            <div class="col-lg-10 text-center">
                                <div class="row align-items-center">
                                    <div class="col-md-6 mt-4 pt-2">
                                        <div class="section-title text-md-start">
                                            <h6 class="text-white-50"><?= (!empty($about_footer->name)) ? $about_footer->name : '' ?></h6>
                                            <h4 class="title text-white title-dark mb-0">
                                                <?= (!empty($about_footer->title)) ? $about_footer->title : '' ?> <br/>
                                                <?= (!empty($about_footer->title_2)) ? $about_footer->title_2 : '' ?>
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mt-4 pt-md-2">
                                        <div class="section-title text-md-start">
                                            <p class="text-white-50 para-desc">
                                                <?= (!empty($about_footer->description)) ? trim(strip_tags($about_footer->description)) : '' ?>
                                            </p>
                                            <a href="<?= (!empty($about_footer->button_link_2)) ? $about_footer->button_link_2 : '' ?>"
                                               class="text-white title-dark"><?= (!empty($about_footer->button_name_2)) ? $about_footer->button_name_2 : '' ?>
                                                <i class="<?= (!empty($about_footer->icons_2)) ? $about_footer->icons_2 : '' ?> align-middle"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="feature-posts-placeholder bg-primary bg-gradient"></div>
    </div>
</section>