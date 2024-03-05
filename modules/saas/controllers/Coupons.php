<?php defined('BASEPATH') or exit('No direct script access allowed');

class Coupons extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        saas_access();
    }

    public function index()
    {
        $data['title'] = _l('coupon');
        $data['active'] = 1;
        $data['subview'] = $this->load->view('coupons/manage', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function couponList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->select = 'tbl_saas_packages.name as package_name,tbl_saas_coupon.*';
            $this->datatables->table = 'tbl_saas_coupon';
            $this->datatables->join_table = array('tbl_saas_packages');
            $this->datatables->join_where = array('tbl_saas_packages.id=tbl_saas_coupon.package_id');
            $column = array('package_name', 'name', 'code', 'amount', 'status');
            $this->datatables->column_order = $column;
            $this->datatables->column_search = $column;
            $this->datatables->order = array('tbl_saas_coupon.id' => 'desc');
            $fetch_data = make_datatables();

            $data = array();

            $access = super_admin_access();
            foreach ($fetch_data as $key => $row) {
                $action = null;
                $sub_array = array();
                if (empty($row->package_id) || $row->package_id == 0) {
                    $package_name = _l('all') . ' ' . _l('package');
                } else {
                    $package_name = $row->package_name;;
                }
                $package_name .= '<div class="row-options">';
                if (!empty($access)) {
                    $package_name .= '<a data-toggle="tooltip" data-placement="top" href="' . saas_url('coupons/create/' . $row->id) . '" title="' . _l('edit') . '">' . _l('edit') . '</a>';
                }
                if (!empty($access)) {
                    $package_name .= '| <a data-toggle="tooltip" data-placement="top"  href="' . saas_url('coupons/delete_coupons/' . $row->id) . '" class="text-danger _delete" title="' . _l('delete') . '">' . _l('delete') . '</a>';
                }

                $sub_array[] = $package_name;
                $sub_array[] = $row->name;
                $sub_array[] = $row->code;
                $sub_array[] = $row->amount . ' ' . ($row->type == 1 ? '%' : lang('flat'));
                $sub_array[] = _d($row->end_date);
                $sub_array[] = $row->status == 'active' ? '<span class="label label-success">' . _l('active') . '</span>' : '<span class="label label-danger">' . _l('inactive') . '</span>';
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('saas/dashboard');
        }
    }

    public function create($id = null)
    {
        $data['title'] = 'Create Coupon';
        $data['active'] = 2;
        if (!empty($id)) {
            $data['coupon_info'] = get_row('tbl_saas_coupon', array('id' => $id));
        }
        $data['subview'] = $this->load->view('coupons/create', $data, true);
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
        redirect('saas/coupons');
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


}
