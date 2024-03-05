<?php defined('BASEPATH') or exit('No direct script access allowed');


class Affiliates extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        saas_access();
    }


    public function Users()
    {
        $data['title'] = _l('affiliates') . ' ' . _l('users');
        $data['subview'] = $this->load->view('affiliates/users', $data, true);
        $this->load->view('_layout_main', $data);
    }


    public function usersList($status = null)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_affiliate_users';
            $this->datatables->select = 'tbl_saas_affiliate_users.*,CONCAT(tbl_saas_affiliate_users.first_name, " ", tbl_saas_affiliate_users.last_name) as fullname,(SELECT COUNT(tbl_saas_companies.id) FROM tbl_saas_companies  WHERE tbl_saas_affiliate_users.user_id = tbl_saas_companies.referral_by) AS total_referral,' . 'COALESCE((SELECT SUM(tbl_saas_affiliate_payouts.amount) FROM tbl_saas_affiliate_payouts WHERE tbl_saas_affiliate_payouts.user_id = tbl_saas_affiliate_users.user_id AND tbl_saas_affiliate_payouts.status = "approved"), 0) AS withdrawal_amount';
            $column = array('first_name', 'last_name', 'total_referral', 'withdrawal_amount');
            $this->datatables->join_table = array('tbl_saas_affiliates', 'tbl_saas_affiliate_payouts', 'tbl_saas_companies');
            $this->datatables->join_where = array('tbl_saas_affiliates.referral_by=tbl_saas_affiliate_users.user_id', 'tbl_saas_affiliate_payouts.user_id=tbl_saas_affiliate_users.user_id', 'tbl_saas_affiliate_users.user_id=tbl_saas_companies.referral_by');
            $this->datatables->column_order = $column;
            $this->datatables->column_search = $column;
            $this->datatables->groupBy = 'tbl_saas_affiliate_users.user_id';
            $this->datatables->order = array('tbl_saas_affiliate_users.user_id' => 'desc');
            $where = array();
            $fetch_data = make_datatables($where);

            $data = array();;
            foreach ($fetch_data as $key => $row) {
                $total = sum_from_table('tbl_saas_affiliates', ['field' => 'get_amount', 'where' => ['referral_by' => $row->user_id]]);
                if ($total == null) {
                    $total = 0;
                }
                $row->total_balance = $total;
                $action = null;
                $sub_array = array();
                $name = '<a href="' . base_url('saas/affiliates/userDetails/' . $row->user_id) . '">' . $row->first_name . ' ' . $row->last_name . '</a>';
                $name .= '<div class="row-options">';
                $name .= '<a
                data-toggle="tooltip" data-placement="top" 
                href="' . base_url('saas/affiliates/userDetails/' . $row->user_id) . '"  title="' . _l('details') . '">' . _l('details') . '</a>';
                $status = $row->isAffiliate == 1 ? 'stop' : 'start';
                $reverse_status = $row->isAffiliate == 1 ? 0 : 1;
                $name .= '| <a href="' . base_url('saas/affiliates/stop_affiliate/' . $row->user_id . '/' . $reverse_status) . '"
                class="text-danger _delete"
                data-toggle="tooltip" data-placement="top" title="' . _l('stop_affiliate') . '">' . _l($status) . '</a>';

                $sub_array[] = $name;
                $sub_array[] = $row->email;
                $sub_array[] = $row->total_referral;
                $sub_array[] = display_money($row->total_balance);
                $sub_array[] = display_money($row->withdrawal_amount);
                $sub_array[] = display_money($row->total_balance - $row->withdrawal_amount);
                $sub_array[] = $row->isAffiliate == 1 ? '<span class="label label-success">' . _l('active') . '</span>' : '<span class="label label-danger">' . _l('inactive') . '</span>';
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('saas/dashboard');
        }
    }


    public function userDetails($id)
    {
        $data['title'] = _l('affiliates') . ' ' . _l('users');
        $data['user'] = $this->saas_model->getAffiliateUser($id);
        $data['states'] = $this->getUserStates($data['user']);
        $data['subview'] = $this->load->view('affiliates/userDetails', $data, true);
        $this->load->view('_layout_main', $data);
    }

    public function getUserStates($user)
    {
        $base_currency = get_base_currency();
        $states = [
            [
                'name' => 'total_referred',
                'icon' => 'fa fa-building',
                'color' => 'info',
                'count' => $user->total_referral ?? 0
            ],
            [
                'name' => 'total_earning',
                'icon' => 'fa fa-building',
                'color' => 'success',
                'count' => app_format_money($user->total_balance, $base_currency)
            ],
            [
                'name' => 'total_withdrawn',
                'icon' => 'fa fa-building',
                'color' => 'danger',
                'count' => app_format_money($user->withdrawal_amount, $base_currency)
            ],
            [
                'name' => 'remaining_balance',
                'icon' => 'fa fa-building',
                'color' => 'warning',
                'count' => app_format_money($user->total_balance - $user->withdrawal_amount, $base_currency)
            ],
        ];
        return $states;
    }

    public function commissionHistoryList($id = null)
    {
        $base_currency = get_base_currency();
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_affiliates';
            $column = array('referral_by', 'referral_to', 'amount_was', 'get_amount', 'transaction_id');
            $this->datatables->column_order = $column;
            $this->datatables->column_search = $column;
            $this->datatables->order = array('tbl_saas_affiliates.affiliate_id' => 'desc');
            $where = array('referral_by' => $id);
            $fetch_data = make_datatables($where);

            $data = array();
            foreach ($fetch_data as $key => $row) {
                $action = null;
                $sub_array = array();
                $sub_array[] = display_money($row->amount_was);
                $sub_array[] = display_money($row->get_amount);
                $sub_array[] = $row->commission_type === "percentage" ? round($row->commission_value, 2) . '%' : app_format_money($row->commission_value, $base_currency);
                $sub_array[] = _d($row->date);
                $data[] = $sub_array;
            }
            render_table($data, $where);
        } else {
            redirect('saas/dashboard');
        }
    }

    public function referralCompanyList($id)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_companies';
            $column = array('name', 'email');
            $this->datatables->column_order = $column;
            $this->datatables->column_search = $column;
            $this->datatables->order = array('id' => 'desc');
            $where = array('referral_by' => $id);
            $fetch_data = make_datatables($where);

            $data = array();
            foreach ($fetch_data as $row) {
                $sub_array = array();
                $sub_array[] = $row->name;
                $sub_array[] = $row->email;
                $sub_array[] = _dt($row->created_date);
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('saas/dashboard');
        }
    }

    public function payoutHistoryList($id)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_affiliate_payouts';
            $column = array('amount', 'notes', 'status', 'created_at');
            $this->datatables->column_order = $column;
            $this->datatables->column_search = $column;
            $this->datatables->order = array('affiliate_payout_id' => 'desc');
            $where = array('user_id' => $id);
            $fetch_data = make_datatables($where);

            $data = array();
            foreach ($fetch_data as $key => $row) {
                $action = null;
                $sub_array = array();
                $sub_array[] = app_format_money($row->amount, get_base_currency());
                $sub_array[] = '<span class="badge bg-' . ($row->status === "pending" ? "warning" : ($row->status === "approved" ? "success" : "danger")) . '">' . $row->status . '</span>';
                $sub_array[] = _dt($row->created_at);
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('saas/dashboard');
        }
    }

    public function stop_affiliate($id, $status = 0)
    {
        $this->db->where('user_id', $id);
        $this->db->update('tbl_saas_affiliate_users', array('isAffiliate' => $status));
        set_alert('success', $status == 0 ? _l('affiliate_stopped') : _l('affiliate_started'));
        redirect('saas/affiliates/users');
    }

    public function Payouts()
    {
        $data['title'] = _l('payouts');
        $data['subview'] = $this->load->view('affiliates/payouts', $data, true);
        $this->load->view('_layout_main', $data);
    }


    public function payoutsList($status = null)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saas_affiliate_payouts';
            $this->datatables->select = 'tbl_saas_affiliate_payouts.*,CONCAT(tbl_saas_affiliate_users.first_name, " ", tbl_saas_affiliate_users.last_name) as fullname';
            $column = array('tbl_saas_affiliate_users.first_name', 'tbl_saas_affiliate_users.last_name', 'total_referral', 'withdrawal_amount');
            $this->datatables->join_table = array('tbl_saas_affiliate_users');
            $this->datatables->join_where = array('tbl_saas_affiliate_payouts.user_id=tbl_saas_affiliate_users.user_id');
            $this->datatables->column_order = $column;
            $this->datatables->column_search = $column;
            $this->datatables->order = array('affiliate_payout_id' => 'desc');
            $where = array();
            $fetch_data = make_datatables($where);

            $data = array();

            $access = super_admin_access();
            foreach ($fetch_data as $key => $row) {
                $action = null;
                $sub_array = array();
                $name = '<a href="' . base_url('saas/affiliates/userDetails/' . $row->user_id) . '">' . $row->fullname . '</a>';
                $name .= '<div class="row-options">';
                // if status is pending then show approved and rejected
                // if status is approved then show pending and rejected
                // if status is rejected then show pending and approved
                if ($row->status === "pending") {
                    $name .= '<a href="' . base_url('saas/affiliates/change_payout/approved/' . $row->affiliate_payout_id) . '" class="text-success">' . _l('approve') . '</a>';
                    $name .= ' | <a href="' . base_url('saas/affiliates/change_payout/rejected/' . $row->affiliate_payout_id) . '" class="text-danger">' . _l('reject') . '</a>';
                } else if ($row->status === "approved") {
                    $name .= '<a href="' . base_url('saas/affiliates/change_payout/pending/' . $row->affiliate_payout_id) . '" class="text-warning">' . _l('pending') . '</a>';
                    $name .= ' | <a href="' . base_url('saas/affiliates/change_payout/rejected/' . $row->affiliate_payout_id) . '" class="text-danger">' . _l('reject') . '</a>';
                } else if ($row->status === "rejected") {
                    $name .= '<a href="' . base_url('saas/affiliates/change_payout/pending/' . $row->affiliate_payout_id) . '" class="text-warning">' . _l('pending') . '</a>';
                    $name .= ' | <a href="' . base_url('saas/affiliates/change_payout/approved/' . $row->affiliate_payout_id) . '" class="text-success">' . _l('approve') . '</a>';
                }
                $name .= ' | <a href="' . base_url('saas/affiliates/delete_payout/' . $row->affiliate_payout_id) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $name .= '</div>';

                $sub_array[] = $name;
                $sub_array[] = display_money($row->available_amount);
                $sub_array[] = $row->amount;
                $sub_array[] = _dt($row->created_at);
                $status = '<span class="badge bg-' . ($row->status === "pending" ? "warning" : ($row->status === "approved" ? "success" : "danger")) . '">' . _l($row->status) . '</span>';
                $sub_array[] = $status;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('saas/dashboard');
        }
    }

    public function change_payout($status, $id)
    {
        $this->db->where('affiliate_payout_id', $id);
        $this->db->update('tbl_saas_affiliate_payouts', array('status' => $status));
        log_activity('Affiliate Payout Status Changed [ID: ' . $id . ']');
        set_alert('success', _l('payout_status_changed'));
        redirect('saas/affiliates/payouts');
    }

    public function delete_payout($id)
    {
        $this->db->where('affiliate_payout_id', $id);
        $this->db->delete('tbl_saas_affiliate_payouts');
        log_activity('Affiliate Payout Deleted [ID: ' . $id . ']');
        set_alert('success', _l('deleted', _l('payout')));
        redirect('saas/affiliates/payouts');
    }

    public function settings()
    {
        if ($this->input->post()) {
            $post = $this->input->post();
            foreach ($post as $key => $value) {
                if (!is_array($value) && strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (!is_array($value) && strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                // check is array and convert to serialize
                if (is_array($value)) {
                    $value = serialize($value);
                }
                update_option($key, $value);
            }
            set_alert('success', _l('updated_successfully', _l('affiliate_settings')));
            redirect('saas/affiliates/settings');
        }

        $data['title'] = _l('affiliate_settings');
        $data['payment_modes'] = $this->saas_model->get_payment_modes();
        $data['subview'] = $this->load->view('affiliates/settings', $data, true);
        $this->load->view('_layout_main', $data);
    }


}

