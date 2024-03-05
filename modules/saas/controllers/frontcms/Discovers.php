<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Discovers extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
    }

    function index()
    {
        $data['title'] = _l('discovers');
        $data['table'] = true;
        $data['subview'] = $this->load->view('frontcms/discovers/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function new($id = null)
    {
        $data['title'] = _l('discovers');
        if (!empty($id)) {
            $data['discovers_card'] = get_row('tbl_saas_all_section_area', array('id' => $id));
        }
        $data['subview'] = $this->load->view('frontcms/discovers/new', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function create($id = null)
    {
        $data['title'] = _l('create_discovers');
        $data['discovers_info'] = get_row('tbl_saas_all_heading_section', array('type' => $this->uri->segment(3)));
        $data['subview'] = $this->load->view('frontcms/discovers/create', $data, FALSE);
        $this->load->view('saas/_layout_modal_lg', $data); //page load

    }

    function create_tabs($id = null)
    {
        $data['title'] = _l('create_tabs');
        $data['create_tabs_info'] = get_row('tbl_saas_all_heading_section', array('type' => $this->uri->segment(3)));
        $data['subview'] = $this->load->view('frontcms/discovers/create_tabs', $data, FALSE);
        $this->load->view('saas/_layout_modal_lg', $data); //page load

    }


    public function save_discovers_tabs($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('title', 'description'));
        $data['type'] = 'discovers_tabs';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('Save discovers tabs [ID:' . $return_id . ', ' . $data['title'] . ']');

        $type = "success";
        $message = _l('save') . ' ' . _l('discovers_tabs');

        set_alert($type, $message);
        redirect('saas/frontcms/discovers');
    }

    public function save_discovers_heading($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('title', 'description'));
        $data['type'] = $this->uri->segment(3);
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('Save discovers tabs [ID:' . $return_id . ', ' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('discovers_heading');

        set_alert($type, $message);
        redirect('saas/frontcms/discovers');
    }

    // discoversList
    public function discoversList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_all_section_area';
            $this->datatables->column_search = array('title', 'description');
            $this->datatables->column_order = array('title', 'description');
            $this->datatables->order = array('tbl_saas_all_section_area.id' => 'asc');
            $where = array('type' => 'discovers');
            $fetch_data = make_datatables($where);

            $data = array();
            $edited = super_admin_access();
            $deleted = super_admin_access();

            foreach ($fetch_data as $_key => $v_discovers) {
                $action = null;
                $sub_array = array();
                $title = $v_discovers->title;
                $title .= '<div class="row-options">';
                $title .= '<a href="' . base_url('saas/frontcms/discovers/new/' . $v_discovers->id) . '" class="">' . _l('edit') . '</a>';

                $title .= ' | <a href="' . base_url('saas/frontcms/discovers/delete_discovers_card/' . $v_discovers->id) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $title .= '</div>';
                $sub_array[] = $title;
                $sub_array[] = $v_discovers->description;
                if ($v_discovers->status == 1) {
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

    public function delete_discovers_card($id = null)
    {
        if (!empty($id)) {
            $discovers_info = get_row('tbl_saas_all_section_area', array('id' => $id));
            log_activity("Delete discovers Card [ID:$id]");

            $this->saas_model->_table_name = 'tbl_saas_all_section_area';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);

            if (is_file($discovers_info->icons)) {
                unlink($discovers_info->icons);
            }

            // messages for user
            $type = "success";
            $message = _l('delete') . " " . _l('discovers_card');
        }
        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function save_discovers_card($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $data = $this->saas_model->array_from_post(array('title', 'description', 'status'));
        if (!empty($_FILES['image']['name'])) {
            $val = $this->saas_model->uploadImage('image', module_direcoty(SaaS_MODULE, 'uploads/'));
            $val == TRUE || redirect('saas/frontcms/discovers');
            $data['image'] = $val['path'];
        }
        $data['type'] = 'discovers';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('Save discovers tabs [ID:' . $return_id . ', ' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('discovers_card');

        set_alert($type, $message);
        redirect('saas/frontcms/discovers');
    }
}
