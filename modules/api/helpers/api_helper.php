<?php 
defined('BASEPATH') or exit('No direct script access allowed');

if(!function_exists('insert')) 
{
    function insert($table_name, $insert_data)
    {
        $CI =& get_instance();
        return $CI->db->insert($table_name, $insert_data);
    }
}

function get_relation_data_api($type, $search = '')
{
    $CI = & get_instance();
    $q  = '';
    if($search != ''){
        $q = $search;
        $q = trim($q);
    }
    $data = [];
    if ($type == 'customer' || $type == 'customers') {
        $where_clients = 'tblclients.active=1';
        

        if ($q) {
            $where_clients .= ' AND types = "customer" AND (company LIKE "%' . $q . '%" OR CONCAT(firstname, " ", lastname) LIKE "%' . $q . '%" OR email LIKE "%' . $q . '%")';
        }
        $data = $CI->clients_model->get('', $where_clients);
    } 
     elseif ($type == 'ticket') {
            $search = $CI->api_model->_search_tickets($q, 0, true);
            $data   = $search['result'];
    } elseif ($type == 'lead' || $type == 'leads') {
            $search = $CI->api_model->_search_leads($q, 0, [
                'junk' => 0,
                ], true);
            $data = $search['result'];
    } elseif ($type == 'project') {
        
            $where_projects = '';
            if ($CI->input->post('customer_id')) {
                $where_projects .= '(clientid=' . $CI->input->post('customer_id').' or clientid in (select id from tblleads where client_id='.$CI->input->post('customer_id').') )';
            }
            if ($CI->input->post('rel_type')) {
                $where_projects .= ' and rel_type="' . $CI->input->post('rel_type').'" ' ;
            }
            $search = $CI->api_model->_search_projects($q, 0, $where_projects,$CI->input->post('rel_type'), true);
            
            
            $data   = $search['result'];
        
    } elseif ($type == 'staff') {
            $search = $CI->api_model->_search_staff($q,0,true);
            $data   = $search['result'];
        
    } elseif ($type == 'tasks') {
        $search = $CI->api_model->_search_tasks($q,0,true);
        $data   = $search['result'];
    }
    return $data;
}