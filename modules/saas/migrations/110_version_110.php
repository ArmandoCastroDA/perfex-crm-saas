<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_110 extends App_module_migration
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

        $CI->db->query("UPDATE `tbl_saas_companies_history` SET `tickets` = '0' WHERE `tbl_saas_companies_history`.`tickets` = 'Yes';");
        $CI->db->query("UPDATE `tbl_saas_companies_history` SET `estimate_no` = '0' WHERE `tbl_saas_companies_history`.`estimate_no` = 'Yes';");
        $CI->db->query("UPDATE `tbl_saas_companies_history` SET `credit_note_no` = '0' WHERE `tbl_saas_companies_history`.`credit_note_no` = 'Yes';");
        $CI->db->query("UPDATE `tbl_saas_companies_history` SET `proposal_no` = '0' WHERE `tbl_saas_companies_history`.`proposal_no` = 'Yes';");
        $CI->db->query("UPDATE `tbl_saas_companies_history` SET `item_no` = '0' WHERE `tbl_saas_companies_history`.`proposal_no` = 'Yes';");
        $CI->db->query("ALTER TABLE `tbl_saas_companies_history` ADD `disk_space` VARCHAR(50) NULL DEFAULT NULL AFTER `item_no`;");

        $CI->db->query("UPDATE `tbl_saas_packages` SET `tickets` = '0' WHERE `tbl_saas_packages`.`tickets` = 'Yes';");
        $CI->db->query("UPDATE `tbl_saas_packages` SET `estimate_no` = '0' WHERE `tbl_saas_packages`.`estimate_no` = 'Yes';");
        $CI->db->query("UPDATE `tbl_saas_packages` SET `credit_note_no` = '0' WHERE `tbl_saas_packages`.`credit_note_no` = 'Yes';");
        $CI->db->query("UPDATE `tbl_saas_packages` SET `proposal_no` = '0' WHERE `tbl_saas_packages`.`proposal_no` = 'Yes';");
        $CI->db->query("UPDATE `tbl_saas_packages` SET `item_no` = '0' WHERE `tbl_saas_packages`.`item_no` = 'Yes';");

        $CI->db->query("INSERT INTO `tbl_saas_package_field` (`field_id`, `field_label`, `field_name`, `field_type`, `help_text`, `status`, `order`) VALUES (NULL, 'tickets', 'tickets', 'text', 'use 0 = unlimited and empty = not included', 'active', '13')");
        $CI->db->query("INSERT INTO `tbl_saas_package_field` (`field_id`, `field_label`, `field_name`, `field_type`, `help_text`, `status`, `order`) VALUES (NULL, 'disk_space', 'disk_space', 'text', 'Include it with MB,GB,TB etc like 1GB.', 'inactive', '2')");

    }

}
