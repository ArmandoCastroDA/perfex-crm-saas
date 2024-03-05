<section class="bg-invoice d-table w-100 bg-primary">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-12 text-center">
                <div class="pages-heading title-heading">
                    <?php $features_heading_info = get_old_result('tbl_saas_all_heading_section', array('type' => 'blogs_heading'),false) ?>
                    <h2 class="text-white title-dark"><?= !empty($features_heading_info->title) ? $features_heading_info->title : 'Latest Blog' ?></h2>
                    <p class="text-white-50 para-desc mb-0 mx-auto"><?= !empty($features_heading_info->description) ? $features_heading_info->description : 'Blocks, Elements and Modifiers. A smart HTML/CSS structure that can easely be reused. Layout driven by the purpose of modularity.' ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section pt-5 bg-light">
    <div class="container">

        <div class="row">
            <?php $blogs_card_info = get_old_result('tbl_saas_all_section_area', array('type' => 'blogs', 'status' => 1));
            foreach ($blogs_card_info as $key => $v_card_info) {
            ?>

                <div class="col-lg-4 col-md-6 mt-4 pt-2">
                    <div class="card blog rounded border-0 shadow">
                        <div class="position-relative">
                            <img src="<?= base_url() ?><?= $v_card_info->image ?: '' ?>" class="card-img-top rounded-top" alt="...">
                            <div class="overlay rounded-top"></div>
                        </div>
                        <div class="card-body content">
                            <h5><a href="<?= $v_card_info->link ?: '' ?>" class="card-title title text-dark"><?= $v_card_info->title ?: '' ?></a></h5>
                            <div class="post-meta d-flex justify-content-between mt-3">
                                <ul class="list-unstyled mb-0">
                                    <li class="list-inline-item me-2 mb-0"><a href="javascript:void(0)" class="text-muted like"><i class="uil uil-heart me-1"></i><?= $v_card_info->color ?: '' ?></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="text-muted comments"><i class="uil uil-comment me-1"></i><?= $v_card_info->color_2 ?: '' ?></a></li>
                                </ul>
                                <a href="blog-detail.html" class="text-muted readmore"><?= $v_card_info->button_name_2 ?: '' ?> <i class="uil uil-angle-right-b align-middle"></i></a>
                            </div>
                        </div>
                        <div class="author">
                            <small class="text-light user d-block"><i class="uil uil-user"></i><?= $v_card_info->name ?: '' ?></small>
                            <small class="text-light date"><i class="uil uil-calendar-alt"></i> <?= $v_card_info->date ?: '' ?></small>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>

    </div>
</section>