<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gallery extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
    }

    function index($id = null)
    {
        $data['title'] = _l('gallery');
        $data['table'] = true;
        $data['subview'] = $this->load->view('frontcms/gallery/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function new($id = null)
    {
        $data['title'] = _l('gallery');
        if (!empty($id)) {
            $data['gallery_card'] = get_row('tbl_saas_all_section_area', array('id' => $id));
        }
        $data['subview'] = $this->load->view('frontcms/gallery/new', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function create($id = null)
    {
        $data['title'] = _l('create_gallery');
        $data['gallery_info'] = get_row('tbl_saas_all_heading_section', array('type' => 'gallery_heading'));
        $data['subview'] = $this->load->view('frontcms/gallery/create', $data, FALSE);
        $this->load->view('saas/_layout_modal_lg', $data); //page load

    }

    public function save_gallery_heading($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('title', 'description'));
        $data['type'] = 'gallery_heading';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('Save gallery heading [ID:' . $return_id . ', ' . $data['title'] . ']');

        $type = "success";
        $message = _l('save') . ' ' . _l('gallery_heading');

        set_alert($type, $message);
        redirect('saas/frontcms/gallery');
    }

    public function save_gallery_card($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $data = $this->saas_model->array_from_post(array('title', 'link', 'status', 'icons', 'color'));
        if (!empty($_FILES['image']['name'])) {
            $val = $this->saas_model->uploadImage('image', module_direcoty(SaaS_MODULE, 'uploads/'));
            $val == TRUE || redirect('saas/frontcms/gallery');
            $data['image'] = $val['path'];
        }
        $data['type'] = 'gallery';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('Save gallery card [ID:' . $return_id . ', ' . $data['title'] . ']');

        $type = "success";
        $message = _l('save') . ' ' . _l('gallery_heading');

        set_alert($type, $message);
        redirect('saas/frontcms/gallery');
    }

    public function galleryList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_all_section_area';
            $this->datatables->column_search = array('title', 'description');
            $this->datatables->column_order = array('title', 'description');
            $this->datatables->order = array('tbl_saas_all_section_area.id' => 'asc');
            $where = array('type' => 'gallery');
            $fetch_data = make_datatables($where);
            $data = array();
            foreach ($fetch_data as $_key => $v_gallery) {
                $action = null;
                $sub_array = array();
                $title = $v_gallery->title;
                $title .= '<div class="row-options">';
                $title .= '<a href="' . base_url('saas/frontcms/gallery/new/' . $v_gallery->id) . '" >' . _l('edit') . '</a>';
                $title .= ' | <a href="' . base_url('saas/frontcms/gallery/delete_gallery_card/' . $v_gallery->id) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $title .= '</div>';
                $sub_array[] = $title;

                $sub_array[] = $v_gallery->icons;
                if ($v_gallery->status == 1) {
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

    public function delete_gallery_card($id = null)
    {
        if (!empty($id)) {
            $gallery_info = get_row('tbl_saas_all_section_area', array('id' => $id));
            log_activity("Gallery Deleted [ID:$id]");

            $this->saas_model->_table_name = 'tbl_saas_all_section_area';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);

            // messages for user
            $type = "success";
            $message = _l('delete') . " " . _l('gallery');
        }
        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }
}
