<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (function_exists('is_subdomain')) {
    $is_subdomain = is_subdomain();
    if (!empty($is_subdomain)) {
        $CI =& get_instance();
        if (!empty(config_item('company_db_name'))) {
            $config_db = $CI->config->config['config_db'];
            $config_db['database'] = config_item('company_db_name');
            $CI->db = $CI->load->database($config_db, true);
            // set session sql mode to empty
            $CI->db->query("SET SESSION sql_mode = ''");
            $CI->db->query("SET sql_mode = ''");
        }
    }
}
