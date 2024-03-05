<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blogs extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
    }

    function index($id = null)
    {
        $data['title'] = _l('blogs');
        $data['table'] = true;
        $data['subview'] = $this->load->view('frontcms/blogs/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function create_blog($id = null)
    {
        $data['title'] = _l('new') . ' ' . _l('blogs');
        if (!empty($id)) {
            $data['blogs_card'] = get_row('tbl_saas_all_section_area', array('id' => $id));
        }
        $data['subview'] = $this->load->view('frontcms/blogs/create_blog', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function create($id = null)
    {
        $data['title'] = _l('create_blogs');
        $data['blogs_info'] = get_row('tbl_saas_all_heading_section', array('type' => 'blogs_heading'));
        $data['subview'] = $this->load->view('frontcms/blogs/create', $data, FALSE);
        $this->load->view('saas/_layout_modal_lg', $data); //page load
    }

    public function save_blogs_heading($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('title', 'description'));
        $data['type'] = 'blogs_heading';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('blogs_card_saved [' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('blogs_heading');

        set_alert($type, $message);
        redirect('saas/frontcms/blogs');
    }

    public function save_blogs_card($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $data = $this->saas_model->array_from_post(array('button_link_2', 'button_name_2', 'color_2', 'color', 'link', 'title', 'date', 'name', 'status'));
        if (!empty($_FILES['image']['name'])) {
            $val = $this->saas_model->uploadImage('image', module_direcoty(SaaS_MODULE, 'uploads/'));
            $val == TRUE || redirect('saas/frontcms/features');
            $data['image'] = $val['path'];
        }
        $data['type'] = 'blogs';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('blogs_card_saved [' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('blogs_heading');

        set_alert($type, $message);
        redirect('saas/frontcms/blogs');
    }

    public function blogsList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_all_section_area';
            $columns = array('title');
            $this->datatables->column_search = $columns;
            $this->datatables->column_order = $columns;
            $this->datatables->order = array('tbl_saas_all_section_area.id' => 'asc');
            $where = array('type' => 'blogs');
            $fetch_data = make_datatables($where);
            $data = array();
            foreach ($fetch_data as $_key => $v_blogs) {
                $action = null;
                $sub_array = array();
                $name = $v_blogs->name;
                $name .= '<div class="row-options">';
                $name .= '<a href="' . admin_url('saas/frontcms/blogs/create_blog/' . $v_blogs->id) . '" class="mright5">' . _l('edit') . '</a>';
                $name .= '| <a href="' . admin_url('saas/frontcms/blogs/delete_blogs_card/' . $v_blogs->id) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $name .= '</div>';

                $sub_array[] = $name;
                $sub_array[] = $v_blogs->title;
                $sub_array[] = $v_blogs->button_name_2;
                if ($v_blogs->status == 1) {
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
                redirect('saas/frontcms/blogs');
            }
        }
        $data['subview'] = $this->load->view('settings/blogs_details', $data, FALSE);
        $this->load->view('saas/_layout_modal_lg', $data); //page load
    }

    public function delete_blogs_card($id = null)
    {

        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $this->saas_model->delete($id);

        // messages for user
        $type = "success";
        $message = _l('delete') . " " . _l('blogs');

        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function delete_contact($id = null)
    {
        $deleted = super_admin_access();
        if (!empty($deleted) && !empty($id)) {
            $contact_info = get_row('tbl_saas_front_contact_us', array('id' => $id));
            log_activity('activity_deleted_contact [' . $contact_info->name . ']');

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
