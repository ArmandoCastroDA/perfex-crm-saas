<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reviews extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
    }

    function index($id = null)
    {
        $data['title'] = _l('reviews');
        $data['table'] = true;
        $data['subview'] = $this->load->view('frontcms/reviews/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function create_review($id = null)
    {
        $data['title'] = _l('reviews');
        if (!empty($id)) {
            $data['review'] = get_row('tbl_saas_all_section_area', array('id' => $id));
        }
        $data['subview'] = $this->load->view('frontcms/reviews/create_review', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function create($id = null)
    {
        $data['title'] = _l('create_creatives');
        $data['review'] = get_row('tbl_saas_all_heading_section', array('type' => 'review_heading'));
        $data['subview'] = $this->load->view('frontcms/reviews/create', $data, FALSE);
        $this->load->view('saas/_layout_modal_lg', $data); //page load
    }


    public function save_review_heading($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('title', 'description', 'name'));
        $data['type'] = 'review_heading';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('review_heading_added [ID:' . $return_id . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('review_heading');

        set_alert($type, $message);
        redirect('saas/frontcms/reviews');
    }

    // creativesList
    public function reviewsList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_all_section_area';
            $this->datatables->column_search = array('title', 'image');
            $this->datatables->column_order = array('title', 'image');
            $this->datatables->order = array('tbl_saas_all_section_area.id' => 'asc');
            $where = array('type' => 'reviews');
            $fetch_data = make_datatables($where);

            $data = array();

            foreach ($fetch_data as $_key => $v_creatives) {
                $action = null;
                $sub_array = array();
                $title = $v_creatives->title;
                $title .= '<div class="row-options">';
                $title .= '<a href="' . saas_url('frontcms/reviews/create_review/' . $v_creatives->id) . '" data-toggle="tooltip" title="' . _l('edit') . '">' . _l('edit') . '</a>';
                $title .= ' | <a href="' . saas_url('frontcms/reviews/delete_review/' . $v_creatives->id) . '" class="text-danger _delete" data-toggle="tooltip" title="' . _l('delete') . '">' . _l('delete') . '</a>';
                $title .= '</div>';
                $sub_array[] = $title;
                $sub_array[] = '<img src="' . (!empty($v_creatives->image) ? base_url($v_creatives->image) : '') . '" class="img-circle" width="50" height="50">';
                // title_2 as rating
                $sub_array[] = $v_creatives->title_2;
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

    public function delete_review($id = null)
    {
        if (!empty($id)) {
            $creatives_info = get_row('tbl_saas_all_section_area', array('id' => $id));
            log_activity('creatives_reviews_deleted [' . $creatives_info->title . ']');
            $this->saas_model->_table_name = 'tbl_saas_all_section_area';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);

            if (is_file($creatives_info->icons)) {
                unlink($creatives_info->icons);
            }

            // messages for user
            $type = "success";
            $message = _l('delete') . " " . _l('reviews');
        }
        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function save_review($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $data = $this->saas_model->array_from_post(array('title', 'name', 'status', 'designation', 'title_2', 'description', 'title_2', 'icons'));
        if (!empty($_FILES['image']['name'])) {
            $val = $this->saas_model->uploadImage('image', module_direcoty(SaaS_MODULE, 'uploads/'));
            $val == TRUE || redirect('saas/frontcms/reviews');
            $data['image'] = $val['path'];
        }
        $data['type'] = 'reviews';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save_reviews [ID:' . $return_id . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('reviews');

        set_alert($type, $message);
        redirect('saas/frontcms/reviews');
    }
}
