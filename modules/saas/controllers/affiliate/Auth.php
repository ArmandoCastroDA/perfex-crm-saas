<?php defined('BASEPATH') or exit('No direct script access allowed');


class Auth extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        if (!empty(is_affiliate_logged_in())) {
            redirect(site_url('affiliate/dashboard'));
        }
    }


    public function index()
    {
        $data['title'] = _l('affiliates') . ' ' . _l('users');
        $data['subview'] = $this->load->view('affiliates/users', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function login()
    {
        $data['affiliate'] = true;
        $data['title'] = _l('affiliate_program');
        $post = $this->input->post();
        if ($post) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', _l('email'), 'trim|required|valid_email');
            $this->form_validation->set_rules('password', _l('password'), 'required');
            if ($this->form_validation->run() == false) {
                set_alert('danger', _l('please_enter_email_and_password'));
            } else {
                $user = get_row('tbl_saas_affiliate_users', ['email' => $post['email']]);
                if ($user) {
                    if (password_verify($post['password'], $user->password)) {
                        if ($user->activated == 0) {
                            set_alert('danger', _l('your_account_is_not_active'));
                        } else if ($user->is_verified == 0) {
                            set_alert('danger', _l('your_account_is_not_verified'));
                        } else {
                            $this->session->set_userdata('affiliate_user_id', $user->user_id);
                            redirect(site_url('affiliate/dashboard'));
                        }
                    } else {
                        set_alert('danger', _l('invalid_credentials'));
                    }
                } else {
                    set_alert('danger', _l('invalid_credentials'));
                }
            }
        }
        $data['subview'] = $this->load->view('affiliates/user/login', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public function become()
    {
        $data['affiliate'] = true;
        $data['title'] = _l('affiliate_program');
        $post = $this->input->post();
        if ($post) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('first_name', _l('first_name'), 'required');
            $this->form_validation->set_rules('last_name', _l('last_name'), 'required');
            $this->form_validation->set_rules('email', _l('email'), 'trim|required|is_unique[tbl_saas_affiliate_users.email]|valid_email');
            $this->form_validation->set_rules('password', _l('password'), 'required');
            $this->form_validation->set_rules('passwordr', _l('confirm_password'), 'required|matches[password]');
            $this->form_validation->set_rules('terms', _l('terms_and_conditions'), 'required');

            if ($this->form_validation->run() !== false) {
                $data = array();
                $referral_link = slug_it($post['first_name'] . '-' . $post['last_name'] . '-' . uniqid());
                $data['first_name'] = $post['first_name'];
                $data['last_name'] = $post['last_name'];
                $data['email'] = $post['email'];
                $data['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
                $data['activated'] = 1;
                $data['banned'] = 0;
                $data['is_verified'] = 0;
                $data['isAffiliate'] = 1;
                $data['referral_link'] = $referral_link;
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->saas_model->_table_name = 'tbl_saas_affiliate_users';
                $this->saas_model->_primary_key = 'user_id';
                $user_id = $this->saas_model->save($data);

                if (!empty($user_id)) {
                    // Send email to user
                    $company_info = get_row('tbl_saas_affiliate_users', array('user_id' => $user_id));
                    try {
                        $send = send_mail_template('affiliate_mail_verification', SaaS_MODULE, $company_info->email, $company_info->user_id, $company_info);
                    } catch (Exception $e) {
                        echo 'Message: ' . $e->getMessage();
                    }
                    set_alert('success', _l('affiliate_register_success'));
                    redirect(site_url('affiliate/login'));
                } else {
                    set_alert('warning', _l('affiliate_register_failed'));
                }
                redirect(site_url('affiliate'));
            }
        }
        $data['subview'] = $this->load->view('affiliates/user/register', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public function verify($user_id)
    {
        $user_id = url_decode($user_id);
        $user = get_row('tbl_saas_affiliate_users', ['user_id' => $user_id]);
        if ($user) {
            $this->saas_model->_table_name = 'tbl_saas_affiliate_users';
            $this->saas_model->_primary_key = 'user_id';
            $this->saas_model->save(['is_verified' => 1], $user_id);
            set_alert('success', _l('affiliate_account_verified'));
            redirect(site_url('affiliate/login'));
        } else {
            set_alert('danger', _l('affiliate_account_not_found'));
            redirect(site_url('affiliate/login'));
        }
    }



}

