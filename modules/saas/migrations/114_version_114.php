<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_114 extends App_module_migration
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

        // check if the column exists in the table
        if (!$CI->db->field_exists('saas_company_id', db_prefix() . 'clients')) {
            $CI->db->query("ALTER TABLE " . db_prefix() . "clients ADD `saas_company_id` INT NULL DEFAULT NULL AFTER `company`;");
        }

        // get all companies and save them in clients table saas_company_id column
        $companies = $CI->db->query("SELECT * FROM tbl_saas_companies")->result();
        if (!empty($companies)) {
            foreach ($companies as $company) {
                $password = 123456;
                //check if the password bycrypt format or not
                if (!empty($company->password)) {
                    if (strlen($company->password) > 60) {
                        $password = $company->password;
                    } else {
                        $password = $company->password;
                    }
                }

                $bycript = null;
                if (!empty($company->db_name)) {
                    $CI->old_db = config_db($company->db_name);
                    // get staff by email from old db
                    // check if $CI->old_db->database is exist or not
                    if (!empty($CI->old_db->database)) {
                        $CI->old_db->query("USE " . $CI->old_db->database);
                        $staff = $CI->old_db->where('email', $company->email)->get(db_prefix() . 'staff')->row();
                        if (!empty($staff)) {
                            $password = $staff->password;
                            $bycript = true;
                        }
                    }
                }
                // load saas model
                $CI->load->model('saas/saas_model');
                $CI->saas_model->save_client($company->id, $password, $bycript);

            }
        }

        $CI->db->query("UPDATE `tbl_saas_front_menu_items` SET `menu` = 'Others' WHERE `tbl_saas_front_menu_items`.`id` = 58;");
        $CI->db->query("UPDATE `tbl_saas_front_menu_items` SET `slug` = 'others' WHERE `tbl_saas_front_menu_items`.`id` = 58;");
        $CI->db->query("UPDATE `tbl_saas_front_menu_items` SET `parent_id` = '58' WHERE `tbl_saas_front_menu_items`.`id` = 3;");
        $CI->db->query("UPDATE `tbl_saas_front_menu_items` SET `parent_id` = '58' WHERE `tbl_saas_front_menu_items`.`id` = 52;");

    }

}
