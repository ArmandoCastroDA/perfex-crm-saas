<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_111 extends App_module_migration
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

        $CI->db->query("UPDATE `tbl_saas_package_field` SET `status` = 'active' WHERE `tbl_saas_package_field`.`field_label` = 'disk_space';");
        $CI->db->query("ALTER TABLE `tbl_saas_packages` ADD `allowed_themes` TEXT NULL DEFAULT NULL AFTER `modules`, ADD `custom_domain` VARCHAR(50) NULL DEFAULT NULL AFTER `allowed_themes`;");
        $CI->db->query("ALTER TABLE `tbl_saas_companies_history` ADD `allowed_themes` TEXT NULL DEFAULT NULL AFTER `modules`, ADD `custom_domain` VARCHAR(50) NULL DEFAULT NULL AFTER `allowed_themes`;");
        $CI->db->query("INSERT INTO `tbl_saas_package_field` (`field_id`, `field_label`, `field_name`, `field_type`, `help_text`, `status`, `order`) VALUES (NULL, 'custom_domain', 'custom_domain', 'checkbox', '', 'active', '1');");
        $CI->db->query("ALTER TABLE `tbl_saas_companies` ADD `domain_url` VARCHAR(100) NULL DEFAULT NULL AFTER `domain`;");
        $CI->db->query("ALTER TABLE `tbl_saas_packages` ADD `additional_staff_no` VARCHAR(20) NULL DEFAULT NULL AFTER `staff_no`,ADD `additional_client_no` VARCHAR(20) NULL DEFAULT NULL AFTER `client_no`,ADD `additional_project_no` VARCHAR(20) NULL DEFAULT NULL AFTER `project_no`,ADD `additional_invoice_no` VARCHAR(20) NULL DEFAULT NULL AFTER `invoice_no`,ADD `additional_leads_no` VARCHAR(50) NULL DEFAULT NULL AFTER `leads_no`,ADD `additional_expense_no` VARCHAR(50) NULL DEFAULT NULL AFTER `expense_no`,ADD `additional_contract_no` VARCHAR(50) NULL DEFAULT NULL AFTER `contract_no`,ADD `additional_estimate_no` VARCHAR(50) NULL DEFAULT NULL AFTER `estimate_no`,ADD `additional_credit_note_no` VARCHAR(50) NULL DEFAULT NULL AFTER `credit_note_no`,ADD `additional_proposal_no` VARCHAR(50) NULL DEFAULT NULL AFTER `proposal_no`,ADD `additional_tickets` VARCHAR(50) NULL DEFAULT NULL AFTER `tickets`,ADD `additional_tasks_no` VARCHAR(50) NULL DEFAULT NULL AFTER `tasks_no`,ADD `additional_item_no` VARCHAR(50) NULL DEFAULT NULL AFTER `item_no`,ADD `additional_disk_space` VARCHAR(100) NULL DEFAULT NULL AFTER `disk_space`;");
        $CI->db->query("ALTER TABLE `tbl_saas_companies_history` ADD `additional_staff_no` VARCHAR(20) NULL DEFAULT NULL AFTER `staff_no`,ADD `additional_client_no` VARCHAR(20) NULL DEFAULT NULL AFTER `client_no`,ADD `additional_project_no` VARCHAR(20) NULL DEFAULT NULL AFTER `project_no`,ADD `additional_invoice_no` VARCHAR(20) NULL DEFAULT NULL AFTER `invoice_no`,ADD `additional_leads_no` VARCHAR(50) NULL DEFAULT NULL AFTER `leads_no`,ADD `additional_expense_no` VARCHAR(50) NULL DEFAULT NULL AFTER `expense_no`,ADD `additional_contract_no` VARCHAR(50) NULL DEFAULT NULL AFTER `contract_no`,ADD `additional_estimate_no` VARCHAR(50) NULL DEFAULT NULL AFTER `estimate_no`,ADD `additional_credit_note_no` VARCHAR(50) NULL DEFAULT NULL AFTER `credit_note_no`,ADD `additional_proposal_no` VARCHAR(50) NULL DEFAULT NULL AFTER `proposal_no`,ADD `additional_tickets` VARCHAR(50) NULL DEFAULT NULL AFTER `tickets`,ADD `additional_tasks_no` VARCHAR(50) NULL DEFAULT NULL AFTER `tasks_no`,ADD `additional_item_no` VARCHAR(50) NULL DEFAULT NULL AFTER `item_no`,ADD `additional_disk_space` VARCHAR(100) NULL DEFAULT NULL AFTER `disk_space`;");
        $CI->db->query("ALTER TABLE `tbl_saas_temp_payment` ADD `new_module` TEXT NULL DEFAULT NULL AFTER `hash`, ADD `new_limit` TEXT NULL DEFAULT NULL AFTER `new_module`;");

        $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_package_module` (
  `package_module_id` int NOT NULL AUTO_INCREMENT,
  `module_name` varchar(200) NOT NULL,
  `price` decimal(18,5) NOT NULL DEFAULT '0.00000',
  PRIMARY KEY (`package_module_id`)
) ENGINE=InnoDB;");

        $CI->db->query("CREATE TABLE IF NOT EXISTS `tbl_saas_domain_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `custom_domain` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;");
    }

}
