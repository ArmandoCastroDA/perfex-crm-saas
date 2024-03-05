<?php defined('BASEPATH') or exit('No direct script access allowed');

class Domain extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        saas_access();
    }

    public function requests()
    {
        $data['title'] = _l('requests');
        $data['active'] = 1;
        $data['subview'] = $this->load->view('domain/requests', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function requestsList($id = null)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->select = 'tbl_saas_companies.name as company_name,tbl_saas_domain_requests.*';
            $this->datatables->table = 'tbl_saas_domain_requests';
            $this->datatables->join_table = array('tbl_saas_companies');
            $this->datatables->join_where = array('tbl_saas_companies.id=tbl_saas_domain_requests.company_id');
            $column = array('company_name', 'custom_domain', 'status', 'request_date');
            $this->datatables->column_order = $column;
            $this->datatables->column_search = $column;
            $this->datatables->order = array('tbl_saas_domain_requests.request_id' => 'desc');
            $fetch_data = make_datatables();

            $data = array();
            foreach ($fetch_data as $key => $row) {
                $action = null;
                $sub_array = array();
                $name = '<a href="' . saas_url('companies/details/' . $row->company_id) . '">' . $row->company_name . '</a>';
                $name .= '<div class="row-options">';
                // if status is pending then show approved and rejected
                // if status is approved then show pending and rejected
                // if status is rejected then show pending and approved
                if ($row->status === "pending") {
                    $name .= '<a href="' . saas_url('domain/change_status/approved/' . $row->request_id) . '" class="text-success">' . _l('approve') . '</a>';
                    $name .= ' | <a href="' . saas_url('domain/change_status/rejected/' . $row->request_id) . '" class="text-danger">' . _l('reject') . '</a>';
                } else if ($row->status === "approved") {
                    $name .= '<a href="' . saas_url('domain/change_status/pending/' . $row->request_id) . '" class="text-warning">' . _l('pending') . '</a>';
                    $name .= ' | <a href="' . saas_url('domain/change_status/rejected/' . $row->request_id) . '" class="text-danger">' . _l('reject') . '</a>';
                } else if ($row->status === "rejected") {
                    $name .= '<a href="' . saas_url('domain/change_status/pending/' . $row->request_id) . '" class="text-warning">' . _l('pending') . '</a>';
                    $name .= ' | <a href="' . saas_url('domain/change_status/approved/' . $row->request_id) . '" class="text-success">' . _l('approve') . '</a>';
                }
                $name .= ' | <a href="' . base_url('saas/domain/delete_domain/' . $row->request_id) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $name .= '</div>';


                $status = '<span class="label label-warning">' . _l('pending') . '</span>';
                if ($row->status == 'approved') {
                    $status = '<span class="label label-success">' . _l('approved') . '</span>';
                } elseif ($row->status == 'rejected') {
                    $status = '<span class="label label-danger">' . _l('rejected') . '</span>';
                }
                $sub_array[] = $name;
                $sub_array[] = $row->custom_domain;
                $sub_array[] = _dt($row->request_date);
                $sub_array[] = $status;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('saas/dashboard');
        }
    }

    public function change_status($status, $id)
    {
        $requestInfo = get_old_result('tbl_saas_domain_requests', array('request_id' => $id), false);
        if ($requestInfo->status == 'approved' && $status == 'approved') {
            set_alert('warning', _l('domain_already_approved'));
            redirect(saas_url('domain/requests'));
        } elseif ($status == 'approved') {
            $custom_domain = $requestInfo->custom_domain;
        }
        if ($requestInfo->status == 'approved' && $status != 'approved') {
            $custom_domain = '';
        }

        $this->db->where('request_id', $id);
        $this->db->update('tbl_saas_domain_requests', array('status' => $status));

        $this->db->where('id', $requestInfo->company_id);
        $this->db->update('tbl_saas_companies', array('domain_url' => $custom_domain ?? ''));

        log_activity('Domain Request Status Changed [ID: ' . $id . ', Status: ' . $status . ']');
        set_alert('success', _l('domain_request_updated_successfully'));
        redirect('saas/domain/requests');
    }

    public function delete_domain($id)
    {
        $requestInfo = get_old_result('tbl_saas_domain_requests', array('request_id' => $id), false);
        if ($requestInfo->status == 'approved') {
            $this->db->where('id', $requestInfo->company_id);
            $this->db->update('tbl_saas_companies', array('domain_url' => ''));
        }

        $this->db->where('request_id', $id);
        $this->db->delete('tbl_saas_domain_requests');


        log_activity('Domain Request Deleted [ID: ' . $id . ']');
        set_alert('success', _l('deleted', _l('domain_request')));
        redirect('saas/domain/requests');
    }

    public function create($id = null)
    {
        $data['title'] = 'Create Coupon';
        $data['active'] = 2;
        if (!empty($id)) {
            $data['coupon_info'] = get_row('tbl_saas_coupon', array('id' => $id));
        }
        $data['subview'] = $this->load->view('domain/create', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function save_coupon($id = null)
    {
        $data = $this->saas_model->array_from_post(array('name', 'code', 'amount', 'type', 'end_date', 'package_id', 'package_type', 'show_on_pricing', 'status'));
        if (!empty($id)) { // if id exist in db update data
            $check_id = array('id !=' => $id);
        } else { // if id is not exist then set id as null
            $check_id = null;
        }
        if (!empty($data['package_id'])) {
            $where = array('package_id' => $data['package_id'], 'show_on_pricing' => 'Yes');
            $already_show = $this->saas_model->check_update('tbl_saas_coupon', $where, $check_id);
            $this->saas_model->_table_name = "tbl_saas_coupon"; // table name
            $this->saas_model->_primary_key = "id"; // $id
            if (!empty($already_show)) {
                foreach ($already_show as $v_show) {
                    $udata['show_on_pricing'] = 'No';
                    $this->saas_model->save($udata, $v_show->id);
                }
            }
        } else {
            $data['package_id'] = 0;
        }
        if (empty($data['show_on_pricing'])) {
            $data['show_on_pricing'] = 'No';
        }
        if (!empty($data['name'])) {
            $this->saas_model->_table_name = "tbl_saas_coupon"; // table name
            $this->saas_model->_primary_key = "id"; // $id
            $this->saas_model->save($data, $id);
        }
        set_alert('success', lang('update_settings'));
        redirect('saas/domain');
    }


    public function delete_coupon($id)
    {
        $coupon = get_row('tbl_saas_coupon', array('id' => $id));
        if (!empty($coupon)) {
            $this->saas_model->_table_name = 'tbl_saas_coupon';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);
            $type = 'success';
            $message = _l('coupon_deleted');
        } else {
            $type = 'error';
            $message = _l('coupon_not_found');
        }
        echo json_encode(array("status" => $type, "message" => $message));
        exit();
    }

    public function settings()
    {
        $data['title'] = 'Settings';
        // check post request
        if ($this->input->post()) {
            $post_data = $this->input->post();
            foreach ($post_data as $key => $value) {
                update_option($key, $value);
            }

        }
        $data['subview'] = $this->load->view('domain/settings', $data, true);
        $this->load->view('_layout_main', $data);
    }

}
