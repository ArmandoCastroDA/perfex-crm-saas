<?php

if ($active_menu == 'home') {
    if (get_option('saas_front_slider') == 1) {
        $this->load->view('frontcms/frontend/slider');
    } else {
        ?>
        <section class="bg-invoice d-table w-100 bg-primary">
            <div class="container">
                <div class="row mt-5 justify-content-center">
                    <div class="col-12 text-center">
                        <div class="pages-heading title-heading">
                            <h2 class="text-white title-dark">
                                <?= get_option('saas_companyname') ? get_option('saas_companyname') : 'Perfect SaaS' ?>
                            </h2>
                            <p class="text-white-50 para-desc mb-0 mx-auto">
                                <?= get_option('saas_companyname') ? get_option('saas_companyname') : 'Perfect SaaS' ?>
                                is a powerful, self-hosted, all-in-one invoicing, accounting, and CRM software.
                                Perfect for small businesses, freelancers and startups.
                                <br>
                                <br>
                                <a href="<?= site_url('register') ?>" class="btn btn-light">Get Started</a>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }

    $this->load->view('frontcms/brands/brand');

    $this->load->view('frontcms/features/features');

    $this->load->view('frontcms/reviews/review');

    $this->load->view('frontcms/discovers/discovers');

    $this->load->view('frontcms/creatives/creatives');

    $this->load->view('frontcms/questions/question');
}
if (!empty($page_info)) {
    if ($active_menu == 'contact-us') {
        $this->load->view('frontcms/frontend/contact');
    } elseif ($active_menu == 'pricing') {
        $this->load->view('frontcms/frontend/pricing');
    } elseif ($active_menu == 'about-us' || $active_menu == 'about') {
        $this->load->view('frontcms/abouts/about_us');
    } elseif ($active_menu == 'features') {
        $this->load->view('frontcms/features/features_list');
    } elseif ($active_menu == 'blog') {
        $this->load->view('frontcms/blogs/blogs_list');
    } elseif ($active_menu == 'gallery') {
        $this->load->view('frontcms/gallery/gallery');
    } elseif ($active_menu == 'affiliate-program' || $active_menu == 'affiliate') {
        $this->load->view('frontcms/frontend/affiliate');
    } else if ($active_menu != 'home') { ?>
        <section class="bg-invoice d-table w-100 bg-primary">
            <div class="container">
                <div class="row mt-5 justify-content-center">
                    <div class="col-12 text-center">
                        <div class="pages-heading title-heading">
                            <h2 class="text-white title-dark"><?= !empty($title) ? $title : '' ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section pt-5 bg-light">
            <div class="container">
                <?= $page_info->description; ?>
            </div>
        </section>
        <?php
    }
}
?>
