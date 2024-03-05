<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page extends AdminController
{

    function __construct()
    {
        parent::__construct();
        $config = array(
            'field' => 'slug',
            'title' => 'title',
            'table' => 'tbl_saas_front_pages',
            'id' => 'pages_id',
        );
        $this->load->library('slug', $config);
        $this->load->config('thumbnail');
        $this->load->library('imageResize');
        $this->load->model('saas_model');
    }

    public function sync()
    {

        $sync_url = base_url('modules/saas/');
        $replace_url = "http://localhost/client_moumen/modules/saas/";

        $all_frontend_page = get_result('tbl_saas_front_pages');
        if (!empty($all_frontend_page)) {
            foreach ($all_frontend_page as $v_page) {

                $descriptions = $v_page->description;
                $new_descriptions['description'] = str_replace($replace_url, base_url('modules/saas/'), $descriptions);

                // get all client
                $this->saas_model->_table_name = 'tbl_saas_front_pages';
                $this->saas_model->_primary_key = 'pages_id';
                $this->saas_model->save($new_descriptions, $v_page->pages_id);
            }
        }
        $col_1 = str_replace(base_url(), $sync_url, config_item('saas_front_footer_col_1_description'));
        $col_2 = str_replace(base_url(), $sync_url, config_item('saas_front_footer_col_2_description'));
        $col_3 = str_replace(base_url(), $sync_url, config_item('saas_front_footer_col_3_description'));
        $col_4 = str_replace(base_url(), $sync_url, config_item('saas_front_footer_col_4_description'));

        $input_data['saas_front_footer_col_1_description'] = $col_1;
        $input_data['saas_front_footer_col_2_description'] = $col_2;
        $input_data['saas_front_footer_col_3_description'] = $col_3;
        $input_data['saas_front_footer_col_4_description'] = $col_4;
        $input_data['saas_sync_frontend'] = 'Done';

        $this->saas_model->update_option($input_data);
        // messages for user
        $type = "success";
        $message = _l('sync_success');
        set_alert($type, $message);
        redirect('saas/frontcms/page');
    }

    function create($pages_id = null)
    {

        $data['title'] = _l('mpage');
        $data['category'] = config_item('pageCategory');
        $edited = super_admin_access();
        if (!empty($pages_id) && !empty($edited)) {
            $data['page_info'] = get_row('tbl_saas_front_pages', array('pages_id' => $pages_id));
        }
        $data['subview'] = $this->load->view('frontcms/pages/create', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    function index($pages_id = null)
    {

        $data['title'] = _l('mpage');
        $data['category'] = get_option('pageCategory');
        $data['subview'] = $this->load->view('frontcms/pages/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    public function pageList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_front_pages';
            $this->datatables->join_table = array('tbl_saas_front_pages_contents');
            $this->datatables->join_where = array('tbl_saas_front_pages_contents.page_id=tbl_saas_front_pages.pages_id');
            $this->datatables->column_search = array('title', 'tbl_saas_front_pages_contents.content_type');
            $this->datatables->column_order = array('tbl_saas_front_pages.pages_id', 'tbl_saas_front_pages.title', 'tbl_saas_front_pages_contents.content_type', 'tbl_saas_front_pages_contents.content');
            $this->datatables->order = array('tbl_saas_front_pages.pages_id' => 'asc');
            $fetch_data = make_datatables();

            $data = array();
            foreach ($fetch_data as $_key => $pages) {
                $action = null;
                $sub_array = array();
                $title = $pages->title;
                $title .= '<div class="row-options">';
                $title .= '<a
                    data-toggle="tooltip" data-placement="top"
                     href="' . base_url('saas/frontcms/page/create/' . $pages->pages_id) . '"  title="' . _l('edit') . '">' . _l('edit') . '</a>';
                $title .= ' | <a
                    data-toggle="tooltip" data-placement="top"
                    class="text-danger _delete"
                     href="' . base_url('saas/frontcms/page/delete_page/' . $pages->pages_id) . '"  title="' . _l('delete') . '">' . _l('delete') . '</a>';
                $title .= '</div>';

                $sub_array[] = $title;
                $sub_array[] = '<a target="_blank" href="' . base_url() . $pages->url . '">' . base_url() . $pages->url . '<a>';

                if ($pages->content_type == "gallery") {
                    $sub_array[] = '<span class="label label-success">' . $pages->content_type . '</span>';
                } elseif ($pages->content_type == "events") {
                    $sub_array[] = '<span class="label label-info">' . $pages->content_type . '</span>';
                } elseif ($pages->content_type == "notice") {
                    $sub_array[] = '<span class="label label-warning">' . $pages->content_type . '</span>';
                } else {
                    $sub_array[] = '<span class="label label-default">' . _l("standard") . '</span>';
                }
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function save_pages($id = null)
    {
        $created = super_admin_access();
        $edited = super_admin_access();
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $this->saas_model->_table_name = 'tbl_saas_front_pages';
            $this->saas_model->_primary_key = 'pages_id';
            $data = $this->saas_model->array_from_post(array('title'));
            $data['description'] = $this->input->post('description');
            $data['type'] = 'page';
            $data['slug'] = $this->slug->create_uri($data, $id);
            $data['url'] = 'frontcms/' . $data['slug'];
            if (!empty($id)) {
                $action = 'activity_update_pages';
                $msg = _l('update') . ' ' . _l('page');
            } else {
                $action = 'activity_save_page';
                $msg = _l('save') . ' ' . _l('page');
            }
            $id = $this->saas_model->save($data, $id);
            $category = $this->input->post('content_category');

            if (!empty($category)) {
                if ($category != "standard") {
                    $data = array();
                    $data["page_id"] = $id;
                    $data["content_type"] = $category;
                    $this->saas_model->_table_name = 'tbl_saas_front_pages_contents';
                    $this->saas_model->_primary_key = 'id';
                    $page_type = get_row('tbl_saas_front_pages_contents', array('page_id' => $id));
                    $page_contents_id = null;
                    if (!empty($page_type)) {
                        $page_contents_id = $page_type->id;
                    }
                    $this->saas_model->save($data, $page_contents_id);
                }
            }
            log_activity($action . ' [ID:' . $id . ']');
            // messages for user
            $type = "success";
            $message = $msg;
            set_alert($type, $message);
        } else {
            set_alert('error', _l('there_in_no_value'));
        }
        redirect('saas/frontcms/page');
    }

    public function delete_page($id = null)
    {
        $deleted = super_admin_access();
        if (!empty($id) && !empty($deleted)) {
            $page_info = get_row('tbl_saas_front_pages', array('pages_id' => $id));


            $this->saas_model->_table_name = 'tbl_saas_front_pages';
            $this->saas_model->_primary_key = 'pages_id';
            $this->saas_model->delete($id);

            $this->saas_model->_table_name = 'tbl_saas_front_pages_contents';
            $this->saas_model->_primary_key = 'page_id';
            $this->saas_model->delete($id);

            log_activity("Delete Page", "Delete Page " . $page_info->title . " (" . $id . ")");

            $type = "success";
            $message = _l('delete') . " " . _l('page');
        } else {
            $type = "error";
            $message = _l('no_permission');
        }
        echo json_encode(array("status" => $type, "message" => $message));
        exit();
    }

    public function add_image()
    {
        $data['title'] = _l('add') . ' ' . _l('menu'); //Page title
        $data['subview'] = $this->load->view('frontcms/pages/add_image', $data, FALSE);
        $data['size'] = 'modal-xl';
        $this->load->view('saas/_layout_modal_xl', $data); //page load
    }
}
