<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contacts extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
    }

    function index()
    {
        $data['title'] = _l('contacts');
        $data['subview'] = $this->load->view('frontcms/settings/contact_us', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function contactsList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_front_contact_us';
            $this->datatables->column_search = array('name', 'email', 'phone', 'subject');
            $this->datatables->order = array('id' => 'asc');
            $fetch_data = make_datatables();
            $data = array();
            $edited = super_admin_access();
            $deleted = super_admin_access();
            foreach ($fetch_data as $_key => $info) {
                $action = null;
                $sub_array = array();
                $sub_array[] = $info->name;
                $sub_array[] = $info->email;
                $sub_array[] = $info->phone;
                $sub_array[] = $info->subject;
                if (!empty($edited)) {
                    $action .= btn_view_modal('saas/frontcms/contacts/details/' . $info->id) . ' ';
                }
                if (!empty($deleted) && admin()) {
                    $action .= ajax_anchor(base_url("saas/frontcms/contacts/delete_contact/$info->id"), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key));
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function details($id = null)
    {
        $data['title'] = _l('contact') . ' ' . _l('details'); //Page title
        if (!empty($id)) {
            $edited = super_admin_access();
            if (!empty($edited)) {
                $data['details'] = get_row('tbl_saas_front_contact_us', array('id' => $id));
            }
            if (empty($data['details'])) {
                $type = "error";
                $message = "No Record Found";
                set_alert($type, $message);
                redirect('saas/frontcms/contacts');
            }
        }
        $data['subview'] = $this->load->view('settings/contacts_details', $data, FALSE);
        $this->load->view('saas/_layout_modal_lg', $data); //page load
    }

    public function delete_contact($id = null)
    {
        $deleted = super_admin_access();
        if (!empty($deleted) && !empty($id)) {
            $contact_info = get_row('tbl_saas_front_contact_us', array('id' => $id));
            log_activity('Contact Deleted [ID:' . $id . ', Name: ' . $contact_info->name . ']');

            $this->saas_model->_table_name = 'tbl_saas_front_contact_us';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);

            // messages for user
            $type = "success";
            $message = _l('delete') . " " . _l('contact');
        } else {
            $type = "error";
            $message = _l('no_permission');
        }
        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

}
