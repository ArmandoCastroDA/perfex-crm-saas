<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Abouts extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
    }

    function index($active = null, $type = null)
    {
        $data['title'] = _l('abouts');
        $data['active'] = $active ?: '1';
        $data['type'] = $type ?: 'abouts';
        $data['subview'] = $this->load->view('frontcms/abouts/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function new($id = null)
    {
        $data['title'] = _l('abouts');
        if (!empty($id)) {
            $data['abouts_card'] = get_row('tbl_saas_all_section_area', array('id' => $id));
        }
        $data['subview'] = $this->load->view('frontcms/abouts/new', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function create()
    {
        $data['title'] = _l('create_abouts');
        $data['abouts_info'] = get_row('tbl_saas_all_heading_section', array('type' => $this->uri->segment(3)));
        $data['subview'] = $this->load->view('frontcms/abouts/create', $data, FALSE);
        $this->load->view('saas/_layout_modal_lg', $data); //page load

    }


    public function save_abouts_heading($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('title', 'description'));
        $data['type'] = 'abouts';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);

        log_activity('save_abouts_heading [' . $data['title'] . ']');

        $type = "success";
        $message = _l('save') . ' ' . _l('abouts_heading');

        set_alert($type, $message);
        redirect('saas/frontcms/abouts');
    }

    // aboutsList

    public function delete_abouts_card($id = null)
    {
        if (!empty($id)) {
            $abouts_info = get_row('tbl_saas_all_section_area', array('id' => $id));
            log_activity("Delete Abouts Card [ID: $id, Title: $abouts_info->title]");

            $this->saas_model->_table_name = 'tbl_saas_all_section_area';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);

            if (is_file($abouts_info->icons)) {
                unlink($abouts_info->icons);
            }

            // messages for user
            $type = "success";
            $message = _l('delete') . " " . _l('abouts_card');
        }
        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function save_abouts_card($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $data = $this->saas_model->array_from_post(array('title', 'description', 'status', 'icons', 'name', 'link', 'color'));
        if (!empty($_FILES['image']['name'])) {
            $val = $this->saas_model->uploadImage('image', module_dir_path(SaaS_MODULE, 'uploads/'));
            $val == TRUE || redirect('saas/frontcms/abouts');
            $data['image'] = $val['path'];
        }
        $data['type'] = 'abouts';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save_abouts_card [' . $data['title'] . ']');

        $type = "success";
        $message = _l('save') . ' ' . _l('abouts_card');

        set_alert($type, $message);
        redirect('saas/frontcms/abouts');
    }

    public function aboutsList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_all_section_area';
            $this->datatables->column_search = array('title', 'description');
            $this->datatables->column_order = array('title', 'description');
            $this->datatables->order = array('tbl_saas_all_section_area.id' => 'asc');
            $where = array('type' => 'about_works');
            $fetch_data = make_datatables($where);

            // where
            $data = array();
            foreach ($fetch_data as $_key => $v_abouts) {
                $action = null;
                $sub_array = array();
                $title = $v_abouts->title;
                $title .= '<div class="row-options">';
                $title .= '<a href="' . admin_url('saas/frontcms/abouts/new/' . $v_abouts->id) . '" >' . _l('edit') . '</a>';

                $title .= ' | <a href="' . admin_url('saas/frontcms/abouts/delete_abouts_card/' . $v_abouts->id) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $title .= '</div>';
                $sub_array[] = $title;
                $sub_array[] = $v_abouts->description;
                if ($v_abouts->status == 1) {
                    $sub_array[] = '<span class="label label-success">' . _l('active') . '</span>';
                } else {
                    $sub_array[] = '<span class="label label-danger">' . _l('deactive') . '</span>';
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data, $where);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function save_about_works($id = null)
    {

        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('title', 'icons', 'status', 'description', 'name'));
        $data['type'] = $this->input->post('type');
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save_abouts_work [' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('about_works');
        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function save_abouts_collaborate($id = null)
    {

        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $data = $this->saas_model->array_from_post(array('title', 'image', 'description', 'status', 'icons', 'name', 'link', 'button_name_2', 'button_link_2', 'icons_2', 'button_name_3', 'button_link_3', 'icons_3'));
        if (!empty($_FILES['image']['name'])) {
            $val = $this->saas_model->uploadImage('image', module_direcoty(SaaS_MODULE, 'uploads/'));
            $val == TRUE || redirect('saas/frontcms/abouts');
            $data['image'] = $val['path'];
        }
        $data['type'] = 'abouts_collaborate';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save_abouts_collaborate [' . $data['title'] . ']');

        $type = "success";
        $message = _l('save') . ' ' . _l('abouts_collaborate');

        set_alert($type, $message);
        redirect('saas/frontcms/abouts');
    }

    public function save_about_footer($id = null)
    {

        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $data = $this->saas_model->array_from_post(array('title', 'description', 'status', 'icons', 'name', 'link', 'title_2', 'button_name_2', 'icons_2', 'icons_3', 'button_link_2'));

        if (!empty($_FILES['image']['name'])) {
            $val = $this->saas_model->uploadImage('image', module_direcoty(SaaS_MODULE, 'uploads/'));
            $val == TRUE || redirect('saas/frontcms/abouts');
            $data['image'] = $val['path'];
        }
        $data['type'] = 'about_footer';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save_about_footer [' . $data['title'] . ']');

        $type = "success";
        $message = _l('save') . ' ' . _l('about_footer');

        set_alert($type, $message);
        redirect('saas/frontcms/abouts');
    }
}
