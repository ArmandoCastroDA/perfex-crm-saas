<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menus extends AdminController
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        $this->load->model('cms_menu_model');
    }

    function index($id = null)
    {
        $data['title'] = _l('menus');
        $data['table'] = true;
        $data['subview'] = $this->load->view('frontcms/menus/index', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    public function add_menu()
    {
        $data['title'] = _l('new') . ' ' . _l('menu'); //Page title
        $data['subview'] = $this->load->view('frontcms/menus/add_menu', $data, FALSE);
        $this->load->view('saas/_layout_modal', $data); //page load
    }

    public function menu_list()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_front_menus';
            $column = array('menu');
            $this->datatables->column_order = $column;
            $this->datatables->column_search = $column;
            $this->datatables->order = array('id' => 'asc');
            $fetch_data = make_datatables();

            $data = array();
            $edited = super_admin_access();
            $deleted = super_admin_access();
            foreach ($fetch_data as $_key => $menus) {
                $action = null;
                $sub_array = array();
                $sub_array[] = $menus->menu;
                if (!empty($edited)) {
                    $action .= '<a href="' . base_url('saas/frontcms/menus/add_menu_item/main-menu') . '"  class="btn btn-success btn-sm" title="Save" data-toggle="tooltip" data-placement="top"><span <i="" class="fa fa-plus-circle"></span></a>';
                }
                if (!empty($deleted) && $menus->content_type != "default") {
                    $action .= '<a 
                    data-toggle="tooltip" data-placement="top"
                    class="btn btn-danger btn-sm _delete tw-ml-1"
                    href="' . base_url('saas/frontcms/menus/delete_menu/' . $menus->id) . '"  title="' . _l('delete') . '" >
                    <i class="fa fa-trash"></i></a>';
                }

                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    // save menu
    public function save_menu()
    {
        $created = super_admin_access();
        if (!empty($created)) {
            $this->saas_model->_table_name = 'tbl_saas_front_menus';
            $this->saas_model->_primary_key = 'id';
            $data = $this->saas_model->array_from_post(array('menu', 'description'));
            $config = array(
                'field' => 'slug',
                'title' => 'menu',
                'table' => 'tbl_saas_front_menus',
                'id' => 'id',
            );
            $this->load->library('slug', $config);
            $data['slug'] = $this->slug->create_uri($data);
            $where = array('slug' => $data['slug']);
            if (!empty($id)) { // if id exist in db update data
                $account_id = array('id !=' => $id);
            } else { // if id is not exist then set id as null
                $account_id = null;
            }
            $check_account = get_row('tbl_saas_front_menus', $where, $account_id);
            if (!empty($check_account)) { // if input data already exist show error alert
                $type = 'error';
                $message = "<strong class='text-danger'>" . $data['slug'] . '</strong>  ' . _l('already_exist');
            } else { // save and update query
                $return_id = $this->saas_model->save($data);
                log_activity("New Menu Created [ID: $return_id]");
                $type = "success";
                $message = _l('save') . ' ' . _l('menu');
            }
        } else {
            $type = "error";
            $message = _l('there_is_no_permission');
        }
        set_alert($type, $message);
        redirect('saas/frontcms/menus');
    }

    // add menu item
    public function add_menu_item($slug = null, $item_slug = null)
    {
        $edited = super_admin_access();
        if (!empty($edited)) {
            $data['title'] = _l('menu'); //Page title
            $data['page_list'] = get_result('tbl_saas_front_pages');
            $data['menu_info'] = get_row('tbl_saas_front_menus', array('slug' => $slug));
            $data['dropdown_menu_list'] = $this->cms_menu_model->getMenus($data['menu_info']->id);
            if (!empty($item_slug)) {
                $data['menu_item'] = get_row('tbl_saas_front_menu_items', array('slug' => $item_slug));
            }
            if (!empty($slug) && isset($_POST['submit'])) {
                $data = $this->saas_model->array_from_post(array('menu_id', 'page_id', 'menu', 'ext_url', 'open_new_tab'));
                $config = array(
                    'field' => 'slug',
                    'title' => 'menu',
                    'table' => 'tbl_saas_front_menu_items',
                    'id' => 'id',
                );
                $this->load->library('slug', $config);
                if ($this->input->post('ext_url')) {
                    $data['ext_url_link'] = $this->input->post('ext_url_link');
                } else {
                    $data['ext_url_link'] = null;
                }
                $data['slug'] = $this->slug->create_uri($data);
                $this->saas_model->_table_name = 'tbl_saas_front_menu_items';
                $this->saas_model->_primary_key = 'id';

                $item_id = $this->input->post('item_id');
                if (!empty($item_id) && isset($_POST['submit'])) {
                    $edited = super_admin_access();
                    if (!empty($edited)) {
                        $id = $this->saas_model->save($data, $item_id);
                        $action = "update_menu_item";
                        $msg = _l('update_menu_item');
                    }
                } else {
                    $id = $this->saas_model->save($data);
                    $action = 'save_menu_item';
                    $msg = _l('save_menu_item');
                }
                log_activity("Menu Item $action [ID: $id]");

                $type = "success";
                $message = $msg;
                set_alert($type, $message);
                redirect('saas/frontcms/menus/add_menu_item/' . $slug);
            }
            $data['subview'] = $this->load->view('frontcms/menus/add_menu_item', $data, TRUE);
            $this->load->view('_layout_main', $data);
        } else {
            $type = "error";
            $message = _l('there_is_no_permission');
            set_alert($type, $message);
            redirect('saas/frontcms/menus');
        }
    }

    // sort menu
    public function sort_menu()
    {
        $order = $this->input->post('order');
        $weight = 1;
        $array = array();
        foreach ($order as $o_key => $o_value) {
            $array[] = array(
                'id' => $o_value['id'],
                'parent_id' => 0,
                'weight' => $weight
            );
            if (isset($o_value['children'])) {
                $weight++;
                foreach ($o_value['children'] as $key => $value) {
                    $array[] = array(
                        'id' => $value['id'],
                        'parent_id' => $o_value['id'],
                        'weight' => $weight
                    );
                    $weight++;
                }
            }
            $weight++;
        }
        $this->saas_model->_table_name = 'tbl_saas_front_menu_items';
        $this->saas_model->save_batch($array, 'id');

    }

    // delete menu
    public function delete_menu($id = null)
    {
        $deleted = super_admin_access();
        if (!empty($id) && !empty($deleted)) {
            $menu_info = get_row('tbl_saas_front_menus', array('id' => $id));
            log_activity("delete menu ID($id) - $menu_info->menu");

            $this->saas_model->_table_name = 'tbl_saas_front_menus';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);
            // messages for user
            $type = "success";
            $message = _l('delete') . ' ' . _l('menu');
        } else {
            $type = "error";
            $message = _l('no_permission');
        }
        set_alert($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    // delete menu item
    public function delete_menu_item()
    {
        $id = $this->input->post('id');
        if (!empty($id)) {
            $item_info = get_row('tbl_saas_front_menu_items', array('id' => $id));
            log_activity('delete menu item ID(' . $id . ') - ' . $item_info->menu);

            $this->saas_model->_table_name = 'tbl_saas_front_menu_items';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->delete($id);

            $type = "success";
            $message = _l('delete') . ' ' . _l('menu');
        } else {
            $type = "error";
            $message = _l('no_permission');
        }
        $data['status'] = $type;
        $data['msg'] = $message;
        echo json_encode($data);
        exit();
    }
}
