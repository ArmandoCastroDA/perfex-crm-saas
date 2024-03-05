<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Features extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
    }

    function index($id = null)
    {
        $data['title'] = _l('features');
        $data['table'] = true;
        $data['subview'] = $this->load->view('frontcms/features/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function features_list($id = null)
    {
        $data['title'] = _l('features');
        $data['table'] = true;
        $data['subview'] = $this->load->view('frontcms/features/features_2', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function new($id = null)
    {
        $data['title'] = _l('features');
        if (!empty($id)) {
            $data['features_card'] = get_row('tbl_saas_all_section_area', array('id' => $id));
        }
        $data['subview'] = $this->load->view('frontcms/features/new', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function new2($id = null)
    {
        $data['title'] = _l('features');
        if (!empty($id)) {
            $data['features_collaborates'] = get_row('tbl_saas_all_section_area', array('id' => $id));
        }
        $data['subview'] = $this->load->view('frontcms/features/new2', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function create($id = null)
    {
        $data['title'] = _l('create_features');
        $data['features_info'] = get_row('tbl_saas_all_heading_section', array('type' => $this->uri->segment(3)));
        $data['subview'] = $this->load->view('frontcms/features/create', $data, FALSE);
        $this->load->view('saas/_layout_modal_lg', $data); //page load

    }


    public function save_features_heading($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('title', 'description'));
        $data['type'] = $this->uri->segment(3);
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save_features_heading [' . $data['title'] . ']');

        $type = "success";
        $message = _l('save') . ' ' . _l('features_heading');

        set_alert($type, $message);
        redirect('saas/frontcms/features');
    }

    // featuresList
    public function featuresList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_all_section_area';
            $this->datatables->column_search = array('title', 'description');
            $this->datatables->column_order = array('title', 'description');
            $this->datatables->order = array('tbl_saas_all_section_area.id' => 'asc');
            $where = array('type' => 'features');
            $fetch_data = make_datatables($where);

            $data = array();
            foreach ($fetch_data as $_key => $v_features) {
                $action = null;
                $sub_array = array();
                $title = $v_features->title;
                $title .= '<div class="row-options">';
                $title .= '<a href="' . base_url('saas/frontcms/features/new/' . $v_features->id) . '" >' . _l('edit') . '</a>';
                $title .= ' | <a href="' . base_url('saas/frontcms/features/delete_features_card/' . $v_features->id) . '" class="text-danger _delete" >' . _l('delete') . '</a>';
                $title .= '</div>';
                $sub_array[] = $title;
                $sub_array[] = $v_features->description;
                if ($v_features->status == 1) {
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

    // featuresList
    public function featuresList_2()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_all_section_area';
            $this->datatables->column_search = array('title', 'description');
            $this->datatables->column_order = array('title', 'description');
            $this->datatables->order = array('tbl_saas_all_section_area.id' => 'asc');
            $where = array('type' => 'features_collaborate');
            $fetch_data = make_datatables($where);

            $data = array();
            foreach ($fetch_data as $_key => $v_features) {
                $action = null;
                $sub_array = array();
                $title = $v_features->title;
                $title .= '<div class="row-options">';
                $title .= '<a href="' . base_url('saas/frontcms/features/new2/' . $v_features->id) . '" >' . _l('edit') . '</a>';
                $title .= ' | <a href="' . base_url('saas/frontcms/features/delete_features_card/' . $v_features->id) . '" class="text-danger _delete" >' . _l('delete') . '</a>';
                $title .= '</div>';
                $sub_array[] = $title;
                $sub_array[] = $v_features->description;
                if ($v_features->status == 1) {
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

    public function delete_features_card($id = null)
    {
        if (!empty($id)) {
            $features_info = get_row('tbl_saas_all_section_area', array('id' => $id));

            log_activity("features_card_deleted [" . $features_info->title . "]");

            $this->saas_model->_table_name = 'tbl_saas_all_section_area';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);

            if (is_file($features_info->icons)) {
                unlink($features_info->icons);
            }

            // messages for user
            $type = "success";
            $message = _l('delete') . " " . _l('features_card');
        }
        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function save_features_card($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $data = $this->saas_model->array_from_post(array('title', 'description', 'status', 'icons', 'name', 'link'));
        if (!empty($_FILES['image']['name'])) {
            $val = $this->saas_model->uploadImage('image', module_direcoty(SaaS_MODULE, 'uploads/'));
            $val == TRUE || redirect('saas/frontcms/features');
            $data['image'] = $val['path'];
        }
        $data['type'] = $this->uri->segment(3);
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save_features_card [' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('features_card');

        set_alert($type, $message);
        redirect('saas/frontcms/features');
    }

    public function save_features_collaborate($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $data = $this->saas_model->array_from_post(array('title', 'image', 'description', 'status', 'icons', 'name', 'link', 'button_name_2', 'button_link_2', 'icons_2', 'button_name_3', 'button_link_3', 'icons_3'));
        if (!empty($_FILES['image']['name'])) {
            $val = $this->saas_model->uploadImage('image', module_direcoty(SaaS_MODULE, 'uploads/'));
            $val == TRUE || redirect('saas/frontcms/features');
            $data['image'] = $val['path'];
        }
        $data['type'] = 'features_collaborate';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save_features_collaborate [' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('features_collaborate');

        set_alert($type, $message);
        redirect('saas/frontcms/features/features_list');
    }
}
