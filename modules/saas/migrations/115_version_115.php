<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_115 extends App_module_migration
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

        if (!$CI->db->field_exists('saas_company_id', db_prefix() . 'clients')) {
            $CI->db->query("ALTER TABLE " . db_prefix() . "clients ADD `saas_company_id` INT NULL DEFAULT NULL AFTER `company`;");
        }

        // check if the column exists in the table
        if (!$CI->db->field_exists('preview_image', 'tbl_saas_package_module')) {
            $CI->db->query("ALTER TABLE tbl_saas_package_module ADD `preview_image` text NULL DEFAULT NULL AFTER `price`;");
            $CI->db->query("ALTER TABLE tbl_saas_package_module ADD `preview_video_url` text NULL DEFAULT NULL AFTER `preview_image`;");
            $CI->db->query("ALTER TABLE tbl_saas_package_module ADD `descriptions` text NULL DEFAULT NULL AFTER `preview_video_url`;");
            $CI->db->query("ALTER TABLE tbl_saas_package_module ADD `module_order` int(11) NULL DEFAULT NULL AFTER `descriptions`;");
            $CI->db->query("ALTER TABLE tbl_saas_package_module ADD `status` enum('published','unpublished') NULL DEFAULT 'published' AFTER `module_order`;");
        }


    }

}
