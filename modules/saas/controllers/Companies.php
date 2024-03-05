<?php defined('BASEPATH') or exit('No direct script access allowed');


class Companies extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        saas_access();
    }


    public function index()
    {
        $data['title'] = _l('companies');
        $data['active'] = 1;
        $data['subview'] = $this->load->view('companies/manage', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function companiesList($status = null)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_companies';
            $this->datatables->select = db_prefix() . 'clients.userid as client_id,tbl_saas_companies_history.package_name,tbl_saas_companies_history.id as company_history_id,tbl_saas_companies.*';
            $this->datatables->join_table = array(db_prefix() . 'clients', 'tbl_saas_companies_history');
            $this->datatables->join_where = array(db_prefix() . 'clients.saas_company_id = tbl_saas_companies.id', 'tbl_saas_companies_history.companies_id=tbl_saas_companies.id AND tbl_saas_companies_history.active=1');
            $column = array('name', 'email', 'package_name', 'domain', 'trail_period', 'status', 'monthly_price', 'yearly_price', 'quaterly_price');
            $this->datatables->column_order = $column;
            $this->datatables->column_search = $column;
            $this->datatables->order = array('id' => 'desc');
//            $this->datatables->groupBy = array('tbl_saas_companies.id');

            $where = array('for_seed' => NULL);
            if (!empty($status)) {
                $where += array('tbl_saas_companies.status' => $status);
            }
            $fetch_data = make_datatables($where);

            $data = array();

            $access = super_admin_access();
            foreach ($fetch_data as $key => $row) {
                $action = null;
                $sub_array = array();
                $name = '<a href="' . base_url('saas/companies/details/' . $row->id) . '">' . $row->name . '</a>';
                $name .= '<div class="row-options">';
                if (!empty($access)) {
                    $name .= '<a
                    data-toggle="tooltip" data-placement="top"
                     href="' . base_url('saas/companies/create/' . $row->id) . '"  title="' . _l('edit') . '">' . _l('edit') . '</a>';
                }
                $name .= '| <a
                data-toggle="tooltip" data-placement="top" 
                href="' . base_url('saas/companies/details/' . $row->id) . '"  title="' . _l('details') . '">' . _l('details') . '</a>';
                // send welcome email
                $name .= '| <a href="' . base_url('saas/companies/send_welcome_email/' . $row->id) . '"
                data-toggle="tooltip" data-placement="top"
                  title="' . _l('send_welcome_mail') . '">' . _l('mail') . '</a>';
                if (!empty($access)) {
                    $name .= '| <a 
                    data-toggle="tooltip" data-placement="top"
                    href="' . base_url('saas/companies/delete_companies/' . $row->id) . '"  title="' . _l('delete') . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }


                $sub_array[] = $name;
                $sub_array[] = $row->email;
                $sub_array[] = $row->domain;
                $sub_array[] = '<a href="' . base_url() . 'saas/gb/package_details/' . $row->company_history_id . '/1" title="' . _l('details') . '" data-toggle="modal" data-target="#myModal">' . $row->package_name . '</a>  ' . $row->amount . '/' . $row->frequency;
                $sub_array[] = $row->trial_period . ' ' . _l('days');
                $status = $row->status;

                if ($status == 'pending') {
                    $label = 'primary';
                } else if ($status == 'running') {
                    $label = 'success';
                } else if ($status == 'expired') {
                    $label = 'warning';
                } else {
                    $label = 'danger';
                }
                if ($status != 'pending') {
                    if ($row->trial_period != 0) {
                        $till_date = trial_period($row);
                    } else {
                        $till_date = running_period($row);
                    }
                    $validity_date = date("Y-m-d", strtotime($till_date . "day"));
                    if ($validity_date < date('Y-m-d') && $status == 'running') {
                        $status = 'expired';
                        $label = 'warning';
                    }
                }
                $sub_array[] = '<span class="label label-' . $label . '">' . _l($status) . '</span>';

                // customize package
                // flex space between items
                $action = '<div class="tw-flex tw-justify-between">';
                $action .= '<a href="' . saas_url('packages/customize/' . $row->id) . '" class="btn btn-primary btn-sm tw-mr-1"> <i class="fa fa-cog"></i> ' . _l('customize') . '</a> ';
                if (!empty($row->client_id) && !empty($row->package_name)) {
                    if (!empty($row->status == 'running')) {
                        $action .= ' ' . ' <a href="' . admin_url('clients/login_as_client/' . $row->client_id) . '" class="btn btn-primary btn-sm tw-mr-1"> <i class="fa fa-sign-in"></i> ' . _l('login_as_client') . '</a>';
                        $action .= ' ' . ' <button                     
                    data-company-id="' . $row->id . '"                   
                    class="btn btn-primary btn-sm view-company"> <i class="fa fa-sign-in"></i> ' . _l('login_as_admin') . '</button>';
                    }
                }
                $action .= '</div>';
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('saas/dashboard');
        }
    }

    public function login_as_companies($id)
    {
        $company_info = get_row('tbl_saas_companies', array('id' => $id));
        if (!empty($company_info)) {
            $config = &get_config();
            $config_db = $this->config->config['config_db'];
            $domain = $company_info->domain;
            $dbName = $company_info->db_name;
            $url = all_company_url($domain)['url'];

            $config_db['database'] = $company_info->db_name;
            $config['csrf_token_name'] = $domain . '_csrf_token_name';
            $config['csrf_cookie_name'] = $domain . '_csrf_cookie_name';
            $config['cookie_prefix'] = $domain;
            $config['sess_cookie_name'] = $domain . '_sp_session';
            $config['base_url'] = $url;
            $config['company_db_name'] = $dbName;
            // switch to new database with csrf token, cookie name, cookie prefix, session name
            $this->load->helper('cookie');
            $this->db = $this->load->database($config_db, true);

            $user = $this->db->get_where(db_prefix() . 'staff', array('email' => $company_info->email))->row();
            $user_id = $user->staffid;
            $path = $domain . '/s';
            // @Ref: models/Authentication_model.php
            $staff = true;
            $key = substr(md5(uniqid(rand() . get_cookie($this->config->item('sess_cookie_name')))), 0, 16);
            $this->user_autologin->delete($user_id, $key, $staff);
            if ($this->user_autologin->set($user_id, md5($key), $staff)) {
                set_cookie([
                    'name' => 'autologin',
                    'value' => serialize([
                        'user_id' => $user_id,
                        'key' => $key,
                    ]),
                    'expire' => 5000, // 5secs
                    'path' => '/' . $path . '/',
                    'httponly' => true,
                ]);
            }
            redirect($url . 'admin');

        }
    }


    public function create($id = null)
    {
        $data['title'] = _l('create_companies');
        if (!empty($id)) {
            $data['company_info'] = get_row('tbl_saas_companies', array('id' => $id));
            if (empty($data['company_info'])) {
                redirect('saas/companies');
            }
        }
        $data['all_packages'] = $this->saas_model->get_packages();
        $data['active'] = 2;
        $data['subview'] = $this->load->view('companies/create', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function reset_password($id)
    {

        $new_password = $this->input->post('password', true);
        $confirm_password = $this->input->post('confirm_password', true);
        if (!empty($new_password)) {
            if ($confirm_password !== $new_password) {
                $type = "error";
                $message = _l('password_does_not_match');
                set_alert($type, $message);
                redirect('saas/companies/details/' . $id); //redirect page
            } else {
                $where = array('id' => $id);
                $action = array('password' => $this->saas_model->hash($new_password));
                $this->db->update('tbl_saas_companies', $action, $where);

                $company_info = get_row('tbl_saas_companies', array('id' => $id));

                // update password in db_prefix() . 'staff' table
                $this->new_db = config_db($company_info->db_name);
                $where = array('email' => $company_info->email);
                $action = array('password' => $this->saas_model->hash($new_password));
                $this->new_db->update(db_prefix() . 'staff', $action, $where);


                $type = "success";
                $message = _l('password_updated');

                set_alert($type, $message);
                redirect('saas/companies/details/' . $id); //redirect page
            }
        } else {
            $data['title'] = _l('see_password');
            $data['company_info'] = get_row('tbl_saas_companies', array('id' => $id));
            $data['subview'] = $this->load->view('companies/reset_password', $data, FALSE);
            $this->load->view('saas/_layout_modal', $data);
        }

    }

    public function save_companies($id = null)
    {
        $data = $this->saas_model->array_from_post(array('name', 'email', 'package_id', 'domain', 'timezone', 'language', 'mobile', 'address', 'country'));
        $data['domain'] = domainUrl(slug_it($data['domain']));
        $data['password'] = $this->input->post('password', true);

        $data['created_date'] = date('Y-m-d H:i:s');
        $data['created_by'] = get_staff_user_id();
        $data['status'] = 'pending';
        $this->load->library('uuid');
        $data['activation_code'] = $this->uuid->v4();
        $check_email = get_row('tbl_saas_companies', array('email' => $data['email']));

        $package_info = get_row('tbl_saas_packages', array('id' => $data['package_id']));
        if (empty($package_info) || empty($data['package_id'])) {
            $type = 'warning';
            $msg = _l('package_field_is_required');
            set_alert($type, $msg);
            redirect('saas/companies/create/');
        }

        if (!empty($id)) {
            unset($data['email']);
            unset($data['status']);
            unset($data['activation_code']);
            $company_info = get_row('tbl_saas_companies', array('id' => $id));
            $data['updated_date'] = date('Y-m-d H:i:s');
            $data['updated_by'] = get_staff_user_id();
            $check_email = '';
        }

        // check email already exist
        $check_domain = get_row('tbl_saas_companies', array('domain' => $data['domain']));
        $reserved = check_reserved_tenant($data['domain']);
        if (!empty($check_email) && $check_email->id != $id) {
            $type = 'error';
            $msg = _l('already_exists', lang('email'));
        } else if (!empty($check_domain) && $check_domain->id != $id) {
            $type = 'error';
            $msg = _l('already_exists', lang('domain'));
        } else if (!empty($reserved)) {
            $type = 'error';
            $msg = _l('already_exists', lang('domain'));
        } else {

            $billing_cycle = $this->input->post('billing_cycle', true);
            $mark_paid = $this->input->post('mark_paid', true);
            // deduct $billing_cycle from price
            $data['frequency'] = str_replace('_price', '', $billing_cycle);;
            $data['trial_period'] = $package_info->trial_period;
            $data['is_trial'] = 'Yes';
            $data['expired_date'] = $this->input->post('expired_date', true);;
            $data['currency'] = config_item('default_currency');
            $data['amount'] = $package_info->$billing_cycle;

            if (!empty($mark_paid)) {
                $data['status'] = 'running';
                $data['is_trial'] = 'No';
            }

            $this->saas_model->_table_name = 'tbl_saas_companies';
            $this->saas_model->_primary_key = 'id';
            $id = $this->saas_model->save($data, $id);

            $this->saas_model->save_client($id, $data['password']);

            if (!empty($company_info) && $company_info->package_id != $data['package_id'] || empty($company_info)) {
                // save data into tbl_saas_companies_history
                // change active status to 0 for all previous data of this company

                $this->saas_model->_table_name = 'tbl_saas_companies_history';
                $this->saas_model->_primary_key = 'companies_id';
                $this->saas_model->save(array('active' => 0), $id);

                $data['companies_id'] = $id;
                $data['ip'] = $this->input->ip_address();


                $companies_history_id = $this->saas_model->update_company_history($data);


                // create database for this company
                if ($data['status'] == 'running') {
                    // create database for the company
                    $this->saas_model->create_database($id);
                }

                if (!empty($mark_paid)) {
                    $discount_percentage = 0;
                    $discount_amount = 0;
                    $coupon_code = '';
                    $is_coupon_applied = $this->input->post('is_coupon', true);
                    if (!empty($is_coupon_applied)) {
                        $coupon_code = $this->input->post('coupon_code', true);
                        $where = array('code' => $coupon_code, 'status' => 'active');
                        $coupon_info = get_old_data('tbl_saas_coupon', $where);

                        if (!empty($coupon_info)) {
                            $user_id = get_staff_user_id();
                            if (!empty($user_id)) {
                                $where = array('user_id' => $user_id, 'coupon' => $coupon_code);
                            } else {
                                $where = array('email' => $data['email'], 'coupon' => $coupon_code);
                            }
                            $already_apply = get_old_data('tbl_saas_applied_coupon', $where);
                            if (empty($already_apply)) {
                                $sub_total = $package_info->$billing_cycle;
                                $percentage = $coupon_info->amount;
                                if ($coupon_info->type == 1) {
                                    $discount_amount = ($percentage / 100) * $sub_total;
                                    $discount_percentage = $percentage . '%';
                                } else {
                                    $discount_amount = $percentage;
                                    $discount_percentage = $percentage;
                                }

                                $coupon_data['discount_amount'] = $discount_amount;
                                $coupon_data['discount_percentage'] = $discount_percentage;
                                $coupon_data['coupon'] = $coupon_code;
                                $coupon_data['coupon_id'] = $coupon_info->id;
                                $coupon_data['user_id'] = $user_id;
                                $coupon_data['email'] = $data['email'];
                                $coupon_data['applied_date'] = date('Y-m-d H:i:s');

                                // save into tbl_saas_applied_coupon
                                $this->saas_model->_table_name = 'tbl_saas_applied_coupon';
                                $this->saas_model->_primary_key = 'id';
                                $applied_coupon_id = $this->saas_model->save($coupon_data);
                            }
                        }
                    }

                    // save payment info
                    $payment_date = $this->input->post('payment_date', true);
                    $pdata = array(
                        'reference_no' => $this->input->post('reference_no', true),
                        'companies_history_id' => $companies_history_id,
                        'companies_id' => $id,
                        'transaction_id' => 'TRN' . date('Ymd') . date('His') . '_' . substr(number_format(time() * rand(), 0, '', ''), 0, 6),
                        'payment_method' => (!empty($pdata['payment_method'])) ? $pdata['payment_method'] : 'manual',
                        'currency' => $data['currency'],
                        'subtotal' => $data['amount'],
                        'discount_percent' => $discount_percentage,
                        'discount_amount' => $discount_amount,
                        'coupon_code' => $coupon_code,
                        'total_amount' => $data['amount'] - $discount_amount,
                        'payment_date' => (!empty($payment_date) ? $payment_date : date("Y-m-d H:i:s")),
                        'created_at' => date("Y-m-d H:i:s"),
                        'ip' => $this->input->ip_address(),
                    );

                    $this->saas_model->_table_name = 'tbl_saas_companies_payment';
                    $this->saas_model->_primary_key = 'id';
                    $this->saas_model->save($pdata);
                }
            }
            if (!empty($id)) {
                $msg = _l('update_company');
                $activity = 'activity_update_company';
            } else {
                $msg = _l('save_company');
                $activity = 'activity_save_company';
            }

            log_activity($activity . ' - ' . $data['name'] . ' [ID:' . $id . ']');
            $this->saas_model->send_welcome_email($id);

            $type = "success";
        }
        $message = $msg;
        set_alert($type, $message);
        redirect('saas/companies');
    }

    public
    function details($id = null)
    {
        $data['title'] = _l('details_companies');
        $data['company_info'] = $this->saas_model->select_data('tbl_saas_companies', 'tbl_saas_companies.*,tbl_saas_companies_history.package_name,tbl_saas_companies_history.modules,tbl_saas_companies_history.disabled_modules,tbl_saas_companies_history.id as company_history_id', NULL, array('tbl_saas_companies.id' => $id, 'tbl_saas_companies.for_seed' => NULL, 'tbl_saas_companies_history.active' => 1), ['tbl_saas_companies_history' => 'tbl_saas_companies.id = tbl_saas_companies_history.companies_id'], 'row');

        if (empty($data['company_info'])) {
            redirect('saas/companies');
        }
        $data['subview'] = $this->load->view('companies/details', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function reset_db($id, $fresh_db = null)
    {
        $result = $this->saas_model->create_database($id, $fresh_db);
        if ($result['result'] == 'success') {
            $type = "success";
            $message = _l('reset_db_success');

            $company_info = $this->saas_model->company_info($id);
            $users = [];
            add_notification([
                'description' => 'callbacks_reset_db',
                'touserid' => 1,
                'link' => '#'
            ]);
            $users[] = 1;
            pusher_trigger_notification(array_unique($users));
            send_mail_template('Saas_company_database_reset', SaaS_MODULE, $company_info->email, $company_info->id, $company_info);

        } else {
            $type = "error";
            $message = _l('reset_db_failed') . ' ' . $result['error'];
        }
        set_alert($type, $message);
        redirect('saas/companies/details/' . $id);
    }

    public
    function invoices()
    {
        $data['title'] = _l('payment') . ' ' . _l('histories');
        $data['active'] = 1;
        $data['subview'] = $this->load->view('companies/invoices', $data, true);
        $this->load->view('_layout_main', $data);
    }


    public
    function delete_companies($id = null)
    {
        // load clients_model
        $this->saas_model->delete_company($id);
        // messages for user
        $type = "success";
        $message = _l('companies_deleted');
        set_alert($type, $message);
        redirect('admin/saas/companies');
    }


    public
    function pricing($id = null)
    {
        $data['title'] = _l('pricing');
        $data['active'] = 1;
        $data['subview'] = $this->load->view('companies/pricing', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function send_welcome_email($id)
    {
        $this->saas_model->send_welcome_email($id);
        $type = "success";
        $message = _l('welcome_email_sent');
        set_alert($type, $message);
        redirect('saas/companies');
    }

    public function update_modules($company_id)
    {
        $companyInfo = $this->saas_model->company_info($company_id, true);

        $company_history_id = $companyInfo->company_history_id;
        $company_history = get_row('tbl_saas_companies_history', array('id' => $company_history_id));
        // convert company history object to array
        $post = (array)$company_history;
        $post['package_id'] = $companyInfo->package_id;
        $delete_modules = $this->input->post('delete_module', true);
        $add_module = $this->input->post('add_module', true);

        if (empty($delete_modules) && empty($add_module)) {
            set_alert('warning', _l('please_select_at_least_one_module'));
        }

        $current_modules = (!empty($companyInfo->modules)) ? unserialize($companyInfo->modules) : [];
        $update = false;
        if (!empty($delete_modules) && count($delete_modules) > 0) {
            $modules = array_diff($current_modules, $delete_modules);
            $post['modules'] = serialize($modules);
            $post['delete_modules'] = $delete_modules;
            $update = true;
        }
        if (!empty($add_module) && count($add_module) > 0) {
            $modules = array_merge($current_modules, $add_module);
            $post['modules'] = serialize($modules);
            $post['add_modules'] = $add_module;
            $update = true;
        }


        if (!empty($update)) {
            $this->saas_model->update_company_history($post, $company_history_id);
        }

        redirect('saas/companies/details/' . $company_id);
    }

    public function dismiss_server_settings_notice()
    {
        update_option('done_server_settings', 1);
        redirect($_SERVER['HTTP_REFERER']);
    }


}

