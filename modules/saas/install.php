<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (function_exists('is_subdomain') && function_exists('subdomain') && !empty(subdomain())) {
    redirect('admin/dashboard');
}


$error = false;
$errorList = array();
$path = APPPATH . 'config';
if (!is_writable($path)) {
    $error = true;
    $errorList[] = $path . ' is not writable. Make writable - Permissions 0755';
}

$app_config = $path . '/app-config.php';
if (!is_writable($app_config)) {
    $error = true;
    $errorList[] = $app_config . ' is not writable. Make writable - Permissions 0755';
}

$database_config = $path . '/database.php';
if (!is_writable($database_config)) {
    $error = true;
    $errorList[] = $database_config . ' is not writable. Make writable - Permissions 0755';
}
$confirmed = false;

if (!$error) {
    installed();
    $confirmed = true;
} else {
    $confirmed = false;
}

// If confirmation screen is not yet done, require confirmation
if (!$confirmed) {
    require_once(__DIR__ . '/views/includes/install_requirements.php');
    exit;
}

function installed()
{
// insert and line into application/config/app-config.php file
    $app_config_path = APPPATH . 'config/app-config.php';
    $app_config_file = file_get_contents($app_config_path);
// check require_once(FCPATH . 'modules/saas/config/my_config.php'); is already added or not into the app-config.php file
// if not added then add the line require_once(FCPATH . 'modules/saas/config/my_config.php'); into the app-config.php file last line
    if (strpos($app_config_file, "require_once(FCPATH . 'modules/saas/config/my_config.php');") !== false) {
        // already added
    } else {
        // not added
        $app_config_file = str_replace("define('APP_CSRF_PROTECTION', true);", "define('APP_CSRF_PROTECTION', true);\n\n\nrequire_once(FCPATH . 'modules/saas/config/my_config.php'); // added by saas", $app_config_file);
        if (!$fp = fopen($app_config_path, 'wb')) {
            die('Unable to write to config file');
        }

        flock($fp, LOCK_EX);
        fwrite($fp, $app_config_file, strlen($app_config_file));
        flock($fp, LOCK_UN);
        fclose($fp);
        @chmod($app_config_path, 0644);
    }
// replace 'database' => config_item('default_database'), with 'database' => APP_DB_NAME, in application/config/database.php file
    $database_path = APPPATH . 'config/database.php';
    $database_file = file_get_contents($database_path);
    $database_file = str_replace("APP_DB_NAME", "config_item('default_database')", $database_file);

    if (!$fp = fopen($database_path, 'wb')) {
        die('Unable to write to config file');
    }

    flock($fp, LOCK_EX);
    fwrite($fp, $database_file, strlen($database_file));
    flock($fp, LOCK_UN);
    fclose($fp);
    @chmod($database_path, 0644);


// upload my_routes_samples.php to application/config and rename it to my_routes.php
    $sample_routes = module_dir_path(SaaS_MODULE) . 'config/routes.sample.php';
// upload the $sample_routes into application/config folder and rename it to my_routes.php
    $routes_path = APPPATH . 'config/my_routes.php';
    @chmod($routes_path, 0666);
    if (@copy($sample_routes, $routes_path) === false) {
        die('Unable to copy sample routes file to config folder . please make sure you have permission to copy routes.sample file');
    }

// upload my_autoload_samples.php to application/config and rename it to my_autoload.php
    $sample_autoload = module_dir_path(SaaS_MODULE) . 'config/autoload.sample.php';
// upload the $sample_autoload into application/config folder and rename it to my_autoload.php
    $autoload_path = APPPATH . 'config/my_autoload.php';
    @chmod($autoload_path, 0666);
    if (@copy($sample_autoload, $autoload_path) === false) {
        die('Unable to copy sample autoload file to config folder . please make sure you have permission to copy autoload.sample file');
    }

// add hook to application/models/Authentication_model.php file after if ((!empty($email)) and (!empty($password))) { line
// add the following line hooks()->do_action('before_login');
    $authentication_model_path = APPPATH . 'models/Authentication_model.php';
    $authentication_model_file = file_get_contents($authentication_model_path);
    $authentication_model_file = str_replace("if ((!empty(\$email)) and (!empty(\$password))) {", "if ((!empty(\$email)) and (!empty(\$password))) {\n\n\nhooks()->do_action('before_login');", $authentication_model_file);

    if (!$fp = fopen($authentication_model_path, 'wb')) {
        die('Unable to write to config file in ' . $authentication_model_path);
    }

    flock($fp, LOCK_EX);
    fwrite($fp, $authentication_model_file, strlen($authentication_model_file));
    flock($fp, LOCK_UN);
    fclose($fp);
    @chmod($authentication_model_path, 0644);

    $CI = &get_instance();
    $CI->db->query("ALTER TABLE `" . db_prefix() . "modules` CHANGE `active` `active` TINYINT(1) NOT NULL DEFAULT '0';");
    // check the column is already exist or not
    if (!$CI->db->field_exists('saas_company_id', db_prefix() . 'clients')) {
        $CI->db->query("ALTER TABLE " . db_prefix() . "clients ADD `saas_company_id` INT NULL DEFAULT NULL AFTER `company`;");
    }

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_affiliates` (
  `affiliate_id` int NOT NULL AUTO_INCREMENT,
  `referral_by` int DEFAULT NULL,
  `referral_to` int DEFAULT NULL,
  `transaction_id` int DEFAULT NULL,
  `amount_was` decimal(18,5) DEFAULT NULL,
  `get_amount` decimal(18,5) DEFAULT NULL,
  `commission_type` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `commission_value` decimal(18,5) DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `affiliate_rule` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `affiliate_payment_rules` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`affiliate_id`)
) ENGINE=InnoDB;");

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_affiliate_payouts` (
  `affiliate_payout_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `notes` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `comments` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `status` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `amount` int NOT NULL,
  `available_amount` decimal(18,5) DEFAULT NULL,
  `payment_method` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`affiliate_payout_id`)
) ENGINE=InnoDB;");

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_affiliate_users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint NOT NULL DEFAULT '0',
  `is_verified` tinyint(1) NOT NULL,
  `ban_reason` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `new_password_key` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `new_email_key` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `last_ip` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `first_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `language` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'en',
  `country` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `mobile` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `avatar` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `isAffiliate` tinyint(1) NOT NULL DEFAULT '0',
  `referral_link` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `address` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `affiliate_link` (`referral_link`)
) ENGINE=InnoDB;");

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_all_heading_section` (
  `heading_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(191) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `icons` text,
  `links` varchar(191) DEFAULT NULL,
  `description` text,
  `type` varchar(100) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`heading_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36;");

// check tbl_saas_all_heading_section table is empty or not
    $check_tbl_saas_all_heading_section = $CI->db->query("SELECT * FROM `tbl_saas_all_heading_section`");
    if ($check_tbl_saas_all_heading_section->num_rows() == 0) {
        $CI->db->query("INSERT INTO `tbl_saas_all_heading_section` (`heading_id`, `title`, `name`, `icons`, `links`, `description`, `type`, `user_id`, `status`) VALUES
(1, 'Features designed for you', NULL, NULL, NULL, 'We believe we have created the most efficient SaaS landing page for your users. Landing page\r\nwith features that will convince you to use it for your SaaS business.', 'features', 2, NULL),
(4, 'Creative Heads', NULL, NULL, NULL, 'Generally, every customer wants a product or service that solves their problem, worth their\r\nmoney, and is delivered with amazing customer service', 'creatives', 1, NULL),
(5, 'Discover what makes Task manager great.', NULL, NULL, NULL, 'Start working with SaaS that can provide everything you need to generate awareness, drive traffic, connect.', 'discovers', 1, NULL),
(11, 'About US', NULL, NULL, NULL, 'Start working with SaaS that can provide everything you need to generate awareness, drive traffic, connect.', 'abouts', 2, NULL),
(12, 'How do we works ?', 'Work Process', NULL, NULL, '<p>Start working with SaaS that can provide everything you need to generate awareness, drive traffic, connect.<br></p>', 'about_works', 2, 1),
(13, 'Discussion', NULL, 'uil uil-presentation-edit', NULL, '<p>The most well-known dummy text is the \'Lorem Ipsum\', which is said to have originated</p>\r\n', 'discussion', 1, 1),
(14, 'Strategy & Testing', '', 'uil uil-airplay', NULL, '<p>Generators convallis odio, vel pharetra quam malesuada vel. Nam porttitor malesuada.</p>', 'strategy', 2, 1),
(15, 'Reporting', '', 'uil uil-image-check', NULL, '<p>Internet Proin tempus odio, vel pharetra quam malesuada vel. Nam porttitor malesuada.</p>', 'reporting', 2, 1),
(16, 'Latest Blog', NULL, NULL, NULL, 'Blocks, Elements and Modifiers. A smart HTML/CSS structure that can easely be reused. Layout driven by the purpose of modularity.', 'blogs_heading', 1, NULL),
(17, 'Our Gallery', NULL, NULL, NULL, 'In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content.', 'gallery_heading', 2, NULL),
(18, NULL, NULL, 'facebook', '#', NULL, 'footer_icons', 2, NULL),
(19, NULL, NULL, 'modules/saas/uploads/btransfer_in-1680588158.png', 'asddsa', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna\r\nCopyright © 2023 CoderItems LTD.\r\nAll Rights Reserved. Proudly made in EU.', 'footer_left', 1, NULL),
(20, 'Sign up and receive the latest tips via email.', 'Newsletter', 'Subscribe', 'asddsa', NULL, 'footer_right', 1, NULL),
(21, NULL, NULL, 'instagram', '', NULL, 'footer_icons', 2, NULL),
(22, NULL, NULL, 'twitter', '', NULL, 'footer_icons', 2, NULL),
(23, NULL, NULL, 'linkedin', '', NULL, 'footer_icons', 2, NULL),
(24, 'by', 'PerfectSaaS. Design with', 'mdi mdi-heart text-danger', 'asddsa', 'coderitems', 'footer_bottom', 1, NULL),
(25, 'Get in Touch', NULL, NULL, NULL, '                                                                <p>Do you wanna know more or have any query, Feel free to contact with us at Our 24/7 Dedicated support team are waiting to solve your doubt :)<br></p>                                                                                    ', 'contact_heading', 1, NULL),
(26, 'Have Question ? Get in touch!', 'Contact us', NULL, '', '<span xss=\"removed\">Start working with SaaS that can provide everything you need to generate awareness, drive triffic, connect.</span>', 'questions', 1, NULL),
(27, 'asddsa', NULL, NULL, NULL, 'asddsa', 'blogs_heading', 1, NULL),
(29, 'by', 'PerfectSaaS. Design with', 'mdi mdi-heart text-danger', 'asddsa', 'CoderItems.', 'footer_bottom', 1, NULL),
(30, 'by', 'PerfectSaaS. Design with', 'mdi mdi-heart text-danger', 'asddsasdadsasda', 'CoderItems.', 'footer_bottom', 1, NULL),
(31, 'by', 'PerfectSaaS. Design with', 'mdi mdi-heart text-danger', 'asddsa', 'CoderItems.', 'footer_bottom', 1, NULL),
(32, 'by', 'PerfectSaaS. Design with', 'mdi mdi-heart text-danger', 'asddsa', 'CoderItems.', 'footer_bottom', 1, NULL),
(33, 'Trusted client by over 10,000+ of the world\'s', NULL, NULL, NULL, 'Start working with PerfectSaaS that can provide everything you need to generate awareness, drive traffic, connect.', 'blogs_heading', 1, NULL),
(34, 'Trusted client by over 10,000+ of the world\'s', NULL, NULL, NULL, 'Start working with PerfectSaaS that can provide everything you need to generate awareness, drive traffic, connect. sad\r\n', 'brand_heading', 1, NULL),
(35, 'We believe in building each other and hence', 'Work with some amazing partners', NULL, NULL, 'Start working with PerfectSaaS that can provide everything you need to generate awareness, drive traffic, connect.', 'review_heading', 1, NULL);");
    }

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_all_section_area` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(191) DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL,
  `link` varchar(191) DEFAULT NULL,
  `designation` varchar(120) DEFAULT NULL,
  `color` int DEFAULT NULL,
  `title_2` varchar(120) DEFAULT NULL,
  `color_2` int DEFAULT NULL,
  `description` text,
  `image` text,
  `icons` text,
  `button_name_2` varchar(120) DEFAULT NULL,
  `button_link_2` text,
  `icons_2` text,
  `button_name_3` varchar(120) DEFAULT NULL,
  `button_link_3` text,
  `icons_3` text,
  `status` int DEFAULT NULL,
  `date` varchar(30) DEFAULT NULL,
  `user_id` int NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68;");

// check data exist or not in table
    $check_data = $CI->db->query("SELECT * FROM `tbl_saas_all_section_area` WHERE `id` = 2")->num_rows();
    if ($check_data == 0) {
        $CI->db->query("INSERT INTO `tbl_saas_all_section_area` (`id`, `title`, `name`, `link`, `designation`, `color`, `title_2`, `color_2`, `description`, `image`, `icons`, `button_name_2`, `button_link_2`, `icons_2`, `button_name_3`, `button_link_3`, `icons_3`, `status`, `date`, `user_id`, `type`) VALUES
(2, 'Responsive Layout Template', 'Read More', '', NULL, NULL, NULL, NULL, '<p>Responsive code that makes your landing page look good on all devices (desktops, tablets, and phones). Created with mobile specialists.</p>', NULL, 'uil uil-airplay', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2, 'features'),
(4, 'SaaS Landing Page Analysis', 'Read More', NULL, NULL, NULL, NULL, NULL, '<p>A perfect structure created after we analized trends in SaaS landing page designs. Analysis made to the most popular SaaS businesses.<br></p>', NULL, 'uil uil-plane-departure', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2, 'features'),
(5, 'Smart BEM Grid', 'Read More', NULL, NULL, NULL, NULL, NULL, '<p>Blocks, Elements and Modifiers. A smart HTML/CSS structure that can easely be reused. Layout driven by the purpose of modularity.<br></p>', NULL, 'uil uil-truck', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2, 'features'),
(6, 'Marketing Online', 'Gail R. Thompson', NULL, 'Co-founder', 77, 'SEO Services', 55, NULL, NULL, 'uil uil-file-bookmark-alt', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2, 'creatives'),
(8, 'Collaborate with your team anytime and anywhare.', 'Find Better Leads', '', NULL, NULL, NULL, NULL, '<p>Start working with SaaS that can provide everything you need to generate awareness, drive traffic, connect.<br></p>', 'modules/saas/uploads/1.jpg', 'uil uil-capture', 'Find Better Leads', '', 'uil uil-file', 'Get Paid Seemlessly', '', 'uil uil-credit-card-search', 1, NULL, 2, 'features_collaborate'),
(9, 'Who we are ?', 'Contact us', '', NULL, 15, NULL, NULL, '                                                                <p>Start working with PerfectSaaS that can provide everything you need to generate awareness, drive traffic, connect. Dummy text is text that is used in the publishing industry or by web designers to occupy the space which will later be filled with \'real\' content. This is required when, for example, the final text is not yet available. Dummy texts have been in use by typesetters since the 16th century.</p><p><br></p>                                                        ', 'modules/saas/uploads/about2.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'abouts'),
(11, 'Meet Experience', 'Team', '', NULL, NULL, 'Team Member', NULL, '                                                                <p>Start working with PerfectSaaS that can provide everything you need to generate awareness, drive traffic, connect.</p>                                                        ', 'modules/saas/uploads/cta-bg.jpg', 'mdi mdi-play text-primary', 'Read More', '', 'uil uil-angle-right-b', NULL, NULL, NULL, 1, NULL, 1, 'about_footer'),
(12, 'How do we works ?', 'Work Process', '', NULL, 33, NULL, 8, '<p>Start working with SaaS that can provide everything you need to generate awareness, drive traffic, connect.<br></p>', 'modules/saas/uploads/01.jpg', NULL, 'Read More', '', NULL, NULL, NULL, NULL, 1, '0000-00-00', 1, 'about_works'),
(13, 'Discussion asddsa', NULL, '', NULL, 35, NULL, 21, '<p>The most well-known dummy text is the \'Lorem Ipsum\', which is said to have originated</p>', 'modules/saas/uploads/02.jpg', 'uil uil-presentation-edit', 'Read More', '', NULL, NULL, NULL, NULL, 1, '2023-06-26', 1, 'discussion'),
(14, 'Smartest Applications for Business', 'Calvin Carlo', '', NULL, 84, NULL, 75, NULL, 'modules/saas/uploads/03.jpg', NULL, 'Read More', '', NULL, NULL, NULL, NULL, 1, '2023-06-20', 2, 'blogs'),
(17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uil uil-angle-right-b', 'About', 'qwe', NULL, NULL, NULL, NULL, 1, NULL, 1, 'company'),
(18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uil uil-angle-right-b', 'Privacy Policy', '/saas_perfex/front/privacy-policy', NULL, NULL, NULL, NULL, 1, NULL, 1, 'usefull_links'),
(19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'uil uil-angle-right-b', 'Terms of Services', '/saas_perfex/front/terms-conditions', NULL, NULL, NULL, NULL, 1, NULL, 1, 'usefull_links'),
(20, 'Kitchen', NULL, '', NULL, 100, NULL, NULL, NULL, 'modules/saas/uploads/01.jpg', 'uil uil-arrow-up-right', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2, 'gallery'),
(22, 'Living Room', NULL, '', NULL, 77, NULL, NULL, NULL, 'modules/saas/uploads/02.jpg', 'uil uil-arrow-up-right', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2, 'gallery'),
(23, 'Office', NULL, '', NULL, 110, NULL, NULL, NULL, 'modules/saas/uploads/03.jpg', 'uil uil-arrow-up-right', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2, 'gallery'),
(24, 'Dining Hall', NULL, '', NULL, 35, NULL, NULL, NULL, 'modules/saas/uploads/04.jpg', 'uil uil-arrow-up-right', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2, 'gallery'),
(27, 'Email', 'CoderItems007@gmail.com', '', NULL, NULL, NULL, NULL, NULL, NULL, 'mail', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 'new_contact'),
(29, 'Location', 'View on Google map', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'map-pin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 'new_contact'),
(34, 'Management Dashboard', NULL, NULL, NULL, NULL, NULL, NULL, 'Dummy text is text that is used in the publishing industry or by web designers.', 'modules/saas/uploads/apps.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2, 'discovers'),
(35, 'Management Timeline', NULL, NULL, NULL, NULL, NULL, NULL, 'Dummy text is text that is used in the publishing industry or by web designers.', 'modules/saas/uploads/task.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2, 'discovers'),
(36, 'Payment Management', NULL, NULL, NULL, NULL, NULL, NULL, 'Dummy text is text that is used in the publishing industry or by web designers.', 'modules/saas/uploads/timeline.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2, 'discovers'),
(37, 'File Integrate', NULL, NULL, NULL, NULL, NULL, NULL, 'Dummy text is text that is used in the publishing industry or by web designers.', 'modules/saas/uploads/widgets2.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 2, 'discovers'),
(38, 'How do we works ?', 'Work Process', NULL, NULL, NULL, NULL, NULL, '<p>Start working with SaaS that can provide everything you need to generate awareness, drive traffic, connect.</p>', NULL, '', '', '', NULL, NULL, NULL, NULL, 1, NULL, 2, 'about_works'),
(50, 'Great Product Analytics With Real Problem', 'Buy Now', '', NULL, NULL, NULL, NULL, 'Due to its widespread use as filler text for layouts, non-readability is of great importance: human perception is tuned to recognize certain patterns and repetitions in texts. If the distribution of letters visual impact.', 'modules/saas/uploads/classic02.png', '', '', '', '', '', '', '', 1, NULL, 1, 'features_collaborate'),
(51, 'PayPal', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'modules/saas/uploads/paypal.svg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'brands'),
(52, 'amazon', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'modules/saas/uploads/amazon.svg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'brands'),
(53, 'google', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'modules/saas/uploads/google.svg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'brands'),
(54, 'Thomas Israel', NULL, NULL, 'C.E.O', NULL, '5', NULL, 'It seems that only fragments of the original text\r\n                                        remain in the Lorem Ipsum texts used today. ', 'modules/saas/uploads/0_1.jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'reviews'),
(55, 'Barbara McIntosh ', NULL, NULL, 'M.D', NULL, '4.5', NULL, 'One disadvantage of Lorum Ipsum is that in Latin\r\n                                        certain letters appear more frequently than others.', 'modules/saas/uploads/02.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'reviews'),
(56, 'Carl Oliver', NULL, NULL, 'P.A', NULL, '5', NULL, 'The most well-known dummy text is the \'Lorem Ipsum\',\r\n                                        which is said to have originated in the 16th century. ', 'modules/saas/uploads/03.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'reviews'),
(57, 'Christa Smith', NULL, NULL, 'Manager', NULL, '5', NULL, 'According to most sources, Lorum Ipsum can be traced\r\n                                        back to a text composed by Cicero. ', 'modules/saas/uploads/04.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'reviews'),
(58, 'Dean Tolle', NULL, NULL, 'Developer', NULL, '4.5', NULL, ' There is now an abundance of readable dummy texts.\r\n                                        These are usually used when a text is required. ', 'modules/saas/uploads/05.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'reviews'),
(59, 'Jill Webb', NULL, NULL, 'Sr. Developer', NULL, '5', NULL, 'Thus, Lorem Ipsum has only limited suitability as a\r\n                                        visual filler for German texts.', 'modules/saas/uploads/06.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'reviews'),
(60, 'Lenevo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'modules/saas/uploads/lenovo.svg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'brands'),
(61, 'Shopify', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'modules/saas/uploads/shopify.svg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'brands'),
(62, 'Spotify', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'modules/saas/uploads/spotify.svg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, 'brands'),
(65, 'asdsda', 'adssad', NULL, NULL, NULL, NULL, NULL, '                                                            dsasadsdasad', NULL, 'asddsa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'about_works'),
(66, 'asd', 'sdsda', NULL, NULL, NULL, NULL, NULL, '                                                            asd', NULL, 'sad', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'discussion'),
(67, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Blogs', '/saas_perfex/front/blog', NULL, NULL, NULL, NULL, 1, NULL, 1, 'company');");
    }
    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_applied_coupon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `discount_amount` varchar(100) DEFAULT NULL,
  `discount_percentage` varchar(100) DEFAULT NULL,
  `coupon_id` int DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `coupon` varchar(50) DEFAULT NULL,
  `applied_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;");

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `domain` varchar(250) DEFAULT NULL,
  `domain_url` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `status` enum('pending','running','expired','suspended','terminated') DEFAULT 'pending',
  `activation_code` varchar(50) DEFAULT NULL,
  `package_id` int NOT NULL,
  `db_name` varchar(120) DEFAULT NULL,
  `amount` decimal(18,2) NOT NULL DEFAULT '0.00',
  `frequency` varchar(50) DEFAULT NULL,
  `trial_period` varchar(20) DEFAULT NULL,
  `is_trial` enum('Yes','No') DEFAULT 'No',
  `expired_date` date DEFAULT NULL,
  `currency` varchar(20) DEFAULT NULL,
  `timezone` varchar(250) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `address` text,
  `remarks` text,
  `maintenance_mode_message` varchar(200) DEFAULT NULL,
  `maintenance_mode` varchar(20) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `updated_by` int NOT NULL,
  `referral_by` int DEFAULT NULL,
  `for_seed` VARCHAR(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1");

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_companies_history` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `companies_id` int DEFAULT NULL,
  `amount` decimal(25,5) NOT NULL DEFAULT '0.00000',
  `reports` varchar(50) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `i_have_read_agree` enum('Yes','No') DEFAULT 'Yes',
  `payment_method` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `frequency` varchar(50) DEFAULT NULL,
  `validity` date DEFAULT NULL,
  `active` int NOT NULL DEFAULT '1',
  `source` varchar(20) DEFAULT NULL,
  `package_name` varchar(250) DEFAULT NULL,
  `staff_no` varchar(50) DEFAULT NULL,
  `additional_staff_no` varchar(20) DEFAULT NULL,
  `client_no` varchar(20) DEFAULT NULL,
  `additional_client_no` varchar(20) DEFAULT NULL,
  `project_no` varchar(20) DEFAULT NULL,
  `additional_project_no` varchar(20) DEFAULT NULL,
  `invoice_no` varchar(20) DEFAULT NULL,
  `additional_invoice_no` varchar(20) DEFAULT NULL,
  `leads_no` varchar(50) DEFAULT NULL,
  `additional_leads_no` varchar(50) DEFAULT NULL,
  `expense_no` varchar(50) DEFAULT NULL,
  `additional_expense_no` varchar(50) DEFAULT NULL,
  `contract_no` varchar(50) DEFAULT NULL,
  `additional_contract_no` varchar(50) DEFAULT NULL,
  `estimate_no` varchar(50) NOT NULL,
  `additional_estimate_no` varchar(50) DEFAULT NULL,
  `calendar` varchar(50) DEFAULT NULL,
  `credit_note_no` varchar(50) DEFAULT NULL,
  `additional_credit_note_no` varchar(50) DEFAULT NULL,
  `proposal_no` varchar(50) DEFAULT NULL,
  `additional_proposal_no` varchar(50) DEFAULT NULL,
  `tickets` varchar(50) DEFAULT NULL,
  `additional_tickets` varchar(50) DEFAULT NULL,
  `tasks_no` varchar(50) DEFAULT NULL,
  `additional_tasks_no` varchar(50) DEFAULT NULL,
  `item_no` varchar(50) DEFAULT NULL,
  `additional_item_no` varchar(50) DEFAULT NULL,
  `disk_space` varchar(50) DEFAULT NULL,
  `additional_disk_space` varchar(100) DEFAULT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `allowed_payment_modes` text DEFAULT NULL,
  `modules` text DEFAULT NULL,
  `allowed_themes` text DEFAULT NULL,
  `disabled_modules` text DEFAULT NULL,
  `custom_domain` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `saas_companies_instance_id_foreign` (`companies_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;");

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_companies_payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `companies_history_id` int DEFAULT NULL,
  `companies_id` int NOT NULL,
  `reference_no` text,
  `transaction_id` text,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_date` varchar(20) DEFAULT NULL,
  `currency` varchar(20) DEFAULT NULL,
  `subtotal` varchar(50) DEFAULT NULL,
  `discount_percent` varchar(50) DEFAULT NULL,
  `discount_amount` varchar(50) DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `total_amount` varchar(50) DEFAULT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;");

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_coupon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `package_id` int NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `amount` varchar(50) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '0=fixed,1=discount',
  `package_type` varchar(30) DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `show_on_pricing` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;");

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_front_cms_media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `image` varchar(300) DEFAULT NULL,
  `thumb_path` varchar(300) DEFAULT NULL,
  `dir_path` varchar(300) DEFAULT NULL,
  `img_name` varchar(300) DEFAULT NULL,
  `thumb_name` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `file_type` varchar(100) DEFAULT NULL,
  `file_ext` varchar(50) DEFAULT NULL,
  `file_size` varchar(100) NOT NULL,
  `vid_url` mediumtext NOT NULL,
  `vid_title` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=145;");

    $check_data = $CI->db->query("SELECT * FROM `tbl_saas_front_cms_media` WHERE `id` = 127")->row();
    if (empty($check_data)) {

        $CI->db->query("INSERT INTO `tbl_saas_front_cms_media` (`id`, `image`, `thumb_path`, `dir_path`, `img_name`, `thumb_name`, `created_at`, `file_type`, `file_ext`, `file_size`, `vid_url`, `vid_title`) VALUES
(127, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'l4.jpg', 'l4.jpg', '2022-11-20 11:56:38', 'image', 'image/jpg', '98.19', '', ''),
(128, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'l2.jpg', 'l2.jpg', '2022-11-20 11:56:38', 'image', 'image/jpg', '72.5', '', ''),
(129, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'l1.jpg', 'l1.jpg', '2022-11-20 11:56:38', 'image', 'image/jpg', '86.27', '', ''),
(130, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'powerfull-features01.png', 'powerfull-features01.png', '2022-11-20 11:56:38', 'image', 'image/png', '20.75', '', ''),
(131, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'team03.png', 'team03.png', '2022-11-20 11:56:38', 'image', 'image/jpg', '142.07', '', ''),
(132, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'team02.jpg', 'team02.jpg', '2022-11-20 11:56:38', 'image', 'image/jpg', '88.26', '', ''),
(133, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'team01.jpg', 'team01.jpg', '2022-11-20 11:56:38', 'image', 'image/jpg', '69.41', '', ''),
(134, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'slack-3.png', 'slack-3.png', '2022-11-20 11:56:38', 'image', 'image/png', '27.59', '', ''),
(135, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', '1.jpg', '1.jpg', '2022-11-20 11:56:38', 'image', 'image/jpg', '56.14', '', ''),
(136, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', '1-1.jpg', '1-1.jpg', '2022-11-20 11:56:38', 'image', 'image/jpg', '62.59', '', ''),
(137, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', '1-2.jpg', '1-2.jpg', '2022-11-20 11:56:38', 'image', 'image/jpg', '59.38', '', ''),
(138, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'slack-1.svg', 'slack-1.svg', '2022-11-20 11:56:38', 'image', 'image/svg+xml', '45.93', '', ''),
(139, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'slack-2.svg', 'slack-2.svg', '2022-11-20 11:56:38', 'image', 'image/svg+xml', '10.31', '', ''),
(140, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'icon1.png', 'icon1.png', '2022-11-20 11:56:38', 'image', 'image/png', '0.72', '', ''),
(141, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'blog03.jpg', 'blog03.jpg', '2022-11-20 11:56:38', 'image', 'image/jpg', '120.05', '', ''),
(142, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'blog02.jpg', 'blog02.jpg', '2022-11-20 11:56:38', 'image', 'image/jpg', '180.06', '', ''),
(143, NULL, 'modules/saas/uploads/', 'modules/saas/uploads/', 'blog01.jpg', 'blog01.jpg', '2022-11-20 11:56:38', 'image', 'image/jpg', '150.62', '', '');");

    }

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_front_contact_us` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(22) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `view_status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 ROW_FORMAT=COMPACT;");

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_front_menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu` varchar(100) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `description` mediumtext,
  `open_new_tab` int NOT NULL DEFAULT '0',
  `ext_url` mediumtext NOT NULL,
  `ext_url_link` mediumtext NOT NULL,
  `publish` int NOT NULL DEFAULT '0',
  `content_type` varchar(10) NOT NULL DEFAULT 'manual',
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15;");

    $check_data = $CI->db->query("SELECT * FROM `tbl_saas_front_menus` WHERE `id` = 1")->num_rows();
    if ($check_data == 0) {
        $CI->db->query("INSERT INTO `tbl_saas_front_menus` (`id`, `menu`, `slug`, `description`, `open_new_tab`, `ext_url`, `ext_url_link`, `publish`, `content_type`, `is_active`, `created_at`) VALUES
(1, 'Main Menu', 'main-menu', 'Main menu', 0, '', '', 0, 'default', 'no', '2022-11-20 11:56:38'),
(2, 'Bottom Menu', 'bottom-menu', 'Bottom Menu', 0, '', '', 0, 'default', 'no', '2022-11-20 11:56:38');");
    }

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_front_menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_id` int NOT NULL,
  `menu` varchar(100) DEFAULT NULL,
  `page_id` int NOT NULL,
  `parent_id` int NOT NULL,
  `ext_url` mediumtext,
  `open_new_tab` int DEFAULT '0',
  `ext_url_link` mediumtext,
  `slug` varchar(200) DEFAULT NULL,
  `weight` int DEFAULT NULL,
  `publish` int NOT NULL DEFAULT '0',
  `description` mediumtext,
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59;");

    $check_data = $CI->db->query("SELECT * FROM `tbl_saas_front_menu_items` WHERE `id` = 1")->num_rows();
    if ($check_data == 0) {
        $CI->db->query("INSERT INTO `tbl_saas_front_menu_items` (`id`, `menu_id`, `menu`, `page_id`, `parent_id`, `ext_url`, `open_new_tab`, `ext_url_link`, `slug`, `weight`, `publish`, `description`, `is_active`, `created_at`) VALUES
(1, 1, 'Home', 1, 0, NULL, NULL, NULL, 'home', 1, 0, NULL, 'no', '2022-11-20 11:56:39'),
(3, 1, 'About', 128, 0, NULL, NULL, NULL, 'about', 2, 0, NULL, 'no', '2023-07-19 15:10:13'),
(16, 2, 'Home', 1, 0, NULL, NULL, NULL, 'home-1', 1, 0, NULL, 'no', '2022-11-20 11:56:39'),
(17, 2, 'About Us', 114, 0, NULL, NULL, NULL, 'about-us', 2, 0, NULL, 'no', '2022-11-20 11:56:39'),
(20, 2, 'Gallery', 117, 0, NULL, NULL, NULL, 'gallery', 6, 0, NULL, 'no', '2022-11-20 11:56:39'),
(21, 2, 'Contact Us', 76, 0, NULL, NULL, NULL, 'contact-us-1', 7, 0, NULL, 'no', '2022-11-20 11:56:39'),
(45, 1, 'Pricing', 125, 0, NULL, NULL, NULL, 'pricing', 4, 0, NULL, 'no', '2023-07-19 15:10:13'),
(48, 1, 'Contact Us', 2, 0, NULL, NULL, NULL, 'contact-us', 10, 0, NULL, 'no', '2023-07-19 15:31:58'),
(50, 1, 'Gallary', 130, 58, NULL, NULL, NULL, 'gallery', 8, 0, NULL, 'no', '2023-07-19 15:31:59'),
(51, 1, 'Blog', 129, 58, NULL, NULL, NULL, 'blog', 7, 0, NULL, 'no', '2023-07-19 15:31:58'),
(52, 1, 'Features', 132, 58, NULL, NULL, NULL, 'features', 3, 0, NULL, 'no', '2023-07-19 15:10:13'),
(57, 1, 'Affiliate', 133, 0, NULL, NULL, NULL, 'affiliate', 5, 0, NULL, 'no', '2023-07-19 15:10:13'),
(58, 1, 'Others', 0, 0, NULL, NULL, NULL, 'others', 6, 0, NULL, 'no', '2023-07-19 15:31:57');");
    }

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_front_pages` (
  `pages_id` int NOT NULL AUTO_INCREMENT,
  `page_type` varchar(10) NOT NULL DEFAULT 'manual',
  `is_homepage` int DEFAULT '0',
  `title` varchar(250) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `meta_title` mediumtext,
  `meta_description` mediumtext,
  `meta_keyword` mediumtext,
  `feature_image` varchar(200) NOT NULL,
  `description` longtext,
  `publish_date` date NOT NULL,
  `publish` int DEFAULT '0',
  `sidebar` int DEFAULT '0',
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pages_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12;");

    $check_data = $CI->db->query("SELECT * FROM `tbl_saas_front_pages` WHERE `pages_id` = 1")->num_rows();
    if ($check_data == 0) {
        $CI->db->query("INSERT INTO `tbl_saas_front_pages` (`pages_id`, `page_type`, `is_homepage`, `title`, `url`, `type`, `slug`, `meta_title`, `meta_description`, `meta_keyword`, `feature_image`, `description`, `publish_date`, `publish`, `sidebar`, `is_active`, `created_at`) VALUES
(1, 'default', 0, 'Home', 'front/home', 'page', 'home', 'Home Page', 'Home Page                                                                                                                                                                                                                         ', 'Home Page', '', '&lt;html&gt;\r\n&lt;head&gt;\r\n &lt;title&gt;&lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body data-gr-ext-disabled=\"forever\" data-gr-ext-installed=\"\" data-new-gr-c-s-check-loaded=\"14.1115.0\" data-new-gr-c-s-loaded=\"14.1115.0\"&gt;\r\n<section class=\"features-area pt-100 pb-70\" id=\"features\">\r\n<div class=\"container\">\r\n<div class=\"section-header text-center mb-50\">\r\n<h2>Don&#39;t write in this page.the page its default. edit will not working</h2>\r\n</div>\r\n</div>\r\n</section>\r\n\r\n<section class=\"our-team bg-light pt-100 pb-70\">\r\n<div class=\"container\">\r\n<div class=\"row\">\r\n<div class=\"col-xl-4 col-lg-4 col-md-4\">\r\n<div class=\"team-box mb-30\">\r\n<div class=\"progess-wrapper\">\r\n<div class=\"single-skill mb-20\">\r\n<div class=\"progress\">\r\n<div aria-valuemax=\"100\" aria-valuemin=\"0\" aria-valuenow=\"80\" class=\"progress-bar wow slideInLeft\" data-wow-delay=\".6s\" data-wow-duration=\"1s\" role=\"progressbar\" xss=\"removed\"> </div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</section>\r\n&lt;/body&gt;\r\n&lt;/html&gt;\r\n', '2019-01-14', 1, 1, 'no', '2023-07-18 13:31:55'),
(2, 'default', 0, 'Contact us', 'front/contact-us', 'page', 'contact-us', '', '', '', '', '&lt;html&gt;\r\n&lt;head&gt;\r\n &lt;title&gt;&lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body data-gr-ext-disabled=\"forever\" data-gr-ext-installed=\"\" data-new-gr-c-s-check-loaded=\"14.1115.0\" data-new-gr-c-s-loaded=\"14.1115.0\"&gt;\r\n<p>Don&#39;t write in this page.the page its default. edit will not working</p>\r\n&lt;/body&gt;\r\n&lt;/html&gt;\r\n', '2019-01-14', 0, NULL, 'no', '2023-07-18 13:31:47'),
(3, 'default', 0, 'Terms & Conditions', 'front/terms-conditions', 'page', 'terms-conditions', '', '', '', '', '<h4>Introduction as</h4>\r\n\r\n<p xss=\"removed\">These Website Standard Terms and Conditions written on this webpage shall manage your use of our website, Webiste Name accessible at Website.com.</p>\r\n\r\n<p xss=\"removed\">These Terms will be applied fully and affect to your use of this Website. By using this Website, you agreed to accept all terms and conditions written in here. You must not use this Website if you disagree with any of these Website Standard Terms and Conditions.</p>\r\n\r\n<p xss=\"removed\">Minors or people below 18 years old are not allowed to use this Website.</p>\r\n\r\n<h4>Intellectual Property Rights</h4>\r\n\r\n<p xss=\"removed\">Other than the content you own, under these Terms, Company Name and/or its licensors own all the intellectual property rights and materials contained in this Website.</p>\r\n\r\n<p xss=\"removed\">You are granted limited license only for purposes of viewing the material contained on this Website.</p>\r\n\r\n<h4>Restrictions</h4>\r\n\r\n<p xss=\"removed\">You are specifically restricted from all of the following:</p>\r\n\r\n<ul xss=\"removed\">\r\n <li xss=\"removed\">publishing any Website material in any other media;</li>\r\n <li xss=\"removed\">selling, sublicensing and/or otherwise commercializing any Website material;</li>\r\n <li xss=\"removed\">publicly performing and/or showing any Website material;</li>\r\n <li xss=\"removed\">using this Website in any way that is or may be damaging to this Website;</li>\r\n <li xss=\"removed\">using this Website in any way that impacts user access to this Website;</li>\r\n <li xss=\"removed\">using this Website contrary to applicable laws and regulations, or in any way may cause harm to the Website, or to any person or business entity;</li>\r\n <li xss=\"removed\">engaging in any data mining, data harvesting, data extracting or any other similar activity in relation to this Website;</li>\r\n <li xss=\"removed\">using this Website to engage in any advertising or marketing.</li>\r\n</ul>\r\n\r\n<p xss=\"removed\">Certain areas of this Website are restricted from being access by you and Company Name may further restrict access by you to any areas of this Website, at any time, in absolute discretion. Any user ID and password you may have for this Website are confidential and you must maintain confidentiality as well.</p>\r\n\r\n<h4>Your Content</h4>\r\n\r\n<p xss=\"removed\">In these Website Standard Terms and Conditions, â€œYour Contentâ€ shall mean any audio, video text, images or other material you choose to display on this Website. By displaying Your Content, you grant Company Name a non-exclusive, worldwide irrevocable, sub licensable license to use, reproduce, adapt, publish, translate and distribute it in any and all media.</p>\r\n\r\n<p xss=\"removed\">Your Content must be your own and must not be invading any third-party&#39;s rights. Company Name reserves the right to remove any of Your Content from this Website at any time without notice.</p>\r\n\r\n<h4>No warranties</h4>\r\n\r\n<p xss=\"removed\">This Website is provided â€œas is,â€ with all faults, and Company Name express no representations or warranties, of any kind related to this Website or the materials contained on this Website. Also, nothing contained on this Website shall be interpreted as advising you.</p>\r\n\r\n<h4>Limitation of liability</h4>\r\n\r\n<p xss=\"removed\">In no event shall Company Name, nor any of its officers, directors and employees, shall be held liable for anything arising out of or in any way connected with your use of this Website whether such liability is under contract.  Company Name, including its officers, directors and employees shall not be held liable for any indirect, consequential or special liability arising out of or in any way related to your use of this Website.</p>\r\n\r\n<h4>Indemnification<br>\r\nYou hereby indemnify to the fullest extent Company Name from and against any and/or all liabilities, costs, demands, causes of action, damages and expenses arising in any way related to your breach of any of the provisions of these Terms.</h4>\r\n\r\n<h2 xss=\"removed\"> </h2>\r\n\r\n<p xss=\"removed\"> </p>\r\n\r\n<h4>Severability</h4>\r\n\r\n<p xss=\"removed\">If any provision of these Terms is found to be invalid under any applicable law, such provisions shall be deleted without affecting the remaining provisions herein.</p>\r\n\r\n<h4>Variation of Terms</h4>\r\n\r\n<p xss=\"removed\">Company Name is permitted to revise these Terms at any time as it sees fit, and by using this Website you are expected to review these Terms on a regular basis.</p>\r\n\r\n<h4>Assignment</h4>\r\n\r\n<p xss=\"removed\">The Company Name is allowed to assign, transfer, and subcontract its rights and/or obligations under these Terms without any notification. However, you are not allowed to assign, transfer, or subcontract any of your rights and/or obligations under these Terms.</p>\r\n\r\n<h4>Entire Agreement</h4>\r\n\r\n<p xss=\"removed\">These Terms constitute the entire agreement between Company Name and you in relation to your use of this Website, and supersede all prior agreements and understandings.</p>\r\n\r\n<h4>Governing Law & Jurisdiction</h4>\r\n\r\n<p xss=\"removed\">These Terms will be governed by and interpreted in accordance with the laws of the State of Country, and you submit to the non-exclusive jurisdiction of the state and federal courts located in Country for the resolution of any disputes.</p>\r\n', '2019-01-14', 0, NULL, 'no', '2023-07-11 17:06:08'),
(4, 'default', 0, '404 not Found', 'front/404-not-found', 'page', '404-not-found', '', '                                ', '', '', '\n<div class=\"cps-main-wrap\">\n<div class=\"cps-section cps-section-padding\">\n<div class=\"container text-center\">\n<div class=\"cps-404-content\">\n<h3 class=\"cps-404-title\">Hey Error 404</h3>\n\n<p class=\"cps-404-text\">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna</p>\n<a class=\"btn btn-to-home\" href=\"#\" tppabs=\"#\">Back to Home</a></div>\n</div>\n</div>\n</div>\n', '2019-01-14', 0, NULL, 'no', '2022-11-20 11:56:39'),
(5, 'default', 0, 'Pricing', 'front/pricing', 'page', 'pricing', NULL, NULL, NULL, '', '&lt;html&gt;\r\n&lt;head&gt;\r\n &lt;title&gt;&lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body data-gr-ext-disabled=\"forever\" data-gr-ext-installed=\"\" data-new-gr-c-s-check-loaded=\"14.1115.0\" data-new-gr-c-s-loaded=\"14.1115.0\"&gt;\r\n<section class=\"cps-section cps-section-padding cps-gray-bg\" id=\"pricing\">\r\n<div class=\"container\">\r\n<div class=\"row\">\r\n<div class=\"col-md-12 col-xs-12\">\r\n<div class=\"cps-section-header text-center\">\r\n<h3 class=\"cps-section-title\">Don&#39;t write in this page.the page its default. edit will not working</h3>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"cps-section cps-section-padding\">\r\n<div class=\"container\">\r\n<div class=\"row\">\r\n<div class=\"col-md-12 text-center\"> </div>\r\n</div>\r\n</div>\r\n</div>\r\n</section>\r\n&lt;/body&gt;\r\n&lt;/html&gt;\r\n', '2019-01-14', 0, 0, 'no', '2023-07-21 19:40:18'),
(6, 'default', 0, 'Privacy Policy', 'front/privacy-policy', 'page', 'privacy-policy', NULL, NULL, NULL, '', '<section class=\"works-area bg-light pt-120 pb-100\">\r\n            <div class=\"container\"><h4 class=\"text-center\">WELCOME TO SOFTWARE ADVICE! PLEASE TAKE TIME TO READ OUR PRIVACY POLICY!</h4>\r\n\r\n\r\n\r\n<p style=\"margin-bottom: 1.25rem; text-align: center; text-rendering: optimizelegibility; padding: 0px; line-height: 1.6; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: rgb(77, 77, 77);\">This Privacy Policy covers the privacy practices of Software Advice, a Texas company, and our Affiliates (\"Software Advice\" \"we\" or \"us\"), along with the Sites on which this Privacy Policy is posted (the \"Sites\"). This Policy does not apply to those of our Affiliates, which due to their different business models, have developed their own privacy policies: CEB, Iconoculture, L2&nbsp;and&nbsp;Gartner.</p>\r\n\r\n\r\n\r\n<p style=\"margin-bottom: 1.25rem; text-align: center; text-rendering: optimizelegibility; padding: 0px; line-height: 1.6; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: rgb(77, 77, 77);\"><span style=\"line-height: inherit; font-weight: 700;\">WHAT WE DO:</span>&nbsp;We millions of users to research and evaluate the right software solutions and services for their organizations. As part of our comprehensive directory of products and services, we provide verified user reviews, original research&nbsp;and&nbsp;personalized guidance. Users may also connect directly with software vendors that choose to participate in our lead generation programs.</p>\r\n\r\n\r\n\r\n<p style=\"margin-bottom: 1.25rem; text-align: center; text-rendering: optimizelegibility; padding: 0px; line-height: 1.6; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: rgb(77, 77, 77);\"><span style=\"line-height: inherit; font-weight: 700;\">OUR PRIVACY PRACTICES:</span>&nbsp;While using our Sites and Services, and as part of the normal course of business, we may collect personal information (\"Information\") about you. We want you to understand how we use the information we collect, and that you share with us, and how you may protect your privacy while using our Sites.</p>\r\n\r\n\r\n\r\n<p style=\"margin-bottom: 1.25rem; text-align: center; text-rendering: optimizelegibility; padding: 0px; line-height: 1.6; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: rgb(77, 77, 77);\"><span style=\"line-height: inherit; font-weight: 700;\">YOUR CONSENT:</span>&nbsp;When you provide your Information to us, you consent to the collection, storage and use of your Information by us, our Affiliates and third parties in accordance with the terms set out in this Policy. \"Affiliate\" is any legal entity that controls, is controlled by or is under common control with Gartner (our parent company).</p>\r\n\r\n\r\n</div>\r\n\r\n\r\n</section>', '2019-01-14', 0, 0, 'no', '2023-07-21 19:40:21'),
(7, 'default', 0, 'About US', 'front/about-us', 'page', 'about-us', NULL, NULL, NULL, '', '\n<section class=\"powerful-features gray-bg pt-120 pb-50\" id=\"features\">\n<div class=\"container\">\n<div class=\"row\">\n<div class=\"col-xl-12\">\n<div class=\"section-header mb-80 text-center\">\n<h2>Powerful Features</h2>\n\n<p>Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod</p>\n</div>\n</div>\n\n<div class=\"col-xl-4 col-lg-4\">\n<div class=\"powerful-features-single-step mb-70 mt-80\">\n<div class=\"features-text text-right fix pr-30\"><span>Easy Instalations</span>\n\n<p>Lorem ipsum dolor sit amet consectr ncididunt ut labore et dolore</p>\n</div>\n</div>\n\n<div class=\"powerful-features-single-step mb-70\">\n<div class=\"features-text text-right fix pr-30\"><span>Real Time Customizat </span>\n\n<p>Lorem ipsum dolor sit amet consectr ncididunt ut labore et dolore</p>\n</div>\n</div>\n\n<div class=\"powerful-features-single-step mb-70\">\n<div class=\"features-text text-right fix pr-30\"><span>Customer Support</span>\n\n<p>Lorem ipsum dolor sit amet consectr ncididunt ut labore et dolore</p>\n</div>\n</div>\n</div>\n\n<div class=\"col-xl-4 col-lg-4\">\n<div class=\"powerfull-features-img\"><img alt=\"\" src=\"http://localhost/client_moumen/uploads/gallery/powerfull-features01.png\"></div>\n</div>\n\n<div class=\"col-xl-4 col-lg-4\">\n<div class=\"powerful-features-single-step mb-70 mt-80\">\n<div class=\"features-text pl-30 fix\"><span>Easy Editable</span>\n\n<p>Lorem ipsum dolor sit amet consectr ncididunt ut labore et dolore</p>\n</div>\n</div>\n\n<div class=\"powerful-features-single-step mb-70\">\n<div class=\"features-text pl-30 fix\"><span>Clean & Unique Design</span>\n\n<p>Lorem ipsum dolor sit amet consectr ncididunt ut labore et dolore</p>\n</div>\n</div>\n\n<div class=\"powerful-features-single-step mb-70\">\n<div class=\"features-text pl-30 fix\"><span>Clean Code</span>\n\n<p>Lorem ipsum dolor sit amet consectr ncididunt ut labore et dolore</p>\n</div>\n</div>\n</div>\n</div>\n</div>\n</section>\n\n<section class=\"powerful-features-video pt-205 pb-130\">\n<div class=\"container\">\n<div class=\"powerfull-features-video position-relative\"><img alt=\"\" src=\"http://localhost/client_moumen/uploads/gallery/powerfull-features-video.jpg\"></div>\n</div>\n</section>\n\n', '2019-01-14', 0, 0, 'no', '2023-07-21 19:40:24'),
(8, 'default', 0, 'Blog', 'front/blog', 'page', 'blog', NULL, NULL, NULL, '', '&lt;html&gt;\r\n&lt;head&gt;\r\n &lt;title&gt;&lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body data-gr-ext-disabled=\"forever\" data-gr-ext-installed=\"\" data-new-gr-c-s-check-loaded=\"14.1115.0\" data-new-gr-c-s-loaded=\"14.1115.0\"&gt;\r\n<section class=\"blog-area pt-120 pb-65\" id=\"latest-blog\">\r\n<div class=\"container\">&lt;!-- Section-header start --&gt;\r\n<div class=\"section-header text-center mb-80\">\r\n<h2>Don&#39;t write in this page.the page its default. edit will not working</h2>\r\n</div>\r\n\r\n<div class=\"row\">\r\n<div class=\"col-xl-4 col-lg-4\">&lt;!-- Blog-wrapper end --&gt;</div>\r\n</div>\r\n</div>\r\n</section>\r\n&lt;/body&gt;\r\n&lt;/html&gt;\r\n', '2019-01-14', 0, 0, 'no', '2023-07-21 19:40:27'),
(9, 'default', 0, 'Gallery', 'front/gallery', 'page', 'gallery', NULL, NULL, NULL, '', '&lt;html&gt;\r\n&lt;head&gt;\r\n &lt;title&gt;&lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body data-gr-ext-disabled=\"forever\" data-gr-ext-installed=\"\" data-new-gr-c-s-check-loaded=\"14.1115.0\" data-new-gr-c-s-loaded=\"14.1115.0\"&gt;&lt;!-- our-gallery strat--&gt;\r\n<div class=\"our-gallery pt-120 pb-100\">\r\n<div class=\"container\">\r\n<div class=\"section-header mb-80 text-center\">\r\n<h2>Don&#39;t write in this page.the page its default. edit will not working</h2>\r\n</div>\r\n</div>\r\n</div>\r\n&lt;!-- our-gallery end--&gt;&lt;/body&gt;\r\n&lt;/html&gt;\r\n', '2019-01-14', 0, 0, 'no', '2023-07-21 19:40:30'),
(10, 'default', 0, 'Features', 'front/features', 'page', 'features', NULL, NULL, NULL, '', '&lt;html&gt;\r\n&lt;head&gt;\r\n &lt;title&gt;&lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body data-gr-ext-disabled=\"forever\" data-gr-ext-installed=\"\" data-new-gr-c-s-check-loaded=\"14.1115.0\" data-new-gr-c-s-loaded=\"14.1115.0\"&gt;\r\n<div class=\"cps-section cps-section-padding\" id=\"service-box\">\r\n<div class=\"container\">\r\n<div class=\"row\">\r\n<div class=\"col-md-12\">\r\n<div class=\"cps-section-header text-center style-4\">\r\n<h3 class=\"cps-section-title\">Don&#39;t write in this page.the page its default. edit will not working</h3>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n&lt;/body&gt;\r\n&lt;/html&gt;\r\n', '2021-06-20', 0, 0, 'no', '2023-07-21 19:40:32'),
(11, 'default', 0, 'Affiliate Program', 'front/affiliate-program', 'page', 'affiliate-program', NULL, NULL, NULL, '', '&lt;html&gt;\r\n&lt;head&gt;\r\n &lt;title&gt;&lt;/title&gt;\r\n&lt;/head&gt;\r\n&lt;body data-gr-ext-disabled=\"forever\" data-gr-ext-installed=\"\" data-new-gr-c-s-check-loaded=\"14.1115.0\" data-new-gr-c-s-loaded=\"14.1115.0\"&gt;\r\n<p>Don&#39;t write in this page.the page its default. edit will not working</p>\r\n&lt;/body&gt;\r\n&lt;/html&gt;\r\n', '0000-00-00', 0, 0, 'no', '2023-07-21 19:40:36');");
    }

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_front_pages_contents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `page_id` int DEFAULT NULL,
  `content_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB;");

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_front_slider` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(350) DEFAULT NULL,
  `subtitle` text NOT NULL,
  `description` text,
  `slider_bg` varchar(255) NOT NULL,
  `slider_img` text,
  `button_text_1` varchar(255) DEFAULT NULL,
  `button_text_2` varchar(255) DEFAULT NULL,
  `button_icon_1` varchar(100) DEFAULT NULL,
  `button_icon_2` varchar(100) DEFAULT NULL,
  `button_link_1` varchar(255) DEFAULT NULL,
  `button_link_2` varchar(255) DEFAULT NULL,
  `sort` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 ROW_FORMAT=COMPACT;");

    $check_data = $CI->db->query("SELECT * FROM `tbl_saas_front_slider`");
    if ($check_data->num_rows() == 0) {
        $CI->db->query("INSERT INTO `tbl_saas_front_slider` (`id`, `title`, `subtitle`, `description`, `slider_bg`, `slider_img`, `button_text_1`, `button_text_2`, `button_icon_1`, `button_icon_2`, `button_link_1`, `button_link_2`, `sort`, `status`) VALUES
(1, 'Better Management  <br>  Less Expense', 'Not sure about Pro? Try trial first!', '                                                                                                <div class=\"slider-intro-list mb-40\">                                        <ul>                                            <li><span class=\"fal fa-check\"></span>Unlimited Projects.</li>                                            <li><span class=\"fal fa-check\"></span>Unlimited Team Members.</li>                                            <li><span class=\"fal fa-check\"></span>Unlimited Disk Space.</li>                                        </ul>                                    </div>                                                                                    ', 'modules/saas/uploads/slider-bg-01.jpeg', 'modules/saas/uploads/slider-thum-01.png', 'Take Off', '14 days free trial', NULL, 'fal fa-user-alt mr-1', '', '', 0, 1),
(2, 'Business Growth', 'Not sure about Pro? Try trial first!', '<div class=\"slider-intro-list mb-40\">                                        <ul>\n                                            <li><span class=\"fal fa-check\"></span>Unlimited Projects.</li>\n                                            <li><span class=\"fal fa-check\"></span>Unlimited Team Members.</li>\n                                            <li><span class=\"fal fa-check\"></span>Unlimited Disk Space.</li>\n                                        </ul>\n                                    </div>', 'modules/saas/uploads/14_1.png', 'modules/saas/uploads/mock-1.png', 'Start Live Demo', '', NULL, NULL, '', '', NULL, 1),
(3, 'Financial Service', 'Try trial first!', '<div class=\"slider-intro-list mb-40\">                                        <ul>\n                                            <li><span class=\"fal fa-check\"></span>Unlimited Projects.</li>\n                                            <li><span class=\"fal fa-check\"></span>Unlimited Team Members.</li>\n                                            <li><span class=\"fal fa-check\"></span>Unlimited Disk Space.</li>\n                                        </ul>\n                                    </div>', 'modules/saas/uploads/wonderfull-bg01.jpg', 'modules/saas/uploads/imac.png', 'See Live Demp', '14 days free trial', NULL, NULL, '', '', NULL, 1),
(4, 'Powerful services', 'We believe we have created the most efficient SaaS landing page for your users', '                                                                                                <div class=\"slider-intro-list mb-40\">                                        <ul>                                            <li><span class=\"fal fa-check\"></span> Unlimited Expense.</li>                                            <li><span class=\"fal fa-check\"></span> Unlimited Transaction.</li>                                            <li><span class=\"fal fa-check\"></span>Unlimited Deposit.</li><li><span class=\"fal fa-check\"></span>Unlimited Transfer.</li>                                        </ul>                                    </div>                                                                                    ', 'modules/saas/uploads/login_cover.jpg', 'modules/saas/uploads/dashboard-2.png', 'Take Off', '10 days free trial', NULL, NULL, '', '', NULL, 1);");
    }

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_menu` (
  `menu_id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `parent` int NOT NULL DEFAULT '0',
  `sort` int NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '1' COMMENT '1= active 0=inactive',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=179;");

    $check_data = $CI->db->query("SELECT * FROM `tbl_saas_menu`");
    if ($check_data->num_rows() == 0) {
        $CI->db->query("INSERT INTO `tbl_saas_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `time`, `status`) VALUES
(1, 'dashboard', 'saas', 'fa fa-dashboard', 0, 1, '2022-11-20 11:56:39', 1),
(2, 'packages', 'saas/packages', 'fa fa-shield', 0, 2, '2022-11-20 11:56:39', 1),
(3, 'companies', 'saas/companies', 'icon icon-people', 0, 3, '2022-11-20 11:56:39', 1),
(4, 'invoices', 'saas/companies/invoices', 'fa fa-book', 0, 4, '2022-11-20 11:56:39', 1),
(5, 'super_admin', 'saas/super_admin', 'icon icon-people', 0, 5, '2022-11-20 11:56:39', 1),
(7, 'settings', 'saas/settings', 'fa fa-gears', 0, 7, '2022-11-20 11:56:39', 1),
(8, 'faq', 'saas/faq', 'fa fa-user-md', 0, 8, '2022-11-20 11:56:39', 1),
(9, 'coupon', 'saas/coupon', 'fa fa-gift', 0, 6, '2022-11-20 11:56:39', 1),
(10, 'assign_package', 'assignPackage', 'fa fa-sign-in', 0, 2, '2022-11-20 11:56:39', 1),
(11, 'frontcms', '#', 'fa fa-empire', 0, 6, '2022-11-20 11:56:39', 1),
(12, 'menu', 'saas/frontcms/menus', 'fa fa-outdent', 11, 0, '2022-11-20 11:56:39', 1),
(13, 'mpage', 'saas/frontcms/page', 'fa fa-table', 11, 1, '2022-11-20 11:56:39', 1),
(14, 'media', 'saas/frontcms/media', 'fa fa-image', 11, 2, '2022-11-20 11:56:39', 1),
(15, 'slider', 'saas/frontcms/settings/slider', 'fa fa-sliders', 11, 3, '2022-11-20 11:56:39', 1),
(16, 'settings', 'saas/frontcms/settings', 'fa fa-cogs', 11, 5, '2022-11-20 11:56:39', 1),
(17, 'abouts', 'saas/frontcms/abouts', 'fa fa-circle-o', 11, 10, '2023-07-18 16:28:48', 1),
(18, 'features', 'saas/frontcms/features', 'fa fa-circle-o', 11, 9, '2023-07-18 16:28:51', 1),
(19, 'discovers', 'saas/frontcms/discovers', 'fa fa-circle-o', 11, 8, '2023-07-18 16:28:53', 1),
(20, 'creatives', 'saas/frontcms/creatives', 'fa fa-circle-o', 11, 7, '2023-07-18 16:28:56', 1),
(21, 'blogs', 'saas/frontcms/blogs', 'fa fa-circle-o', 11, 6, '2023-07-18 16:28:58', 1),
(22, 'gallery', 'saas/frontcms/gallery', 'fa fa-circle-o', 11, 14, '2023-07-18 16:29:01', 1),
(23, 'brand', 'saas/frontcms/brand', 'fa fa-circle-o', 11, 10, '2023-07-18 16:29:03', 1),
(24, 'reviews', 'saas/frontcms/reviews', 'fa fa-circle-o', 11, 9, '2023-07-18 16:29:11', 1);");
    }

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_packages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `monthly_price` decimal(18,2) DEFAULT '0.00',
  `lifetime_price` decimal(18,2) DEFAULT '0.00',
  `yearly_price` decimal(18,2) DEFAULT '0.00',
  `sort` int DEFAULT NULL,
  `staff_no` varchar(20) DEFAULT NULL,
  `additional_staff_no` varchar(20) DEFAULT NULL,
  `client_no` varchar(20) DEFAULT NULL,
  `additional_client_no` varchar(20) DEFAULT NULL,
  `project_no` varchar(20) DEFAULT NULL,
  `additional_project_no` varchar(20) DEFAULT NULL,
  `invoice_no` varchar(20) DEFAULT NULL,
  `additional_invoice_no` varchar(20) DEFAULT NULL,
  `leads_no` varchar(50) DEFAULT NULL,
  `additional_leads_no` varchar(50) DEFAULT NULL,
  `expense_no` varchar(50) DEFAULT NULL,
  `additional_expense_no` varchar(50) DEFAULT NULL,
  `contract_no` varchar(50) DEFAULT NULL,
  `additional_contract_no` varchar(50) DEFAULT NULL,
  `estimate_no` varchar(50) NOT NULL,
  `additional_estimate_no` varchar(50) DEFAULT NULL,
  `calendar` varchar(50) DEFAULT NULL,
  `credit_note_no` varchar(50) DEFAULT NULL,
  `additional_credit_note_no` varchar(50) DEFAULT NULL,
  `proposal_no` varchar(50) DEFAULT NULL,
  `additional_proposal_no` varchar(50) DEFAULT NULL,
  `tickets` varchar(50) DEFAULT NULL,
  `additional_tickets` varchar(50) DEFAULT NULL,
  `tasks_no` varchar(50) DEFAULT NULL,
  `additional_tasks_no` varchar(50) DEFAULT NULL,
  `item_no` varchar(50) DEFAULT NULL,
  `additional_item_no` varchar(50) DEFAULT NULL,
  `reports` varchar(50) DEFAULT NULL,
  `disk_space` varchar(100) DEFAULT NULL,
  `additional_disk_space` varchar(100) DEFAULT NULL,
  `trial_period` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `recommended` enum('Yes','No') DEFAULT 'No',
  `trail_period` varchar(20) DEFAULT NULL,
  `allowed_payment_modes` text DEFAULT NULL,
  `modules` text DEFAULT NULL,
  `allowed_themes` text DEFAULT NULL,
  `disabled_modules` text DEFAULT NULL,
  `custom_domain` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6;");

    $check_data = $CI->db->query("SELECT * FROM `tbl_saas_packages`")->num_rows();
    if ($check_data == 0) {
        $CI->db->query("INSERT INTO `tbl_saas_packages` (`id`, `name`, `monthly_price`, `lifetime_price`, `yearly_price`, `sort`, `staff_no`, `additional_staff_no`, `client_no`, `additional_client_no`, `project_no`, `additional_project_no`, `invoice_no`, `additional_invoice_no`, `leads_no`, `additional_leads_no`, `expense_no`, `additional_expense_no`, `contract_no`, `additional_contract_no`, `estimate_no`, `additional_estimate_no`, `calendar`, `credit_note_no`, `additional_credit_note_no`, `proposal_no`, `additional_proposal_no`, `tickets`, `additional_tickets`, `tasks_no`, `additional_tasks_no`, `item_no`, `additional_item_no`, `reports`, `disk_space`, `additional_disk_space`, `trial_period`, `description`, `status`, `recommended`, `trail_period`, `allowed_payment_modes`, `modules`, `allowed_themes`, `custom_domain`) VALUES
(1, 'BIZTEAM', '20.00', '63.00', '3000.00', 4, '10', NULL, '', NULL, '0', NULL, '0', NULL, '0', NULL, '0', NULL, '0', NULL, '', NULL, 'Yes', '', NULL, '', NULL, '0', NULL, '0', NULL, '', NULL, 'Yes', '50GB', NULL, '30', '', 'published', 'No', NULL, 'a:0:{}', 'a:0:{}', NULL, NULL),
(2, 'BIZPLUS', '20.00', '63.00', '300.00', 3, '5', '2', '30', '3', '', NULL, '', NULL, '', NULL, '', NULL, '', NULL, '', NULL, 'Yes', '', NULL, '', NULL, '2', NULL, '', NULL, '', NULL, 'Yes', '', NULL, '30', 'sad', 'published', 'Yes', NULL, 'a:1:{i:0;s:1:\"1\";}', 'a:2:{i:0;s:10:\"menu_setup\";i:1;s:7:\"surveys\";}', 'a:2:{i:0;s:7:\"appline\";i:1;s:7:\"appvila\";}', 'Yes'),
(3, 'FREE PLAN', '300.00', '45.00', '36.00', 1, '2', NULL, '1000', NULL, '30', NULL, '0', NULL, '1000', NULL, '50', NULL, '1', NULL, '0', NULL, 'Yes', '0', NULL, '0', NULL, '0', NULL, '100', NULL, 'No', NULL, 'Yes', '5GB', NULL, '30', '<p><br></p><p><br></p><p><br></p>', 'published', 'No', NULL, NULL, NULL, NULL, NULL),
(4, 'BIZPLAN', '300.00', '69.00', '69.00', 2, '51', '10', '2000', NULL, '100', '1', '100', NULL, '1000', NULL, '0', '3', '1', NULL, '', NULL, 'Yes', '', NULL, '4', NULL, '1', NULL, '500', NULL, '5', NULL, 'Yes', '1GB', '1', '14', 'ds', 'published', 'No', NULL, 'a:1:{i:0;s:1:\"1\";}', 'a:6:{i:0;s:7:\"exports\";i:1;s:6:\"backup\";i:2;s:5:\"goals\";i:3;s:10:\"menu_setup\";i:4;s:7:\"surveys\";i:5;s:11:\"theme_style\";}', 'a:3:{i:0;s:7:\"appline\";i:1;s:7:\"appvila\";i:2;s:5:\"basic\";}', 'Yes'),
(5, 'TEAMPLUS', '0.00', '600.00', '10.00', 5, '500', NULL, '0', NULL, '0', NULL, '0', NULL, '0', NULL, '0', NULL, '0', NULL, '0', NULL, 'No', '0', NULL, '0', NULL, '0', NULL, '0', NULL, '0', NULL, 'Yes', '100GB', NULL, '15', 'ssdfffds', 'published', 'No', NULL, NULL, NULL, NULL, NULL);");
    }

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_package_field` (
  `field_id` int NOT NULL AUTO_INCREMENT,
  `field_label` varchar(250) NOT NULL,
  `field_name` varchar(250) NOT NULL,
  `field_type` enum('text','textarea','checkbox','radio','date') NOT NULL DEFAULT 'text',
  `help_text` varchar(250) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `order` int NOT NULL,
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16;");

    $check_data = $CI->db->query("SELECT * FROM `tbl_saas_package_field`")->num_rows();
    if ($check_data == 0) {
        $CI->db->query("INSERT INTO `tbl_saas_package_field` (`field_id`, `field_label`, `field_name`, `field_type`, `help_text`, `status`, `order`) VALUES
(1, 'staff', 'staff_no', 'text', 'use 0 = unlimited and empty = not included', 'active', 1),
(2, 'client_no', 'client_no', 'text', 'use 0 = unlimited and empty = not included', 'active', 4),
(3, 'leads', 'leads_no', 'text', 'use 0 = unlimited and empty = not included', 'active', 5),
(4, 'expense', 'expense_no', 'text', 'use 0 = unlimited and empty = not included', 'active', 6),
(5, 'tasks_no', 'tasks_no', 'text', 'use 0 = unlimited and empty = not included', 'active', 7),
(6, 'projects', 'project_no', 'text', 'use 0 = unlimited and empty = not included', 'active', 8),
(7, 'invoice_no', 'invoice_no', 'text', 'use 0 = unlimited and empty = not included', 'active', 9),
(8, 'contracts', 'contract_no', 'text', 'use 0 = unlimited and empty = not included', 'active', 10),
(9, 'estimates', 'estimate_no', 'text', 'use 0 = unlimited and empty = not included', 'active', 11),
(10, 'credit_notes', 'credit_note_no', 'text', 'use 0 = unlimited and empty = not included', 'active', 12),
(11, 'proposals', 'proposal_no', 'text', 'use 0 = unlimited and empty = not included', 'active', 13),
(12, 'items', 'item_no', 'text', 'use 0 = unlimited and empty = not included', 'active', 14),
(13, 'tickets', 'tickets', 'text', 'use 0 = unlimited and empty = not included', 'active', 15),
(14, 'disk_space', 'disk_space', 'text', 'Include it with MB,GB,TB etc like 1GB.', 'active', 3),
(15, 'custom_domain', 'custom_domain', 'checkbox', '', 'active', 2);");
    }

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_temp_payment` (
  `temp_payment_id` int NOT NULL AUTO_INCREMENT,
  `companies_id` int NOT NULL,
  `package_id` int NOT NULL,
  `billing_cycle` varchar(30) NOT NULL,
  `expired_date` varchar(30) NOT NULL,
  `coupon_code` varchar(30) DEFAULT NULL,
  `invoice_id` int NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `clientid` int NOT NULL,
  `hash` varchar(32) NOT NULL,
  `new_module` text,
  `new_limit` text,
  PRIMARY KEY (`temp_payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;");

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_package_module` (
  `package_module_id` int NOT NULL AUTO_INCREMENT,
  `module_name` varchar(200) NOT NULL,
  `module_title` varchar(200) NOT NULL,
  `price` decimal(18,5) NOT NULL DEFAULT '0.00000',
  `preview_image` text,
  `preview_video_url` text,
  `descriptions` text,
  `module_order` int DEFAULT NULL,
  `status` enum('published','unpublished') DEFAULT 'published',
  PRIMARY KEY (`package_module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;");

    $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_domain_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `custom_domain` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;");

    $CI->db->query("INSERT INTO " . db_prefix() . "options (`id`, `name`, `value`, `autoload`) VALUES
(NULL, 'saas_companyname', 'Perfect SaaS', 1),
(NULL, 'saas_allowed_files', '.png,.jpg,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt', 1),
(NULL, 'saas_company_logo', 'd33bf9a5e1bb7471b7534ecfe8e12ff1.png', 1),
(NULL, 'saas_company_logo_dark', 'd85f63a4a464cba0dcc2e84805f209c4.png', 1),
(NULL, 'saas_favicon', 'favicon.png', 1),
(NULL, 'saas_dateformat', 'Y-m-d|%Y-%m-%d', 1),
(NULL, 'saas_time_format', '24', 1),
(NULL, 'saas_default_timezone', 'Asia/Dhaka', 1),
(NULL, 'saas_active_language', 'english', 1),
(NULL, 'saas_mail_engine', 'phpmailer', 1),
(NULL, 'saas_email_protocol', 'smtp', 1),
(NULL, 'saas_microsoft_mail_client_id', 'd', 1),
(NULL, 'saas_microsoft_mail_client_secret', '+XMWQ0rwL5X7zYHiyLQ=', 1),
(NULL, 'saas_microsoft_mail_azure_tenant_id', 'd', 1),
(NULL, 'saas_google_mail_client_id', 'e', 1),
(NULL, 'saas_google_mail_client_secret', '125455/=', 1),
(NULL, 'saas_smtp_encryption', 'tls', 1),
(NULL, 'saas_smtp_host', 'd', 1),
(NULL, 'saas_smtp_port', 'd', 1),
(NULL, 'saas_smtp_email', 'e', 1),
(NULL, 'saas_smtp_username', 'admin@gmail.com', 1),
(NULL, 'saas_smtp_password', '125455', 1),
(NULL, 'saas_smtp_email_charset', 'e', 1),
(NULL, 'saas_bcc_emails', 'e', 1),
(NULL, 'saas_email_signature', 'e', 1),
(NULL, 'saas_email_header', 'e', 1),
(NULL, 'saas_email_footer', 'e', 1),
(NULL, 'saas_server', 'local', 1),
(NULL, 'saas_cpanel_host', 'maluzts.sddsaddsa', 1),
(NULL, 'saas_cpanel_port', '2083', 1),
(NULL, 'saas_cpanel_username', 'maluztxf', 1),
(NULL, 'saas_cpanel_password', 'b7ee3ff4d0a87e46445ce387efb7e853e293a42139f77224683237b9e6d28a8a9209e06d3eb36dd654088aa2f47dc34aa8fc57151dadf1ce89a28950a9ba03d22EqSoFSJGPcSbPWpxGvenLQ5bAncHwOrMQpJJ7moaYA=', 1),
(NULL, 'saas_cpanel_output', 'json', 1),
(NULL, 'saas_plesk_host', '45.77.230.168', 1),
(NULL, 'saas_plesk_username', 'admin2', 1),
(NULL, 'saas_plesk_password', '0d5c9a888b93b38243ee37557238881851ed0627b83dfe33c135f6df01c0183fe79a823a77d2c243b734e8c2140e3bf20db727a01f85298f22daa3a36bcb76e0zx5yGhWV/vlsh/8vbFPwNqbA0ADJd4CJ3J1HqvlZ63M=', 1),
(NULL, 'saas_plesk_webspace_id', 'asdsdasda', 1),
(NULL, 'saas_paymentmethod_authorize_acceptjs_label', 'Authorize.net Accept.js', 1),
(NULL, 'saas_paymentmethod_authorize_acceptjs_public_key', 'asddsa', 1),
(NULL, 'saas_paymentmethod_authorize_acceptjs_api_login_id', 'sdadsa', 1),
(NULL, 'saas_paymentmethod_authorize_acceptjs_api_transaction_key', '', 1),
(NULL, 'saas_paymentmethod_authorize_acceptjs_description_dashboard', 'asddsa', 1),
(NULL, 'saas_paymentmethod_authorize_acceptjs_currencies', 'sdadsa', 1),
(NULL, 'saas_paymentmethod_instamojo_label', 'Instamojo', 1),
(NULL, 'saas_paymentmethod_instamojo_fee_fixed', '', 1),
(NULL, 'saas_paymentmethod_instamojo_fee_percent', '', 1),
(NULL, 'saas_paymentmethod_instamojo_api_key', '', 1),
(NULL, 'saas_paymentmethod_instamojo_auth_token', '', 1),
(NULL, 'saas_paymentmethod_instamojo_description_dashboard', '', 1),
(NULL, 'saas_paymentmethod_mollie_label', 'Mollie', 1),
(NULL, 'saas_paymentmethod_mollie_api_key', '', 1),
(NULL, 'saas_paymentmethod_mollie_description_dashboard', '', 1),
(NULL, 'saas_paymentmethod_mollie_currencies', '', 1),
(NULL, 'saas_paymentmethod_paypal_braintree_label', 'Braintree', 1),
(NULL, 'saas_paymentmethod_paypal_braintree_merchant_id', '', 1),
(NULL, 'saas_paymentmethod_paypal_braintree_api_public_key', '', 1),
(NULL, 'saas_paymentmethod_paypal_braintree_api_private_key', '', 1),
(NULL, 'saas_paymentmethod_paypal_braintree_currencies', '', 1),
(NULL, 'saas_paymentmethod_paypal_checkout_label', 'Paypal Smart Checkout', 1),
(NULL, 'saas_paymentmethod_paypal_checkout_fee_fixed', '', 1),
(NULL, 'saas_paymentmethod_paypal_checkout_fee_percent', '', 1),
(NULL, 'saas_paymentmethod_paypal_checkout_client_id', '', 1),
(NULL, 'saas_paymentmethod_paypal_checkout_secret', '', 1),
(NULL, 'saas_paymentmethod_paypal_checkout_payment_description', '', 1),
(NULL, 'saas_paymentmethod_paypal_checkout_currencies', '', 1),
(NULL, 'saas_paymentmethod_paypal_label', 'Paypal', 1),
(NULL, 'saas_paymentmethod_paypal_fee_fixed', '', 1),
(NULL, 'saas_paymentmethod_paypal_fee_percent', '', 1),
(NULL, 'saas_paymentmethod_paypal_username', '', 1),
(NULL, 'saas_paymentmethod_paypal_password', '', 1),
(NULL, 'saas_paymentmethod_paypal_signature', '', 1),
(NULL, 'saas_paymentmethod_paypal_description_dashboard', '', 1),
(NULL, 'saas_paymentmethod_paypal_currencies', '', 1),
(NULL, 'saas_paymentmethod_payu_money_label', 'PayU Money', 1),
(NULL, 'saas_paymentmethod_payu_money_fee_fixed', '', 1),
(NULL, 'saas_paymentmethod_payu_money_fee_percent', '', 1),
(NULL, 'saas_paymentmethod_payu_money_key', '', 1),
(NULL, 'saas_paymentmethod_payu_money_salt', '', 1),
(NULL, 'saas_paymentmethod_payu_money_description_dashboard', '', 1),
(NULL, 'saas_paymentmethod_payu_money_currencies', '', 1),
(NULL, 'saas_paymentmethod_stripe_label', 'Stripe Checkout', 1),
(NULL, 'saas_paymentmethod_stripe_fee_fixed', '', 1),
(NULL, 'saas_paymentmethod_stripe_fee_percent', '', 1),
(NULL, 'saas_paymentmethod_stripe_api_publishable_key', '', 1),
(NULL, 'saas_paymentmethod_stripe_api_secret_key', '', 1),
(NULL, 'saas_paymentmethod_stripe_description_dashboard', '', 1),
(NULL, 'saas_paymentmethod_stripe_currencies', '', 1),
(NULL, 'saas_paymentmethod_stripe_ideal_label', 'Stripe iDEAL', 1),
(NULL, 'saas_paymentmethod_stripe_ideal_api_secret_key', '', 1),
(NULL, 'saas_paymentmethod_stripe_ideal_api_publishable_key', '', 1),
(NULL, 'saas_paymentmethod_stripe_ideal_description_dashboard', '', 1),
(NULL, 'saas_paymentmethod_stripe_ideal_statement_descriptor', '', 1),
(NULL, 'saas_paymentmethod_two_checkout_label', '2Checkout', 1),
(NULL, 'saas_paymentmethod_two_checkout_fee_fixed', '', 1),
(NULL, 'saas_paymentmethod_two_checkout_fee_percent', '', 1),
(NULL, 'saas_paymentmethod_two_checkout_merchant_code', '', 1),
(NULL, 'saas_paymentmethod_two_checkout_secret_key', '', 1),
(NULL, 'saas_paymentmethod_two_checkout_description', '', 1),
(NULL, 'saas_paymentmethod_two_checkout_currencies', '', 1),
(NULL, 'saas_front_pricing_title', 'Our Pricing Rates', 1),
(NULL, 'saas_front_pricing_description', 'Start working with Perfect SaaS that can provide everything you need to generate awareness, drive traffic, connect.', 1),
(NULL, 'saas_front_slider', '1', 1),
(NULL, 'home_slider_speed', '10', 1),
(NULL, 'saas_server_wildcard', 'on', 1),
(NULL, 'enable_affiliate', 'TRUE', 1),
(NULL, 'affiliate_commission_amount', '203', 1),
(NULL, 'payment_rules_for_affiliates', 'no_payment_required', 1),
(NULL, 'withdrawal_payment_method', 'a:2:{i:0;s:1:\"1\";i:1;s:9:\"instamojo\";}', 1),
(NULL, 'affiliate_commission_type', 'percentage', 1),
(NULL, 'affiliate_rule', 'only_first_subscription', 1),
(NULL, 'saas_calculate_disk_space', 'both', 1),
(NULL, 'saas_reserved_tenant', 'admin,administrator,root,perfectsaas,acme,saaserp,hack,www', 1),
(NULL, 'custom_domain_title', 'Custom Domain Integration Guideline', 1),
(NULL, 'saas_default_theme', 'default', 1),
(NULL, 'custom_domain_details', '<div>Integrating a custom domain with DNS settings typically involves the following steps:</div>\n<div></div>\n<ol>\n<li><b>Purchase a domain name:</b><span> </span>You\'ll need to purchase a domain name from a domain registrar such as GoDaddy, Namecheap, or Google Domains.</li>\n<li><b>Obtain your DNS records:<span> </span></b>Once you have a domain provider, they will provide you with<span> </span><b>DNS records</b><span> </span>that you\'ll need to configure for your domain. These records will typically include an<span> </span><b>A record & CNAME record</b>.</li>\n<li><b>Configure DNS settings:</b><span> </span>Log in to your domain registrar\'s account and navigate to the DNS management section.You need to add 2 new DNS record, choose the record type (<b>A & CNAME</b>) & follow the settings below<span> </span><b>(<span>DNS Settings One </span><span>& </span><span>DNS Settings Two</span>)</b>, and enter the corresponding value.</li>\n<li><b>Wait for propagation:</b><span> </span>Once you\'ve made the changes to your DNS settings, it can take up to 48 hours for the changes to propagate throughout the internet. During this time, your website or application may be temporarily unavailable.</li>\n</ol>\n<div>That\'s it! Once your DNS records have propagated, your custom domain should be fully integrated with our application.</div>', 1),
(NULL, 'minimum_payout_amount', '20', 1);");

// SaaS Email
    $welcome_email = [
        'type' => 'saas',
        'slug' => 'saas-welcome-mail',
        'name' => 'SaaS Welcome Email',
        'subject' => 'Welcome aboard',
        'message' => 'Dear {name},<br/><br/>
    Thank you for registering on the  <b>{companyname}</b> platform. We are happy to have you on board.<br/><br/> 
    We just wanted to say welcome. We are thrilled to have you on board and look forward to working with you.<br/><br/>
    Please let us know if you have any questions or concerns. We are always happy to help.<br/><br/>
    
    <br/><br/>
    Please click on the link below to activate your account.<br/><br/>
    <big><strong><a href="{activation_url}">Start your registration</a></strong></big><br/><br/>
    link does not work? copy and paste this link into your browser:<br/>
    <big><strong><a href="{activation_url}">{activation_url}</a></strong></big><br/><br/>
    
    We listed your company details below, make sure you keep them safe your account details
    <br/><br/>
    please follow this link:<big><strong><a href="{company_url}">View company url</a></strong></big><br/><br/>
    link does not work? copy and paste this link into your browser:<br/>
    <big><strong><a href="{company_url}">{company_url}</a></strong></big><br/><br/>
   
    Best regards,<br/>
    {email_signature}<br/>
    (This is an automated email, so please do not reply to this.)',
    ];

    $token_activate_account = [
        'type' => 'saas',
        'slug' => 'saas-token-activate-account',
        'name' => 'SaaS Token Activate Account',
        'subject' => 'Activate your account',
        'message' => 'Dear {name},<br/><br/>   
    Thank you for registering on the  <b>{companyname}</b> platform. We are happy to have you on board.<br/><br/>
    To verify your Your activation token please copy the activation code: {activation_token} and paste it into the activation form.<br/><br/>
    
    Please click on the link below to activate your account.<br/><br/>
    <big><strong><a href="{activation_url}">Start your registration</a></strong></big><br/><br/>
    link does not work? copy and paste this link into your browser:<br/>
    <big><strong><a href="{activation_url}">{activation_url}</a></strong></big><br/><br/>
    Please activate your account within 48 hours. Otherwise, your registration will be canceled.<br/><br/>
    Best regards,<br/>
    {email_signature}<br/>
    (This is an automated email, so please do not reply to this.)',
    ];

    $faq_request_email = [
        'type' => 'saas',
        'slug' => 'saas-faq-request-email',
        'name' => 'SaaS FAQ Request Email',
        'subject' => 'FAQ Request',
        'message' => 'Hi there,,<br/><br/>
    {name} has requested a FAQ.<br/><br/>
    <b>Question:</b><br/>
    {question}<br/><br/>
    
    you can answer this question by clicking on the link below.<br/><br/>
    <big><strong><a href="{faq_url}">Answer this question</a></strong></big><br/><br/>
    link does not work? copy and paste this link into your browser:<br/>
    <big><strong><a href="{faq_url}">{faq_url}</a></strong></big><br/><br/>
    
    Best regards,<br/>
    {email_signature}<br/>
    (This is an automated email, so please do not reply to it.)',
    ];

    $assign_new_package = [
        'type' => 'saas',
        'slug' => 'saas-assign-new-package',
        'name' => 'SaaS Assign New Package',
        'subject' => 'New Package',
        'message' => 'Dear {name},<br/><br/>
    We have assigned a new package to your account.<br/><br/>
    <b>Package:</b><br/>
    {package_name}<br/><br/>
    
    Best regards,<br/>
    {email_signature}<br/>
    (This is an automated email, so please do not reply to it.)',
    ];

    $company_expiration_email = [
        'type' => 'saas',
        'slug' => 'saas-company-expiration-email',
        'name' => 'Company Expiration Email',
        'subject' => '[Attention needed] - Company Expiration Reminder',
        'message' => 'Dear {name},<br/><br/>
As a valued user, we wanted to ensure you are aware of the upcoming expiration date for your company.<br/><br/>
As of {expiration_date}, your company will be expired.<br/><br/>
to avoid any interruption in service, please renew your company as soon as possible.<br/><br/>
by renewing your company, you will continue to enjoy all the benefits of your current plan.<br/><br/>
to renew your company, please follow this link:<br/><br/>
<big><strong><a href="{company_url}">Renew your company</a></strong></big><br/><br/>
link does not work? copy and paste this link into your browser:<br/>
<big><strong><a href="{company_url}">{company_url}</a></strong></big><br/><br/>

<strong>
    If you have any questions or concerns, please do not hesitate to contact us. We are always happy to help.   
</strong>
<br/><br/>

Best regards,<br/>
{email_signature}<br/>
(This is an automated email, so please do not reply to it.)',
    ];

    $inactive_company_email = [
        'type' => 'saas',
        'slug' => 'saas-inactive-company-email',
        'name' => 'Inactive Company Email',
        'subject' => '[Attention] - your company is inactive soon! Please take action',
        'message' => 'Dear {name},<br/><br/>
As a valued user, we wanted to ensure you are aware of the upcoming expiration date for your company.<br/><br/>
<strong>Despite our previous notifications,it seems that you have not renewed your company yet.</strong><br/><br/>
According to our records, your company already expired on {expiration_date}.<br/><br/>
Unfortunately, your company is inactive now.<br/><br/>
to avoid any interruption in service, please renew your company as soon as possible.<br/><br/>
by renewing your company, you will continue to enjoy all the benefits of your current plan.<br/><br/>
to renew your company, please follow this link:<br/><br/>
<big><strong><a href="{company_url}">Renew your company</a></strong></big><br/><br/>
link does not work? copy and paste this link into your browser:<br/>
<big><strong><a href="{company_url}">{company_url}</a></strong></big><br/><br/>

Best regards,<br/>
{email_signature}<br/>
(This is an automated email, so please do not reply to it.)',
    ];

    $company_url = [
        'type' => 'saas',
        'slug' => 'saas-company-url',
        'name' => 'Company URL',
        'subject' => 'Company URL',
        'message' => 'Dear {name},<br/><br/>
you had requested your company URL.<br/><br/>
so here is your company URL:<br/><br/>
<big><strong><a href="{company_url}">{company_url}</a></strong></big><br/><br/>
link does not work? copy and paste this link into your browser:<br/>
<big><strong><a href="{company_url}">{company_url}</a></strong></big><br/><br/>

Best regards,<br/>
{email_signature}<br/>
(This is an automated email, so please do not reply to it.)',
    ];

    $company_database_reset = [
        'type' => 'saas',
        'slug' => 'saas-company-database-reset',
        'name' => 'Company Database Reset',
        'subject' => 'Company Database Reset',
        'message' => 'Dear {name},<br/><br/>
your company database has been reset.<br/><br/>
you can login to your company by clicking on the link below.<br/><br/>
<big><strong><a href="{company_url}">Login to your company</a></strong></big><br/><br/>
link does not work? copy and paste this link into your browser:<br/>
<big><strong><a href="{company_url}">{company_url}</a></strong></big><br/><br/>

Best regards,<br/>
{email_signature}<br/>
(This is an automated email, so please do not reply to it.)',
    ];
    $affiliate_request = [
        'type' => 'affiliate',
        'slug' => 'affiliate-verification-email',
        'name' => 'Email Verification (Sent to Affiliate User After Registration)',
        'subject' => 'Verify Email Address',
        'message' => 'Dear {first_name},<br/><br/>
Thank you for registering with us.<br/><br/>
Please click on the link below to verify your email address and activate your account.<br/><br/>
<big><strong><a href="{verification_url}">Verify Email Address</a></strong></big><br/><br/>
link does not work? copy and paste this link into your browser:<br/>
<big><strong><a href="{verification_url}">{verification_url}</a></strong></big><br/><br/>

Best regards,<br/>
{email_signature}<br/>
(This is an automated email, so please do not reply to it.)'];

    $affiliate_withdrawal_request = [
        'type' => 'affiliate',
        'slug' => 'affiliate-withdrawal-request',
        'name' => 'Affiliate Withdrawal Request (Sent to Super Admin)',
        'subject' => 'Affiliate Withdrawal Request',
        'message' => 'Hello ,<br/><br/> 
an affiliate withdrawal request has been sent from {first_name} {last_name} <br/><br>
amount : {withdrawal_amount} <br/><br/>

check your affiliation to get Withdrawal Request and you can approve or reject the request .

Best regards,<br>
{email_signature}<br/>
(This is an automated email, so please do not reply to it.)
'];

    $affiliate_withdrawal_accepted = [
        'type' => 'affiliate',
        'slug' => 'affiliate-withdrawal-accepted',
        'name' => 'Affiliate Withdrawal Accepted (Sent to affiliate users)',
        'subject' => 'Affiliate Withdrawal Accepted',
        'message' => 'Dear {first_name} <br/><br/>
your affiliate withdraw request has been accepted.

you can view the request from your affiliation portal .


Best regards,<br/>
{email_signature}<br/>
(This is an automated email, so please do not reply to it.)'];


    $affiliate_withdrawal_declined = [
        'type' => 'affiliate',
        'slug' => 'affiliate-withdrawal-declined',
        'name' => 'Affiliate Withdrawal Declined (Sent to affiliate users)',
        'subject' => 'Affiliate Withdrawal Declined',
        'message' => 'Dear {first_name} <br/><br/>
your affiliate withdraw request has been declined.

you can view the request from your affiliation portal .


Best regards,<br/>
{email_signature}<br/>
(This is an automated email, so please do not reply to it.)'];


    $CI->load->model('emails_model');
    $templates = [$welcome_email, $faq_request_email, $assign_new_package, $company_expiration_email, $inactive_company_email, $company_url, $affiliate_request,
        $affiliate_withdrawal_request, $affiliate_withdrawal_accepted, $affiliate_withdrawal_declined];

    foreach ($templates as $t) {
        //this helper check buy slug and create if not exist by slug
        create_email_template($t['subject'], $t['message'], $t['type'], $t['name'], $t['slug']);
    }

    $CI->db->query("UPDATE " . db_prefix() . "staff SET role = '4' WHERE staffid = '" . get_staff_user_id() . "'");

}