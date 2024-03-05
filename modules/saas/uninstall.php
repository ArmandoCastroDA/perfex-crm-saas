<?php defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();
if (function_exists('subdomain') && !empty(subdomain())) {
    redirect('admin/dashboard');
}

// insert and line into application/config/app-config.php file
$app_config_path = APPPATH . 'config/app-config.php';
$app_config_file = file_get_contents($app_config_path);
$app_config_file = str_replace("require_once(FCPATH . 'modules/saas/config/my_config.php'); // added by saas", '', $app_config_file);
file_put_contents($app_config_path, $app_config_file);

$router_path = APPPATH . 'config/my_routes.php';
// delete the file if exist
if (file_exists($router_path)) {
    unlink($router_path);
}

$autoload_path = APPPATH . 'config/my_autoload.php';
// delete the file if exist
if (file_exists($autoload_path)) {
    unlink($autoload_path);
}

// remove my_email.php file from config folder if it exists
$email_path = APPPATH . 'config/my_email.php';
if (file_exists($email_path)) {
    unlink($email_path);
}


$database_path = APPPATH . 'config/database.php';
$database_file = file_get_contents($database_path);
$database_file = str_replace("config_item('default_database')", "APP_DB_NAME", $database_file);

if (!$fp = fopen($database_path, 'wb')) {
    die('Unable to write to config file');
}

flock($fp, LOCK_EX);
fwrite($fp, $database_file, strlen($database_file));
flock($fp, LOCK_UN);
fclose($fp);
@chmod($database_path, 0644);


// Delete the all the tables which prefix is tbl_saas_ by query
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_affiliates`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_affiliate_payouts`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_affiliate_users`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_all_heading_section`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_all_section_area`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_applied_coupon`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_companies`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_companies_history`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_companies_payment`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_coupon`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_domain_requests`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_front_cms_media`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_front_contact_us`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_front_menus`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_front_menu_items`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_front_pages`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_front_pages_contents`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_front_slider`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_menu`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_packages`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_package_field`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_package_module`");
$CI->db->query("DROP TABLE IF EXISTS `tbl_saas_temp_payment`");



