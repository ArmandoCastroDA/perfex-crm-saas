<?php defined('BASEPATH') or exit('No direct script access allowed');


if (!empty(subdomain())) {
    redirect('admin/dashboard');
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

// remove my_routes.php file from config folder if it exists
$routes_path = APPPATH . 'config/my_routes.php';
if (file_exists($routes_path)) {
    unlink($routes_path);
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


$app_config_path = APPPATH . 'config/app-config.php';
$app_config_file = file_get_contents($app_config_path);
// remove the line to the $app_config_file require_once(FCPATH . 'modules/saas/config/my_config.php'); // added by saas
$app_config_file = str_replace("require_once(FCPATH . 'modules/saas/config/my_config.php'); // added by saas", '', $app_config_file);
file_put_contents($app_config_path, $app_config_file);

