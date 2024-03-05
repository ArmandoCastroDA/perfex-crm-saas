<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_113 extends App_module_migration
{
    /**
     * @throws Exception
     */
    public function up()
    {
        $CI = &get_instance();

        if (!empty(subdomain())) {
            set_alert('warning', 'Only superadmin can update the system.');
            redirect('admin/dashboard');
        }

        $CI->db->query("ALTER TABLE `tbl_saas_companies` CHANGE `frequency` `frequency` VARCHAR(50) NULL DEFAULT NULL;");

        // upload my_autoload_samples.php to application/config and rename it to my_autoload.php
        $sample_autoload = module_dir_path(SaaS_MODULE) . 'config/autoload.sample.php';
        // upload the $sample_autoload into application/config folder and rename it to my_autoload.php
        $autoload_path = APPPATH . 'config/my_autoload.php';
        @chmod($autoload_path, 0666);
        if (@copy($sample_autoload, $autoload_path) === false) {
            die('Unable to copy sample autoload file to config folder . please make sure you have permission to copy autoload.sample file');
        }

    }

}
