<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_116 extends App_module_migration
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

        // upload email_config.sample.php to application/config and rename it to my_email.php
        $sample_email = module_dir_path(SaaS_MODULE) . 'config/email_config.sample.php';
        // upload the $sample_email into application/config folder and rename it to my_email.php
        $email_path = APPPATH . 'config/my_email.php';
        @chmod($email_path, 0666);
        if (@copy($sample_email, $email_path) === false) {
            die('Unable to copy sample email file to config folder . please make sure you have permission to copy email_config.sample file');
        }


    }

}
