<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
    }

    public function index()
    {
        $data['title'] = _l('general_settings');
        $data['page'] = _l('settings');
        $data['load_setting'] = 'general';
        $data['subview'] = $this->load->view('frontcms/settings/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }


    public function updateOption()
    {
        $logo_uploaded = (handle_saas_company_logo_upload() ? true : false);
        $favicon_uploaded = (handle_saas_favicon_upload() ? true : false);
        $signatureUploaded = (handle_saas_company_signature_upload() ? true : false);

        $post_data = $this->input->post();
        $tmpData = $this->input->post(null, false);

        foreach ($post_data as $key => $value) {
            update_option($key, $value);
        }

        set_alert('success', _l('settings_updated'));


        if ($logo_uploaded || $favicon_uploaded) {
            set_debug_alert(_l('logo_favicon_changed_notice'));
        }
        redirect($_SERVER['HTTP_REFERER']);
    }


    public function footer_middle($id = null)
    {
        $data['title'] = _l('footer');
        $data['load_setting'] = 'footer_middle';
        $data['table'] = 'footer_middle';

        if (!empty($id)) {
            $data['active'] = 2;
            $data['footer_card'] = get_row('tbl_saas_all_section_area', array('id' => $id));
        } else {
            $data['active'] = 1;
        }
        $data['subview'] = $this->load->view('frontcms/settings/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    public function footerList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_all_section_area';
            $this->datatables->column_search = array('title', 'description');
            $this->datatables->column_order = array('title', 'description');
            $this->datatables->order = array('tbl_saas_all_section_area.id' => 'asc');
            $status = array('company', 'usefull_links');
            $where_in = array('type', $status);
            $fetch_data = make_datatables(null, $where_in);
            $data = array();

            foreach ($fetch_data as $_key => $v_footer) {
                $action = null;
                $sub_array = array();
                $name = $v_footer->button_name_2;
                $name .= '<div class="row-options">';
                $name .= '<a href="' . base_url('saas/frontcms/settings/footer_middle/' . $v_footer->id) . '" >' . _l('edit') . '</a>';
                // delete
                $name .= ' | <a href="' . base_url('saas/frontcms/settings/delete_footer_card/' . $v_footer->id) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $name .= '</div>';
                $sub_array[] = $name;
                $sub_array[] = $v_footer->button_link_2;
                if ($v_footer->status == 1) {
                    $sub_array[] = '<span class="label label-success">' . _l('active') . '</span>';
                } else {
                    $sub_array[] = '<span class="label label-danger">' . _l('deactive') . '</span>';
                }

                $data[] = $sub_array;
            }
            render_table($data, null, $where_in);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function delete_footer_card($id = null)
    {
        if (!empty($id)) {
            $features_info = get_row('tbl_saas_all_section_area', array('id' => $id));
            log_activity("Delete Footer Card [ID: $features_info->id, $features_info->button_name_2]");

            $this->saas_model->_table_name = 'tbl_saas_all_section_area';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);

            // messages for user
            $type = "success";
            $message = _l('delete') . " " . _l('footer');
        }
        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function save_footer_card($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $data = $this->saas_model->array_from_post(array('button_link_2', 'button_name_2', 'status', 'icons', 'type'));

        // $data['type']  = 'footer';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save Footer card [' . $data['button_name_2'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('footer_heading');

        set_alert($type, $message);
        redirect('saas/frontcms/settings/footer_middle');
    }

    public function blogs()
    {
        $data['title'] = _l('blogs');
        $data['load_setting'] = 'blogs';
        $data['blogs_info'] = get_row('tbl_saas_all_heading_section', array('type' => $this->uri->segment(4)));
        $data['subview'] = $this->load->view('frontcms/settings/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function create_footer_icons($id = null)
    {
        $data['title'] = _l('create_footer_icons');
        $data['footer_icons_info'] = get_row('tbl_saas_all_heading_section', array('type' => 'footer_icons', 'heading_id' => $id));
        $data['subview'] = $this->load->view('frontcms/settings/create_footer_icons', $data, FALSE);
        $this->load->view('saas/_layout_modal_lg', $data); //page load

    }

    public function footer_left()
    {
        $data['title'] = _l('footer_left');
        $data['load_setting'] = 'footer_left';
        $data['footer_left_info'] = get_row('tbl_saas_all_heading_section', array('type' => $this->uri->segment(4)));
        $data['subview'] = $this->load->view('frontcms/settings/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    public function footer_left_icon()
    {
        $data['title'] = _l('footer_left_icon');
        $data['load_setting'] = 'footer_left_icon';
        $data['table'] = true;
        // $data['footer_left_icon_info'] = get_row('tbl_saas_all_heading_section', array('type' => $this->uri->segment(4)));
        $data['subview'] = $this->load->view('frontcms/settings/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    public function footer_iconsList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_all_heading_section';
            $this->datatables->column_search = array('title', 'description');
            $this->datatables->column_order = array('title', 'description');
            $this->datatables->order = array('tbl_saas_all_heading_section.heading_id' => 'asc');
            $where = array('type' => 'footer_icons');
            $fetch_data = make_datatables($where);

            $data = array();

            foreach ($fetch_data as $_key => $v_icon_footer) {
                $action = null;
                $sub_array = array();
                $icon = $v_icon_footer->icons;
                // for edit and delete actions
                $icon .= '<div class="row-options">';
                $icon .= '<a
                 data-toggle="modal" data-target="#myModal" 
                 href="' . base_url('saas/frontcms/settings/create_footer_icons/' . $v_icon_footer->heading_id) . '" >' . _l('edit') . '</a>';
                $icon .= ' | <a href="' . base_url('saas/frontcms/settings/delete_icons_footer/' . $v_icon_footer->heading_id) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $icon .= '</div>';
                $sub_array[] = $icon;
                $sub_array[] = $v_icon_footer->links;
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data, $where);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function delete_icons_footer($id = null)
    {

        if (!empty($id)) {
            $features_info = get_row('tbl_saas_all_heading_section', array('heading_id' => $id));

            log_activity('delete icons footer [' . $features_info->title . ']');

            $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
            $this->saas_model->_primary_key = 'heading_id';
            $this->saas_model->delete($id);
            // check its delete or not
            $affectedRows = $this->db->affected_rows();
            if ($affectedRows == 0) {
                set_alert('warning', _l('no_record_found_to_delete'));
                redirect($_SERVER['HTTP_REFERER']);
            }


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

    public function footer_right()
    {
        $data['title'] = _l('footer_right');
        $data['load_setting'] = 'footer_right';
        $data['footer_right_info'] = get_row('tbl_saas_all_heading_section', array('type' => $this->uri->segment(4)));
        $data['subview'] = $this->load->view('frontcms/settings/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    public function footer_bottom()
    {
        $data['title'] = _l('footer_bottom');
        $data['load_setting'] = 'footer_bottom';
        $data['footer_bottom_info'] = get_row('tbl_saas_all_heading_section', array('type' => $this->uri->segment(4)));
        $data['subview'] = $this->load->view('frontcms/settings/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    public function save_footer_icons($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('icons', 'links'));
        $data['type'] = 'footer_icons';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);

        log_activity('save footer icons [' . $data['title'] . ']');

        $type = "success";
        $message = _l('save') . ' ' . _l('footer_icons');

        set_alert($type, $message);
        redirect('saas/frontcms/settings/footer_left_icon');
    }

    public function save_footer_bottom($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('name', 'icons', 'links', 'description', 'title'));
        $data['type'] = 'footer_bottom';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save footer bottom [' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('footer_bottom');

        set_alert($type, $message);
        redirect('saas/frontcms/settings/footer_bottom');
    }

    public function questions()
    {
        $data['title'] = _l('questions');
        $data['load_setting'] = 'questions';
        $data['questions_info'] = get_row('tbl_saas_all_heading_section', array('type' => 'questions'));
        $data['subview'] = $this->load->view('frontcms/settings/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    public function save_questions($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('title', 'description', 'name', 'links'));
        $data['type'] = 'questions';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save questions [' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('questions_heading');

        set_alert($type, $message);
        redirect('saas/frontcms/settings/questions');
    }

    public function save_footer_left($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('description', 'links'));
        if (!empty($_FILES['icons']['name'])) {
            $val = $this->saas_model->uploadImage('icons', module_direcoty(SaaS_MODULE, 'uploads/'));
            $val == TRUE || redirect('saas/frontcms/features');
            $data['icons'] = $val['path'];
        }
        $data['type'] = 'footer_left';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save footer left [' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('footer_left');

        set_alert($type, $message);
        redirect('saas/frontcms/settings/footer_left');
    }

    public function save_footer_right($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('name', 'icons', 'links', 'title'));

        $data['type'] = 'footer_right';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save footer right [' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('footer_right');

        set_alert($type, $message);
        redirect('saas/frontcms/settings/footer_right');
    }


    // pricint
    public function pricing()
    {
        $data['title'] = _l('pricing');
        $data['load_setting'] = 'pricing';

        $data['subview'] = $this->load->view('saas/frontcms/settings/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }


    public function contact()
    {
        $data['title'] = _l('contact');
        $data['load_setting'] = 'contact';
        $data['active'] = 1;
        $data['table'] = true;
        $data['subview'] = $this->load->view('frontcms/settings/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    public function save_contact($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $data = $this->saas_model->array_from_post(array('name', 'icons', 'link', 'title'));

        $data['type'] = 'new_contact';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save contact [' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('new_contact');

        set_alert($type, $message);
        redirect('saas/frontcms/settings/contact');
    }

    function create_contact($id = null)
    {
        $data['title'] = _l('create_contact');
        if (!empty($id)) {
            $data['contact_info'] = get_row('tbl_saas_all_section_area', array('id' => $id));
        }
        $data['subview'] = $this->load->view('frontcms/settings/create_contact', $data, FALSE);
        $this->load->view('saas/_layout_modal_lg', $data); //page load

    }

    public function contactList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_all_section_area';
            $this->datatables->column_search = array('title', 'description');
            $this->datatables->column_order = array('title', 'description');
            $this->datatables->order = array('tbl_saas_all_section_area.id' => 'asc');
            $where = array('type' => 'new_contact');
            $fetch_data = make_datatables($where);

            $data = array();

            foreach ($fetch_data as $_key => $v_contact) {
                $action = null;
                $sub_array = array();
                $name = $v_contact->name;
                $name .= '<div class="row-options">';

                $name .= '<a data-toggle="modal" data-target="#myModal" href="' . base_url('saas/frontcms/settings/create_contact/' . $v_contact->id) . '" class="edit">' . _l('edit') . '</a>';
                $name .= ' | <a href="' . base_url('saas/frontcms/settings/delete_contact/' . $v_contact->id) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $name .= '</div>';
                $sub_array[] = $name;

                $sub_array[] = $v_contact->icons;
                $sub_array[] = $v_contact->title;
                $data[] = $sub_array;
            }
            render_table($data, $where);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function delete_contact($id = null)
    {

        $this->saas_model->_table_name = 'tbl_saas_all_section_area';
        $this->saas_model->_primary_key = 'id';
        $this->saas_model->delete($id);

        // messages for user
        $type = "success";
        $message = _l('delete') . " " . _l('contact');
        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function save_contact_heading($id = null)
    {
        $this->saas_model->_table_name = 'tbl_saas_all_heading_section';
        $this->saas_model->_primary_key = 'heading_id';
        $data = $this->saas_model->array_from_post(array('description', 'title'));

        $data['type'] = 'contact_heading';
        $data['user_id'] = get_staff_user_id();
        $return_id = $this->saas_model->save($data, $id);
        log_activity('save contact heading [' . $data['title'] . ']');
        $type = "success";
        $message = _l('save') . ' ' . _l('contact_heading');

        set_alert($type, $message);
        redirect('saas/frontcms/settings/contact');
    }

    // slider
    public function slider($create = null, $id = null)
    {
        $data['title'] = _l('slider');
        $data['load_setting'] = 'slider';
        $edited = super_admin_access();
        if (!empty($id) && !empty($edited)) {
            $data['slider_info'] = get_row('tbl_saas_front_slider', array('id' => $id));
        }
        $view = 'slider';
        if (!empty($create)) {
            $view = 'new_slider';
        }
        $data['subview'] = $this->load->view('frontcms/settings/' . $view, $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    public function slider_list()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_front_slider';
            $this->datatables->column_search = array('title');
            $this->datatables->column_order = array('title');
            $this->datatables->order = array('id' => 'asc');
            $fetch_data = make_datatables();
            $data = array();

            foreach ($fetch_data as $_key => $info) {
                $action = null;
                $sub_array = array();
                $title = $info->title;
                $title .= '<div class="row-options">';

                $title .= '<a
                    data-toggle="tooltip" data-placement="top"
                     href="' . base_url('saas/frontcms/settings/slider/create/' . $info->id) . '"  
                      title="' . _l('edit') . '">' . _l('edit') . '</a>';
                $title .= '| <a 
                    data-toggle="tooltip" data-placement="top"
                    href="' . base_url('saas/frontcms/settings/delete_slider/' . $info->id) . '"
                      title="' . _l('delete') . '" class="text-danger _delete">' . _l('delete') . '</a>';

                $title .= '</div>';
                $sub_array[] = $title;
                $sub_array[] = '<img class="w-210" src="' . base_url() . $info->slider_img . '">';
                $sub_array[] = ($info->description);
                if ($info->status == 1) {
                    $sub_array[] = '<span class="label label-success">' . _l('active') . '</span>';
                } else {
                    $sub_array[] = '<span class="label label-danger">' . _l('deactive') . '</span>';
                }
                if (!empty($edited)) {
                    $action .= btn_edit('saas/frontcms/settings/slider/' . $info->id) . ' ';
                }
                if (!empty($deleted)) {
                    $action .= ajax_anchor(base_url("saas/frontcms/settings/delete_slider/$info->id"), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key));
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function save_slider($id = null)
    {
        $created = super_admin_access();
        $edited = super_admin_access();
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $data = $this->saas_model->array_from_post(array('title', 'subtitle', 'description', 'button_icon_1', 'button_icon_1', 'button_text_1', 'button_link_1', 'button_text_2', 'button_link_2', 'status'));
            if (!empty($_FILES['slider_bg']['name'])) {
                $val = $this->saas_model->uploadImage('slider_bg', module_direcoty(SaaS_MODULE, 'uploads/'));
                $val == TRUE || redirect('saas/frontcms/settings/slider');
                $data['slider_bg'] = $val['path'];
            }

            if (!empty($_FILES['slider_img']['name'])) {
                $val = $this->saas_model->uploadImage('slider_img', module_direcoty(SaaS_MODULE, 'uploads/'));
                $val == TRUE || redirect('saas/frontcms/settings/slider');
                $data['slider_img'] = $val['path'];
            }
            $this->saas_model->_table_name = "tbl_saas_front_slider"; // table name
            $this->saas_model->_primary_key = "id"; // $id
            $this->saas_model->save($data, $id);

            if (!empty($id)) {
                $activity = 'update_slider';
                $msg = _l($activity);
            } else {
                $activity = 'save_slider';
                $msg = _l($activity);
            }
            log_activity('slider', $msg . ' [' . $data['title'] . ']');

            // messages for user
            $type = "success";
            $message = $msg;
        } else {
            $type = "error";
            $message = _l('there_is_no_permission');
        }
        set_alert($type, $message);
        redirect('saas/frontcms/settings/slider');
    }

    public function delete_slider($id = null)
    {
        $deleted = super_admin_access();
        if (!empty($id) && !empty($deleted)) {
            $slider_info = get_row('tbl_saas_front_slider', array('id' => $id));
            log_activity("delete_slider [$slider_info->title] ");

            $this->saas_model->_table_name = 'tbl_saas_front_slider';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);

            if (is_file($slider_info->slider)) {
                unlink($slider_info->slider);
            }

            // messages for user
            $type = "success";
            $message = _l('delete') . " " . _l('slider');
        } else {
            $type = "error";
            $message = _l('no_permission');
        }
        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

}
