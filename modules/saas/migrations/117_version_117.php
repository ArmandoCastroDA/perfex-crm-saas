<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_117 extends App_module_migration
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

        if (!$CI->db->field_exists('module_title', 'tbl_saas_package_module')) {
            $CI->db->query("ALTER TABLE `tbl_saas_package_module` ADD `module_title` VARCHAR(200) NULL DEFAULT NULL AFTER `module_name`;");
        }

    }

}
