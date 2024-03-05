<?php defined('BASEPATH') or exit('No direct script access allowed');

class Super_admin extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        $this->load->model('staff_model');
        saas_access();
    }

    public function index()
    {
        // $user_id = $id;
        $data['active'] = 1;
        $data['title'] = 'User List';
        $data['breadcrumbs'] = _l('super_admin');
        $data['staff_members'] = $this->staff_model->get('', ['role' => 4,'active' => 1]);
        $data['subview'] = $this->load->view('user/user_list', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function userList($filterBy = null)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = db_prefix() . 'staff';

            $custom_field = array();
            $main_column = array('staffid', 'fullname', 'email', 'active');
            $result = array_merge($main_column, $custom_field);
            $this->datatables->column_order = $result;
            $this->datatables->column_search = $result;
            $this->datatables->order = array('staffid' => 'desc');
            $where = array('role' => 4);
            // get all invoice
            $fetch_data = make_datatables($where);

            $data = array();

            foreach ($fetch_data as $_key => $v_user) {
                $action = null;
                $sub_array = array();
                $name = $v_user->firstname . ' ' . $v_user->lastname;
                if ($v_user->staffid != get_staff_user_id()) {
                    $name .= '<div class="row-options">';
                    $name .= '<a href="' . saas_url('super_admin/create/edit/' . $v_user->staffid) . '">' . _l('edit') . '</a>';
                    $name .= ' | <a href="#" onclick="delete_staff_member(' . $v_user->staffid . '); return false;" class="text-danger">' . _l('delete') . '</a>';
                    $name .= '</div>';
                }
                $sub_array[] = $name;
                $sub_array[] = $v_user->email;
                $sub_array[] = ($v_user->last_login != null) ? '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($v_user->last_login) . '">' . time_ago($v_user->last_login) . '</span>' : _l('never');
                $checked = '';
                if ($v_user->active == 1) {
                    $checked = 'checked';
                }
                $_data = '<div class="onoffswitch">
                <input type="checkbox" ' . (($v_user->staffid == get_staff_user_id()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'staff/change_staff_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $v_user->staffid . '" data-id="' . $v_user->staffid . '" ' . $checked . '>
                <label class="onoffswitch-label" for="c_' . $v_user->staffid . '"></label></div>';
                $sub_array[] = $_data;
                $data[] = $sub_array;
            }
            render_table($data, $where);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function create($action = NULL, $id = NULL)
    {
        $user_id = $id;
        $data['breadcrumbs'] = _l('super_admin');

        if ($action == 'edit') {
            $edited = super_admin_access();
            if (!empty($edited) && $id != get_staff_user_id()) {
                $data['member'] = $this->staff_model->get($id);
            }
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            // Don't do XSS clean here.
            $data['email_signature'] = $this->input->post('email_signature', false);
            $data['email_signature'] = html_entity_decode($data['email_signature']);

            if ($data['email_signature'] == strip_tags($data['email_signature'])) {
                // not contains HTML, add break lines
                $data['email_signature'] = nl2br_save_html($data['email_signature']);
            }

            $data['password'] = $this->input->post('password', false);
            $data['role'] = 4;
            $data['admin'] = 1;

            if ($id == '') {
                if (!has_permission('staff', '', 'create')) {
                    access_denied('staff');
                }
                $id = $this->staff_model->add($data);
                if ($id) {
                    handle_staff_profile_image_upload($id);
                    set_alert('success', _l('added_successfully', _l('staff_member')));
                    redirect(admin_url('saas/super_admin/'));
                }
            } else {
                if (!has_permission('staff', '', 'edit')) {
                    access_denied('staff');
                }
                handle_staff_profile_image_upload($id);
                $response = $this->staff_model->update($data, $id);
                if (is_array($response)) {
                    if (isset($response['cant_remove_main_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_main_admin'));
                    } elseif (isset($response['cant_remove_yourself_from_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
                    }
                } elseif ($response == true) {
                    set_alert('success', _l('updated_successfully', _l('staff_member')));
                }
                redirect(admin_url('saas/super_admin/'));
            }
        }
        $data['active'] = 2;
        $data['title'] = 'Create User ';
        // get all language
        $data['subview'] = $this->load->view('user/create', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function save_user($id = null)
    {
        if (!empty($id) && $id == get_staff_user_id()) {
            set_alert('error', lang('can_not_edit_yourself'));
            redirect('saas/super_admin');
        }

        $created = super_admin_access();
        $edited = super_admin_access();
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $login_data = $this->user_model->array_from_post(array('username', 'email', 'role_id'));

            $user_id = $this->input->post('user_id', true);
            // update root category
            $where = array('username' => $login_data['username']);
            $email = array('email' => $login_data['email']);
            $login_data['role_id'] = 4;
            // duplicate value check in DB
            if (!empty($user_id)) { // if id exist in db update data
                $check_id = array('user_id !=' => $user_id);
            } else { // if id is not exist then set id as null
                $check_id = null;
            }
            // check whether this input data already exist or not
            $check_user = $this->user_model->check_update('tbl_users', $where, $check_id);
            $check_email = $this->user_model->check_update('tbl_users', $email, $check_id);
            if (!empty($check_user) || !empty($check_email)) { // if input data already exist show error alert
                if (!empty($check_user)) {
                    $error = $login_data['username'];
                } else {
                    $error = $login_data['email'];
                }

                // massage for user
                $type = 'error';
                $message = "<strong style='color:#000'>" . $error . '</strong>  ' . _l('already_exist');

                $password = $this->input->post('password', TRUE);
                $confirm_password = $this->input->post('confirm_password', TRUE);
                if ($password != $confirm_password) {
                    $type = 'error';
                    $message = _l('password_does_not_match');
                }
            } else { // save and update query
                $login_data['last_ip'] = $this->input->ip_address();

                if (empty($user_id)) {
                    $password = $this->input->post('password', TRUE);
                    $login_data['password'] = $this->hash($password);
                }

                $this->user_model->_table_name = 'tbl_users'; // table name
                $this->user_model->_primary_key = 'user_id'; // $id
                if (!empty($user_id)) {
                    $id = $this->user_model->save($login_data, $user_id);
                } else {
                    $login_data['activated'] = '1';
                    $id = $this->user_model->save($login_data);
                }
                // save into tbl_account details
                $profile_data = $this->user_model->array_from_post(array('fullname', 'employment_id', 'company', 'locale', 'language', 'phone', 'mobile', 'skype', 'designations_id', 'direction', 'warehouse_id'));

                if ($login_data['role_id'] != 2) {
                    $profile_data['company'] = 0;
                }

                $account_details_id = $this->input->post('account_details_id', TRUE);
                if (!empty($_FILES['avatar']['name'])) {
                    $val = $this->user_model->uploadImage('avatar');
                    $val == TRUE || redirect('saas/super_admin');
                    $profile_data['avatar'] = $val['path'];
                }

                $profile_data['user_id'] = $id;

                $this->user_model->_table_name = 'tbl_account_details'; // table name
                $this->user_model->_primary_key = 'account_details_id'; // $id
                if (!empty($account_details_id)) {
                    $this->user_model->save($profile_data, $account_details_id);
                } else {
                    $id = $this->user_model->save($profile_data);
                }
                if (!empty($profile_data['designations_id'])) {
                    $desig = $this->db->where('designations_id', $profile_data['designations_id'])->get('tbl_designations')->row();
                    $department_head_id = $this->input->post('department_head_id', true);
                    if (!empty($department_head_id)) {
                        $head['department_head_id'] = $id;
                    } else {
                        $dep_head = $this->user_model->check_by(array('departments_id' => $desig->departments_id), 'tbl_departments');

                        if (empty($dep_head->department_head_id)) {
                            $head['department_head_id'] = $id;
                        }
                    }
                    if (!empty($desig->departments_id) && !empty($head)) {
                        $this->user_model->_table_name = "tbl_departments"; //table name
                        $this->user_model->_primary_key = "departments_id";
                        $this->user_model->save($head, $desig->departments_id);
                    }
                }

                $activities = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'user',
                    'module_field_id' => $id,
                    'activity' => 'activity_added_new_user',
                    'icon' => 'fa-user',
                    'value1' => $login_data['username']
                );
                $this->user_model->_table_name = 'tbl_activities';
                $this->user_model->_primary_key = "activities_id";
                $this->user_model->save($activities);
                if (!empty($id)) {
                    $this->user_model->_table_name = 'tbl_client_role'; //table name
                    $this->user_model->delete(array('user_id' => $id));
                    $all_client_menu = $this->db->get('tbl_client_menu')->result();
                    foreach ($all_client_menu as $v_client_menu) {
                        $client_role_data['menu_id'] = $this->input->post($v_client_menu->label, true);
                        if (!empty($client_role_data['menu_id'])) {
                            $client_role_data['user_id'] = $id;
                            $this->user_model->_table_name = 'tbl_client_role';
                            $this->user_model->_primary_key = 'client_role_id';
                            $this->user_model->save($client_role_data);
                        }
                    }
                }

                if (!empty($user_id)) {
                    $message = _l('update_user_info');
                } else {
                    $message = _l('save_user_info');
                }
                $type = 'success';
            }
            set_alert($type, $message);
        }
        redirect('saas/super_admin'); //redirect page
    }

    public function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

    /*     * * Delete User ** */
    public function delete_user($id = null)
    {
        $deleted = super_admin_access();
        $user_info = get_row('tbl_users', array('user_id' => $id));
        if (!empty($user_info)) {
            if (!empty($deleted)) {
                $this->user_model->_table_name = "tbl_users"; //table name
                $this->user_model->_primary_key = "user_id";
                $this->user_model->delete($id);

                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'items',
                    'module_field_id' => $id,
                    'activity' => 'activity_items_deleted',
                    'icon' => 'fa-circle-o',
                    'value1' => $user_info->username
                );
                $this->user_model->_table_name = 'tbl_activities';
                $this->user_model->_primary_key = 'activities_id';
                $this->user_model->save($activity);

                $type = 'success';
                $msg = _l('User has been successfully delete');
            } else {
                $type = 'error';
                $msg = _l('there_in_no_value');
            }
            echo json_encode(array("status" => $type, 'message' => $msg));
            exit();
        }


    }
}