<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gb extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');

    }

    public function signup_company()
    {
        $data['title'] = 'Signup Company';
        $data['package_id'] = $this->input->post('package_id', true);
        $data['package'] = get_old_result('tbl_saas_packages', array('id' => $data['package_id']), false);
        $data['all_packages'] = $this->saas_model->get_packages();

        $data['countries'] = get_all_countries();
        $_data['subview'] = $this->load->view('saas/frontcms/frontend/signup_company', $data, true);
        echo json_encode($_data);
        exit();
    }

    public function package_details($id, $status = null)
    {

        $data['title'] = 'Dashboard';
        if (!empty($status)) {
            $data['package_info'] = get_old_result('tbl_saas_companies_history', array('id' => $id), false);
            $data['package_info']->name = $data['package_info']->package_name;
        } else {
            $data['package_info'] = get_old_result('tbl_saas_packages', array('id' => $id), false);
        }
        $data['modal_subview'] = $this->load->view('packages/package_details', $data, false);
        $this->load->view('saas/_layout_modal', $data);
    }

    public function get_package_info()
    {
        // check is ajax request
        if (!$this->input->is_ajax_request()) {
            if (is_admin()) {
                redirect('admin/dashboard');
            } else {
                redirect('/register');
            }
        }

        $data['title'] = 'Dashboard';
        $package_id = $this->input->post('package_id') ?? 2;
        $front = $this->input->post('front');
        $company_id = $this->input->post('company_id', true);
        $package_type = $this->input->post('package_type');
        $package_type = (!empty($package_type)) ? $package_type : 'monthly_price';
        // cut _price from package_type
        $type = str_replace('_price', '', $package_type);
        $data['type_title'] = _l($type);
        if ($type == 'lifetime') {
            $data['renew_date'] = date('Y-m-d', strtotime('+100 year'));
        } elseif ($type == 'yearly') {
            $data['renew_date'] = date('Y-m-d', strtotime('+1 year'));
        } else {
            $data['renew_date'] = date('Y-m-d', strtotime('+1 month'));
        }

        $data['type'] = (!empty($package_type)) ? $package_type : 'monthly_price';
        $data['package_info'] = get_old_result('tbl_saas_packages', array('id' => $package_id), false);
        $data['package_info'] = apply_coupon($data['package_info']);
        $data['options'] = get_active_frequency(true);
        $data['company_id'] = $company_id;
        $data['front'] = $front;
        $data['other'] = str_replace('_price', '_offer', $data['type']);

        $_data['package_form_group'] = $this->load->view('saas/packages/package_billing', $data, true);
        $_data['package_details'] = $this->load->view('saas/packages/plain_package_details', $data, true);
        $_data['package_info'] = $data['package_info'];
        echo json_encode($_data);
        exit();
    }


    public function check_coupon_code()
    {
        // check is ajax request
        if (!$this->input->is_ajax_request()) {
            if (is_admin()) {
                redirect('admin/dashboard');
            } else {
                redirect('/register');
            }
        }
        $coupon_code = $this->input->post('coupon_code', true);
        $package_id = $this->input->post('package_id', true);
        $billing_cycle = $this->input->post('billing_cycle', true);
        $email = $this->input->post('email', true);

        $where = array('code' => $coupon_code, 'status' => 'active');
        $coupon_info = get_old_result('tbl_saas_coupon', $where, false);
        if (!empty($coupon_info)) {
            // check coupon end date must be greater than or equal to current date
            if (strtotime($coupon_info->end_date) <= strtotime(date('Y-m-d'))) {
                $result['error'] = true;
                $result['message'] = _l('coupon_expired');
                $result['coupon_code_input'] = null;
            } else {
                $user_id = get_staff_user_id();
                if (!empty($user_id)) {
                    $where = array('user_id' => $user_id, 'coupon' => $coupon_code);
                } else {
                    $where = array('email' => $email, 'coupon' => $coupon_code);
                }
                $already_apply = get_old_result('tbl_saas_applied_coupon', $where, false);
                if (empty($already_apply)) {
                    $package_info = get_old_result('tbl_saas_packages', array('id' => $package_id), false);
                    $sub_total = $package_info->$billing_cycle;
                    $percentage = $coupon_info->amount;

                    if ($coupon_info->type == 1) {
                        $discount_amount = ($percentage / 100) * $sub_total;
                        $discount_percentage = $percentage . '%';
                    } else {
                        $discount_amount = $percentage;
                        $discount_percentage = $percentage;
                    }
                    $result['sub_total_text'] = display_money($sub_total, default_currency());
                    $result['sub_total_input'] = $sub_total;
                    $result['total_text'] = display_money($sub_total - $discount_amount, default_currency());
                    $result['total_input'] = $sub_total - $discount_amount;
                    $result['discount_percentage'] = $discount_percentage;
                    $result['coupon_code_input'] = $coupon_code;

                    $html = '';
                    $html .= '<div class="form-group mt-2 mb-2">';
                    $html .= '<div class="input-group"><span class="input-group-text">(' . $discount_percentage . ')</span>';
                    $html .= '<input type="text" class="form-control" name="discount_amount" value="' . $discount_amount . '" readonly >';
                    $html .= '</div></div></div>';

                    $thtml = '';
                    $thtml .= '<div class="form-group mt-2 mb-2"><label class="col-sm-3 control-label">' . _l('total_amount') . '</label>';
                    $thtml .= '<div class="col-sm-5"><div class="input-group"><span class="input-group-text">' . default_currency() . '</span>';
                    $thtml .= '<input type="text" class="form-control" name="total_amount" value="' . $result['total_input'] . '" readonly >';
                    $thtml .= '</div></div></div>';

                    if ($coupon_info->package_id == 0) {
                        $result['success'] = true;
                        $result['applied_discount'] = $html;
                        $result['total_amount'] = $thtml;
                        $result['discount_amount_text'] = display_money($discount_amount, default_currency());
                        $result['discount_amount_input'] = $discount_amount;
                    } elseif ($coupon_info->package_id == $package_id) {
                        $result['success'] = true;
                        $result['html'] = $html;
                        $result['message'] = '';
                        $result['discount_amount_text'] = display_money($discount_amount, default_currency());
                        $result['discount_amount_input'] = $discount_amount;
                    } else {
                        $result['error'] = true;
                        $result['message'] = _l('the_coupon_code_is_invalid');
                        $result['coupon_code_input'] = null;
                    }
                } else {
                    $result['error'] = true;
                    $result['message'] = _l('the_coupon_code_already_used');
                    $result['coupon_code_input'] = null;
                }
            }
        } else {
            $result['error'] = true;
            $result['message'] = _l('coupon_not_exist');
            $result['coupon_code_input'] = null;
        }
        echo json_encode($result);
        exit();
    }

    function check_already_exists()
    {
        // check ajax request or not
        if (!$this->input->is_ajax_request()) {
            redirect('saas/dashboard');
        }
        $type = $this->input->post('type', true);
        $value = $this->input->post('value', true);

        $reserved = check_reserved_tenant($value);
        $companies = get_row('tbl_saas_companies', array($type => $value));
        if (!empty($companies) || !empty($reserved)) {
            $result['status'] = 'error';
            $result['message'] = _l('already_exists', _l($type));
            if ($type == 'activation_code') {
                $result['info'] = $companies;
            }
        } else {
            $result['status'] = 'success';
        }
        echo json_encode($result);
        exit();
    }

    public function update_sub_validity($id)
    {
        $validity = $this->input->post('validity', true);
        $status = $this->input->post('status', true);
        $disabled_modules = (!empty($this->input->post('disabled_modules'))) ? serialize($this->input->post('disabled_modules')) : '';
        $company_info = $this->saas_model->company_info($id);

        $data = $this->total_count_date($id, $validity, $status);

        if (empty($data['status'])) {
            $data['status'] = $status;
        }
        $data['maintenance_mode'] = ($this->input->post('maintenance_mode', true) == 'on') ? 'Yes' : 'No';
        $data['remarks'] = $this->input->post('remarks', true);
        $data['maintenance_mode_message'] = $this->input->post('maintenance_mode_message', true);

        // update sub_validity into saas_companies table
        $this->saas_model->_table_name = 'tbl_saas_companies';
        $this->saas_model->_primary_key = 'id';
        $this->saas_model->save($data, $id);

        // update disabled modules into saas_companies_history table
        $this->saas_model->_table_name = 'tbl_saas_companies_history';
        $this->saas_model->_primary_key = 'id';
        $this->saas_model->save(array('disabled_modules' => $disabled_modules), $company_info->company_history_id);

        set_alert('success', _l('update_settings'));
        redirect('saas/companies/details/' . $id);


    }

    function total_count_date($id, $date, $status): array
    {
        $company_info = get_row('tbl_saas_companies', array('id' => $id));
        $time = date('Y-m-d');

        $to_date = strtotime($time); //past date.
        $cur_date = strtotime($date);
        $timeleft = $cur_date - $to_date;
        $daysleft = round((($timeleft / 24) / 60) / 60);
        if ($date > $time && $status == 'expired') {
            $data['status'] = 'running';
        }
        if ($company_info->trial_period != 0) {
            $data['trial_period'] = $daysleft;
            $data['is_trial'] = 'Yes';
        } else {
            $data['trial_period'] = 0;
            $data['is_trial'] = 'No';
        }
        $data['expired_date'] = date("Y-m-d", strtotime($daysleft . "day"));
        return $data;
    }


    public function privacy()
    {
        $data['title'] = _l('privacy');
        $data['result'] = config_item('saas_privacy');
        $data['subview'] = $this->load->view('saas/settings/privacy', $data, TRUE);
        $this->load->view('_layout_open', $data);
    }

    public function tos()
    {
        $data['title'] = _l('tos');
        $data['result'] = config_item('saas_tos');
        $this->load->view('saas/settings/privacy', $data, TRUE);
        $this->load->view('_layout_front', $data);
    }

    public function update_company_packages($companies_id = null, $data = array())
    {
        if (empty($companies_id)) {
            $companies_id = $this->input->post('companies_id', true);
            $package_id = $this->input->post('package_id', true);
        }

        if ($companies_id == '') {
            set_alert('warning', _l('no_company_selected'));
            redirect('saas/companies');
        }


        $company_info = get_row('tbl_saas_companies', array('id' => $companies_id));
        if (empty($package_id)) {
            $package_id = $company_info->package_id;
        }

        // $this->saas_model->create_database($companies_id);

        if ($company_info->package_id != $package_id) {

            $package_info = get_row('tbl_saas_packages', array('id' => $package_id));

            if (empty($package_info) || empty($package_id)) {
                $type = 'warning';
                $msg = _l('package_field_is_required');
                set_alert($type, $msg);
                redirect('saas/dashboard');
            }

            $data['updated_date'] = date('Y-m-d H:i:s');
            $data['updated_by'] = get_staff_user_id();
            $billing_cycle = $this->input->post('billing_cycle', true);
            $mark_paid = $this->input->post('mark_paid', true);
            $data['frequency'] = str_replace('_price', '', $billing_cycle);;
            $data['trial_period'] = $package_info->trial_period;
            $data['is_trial'] = 'Yes';
            $data['expired_date'] = $this->input->post('expired_date', true);;
            $data['package_id'] = $package_id;
            $data['currency'] = get_base_currency()->name;
            $data['amount'] = $package_info->$billing_cycle;
            if (!empty($mark_paid)) {
                $data['status'] = 'running';
                $data['is_trial'] = 'No';
            }
            $this->saas_model->_table_name = 'tbl_saas_companies';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->save($data, $companies_id);


            $this->saas_model->_table_name = 'tbl_saas_companies_history';
            $this->saas_model->_primary_key = 'companies_id';
            $this->saas_model->save(array('active' => 0), $companies_id);


            $data['companies_id'] = $companies_id;
            $data['ip'] = $this->input->ip_address();
            $companies_history_id = $this->saas_model->update_company_history($data);

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
                    'companies_id' => $companies_id,
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
            // save into activities
            log_activity(_l('activity_new_saas_payment') . ' - ' . $data['amount']);
            $type = 'success';
            $message = _l('package_successfully_assigned');
            // send email to client
            $this->saas_model->send_email_to_company($companies_id);
        } else {
            $type = 'warning';
            $message = _l('you_can_not_assign_same_package_to_company');
        }
        set_alert($type, $message);
        redirect('saas/dashboard');
    }


    public function completePaypalPayment($id)
    {
        $input_data = $this->session->userdata('input_info');
        if (!empty($input_data)) {
            $reference_no = $this->session->userdata('reference_no');
            $cf = $input_data['payment_method'] . '_payment';
            $paypalResponse = $this->$cf->complete_purchase([
                'token' => $reference_no,
                'amount' => $input_data['amount'],
                'currency' => $input_data['currency'],
            ]);

            $state = $paypalResponse['state'];
            if ($state === 'approved') {
                $input_data['transaction_id'] = $paypalResponse['id'];
                $input_data['mark_paid'] = 'Yes';
                $result = $this->saas_model->update_package($id, $input_data);
                if (!empty($result)) {
                    set_alert('success', 'Payment successfully completed');
                } else {
                    set_alert('error', 'Payment failed');
                }
            } else {
                $type = 'error';
                $message = 'Payment failed';
                set_alert($type, $message);
            }
        }
        $this->session->unset_userdata('input_info');
        $this->session->unset_userdata('reference_no');
        redirect('admin/dashboard');
    }

    public function stipePaymentSuccess($data = null)
    {
        // receive metadata from stripe
        $data = url_decode($data);
        $data['mark_paid'] = 'Yes';
        $result = $this->saas_model->update_package($data['companies_id'], $data);
        if (!empty($result)) {
            set_alert('success', 'Payment successfully completed');
        } else {
            set_alert('error', 'Payment failed');
        }
        redirect('admin/dashboard');
    }

    public function paymentCancel($data = null)
    {
        $data = url_decode($data);
        $type = 'error';
        $message = _l('payment_cancelled');
        set_alert($type, $message);
        redirect('admin/dashboard');
    }

    public function get_expired_date($package_type)
    {
        $type_title = str_replace('_price', '', $package_type);
        if ($type_title == 'lifetime') {
            $renew_date = date('Y-m-d', strtotime('+100 year'));
        } elseif ($type_title == 'yearly') {
            $renew_date = date('Y-m-d', strtotime('+1 year'));
        } else {
            $renew_date = date('Y-m-d', strtotime('+1 month'));
        }
        return $renew_date;
    }

    /**
     * @throws Exception
     */
    public function proceedPackage($package_id = null, $company_id = null)
    {
        $data['package_id'] = $package_id;
        $data['frequency'] = 'monthly';
        if (empty($data['package_id']) && !empty(is_client_logged_in())) {
            $subs_info = get_company_subscription_by_id(null, 'running');
            $data['package_id'] = $subs_info->package_id;
            $data['frequency'] = $subs_info->frequency;
        }
        $package_info = get_old_result('tbl_saas_packages', array('id' => $data['package_id']), false);
        $data['title'] = _l('checkout') . ' ' . _l('payment') . ' ' . _l('for') . ' ' . $package_info->name;
        $data['package_info'] = $package_info;
        $data['all_packages'] = get_old_result('tbl_saas_packages', array('status' => 'published'));
        $subview = 'checkoutPayment';
        if (!empty(is_client_logged_in())) {
            $data['subs_info'] = get_company_subscription_by_id();
            $data['payment_modes'] = $this->saas_model->get_payment_modes();
            $subview = 'checkoutPaymentOpen';
        } else if (!empty($company_id)) {
            $company_id = url_decode($company_id);
            $data['subs_info'] = $this->saas_model->company_info($company_id);
            $data['subs_info']->companies_id = $company_id;
            $data['payment_modes'] = $this->saas_model->get_payment_modes();
            $subview = 'checkoutPaymentOpen';
            $data['company_id'] = $company_id;
            $data['front'] = true;
        }
        $view = 'saas/packages/' . $subview;
        $data['subview'] = $this->load->view($view, $data, TRUE);
        $this->load->view('_layout_package', $data); //page load

    }


