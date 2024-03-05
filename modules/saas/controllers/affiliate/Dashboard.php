<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends App_Controller
{
    public $old_db;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        affiliate_access();
    }

    public function index()
    {
        $data['affiliate'] = true;
        $data['title'] = _l('dashboard');
        $id = get_affiliate_user_id();
        $data['affiliate_info'] = $this->saas_model->getAffiliateUser($id);
        $data['states'] = $this->saas_model->get_affiliate_states($data['affiliate_info']);
        $data['commission_histories'] = get_order_by('tbl_saas_affiliates', array('referral_by' => $id), 'affiliate_id', null, 5);
        $data['payout_histories'] = get_order_by('tbl_saas_affiliate_payouts', array('user_id' => $id), 'affiliate_payout_id', null, 5);
        $data['subview'] = $this->load->view('affiliates/user/dashboard', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }


    public function commissions()
    {
        $data['affiliate'] = true;
        $data['title'] = _l('affiliates') . ' ' . _l('users');
        $id = get_affiliate_user_id();
        $data['commission_histories'] = get_order_by('tbl_saas_affiliates', array('referral_by' => $id), 'affiliate_id', null);
        $data['subview'] = $this->load->view('affiliates/user/commissions', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public function payouts()
    {
        $data['affiliate'] = true;
        $data['title'] = _l('affiliates') . ' ' . _l('users');
        $id = get_affiliate_user_id();
        $data['affiliate_info'] = $this->saas_model->getAffiliateUser($id);
        $data['payouts'] = true;
        $post = $this->input->post();
        if (!empty($post)) {
            $remaining_balance = $data['affiliate_info']->total_balance - $data['affiliate_info']->withdrawal_amount;
            $minimum_payout_amount = get_option('minimum_payout_amount');
            // max will be remaining balance
            // min will be min($remaining_balance, $minimum_payout_amount)
            $this->load->library('form_validation');
            $this->form_validation->set_rules('amount', _l('amount'), 'required|numeric|greater_than_equal_to[' . $minimum_payout_amount . ']|less_than_equal_to[' . $remaining_balance . ']');
            $this->form_validation->set_rules('payment_method', _l('payment_method'), 'required');
            $this->form_validation->set_rules('notes', _l('notes'), 'required');
            if ($this->form_validation->run() == true) {
                $data = array(
                    'user_id' => $id,
                    'amount' => $post['amount'],
                    'payment_method' => $post['payment_method'],
                    'status' => 'pending',
                    'available_amount' => $remaining_balance,
                    'notes' => $post['notes'],
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $this->db->insert('tbl_saas_affiliate_payouts', $data);
                set_alert('success', _l('payout_request_submitted'));
                redirect(site_url('affiliate/payouts'));
            } else {
                $data['error_messages'] = $this->form_validation->error_array();
            }

        }
        $data['states'] = $this->get_payout_states($data['affiliate_info']);
        $data['payout_histories'] = get_order_by('tbl_saas_affiliate_payouts', array('user_id' => $id), 'affiliate_payout_id', null);
        $data['subview'] = $this->load->view('affiliates/user/payouts', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public function get_payout_states($affiliate_info)
    {
        $base_currency = get_base_currency();
        $remaining_balance = $affiliate_info->total_balance - $affiliate_info->withdrawal_amount;
        $states = [
            [
                'name' => 'minimum_payout',
                'icon' => 'uil uil-wallet fs-24',
                'color' => 'primary',
                'count' => app_format_money(get_option('minimum_payout_amount'), $base_currency)
            ],
            [
                'name' => 'total_earning',
                'icon' => 'uil uil-dollar-sign fs-24',
                'color' => 'success',
                'count' => app_format_money($affiliate_info->total_balance, $base_currency)
            ],
            [
                'name' => 'total_withdrawn',
                'icon' => 'uil uil-wallet fs-24',
                'color' => 'danger',
                'count' => app_format_money($affiliate_info->withdrawal_amount, $base_currency)
            ],
            [
                'name' => 'remaining_balance',
                'icon' => 'uil uil-wallet fs-24',
                'color' => 'warning',
                'count' => app_format_money($remaining_balance, $base_currency)
            ],
        ];
        return $states;
    }

    public function delete_payouts($id)
    {
        $this->db->where('affiliate_payout_id', $id);
        $this->db->delete('tbl_saas_affiliate_payouts');
        set_alert('success', _l('payout_deleted'));
        redirect(site_url('affiliate/payouts'));
    }

    public function referrals()
    {
        $data['affiliate'] = true;
        $data['title'] = _l('affiliates') . ' ' . _l('users');
        $id = get_affiliate_user_id();
        $data['referrals_histories'] = get_order_by('tbl_saas_affiliates', array('referral_by' => $id), 'affiliate_id', null);
        $data['subview'] = $this->load->view('affiliates/user/referrals', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public function settings()
    {
        $data['affiliate'] = true;
        $data['title'] = _l('affiliates') . ' ' . _l('settings');
        $id = get_affiliate_user_id();
        $data['user'] = $this->saas_model->getAffiliateUser($id);
        $referral_link = $this->input->post('referral_link', true);
        $first_name = $this->input->post('first_name', true);
        $new_email = $this->input->post('new_email', true);

        $old_password = $this->input->post('old_password', true);
        // check if referral link is already exists or not
        if ($this->input->post()) {
            $this->saas_model->_table_name = 'tbl_saas_affiliate_users'; //table name
            $this->saas_model->_primary_key = 'user_id'; // $id
            $this->load->library('form_validation');
        }
        if (!empty($referral_link)) {
            $referral_link = slug_it($referral_link);
            $this->old_db = config_db(true, true);
            $this->old_db->where('referral_link', $referral_link);
            $this->old_db->where('user_id !=', $id);
            $this->old_db->from('tbl_saas_affiliate_users');
            $count = $this->old_db->count_all_results();
            if ($count > 0) {
                set_alert('danger', _l('referral_link_already_exists'));
                redirect(site_url('affiliate/settings'));
            } else {
                $this->form_validation->set_rules('referral_link', _l('referral_link'), 'required');
                $this->form_validation->set_rules('referral_link', _l('referral_link'), 'required');
                // check is unique or not the referral_link
                $is_exist = $this->old_db->where('referral_link', $referral_link)
                    ->where('user_id !=', $id)
                    ->get('tbl_saas_affiliate_users')->row();
                $post['referral_link'] = $referral_link;
                if ($this->form_validation->run() && empty($is_exist)) {
                    $this->saas_model->save_old($post, $id);
                    set_alert('success', _l('referral_link_updated'));
                    if (!empty(is_client_logged_in()) && !empty($this->input->post())) {
                        redirect('clients/referrals');
                    }
                    if (!empty(is_staff_logged_in()) && !empty($this->input->post())) {
                        redirect('admin/referrals');
                    }
                    redirect(site_url('affiliate/settings'));
                } else {
                    $data['error_messages'] = $this->form_validation->error_array();
                    if (!empty(is_client_logged_in()) && !empty($this->input->post())) {
                        set_alert('success', $data['error_messages']);
                        redirect('clients/referrals');
                    }
                    if (!empty(is_staff_logged_in()) && !empty($this->input->post())) {
                        set_alert('success', $data['error_messages']);
                        redirect('admin/referrals');
                    }
                }
            }
            redirect($_SERVER['HTTP_REFERER']);
        } else if ($first_name) {
            $post = $this->saas_model->array_from_post(array('first_name', 'last_name', 'country', 'address', 'mobile'));
            $this->form_validation->set_rules('first_name', _l('first_name'), 'required');
            $this->form_validation->set_rules('last_name', _l('last_name'), 'required');
            if ($this->form_validation->run()) {
                $this->saas_model->save($post, $id);
                set_alert('success', _l('profile_updated'));
                redirect(site_url('affiliate/settings'));
            } else {
                $data['error_messages'] = $this->form_validation->error_array();
            }
        } else if ($new_email) {
            $current_password = $this->input->post('current_password', true);
            $this->form_validation->set_rules('current_password', _l('current_password'), 'required');
            $user_password = $data['user']->password;

            if (password_verify($current_password, $user_password)) {
                if ($this->form_validation->run()) {
                    $post['email'] = $new_email;
                    $this->saas_model->save($post, $id);
                    set_alert('success', _l('email_updated'));
                    redirect(site_url('affiliate/settings'));
                } else {
                    $data['error_messages'] = $this->form_validation->error_array();
                }
                $this->form_validation->set_rules('new_email', _l('email'), 'required|valid_email|is_unique[tbl_saas_affiliate_users.email]');
            } else {
                $data['error_messages'] = array('current_password' => _l('invalid_current_password'));
            }


        } else if ($old_password) {
            $new_password = $this->input->post('new_password', true);
            $confirm_password = $this->input->post('confirm_password', true);
            $this->form_validation->set_rules('old_password', _l('old_password'), 'required');
            $user_password = $data['user']->password;

            if (password_verify($old_password, $user_password)) {
                $this->form_validation->set_rules('new_password', _l('new_password'), 'required');
                $this->form_validation->set_rules('confirm_password', _l('confirm_password'), 'required|matches[new_password]');
                if ($this->form_validation->run()) {
                    $post['password'] = password_hash($new_password, PASSWORD_BCRYPT);
                    $this->saas_model->save($post, $id);
                    set_alert('success', _l('password_updated'));
                    redirect(site_url('affiliate/settings'));
                } else {
                    $data['error_messages'] = $this->form_validation->error_array();
                }
            } else {
                $data['error_messages'] = array('old_password' => _l('invalid_old_password'));
            }
        }

        echo "<pre>";
        print_r($data['user']);
        exit();
        $data['subview'] = $this->load->view('affiliates/user/settings', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public function logout()
    {
        $this->session->unset_userdata('affiliate_user_id');
        redirect(site_url('affiliate/login'));
    }


}