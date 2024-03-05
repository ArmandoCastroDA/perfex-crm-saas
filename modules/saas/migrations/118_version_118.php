<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_118 extends App_module_migration
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

        $CI->db->query("ALTER TABLE `tbl_saas_companies` CHANGE `frequency` `frequency` VARCHAR(50) NULL DEFAULT NULL;");
        $CI->db->query("ALTER TABLE `tbl_saas_companies` ADD `for_seed` VARCHAR(20) NULL DEFAULT NULL AFTER `referral_by`;");

        // remove my_email.php file from application/config folder if exists
        $my_email_file = APPPATH . 'config/my_email.php';
        if (file_exists($my_email_file)) {
            unlink($my_email_file);
        }


    }

}
