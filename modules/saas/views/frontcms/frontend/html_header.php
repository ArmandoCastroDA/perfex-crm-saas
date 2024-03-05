<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <?php
    $meta_title = get_option('home_meta_title');
    $meta_author = get_option('home_meta_author');
    $meta_description = get_option('home_meta_description');
    $meta_keywords = get_option('home_meta_keywords');

    if (!empty($meta_title)) {
        $title = $meta_title;
    }
    $google_analytics_tracking = get_option('google_analytics_tracking_id');
    if (!empty($google_analytics_tracking)) {
        ?>
        <!-- Google tag (gtag.js) -->
        <script async
                src="https://www.googletagmanager.com/gtag/js?id="<?php echo $google_analytics_tracking; ?>></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', '<?php echo $google_analytics_tracking; ?>');
        </script>
    <?php } ?>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <?php
    if (!empty($meta_title)) {
        ?>
        <meta name="title" content="<?php echo $meta_title; ?>">
        <?php
    }
    ?>
    <?php
    if (!empty($meta_author)) {
        ?>
        <meta name="author" content="<?php echo $meta_author; ?>">
        <?php
    }
    ?>
    <?php
    if (!empty($meta_description)) {
        ?>
        <meta name="description" content="<?php echo $meta_description; ?>">
        <?php
    }
    ?>
    <?php
    if (!empty($meta_keywords)) {
        ?>
        <meta name="keywords" content="<?php echo $meta_keywords; ?>">
        <?php
    }
    ?>
    <title><?php echo $title; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo base_url() . get_option('saas_front_favicon'); ?>">
    <!-- CSS here -->
    <link rel="stylesheet" type="text/css" href="<?= module_dir_url(SaaS_MODULE) ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
          href="<?= module_dir_url(SaaS_MODULE) ?>assets/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
    <link rel="stylesheet" type="text/css" href="<?= module_dir_url(SaaS_MODULE) ?>assets/css/tobii.min.css">
    <link rel="stylesheet" href="<?= module_dir_url(SaaS_MODULE) ?>assets/css/tiny-slider.css">
    <link type="text/css" id="theme-opt" rel="stylesheet" href="<?= module_dir_url(SaaS_MODULE) ?>assets/css/style.css">
    <link rel="stylesheet" id="color-opt" href="<?= module_dir_url(SaaS_MODULE) ?>assets/css/colors/default.css">
    <script src="<?= base_url() ?>assets/plugins/jquery/jquery.min.js"></script>

    <?php
    $header_custom_css = get_option('header_custom_css');
    if (!empty($header_custom_css)) {
        echo '<style type="text/css"> ';
        echo html_entity_decode($header_custom_css);
        echo ' </style> ';
    }

    $header_custom_script = get_option('header_custom_script');
    if (!empty($header_custom_script)) {
        $script = '<script type="text/javascript"> ';
        $script .= html_entity_decode($header_custom_script);
        $script .= ' </script>';
        echo $script;
    }
    ?>

</head>

<body>

<?php echo csrf_jquery_token() ?>
<?php
if (empty($affiliate) || !is_affiliate()) {
    $menu_list = get_old_result('tbl_saas_front_menus', array('slug' => 'main-menu'), false);
    if (!empty($menu_list)) {
        $this->load->model('cms_menuitems_model');
        $main_menus = $this->cms_menuitems_model->getMenus($menu_list->id);
    }
} else {
    $main_menus = [
        [
            'id' => '1',
            'slug' => 'dashboard',
            'menu' => 'Dashboard',
            'page_id' => '1',
            'parent_id' => '0',
            'is_homepage' => 0,
            'ext_url' => '',
            'ext_url_link' => '',
            'open_new_tab' => '',
            'publish' => '1',
            'page_slug' => 'home',
            'page_url' => 'affiliate/dashboard',
            'submenus' => []
        ],
        //commissions
        [
            'id' => '2',
            'slug' => 'commissions',
            'menu' => 'Commissions',
            'page_id' => '2',
            'parent_id' => '0',
            'is_homepage' => 0,
            'ext_url' => '',
            'ext_url_link' => '',
            'open_new_tab' => '',
            'publish' => '1',
            'page_slug' => 'commissions',
            'page_url' => 'affiliate/commissions',
            'submenus' => []
        ],
        // payouts
        [
            'id' => '3',
            'slug' => 'payouts',
            'menu' => 'Payouts',
            'page_id' => '3',
            'parent_id' => '0',
            'is_homepage' => 0,
            'ext_url' => '',
            'ext_url_link' => '',
            'open_new_tab' => '',
            'publish' => '1',
            'page_slug' => 'payouts',
            'page_url' => 'affiliate/payouts',
            'submenus' => []
        ],
        // referrals
        [
            'id' => '4',
            'slug' => 'referrals',
            'menu' => 'Referrals',
            'page_id' => '4',
            'parent_id' => '0',
            'is_homepage' => 0,
            'ext_url' => '',
            'ext_url_link' => '',
            'open_new_tab' => '',
            'publish' => '1',
            'page_slug' => 'referrals',
            'page_url' => 'affiliate/referrals',
            'submenus' => []
        ],
        // settings
        [
            'id' => '5',
            'slug' => 'settings',
            'menu' => 'Settings',
            'page_id' => '5',
            'parent_id' => '0',
            'is_homepage' => 0,
            'ext_url' => '',
            'ext_url_link' => '',
            'open_new_tab' => '',
            'publish' => '1',
            'page_slug' => 'settings',
            'page_url' => 'affiliate/settings',
            'submenus' => []
        ],
        // logout
        [
            'id' => '6',
            'slug' => 'logout',
            'menu' => 'Logout',
            'page_id' => '6',
            'parent_id' => '0',
            'is_homepage' => 0,
            'ext_url' => '',
            'ext_url_link' => '',
            'open_new_tab' => '',
            'publish' => '1',
            'page_slug' => 'logout',
            'page_url' => 'saas/affiliate/dashboard/logout',
            'submenus' => []
        ]

    ];
}

