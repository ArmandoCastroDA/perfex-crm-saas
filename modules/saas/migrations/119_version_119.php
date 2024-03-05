<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_119 extends App_module_migration
{
    /**
     * @throws Exception
     */
    public function up()
    {
        $CI = &get_instance();

        if (!empty(subdomain())) {
            set_alert('warning', 'Only super admin can update the system.');
            redirect('admin/dashboard');
        }

        // check if disabled_modules column exists in tbl_saas_packages
        if (!$CI->db->field_exists('disabled_modules', 'tbl_saas_packages')) {
            $CI->db->query("ALTER TABLE `tbl_saas_packages` ADD `disabled_modules` TEXT NULL DEFAULT NULL AFTER `allowed_themes`;");
        }
        // check if disabled_modules column exists in tbl_saas_companies_history
        if (!$CI->db->field_exists('disabled_modules', 'tbl_saas_companies_history')) {
            $CI->db->query("ALTER TABLE `tbl_saas_companies_history` ADD `disabled_modules` TEXT NULL DEFAULT NULL AFTER `allowed_themes`;");
        }
    }

}
