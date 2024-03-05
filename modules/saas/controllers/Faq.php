<?php defined('BASEPATH') or exit('No direct script access allowed');


class Faq extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');

    }

    public function index()
    {
        $data['title'] = _l('faq');
        $data['subview'] = $this->load->view('faq/manage', $data, true);
        $this->load->view('_layout_main', $data);
    }

    // Show page list
    public function faqList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_front_contact_us';
            $main_column = array('id', 'subject', 'name', 'email', 'phone');
            $this->datatables->column_order = $main_column;
            $this->datatables->column_search = $main_column;
            $this->datatables->order = array('id' => 'desc');
            $fetch_data = make_datatables();
            $data = array();

            foreach ($fetch_data as $_key => $info) {
                $action = null;
                $sub_array = array();
                $subject = $info->subject;
                $subject .= '<div class="row-options">';
                $subject .= '<a href="' . base_url('saas/faq/view_faq/') . $info->id . '">' . _l('view') . '</a>';
                $subject .= ' | <a href="' . saas_url('faq/delete_faq/' . $info->id) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $subject .= '</div>';

                $sub_array[] = $subject;
                $sub_array[] = '<a title="View Email" style="' . ($info->view_status == 1 ? 'color: #656565;' : ' ') . '"  data-target="#myModal_lg" data-toggle="modal" href="' . base_url('saas/faq/view_faq/') . $info->id . '">' . $info->name . '</a>';
                $sub_array[] = '<a href="mailto:' . $info->email . '">' . $info->email . '</a>';
                $sub_array[] = '<a href="tel:' . $info->phone . '">' . $info->phone . '</a>';

                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    // view email
    public function view_faq($id = null, $inline = null)
    {
        $data['title'] = _l('mailbox');

        if (!empty($id)) {
            $data['email_info'] = get_row('tbl_saas_front_contact_us', array('id' => $id));
            if ($data['email_info']->view_status != 1) {
                $this->saas_model->_table_name = 'tbl_saas_front_contact_us';
                $this->saas_model->_primary_key = 'id';

                $this->saas_model->save(array('view_status' => 1), $id);
            }
            $data['subview'] = $this->load->view('saas/faq/preview', $data, true);
            $this->load->view('saas/_layout_main', $data);
        } else {
            redirect('saas/faq');
        }
    }


    // delete email
    public function delete_faq($id = null)
    {
        if ($id) {
            $email_info = get_row('tbl_saas_front_contact_us', array('id' => $id));
            log_activity('Email Deleted [ID:' . $id . ', Subject: ' . $email_info->subject . ', Name: ' . $email_info->name . ', Email: ' . $email_info->email . ', Phone: ' . $email_info->phone . ']');

            // deletre into tbl_saas_front_contact_us details by id
            $this->saas_model->_table_name = 'tbl_saas_front_contact_us';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);

            // messages for user
            $type = "success";
            $message = _l('delete') . " " . _l('faq');
        } else {
            $type = "error";
            $message = _l('no_permission');
        }
        set_alert($type, $message);
        redirect('saas/faq');
    }

}