<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Brands extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
    }

    function index($id = null)
    {
        $data['title'] = _l('brands');
        $data['table'] = true;
        $data['subview'] = $this->load->view('frontcms/brands/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function create_brand($id = null)
    {
        $data['title'] = _l('brands');
        if (!empty($id)) {
            $data['brand'] = get_row('tbl_saas_all_section_area', array('id' => $id));
        }
        $data['subview'] = $this->load->view('frontcms/brands/create_brand', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function create($id = null)
    {
        $data['title'] = _l('create_creatives');
        $data['brand'] = get_row('tbl_saas_all_heading_section', array('type' => 'brand_heading'));
        $data['subview'] = $this->load->view('frontcms/brands/create', $data, FALSE);
        $this->load->view('saas/_layout_modal_lg', $data); //page load
    }


    public function save_brand_heading($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('title', 'description'));
        $data['type'] = 'brand_heading';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('brand_heading_added [ID:' . $return_id . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('brand_heading');

        set_alert($type, $message);
        redirect('saas/frontcms/brands');
    }

    // creativesList
    public function brandsList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_all_section_area';
            $this->datatables->column_search = array('title', 'image');
            $this->datatables->column_order = array('title', 'image');
            $this->datatables->order = array('tbl_saas_all_section_area.id' => 'asc');
            $where = array('type' => 'brands');
            $fetch_data = make_datatables($where);

            $data = array();

            foreach ($fetch_data as $_key => $v_creatives) {
                $action = null;
                $sub_array = array();
                $title = $v_creatives->title;
                $title .= '<div class="row-options">';
                $title .= '<a href="' . saas_url('frontcms/brands/create_brand/' . $v_creatives->id) . '" data-toggle="tooltip" title="' . _l('edit') . '">' . _l('edit') . '</a>';
                $title .= ' | <a href="' . saas_url('frontcms/brands/delete_brand/' . $v_creatives->id) . '" class="text-danger _delete" data-toggle="tooltip" title="' . _l('delete') . '">' . _l('delete') . '</a>';
                $title .= '</div>';
                $sub_array[] = $title;
                $sub_array[] = '<img src="' . base_url($v_creatives->image) . '" class="img-thumbnail" width="100px" height="100px">';
                if ($v_creatives->status == 1) {
                    $sub_array[] = '<span class="label label-success">' . _l('active') . '</span>';
                } else {
                    $sub_array[] = '<span class="label label-danger">' . _l('deactive') . '</span>';
                }

                $data[] = $sub_array;
            }
            render_table($data, $where);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function delete_brand($id = null)
    {
        if (!empty($id)) {
            $creatives_info = get_row('tbl_saas_all_section_area', array('id' => $id));
            log_activity('creatives_brands_deleted [' . $creatives_info->title . ']');
            $this->saas_model->_table_name = 'tbl_saas_all_section_area';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);

            if (is_file($creatives_info->icons)) {
                unlink($creatives_info->icons);
            }

            // messages for user
            $type = "success";
            $message = _l('delete') . " " . _l('brands');
        }
        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function save_brand($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $data = $this->saas_model->array_from_post(array('title', 'name', 'status', 'designation', 'color', 'color_2', 'title_2', 'icons'));
        if (!empty($_FILES['image']['name'])) {
            $val = $this->saas_model->uploadImage('image', module_direcoty(SaaS_MODULE, 'uploads/'));
            $val == TRUE || redirect('saas/frontcms/brands');
            $data['image'] = $val['path'];
        }
        $data['type'] = 'brands';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save_brands [ID:' . $return_id . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('brands');

        set_alert($type, $message);
        redirect('saas/frontcms/brands');
    }
}