//    public function proceedPayment($payment_method = null)
//    {
//
//        if (!empty($payment_method)) {
//            $subs_info = get_company_subscription_by_id(null, 'running');
//            $data['companies_id'] = $subs_info->companies_id;
//            $data['package_id'] = $subs_info->package_id;
//            $data['billing_cycle'] = $subs_info->frequency . '_price';
//            $data['expired_date'] = $this->get_expired_date($data['billing_cycle']);
//            $data['amount'] = $subs_info->amount;
//            $data['payment_method'] = $payment_method;
//            $data['i_have_read_agree'] = 'on';
//        } else {
//            $data = $_POST;
//            $subs_info = get_company_subscription_by_id($data['companies_id'], 'running');
//        }
//
//        // get client_id by saas_company_id
//        $client_id = get_saas_client_id($data['companies_id']);
//        $payment_modes = $this->saas_model->get_payment_modes();
//        $modes = array();
//        foreach ($payment_modes as $mode) {
//            $modes[$mode['id']] = $mode['name'];
//        }
//
//        $pData = array();
//
//
//        $i_data['clientid'] = $client_id;
//        $i_data['number'] = get_option('next_invoice_number');
//        $i_data['project_id'] = 0;
//        $i_data['include_shipping'] = 0;
//        $i_data['discount_type'] = '';
//        $i_data['date'] = date('Y-m-d');
//        $i_data['duedate'] = date('Y-m-d');
//        $i_data['allowed_payment_modes'] = serialize(array_keys($modes));
//        $i_data['currency'] = get_base_currency()->id;
//        $i_data['sale_agent'] = $subs_info->created_by ?? $subs_info->updated_by ?? 0;
//        $i_data['subtotal'] = $data['amount'];
//        $i_data['total'] = $data['amount'];
//        $i_data['prefix'] = 'SaaS ' . get_option('invoice_prefix');
//        $i_data['number_format'] = get_option('invoice_number_format');
//        $i_data['datecreated'] = date('Y-m-d H:i:s');
//        $i_data['addedfrom'] = !DEFINED('CRON') ? get_staff_user_id() : 0;
//        $i_data['hash'] = app_generate_hash();
//
//        $this->saas_model->_table_name = db_prefix() . 'invoices';
//        $this->saas_model->_primary_key = 'id';
//        $invoice_id = $this->saas_model->save($i_data);
//
//        $item_data = array();
//        $item_data['description'] = 'Subscription for package ' . $subs_info->package_name;
//        $item_data['long_description'] = 'Subscription for package ' . $subs_info->package_name;
//        $item_data['qty'] = 1;
//        $item_data['rate'] = number_format($data['amount'], get_decimal_places(), '.', '');
//        $item_data['rel_id'] = $invoice_id;
//        $item_data['rel_type'] = 'invoice';
//
//        $this->saas_model->_table_name = db_prefix() . 'itemable';
//        $this->saas_model->_primary_key = 'id';
//        $this->saas_model->save($item_data);
//
//        $temp_data = array();
//        $temp_data['companies_id'] = $data['companies_id'];
//        $temp_data['invoice_id'] = $invoice_id;
//        $temp_data['package_id'] = $data['package_id'];
//        $temp_data['billing_cycle'] = $data['billing_cycle'];
//        $temp_data['expired_date'] = $data['expired_date'];
//        $temp_data['coupon_code'] = $data['coupon_code'];
//        $temp_data['amount'] = $data['amount'];
//        $temp_data['clientid'] = $client_id;
//        $temp_data['hash'] = $i_data['hash'];
//
//        $this->saas_model->_table_name = 'tbl_saas_temp_payment';
//        $this->saas_model->_primary_key = 'temp_payment_id';
//        $this->saas_model->save_old($temp_data);
//
//
//        $pData['hash'] = $i_data['hash'];
//        $pData['paymentmode'] = $data['paymentmode'];
//        $pData['amount'] = $data['amount'];
//        $pData['make_payment'] = 'Pay Now';
//        $pData['invoice_id'] = $invoice_id;
//        $pData['companies_id'] = $data['companies_id'];
//
//        // set session for payment
//        $this->session->set_userdata('saas_payment_data', $data);
//
//        $this->load->model('payments_model');
//        $this->payments_model->process_payment($pData, $invoice_id);
//
//
//    }


    public
    function signed_up()
    {
        $data = $this->saas_model->array_from_post(array('name', 'email', 'package_id', 'domain', 'mobile', 'address', 'country'));
        $domain = $this->input->post('domain', true);
        $data['domain'] = domainUrl(slug_it($domain));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'name', 'required|trim|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('email', 'email', 'required|valid_email|trim|is_unique[tbl_saas_companies.email]');
        $this->form_validation->set_rules('package_id', 'package', 'required|trim');
        $this->form_validation->set_rules('domain', 'domain', 'required|trim|callback_check_domain');

        $data['timezone'] = ConfigItems('saas_default_timezone');
        $data['language'] = ConfigItems('saas_active_language');
        $data['created_date'] = date('Y-m-d H:i:s');
        $data['created_by'] = NULL;
        $disable_email_verification = ConfigItems('disable_email_verification');
        if (!empty($disable_email_verification) && $disable_email_verification == 1) {
            $data['status'] = 'running';
            $data['password'] = '123456';
        } else {
            $data['status'] = 'pending';
        }
        $company_url = companyUrl($data['domain']);
        $this->load->library('uuid');
        $data['activation_code'] = $this->uuid->v4();

        $check_email = get_row('tbl_saas_companies', array('email' => $data['email']));
        // check email already exist
        $check_domain = get_row('tbl_saas_companies', array('domain' => $data['domain']));
        $reserved = check_reserved_tenant($data['domain']);
        if (!empty($check_email)) {
            $type = 'error';
            $msg = _l('already_exists', _l('email'));
        } else if (!empty($check_domain)) {
            $type = 'error';
            $msg = _l('already_exists', _l('domain'));
        } else if (!empty($reserved)) {
            $type = 'error';
            $msg = _l('already_exists', _l('domain'));
        } else {
            if ($this->form_validation->run() == FALSE) {
                $type = 'warning';
                $msg = $this->form_validation->error_array();
                set_alert($type, $msg);
                redirect('register');
            } else {
                $billing_cycle = $this->input->post('billing_cycle', true);
                $package_info = get_row('tbl_saas_packages', array('id' => $data['package_id']));
                $package_info = apply_coupon($package_info);
                // deduct $billing_cycle from price
                $data['frequency'] = str_replace('_price', '', $billing_cycle);;
                $data['trial_period'] = $package_info->trial_period;
                $data['is_trial'] = 'Yes';
                $data['expired_date'] = $this->input->post('expired_date', true);;
                $data['currency'] = get_base_currency()->name;
                $offer_price = $data['frequency'] . '_offer';
                if (!empty($package_info->$offer_price)) {
                    $data['amount'] = $package_info->$offer_price;
                } else {
                    $data['amount'] = $package_info->$billing_cycle;
                }

                // enable_affiliate and get referral code from session
                $is_enabled = ConfigItems('enable_affiliate');
                $referer = $this->session->userdata('referer');
                if ($is_enabled && !empty($referer)) {
                    // get user id from referral
                    $user_info = get_row('tbl_saas_affiliate_users', array('referral_link' => $referer));
                    if (!empty($user_info)) {
                        $data['referral_by'] = $user_info->user_id;
                    }

                }

                $this->saas_model->_table_name = 'tbl_saas_companies';
                $this->saas_model->_primary_key = 'id';
                $id = $this->saas_model->save($data);

                $this->saas_model->save_client($id, $data['password']);

                if (!empty($data['referral_by'])) {
                    $this->saas_model->add_affiliate($id, $data, true);
                    // remove referral from session
                    $this->session->unset_userdata('referer');
                }

                // change active status to 0 for all previous data of this company
                $this->saas_model->_table_name = 'tbl_saas_companies_history';
                $this->saas_model->_primary_key = 'companies_id';
                $this->saas_model->save(array('active' => 0), $id);

                $data['companies_id'] = $id;
                $data['ip'] = $this->input->ip_address();
                $this->saas_model->update_company_history($data);

                // create database for this company
                if ($data['status'] == 'running') {
                    // create database for the company
                    $this->saas_model->create_database($id);
                }

                if (empty($disable_email_verification) && $disable_email_verification !== 1) {
                    $this->saas_model->send_activation_token_email($id);
                }

                $type = "success";
                if ($data['status'] == 'running') {
                    $msg = '';
                    $msg .= '<p>Hi ' . $data['name'] . ',</p>';
                    $msg .= '<p>here is your company URL Admin: <a href="' . $company_url . 'admin" target="_blank">' . $company_url . 'admin</a></p>';
                    $msg .= 'Username: ' . $data['email'] . '<br>';
                    $msg .= 'Password: ' . $data['password'] . '<br>';
                    $msg .= '<p>Thanks</p>';
                } else {
                    $msg = 'Registration Successfully Completed. Please check your email for activation link. if you not received email please check spam folder.if you still not received email please contact with us for activate your account.';
                }
                log_activity('New Company Created [ID:' . $id . ', Name: ' . $data['name'] . ']');

            }
        }
        $message = $msg;
        set_alert($type, $message);
        redirect('register');
    }

    public
    function check_domain($domain)
    {
        $domain = domainUrl(slug_it($domain));
        $check_domain = get_row('tbl_saas_companies', array('domain' => $domain));
        $reserved = check_reserved_tenant($domain);
        if (!empty($check_domain) || !empty($reserved)) {
            $this->form_validation->set_message('check_domain', _l('already_exists', _l('domain')));
            return false;
        }
        return true;
    }


    public
    function companyHistoryList($id = null)
    {
        // make datatable
        $this->db = config_db(null, true);
        $this->load->model('datatables');
        $this->datatables->table = 'tbl_saas_companies_history';
        $column = array('package_name', 'amount', 'frequency', 'created_at', 'validity', 'payment_method', 'status');
        $this->datatables->column_order = $column;
        $this->datatables->column_search = $column;
        $this->datatables->order = array('id' => 'desc');
        if ($id) {
            $where = array('tbl_saas_companies_history.companies_id' => $id);
        } else {
            $where = array();
        }
        $fetch_data = make_datatables($where, null, true);
        $data = array();
        $access = super_admin_access();

        foreach ($fetch_data as $_key => $v_history) {
            if ($v_history->active == 1) {
                $label = 'success';
                $status = 'active';
            } else {
                $label = 'warning';
                $status = 'inactive';
            }
            if ($v_history->frequency == 'monthly') {
                $frequency = _l('mo');
            } else if ($v_history->frequency == 'lifetime') {
                $frequency = _l('lt');
            } else if ($v_history->frequency == 'yearly') {
                $frequency = _l('yr');
            }
            $action = null;
            $sub_array = array();
            $name = '<a href="' . base_url('subs_package_details/' . $v_history->id . '/1') . '"  data-toggle="modal" data-target="#myModal" >' . $v_history->package_name . '</a>';
            if (!empty($access)) {
                $name .= '<div class="row-options">';
                if (!empty($access) && $v_history->active != 1) {
                    $name .= '<a 
                    data-toggle="tooltip" data-placement="top"
                    href="' . base_url('saas/gb/delete_companies_history/' . $v_history->id) . '"  title="' . _l('delete') . '" class="text-danger _delete">' . _l('delete') . '</a>';
                }
                $name .= '</div>';
            }
            $sub_array[] = $name;
            $sub_array[] = display_money($v_history->amount, default_currency()) . ' /' . $frequency;
            $sub_array[] = _dt($v_history->created_at);
            $sub_array[] = (!empty($v_history->validity) ? $v_history->validity : '-');
            $sub_array[] = $v_history->payment_method;
            if (!empty($access)) {
                $sub_array[] = '<span class="label label-' . $label . '">' . _l($status) . '</span>';
            }
            $data[] = $sub_array;
        }

        render_table_old($data, $where);
    }


    public
    function companyPaymentList($id = null)
    {
        // make datatable
        $this->db = config_db(null, true);
        $this->load->model('datatables');
        $this->datatables->table = 'tbl_saas_companies_payment';
        $this->datatables->join_table = array('tbl_saas_companies', 'tbl_saas_companies_history');
        $this->datatables->join_where = array('tbl_saas_companies.id=tbl_saas_companies_payment.companies_id', 'tbl_saas_companies_history.id=tbl_saas_companies_payment.companies_history_id');

        $column = array('tbl_saas_companies_history.package_name', 'transaction_id', 'total_amount', 'payment_date', 'payment_method');
        $this->datatables->column_order = $column;
        $this->datatables->column_search = $column;
        $this->datatables->order = array('id' => 'desc');
        $this->datatables->select = ('tbl_saas_companies_payment.*,tbl_saas_companies_history.package_name,tbl_saas_companies.name as company_name');
        // select tbl_saas_companies_history.name
        if (!empty($id)) {
            $where = array('tbl_saas_companies_payment.companies_id' => $id);
        } else {
            $where = array();
        }
        $fetch_data = make_datatables($where);
        $access = super_admin_access();
        $data = array();
        foreach ($fetch_data as $_key => $v_history) {
            $action = null;
            $sub_array = array();

            if (!empty($access)) {
                $name = $v_history->company_name;

                $name .= '<div class="row-options">';
                $name .= '<a 
                    data-toggle="tooltip" data-placement="top"
                    href="' . base_url('saas/gb/delete_companies_payment/' . $v_history->id) . '"  title="' . _l('delete') . '" class="text-danger _delete">' . _l('delete') . '</a>';
                $name .= '</div>';
                $sub_array[] = $name;
            }
            $sub_array[] = '<a href="' . base_url('subs_package_details/' . $v_history->companies_history_id . '/1') . '"  data-toggle="modal" data-target="#myModal" >' . $v_history->package_name . '</a>';
            $sub_array[] = $v_history->transaction_id;
            $sub_array[] = display_money($v_history->total_amount, default_currency());
            $sub_array[] = _dt($v_history->payment_date);
            $sub_array[] = $v_history->payment_method;
            $data[] = $sub_array;
        }
        render_table_old($data, $where);
    }

    public
    function delete_companies_history($id)
    {
        $access = super_admin_access();
        if (empty($access)) {
            access_denied('companies_history_delete');
        }
        $this->db->where('id', $id);
        $this->db->delete('tbl_saas_companies_history');
        set_alert('success', _l('saas_companies_history_deleted'));
        redirect($_SERVER['HTTP_REFERER']);
    }

    public
    function delete_companies_payment($id)
    {
        $access = super_admin_access();
        if (empty($access)) {
            access_denied('companies_payment_delete');
        }
        $this->db->where('id', $id);
        $this->db->delete('tbl_saas_companies_payment');
        set_alert('success', _l('saas_companies_payment_deleted'));
        redirect($_SERVER['HTTP_REFERER']);
    }


    public
    function purchase_package_details($id)
    {
        $data['title'] = 'Dashboard';
        $data['package_info'] = get_old_result('tbl_saas_companies_history', array('active' => 1, 'companies_id' => $id), false);
        $data['package_info']->name = $data['package_info']->package_name;
        $data['modal_subview'] = $this->load->view('saas/packages/package_details', $data, false);
        $this->load->view('saas/layout/_layout_modal', $data);
    }

    public function login_as_company($id)
    {
        if (!is_client_logged_in() && !super_admin_access()) {
            access_denied('login_as_company');
        }
        $company_info = get_old_result('tbl_saas_companies', array('id' => $id), false);
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

    public function proceedPayment($payment_method = null)
    {
        if (!empty(is_client_logged_in())) {
            $subs_info = get_company_subscription_by_id(null, 'running');
        } else {
            $subs_info = get_company_subscription(null, 'running');
        }
        $data = $_POST;
        if (!empty($subs_info) && !empty($data['paymentmode'])) {
            $this->saas_model->proceedPayment($subs_info);
        } else {
            set_alert('warning', _l('select_payment_method'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function contact()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $comments = $_POST['comments'];
        $subject = $_POST['subject'];

        $address = get_option('smtp_email');

        $comments = stripslashes($comments);


        $e_subject = 'You have been contacted by ' . $name . '.';

        $e_body = "You have been contacted by $name. Their additional message is as follows." . PHP_EOL . PHP_EOL;
        $e_content = "\"$comments\"" . PHP_EOL . PHP_EOL;
        $e_reply = "You can contact $name via email, $email";

        $msg = wordwrap($e_body . $e_content . $e_reply, 70);

        $headers = "From: $email" . PHP_EOL;
        $headers .= "Reply-To: $email" . PHP_EOL;
        $headers .= "MIME-Version: 1.0" . PHP_EOL;
        $headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;
        $headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

        if (mail($address, $e_subject, $msg, $headers)) {
            set_alert('success', _l('contact_successfully'));
            redirect($_SERVER['HTTP_REFERER']);

        } else {
            echo 'ERROR!';
        }
    }


}