if (empty($affiliate) || is_affiliate()) {
    ?>
    <!-- Navbar STart -->
    <header id="topnav" class="defaultscroll sticky bg-white">
        <div class="container">
            <!-- Logo container-->


            <a class="logo" href="<?= base_url() ?>home"><img height="24" class="logo-light-mode"
                                                              src="<?php echo saas_logo() ?>"
                                                              alt="PerfexSaaS"></a>
            <!-- Logo End -->

            <!-- End Logo container-->
            <div class="menu-extras">
                <div class="menu-item">
                    <!-- Mobile menu toggle-->
                    <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                    <!-- End mobile menu toggle-->
                </div>
            </div>

            <!--Login button Start-->
            <?php
            if (empty($affiliate) || !is_affiliate()) {
                ?>
                <ul class="buy-button list-inline mb-0">
                    <li class="list-inline-item mb-0">
                        <a href="<?= base_url() . 'register' ?>" class="btn btn-pills btn-primary">
                            <?= _l('get_started') ?>
                            <i class="uil uil-arrow-right align-middle"></i></a>
                    </li>
                    <li class="list-inline-item mb-0">
                        <a href="<?= base_url() . 'login' ?>" class="btn btn-pills btn-info">
                            <i class="uil uil-user-circle"></i> <?= _l('login') ?>
                            <i class="uil uil-arrow-right align-middle"></i></a>
                    </li>
                </ul>
                <?php
            }
            ?>
            <!--Login button End-->

            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">
                    <?php

                    if (!empty($main_menus)) {
                        foreach ($main_menus as $menu_key => $menu_value) {
                            $submenus = false;
                            $cls_menu_dropdown = "";
                            $menu_selected = "";

                            if (!empty($menu_value['submenus'])) {
                                $submenus = true;
                                $cls_menu_dropdown = "dropdown has-submenu parent-parent-menu-item";
                            }

                            if (!empty($active_menu) && $menu_value['slug'] == $active_menu) {
                                $menu_selected = "active";
                            }
                            ?>
                            <li class="<?php echo $menu_selected . " " . $cls_menu_dropdown; ?> ">
                                <?php
                                if (!$submenus) {
                                    $top_new_tab = '';
                                    $url = '#';

                                    if ($menu_value['open_new_tab']) {
                                        $top_new_tab = "target='_blank'";
                                    }
                                    if ($menu_value['ext_url']) {
                                        $url = $menu_value['ext_url_link'];
                                    } else if ($menu_value['page_url']) {
                                        $url = site_url($menu_value['page_url']);
                                    } else if ($menu_value['slug']) {
                                        $url = site_url('front/' . $menu_value['slug']);
                                    }

                                    ?>

                                    <a href="<?php echo $url; ?>" <?php echo $top_new_tab; ?>><?php echo _l($menu_value['slug']); ?></a>

                                    <?php
                                } else {
                                    $child_new_tab = '';
                                    $url = '#';
                                    ?>
                                    <a href="#"><?php echo _l($menu_value['menu']); ?></a><span
                                            class="menu-arrow"></span>
                                    <ul class="submenu ">
                                        <?php
                                        foreach ($menu_value['submenus'] as $submenu_key => $submenu_value) {
                                            if ($submenu_value['open_new_tab']) {
                                                $child_new_tab = "target='_blank'";
                                            }
                                            if ($submenu_value['ext_url']) {
                                                $url = $submenu_value['ext_url_link'];
                                            } else if ($submenu_value['page_url']) {
                                                $url = site_url($submenu_value['page_url']);
                                            } else if ($submenu_value['slug']) {
                                                $url = site_url('front/' . $submenu_value['slug']);
                                            }
                                            ?>
                                            <li>
                                                <a href="<?php echo $url; ?>" <?php echo $child_new_tab; ?>><?php echo _l($submenu_value['menu']) ?></a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                    <?php
                                }
                                ?>
                            </li>

                            <?php
                        }
                    }
                    ?>
                </ul>
                <!--end navigation menu-->
            </div>
            <!--end navigation-->
        </div>
        <!--end container-->
    </header>
<?php } ?>
<!--end header-->
<!-- Navbar End -->
<main>