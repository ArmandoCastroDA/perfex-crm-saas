<?php defined('BASEPATH') or exit('No direct script access allowed');


class Settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        $this->load->model('payment_modes_model');
        $this->load->model('settings_model');
        saas_access();
    }


    public function index($active = null)
    {
        if (empty($active)) {
            $data['active'] = 'general';
        } else {
            $data['active'] = $active;
        }
        $data['title'] = _l('saas_settings') . ' - ' . _l($data['active']);
        $data['all_tabs'] = $this->saas_model->get_all_tabs();
        $data['subview'] = $this->load->view('saas/settings/tab_view', $data, TRUE);
        $this->load->view('_layout_main', $data);
    }

    public function updateOption()
    {
        $logo_uploaded = (handle_saas_company_logo_upload() ? true : false);
        $favicon_uploaded = (handle_saas_favicon_upload() ? true : false);
        $signatureUploaded = (handle_saas_company_signature_upload() ? true : false);

        $post_data = $this->input->post();
        $tmpData = $this->input->post(null, false);


        if (!empty($post_data['settings']['saas_email_header'])) {
            $post_data['settings']['saas_email_header'] = ($tmpData['settings']['saas_email_header']);
        }

        if (!empty($post_data['settings']['saas_email_footer'])) {
            $post_data['settings']['saas_email_footer'] = ($tmpData['settings']['saas_email_footer']);
        } else {
            unset($post_data['settings']['saas_email_footer']);
        }

        if (!empty($post_data['settings']['saas_email_signature'])) {
            $post_data['settings']['saas_email_signature'] = ($tmpData['settings']['saas_email_signature']);
        } else {
            unset($post_data['settings']['saas_email_signature']);
        }

        if (!empty($post_data['settings']['saas_smtp_password'])) {
            $post_data['settings']['saas_smtp_password'] = encrypt($tmpData['settings']['saas_smtp_password']);
        } else {
            unset($post_data['settings']['saas_smtp_password']);
        }
        if (!empty($post_data['settings']['saas_microsoft_mail_client_secret'])) {
            $post_data['settings']['saas_microsoft_mail_client_secret'] = encrypt($tmpData['settings']['saas_microsoft_mail_client_secret']);
        } else {
            unset($post_data['settings']['saas_microsoft_mail_client_secret']);
        }
        if (!empty($post_data['settings']['saas_google_mail_client_secret'])) {
            $post_data['settings']['saas_google_mail_client_secret'] = encrypt($tmpData['settings']['saas_google_mail_client_secret']);
        } else {
            unset($post_data['settings']['saas_google_mail_client_secret']);
        }
        if (!empty($post_data['settings']['saas_cpanel_password'])) {
            $post_data['settings']['saas_cpanel_password'] = encrypt($tmpData['settings']['saas_cpanel_password']);
        } else {
            unset($post_data['settings']['saas_cpanel_password']);
        }
        if (!empty($post_data['settings']['saas_plesk_password'])) {
            $post_data['settings']['saas_plesk_password'] = encrypt($tmpData['settings']['saas_plesk_password']);
        } else {
            unset($post_data['settings']['saas_plesk_password']);
        }
        if (!empty($post_data['settings']['saas_mysql_password'])) {
            $post_data['settings']['saas_mysql_password'] = encrypt($tmpData['settings']['saas_mysql_password']);
        } else {
            unset($post_data['settings']['saas_mysql_password']);
        }

        $test_connection = $this->input->post('test_connection', true);
        if (!empty($test_connection)) {
            $this->saas_model->test_connection($post_data);
        }

        if (!empty($post_data['settings']['saas_landing_page_url'])) {
            $url = $post_data['settings']['saas_landing_page_url'];
        } else {
            $url = null;
        }
        if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
            set_alert('danger', _l('saas_landing_page_url_invalid'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        if (!empty($post_data['settings']['saas_landing_page_url_mode'])) {
            $mode = $post_data['settings']['saas_landing_page_url_mode'];
        } else {
            $mode = 'default';
        }
        $main_url = rtrim(config_item('main_url'), '/');
        if (!empty($mode) && !empty($url) && $mode == 'default' && $url != $main_url) {
            // update main url in my_config.php file if changed
            // check if url dont have last slash add it
            if (substr($url, -1) != '/') {
                $url = $url . '/';
            }
            $this->update_config_file($url);
        }
        $success = $this->settings_model->update($post_data);

        if ($success > 0) {
            set_alert('success', _l('settings_updated'));
        }

        if ($logo_uploaded || $favicon_uploaded) {
            set_debug_alert(_l('logo_favicon_changed_notice'));
        }
        redirect($_SERVER['HTTP_REFERER']);
    }


    /* Since version 1.0.1 - test your smtp settings */
    public function sent_smtp_test_email()
    {
        if ($this->input->post()) {
            $this->load->config('email');
            // Simulate fake template to be parsed
            $template           = new StdClass();
            $template->message  = get_option('email_header') . 'This is test SMTP email. <br />If you received this message that means that your SMTP settings is set correctly.' . get_option('email_footer');
            $template->fromname = get_option('companyname') != '' ? get_option('companyname') : 'TEST';
            $template->subject  = 'SMTP Setup Testing';

            $template = parse_email_template($template);

            hooks()->do_action('before_send_test_smtp_email');
            $this->email->initialize();
            if (get_option('mail_engine') == 'phpmailer') {
                $this->email->set_debug_output(function ($err) {
                    if (!isset($GLOBALS['debug'])) {
                        $GLOBALS['debug'] = '';
                    }
                    $GLOBALS['debug'] .= $err . '<br />';

                    return $err;
                });

                $this->email->set_smtp_debug(3);
            }

            $this->email->set_newline(config_item('newline'));
            $this->email->set_crlf(config_item('crlf'));

            $this->email->from(get_option('smtp_email'), $template->fromname);
            $this->email->to($this->input->post('test_email'));

            $systemBCC = get_option('bcc_emails');

            if ($systemBCC != '') {
                $this->email->bcc($systemBCC);
            }

            $this->email->subject($template->subject);
            $this->email->message($template->message);

            if ($this->email->send(true)) {
                set_alert('success', 'Seems like your SMTP settings is set correctly. Check your email now.');
                hooks()->do_action('smtp_test_email_success');
            } else {
                set_debug_alert('<h1>Your SMTP settings are not set correctly here is the debug log.</h1><br />' . $this->email->print_debugger() . (isset($GLOBALS['debug']) ? $GLOBALS['debug'] : ''));

                hooks()->do_action('smtp_test_email_failed');
            }
        }
    }

    public function update_config_file($url)
    {
        $path = APP_MODULES_PATH . 'saas/config/my_config.php';
        $main_url = config_item('main_url');
        // update $config['main_url'] in $path file with $url
        // find $config['main_url'] in file and replace with $url after = sign and before ;
        $file = fopen($path, 'r+');
        $content = fread($file, filesize($path));
        $content = str_replace($main_url, $url, $content);
        fseek($file, 0);
        fwrite($file, $content);
        fclose($file);
    }


    /* Remove company logo from settings / ajax */
    public
    function remove_company_logo($type = '')
    {

        $logoName = get_option('saas_company_logo');
        if ($type == 'dark') {
            $logoName = get_option('saas_company_logo_dark');
        }

        $path = get_upload_path_by_type('company') . '/' . $logoName;
        if (file_exists($path)) {
            unlink($path);
        }

        update_option('saas_company_logo' . ($type == 'dark' ? '_dark' : ''), '');
        redirect($_SERVER['HTTP_REFERER']);
    }

    public
    function remove_fv()
    {
        if (file_exists(get_upload_path_by_type('company') . '/' . get_option('saas_favicon'))) {
            unlink(get_upload_path_by_type('company') . '/' . get_option('saas_favicon'));
        }
        update_option('saas_favicon', '');
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function save_server()
    {
        $can_do = super_admin_access();
        if (!empty($can_do)) {
            $saas_server = $this->input->post('saas_server');
            if ($saas_server == 'cpanel') {
                $input_data = $this->saas_model->array_from_post(array(
                    'saas_server', 'saas_cpanel_host',
                    'saas_cpanel_port', 'saas_cpanel_username', 'saas_cpanel_output'
                ));
                $saas_cpanel_password = $this->input->post('saas_cpanel_password', true);
                if (!empty($saas_cpanel_password)) {
                    $input_data['saas_cpanel_password'] = encrypt($saas_cpanel_password);
                }
            } elseif ($saas_server == 'plesk') {
                $input_data = $this->saas_model->array_from_post(array(
                    'saas_server', 'saas_plesk_host', 'saas_plesk_username', 'saas_plesk_webspace_id'
                ));
                $saas_plesk_password = $this->input->post('saas_plesk_password', true);
                if (!empty($saas_plesk_password)) {
                    $input_data['saas_plesk_password'] = encrypt($saas_plesk_password);
                }
            } elseif ($saas_server == 'mysql') {
                $input_data = $this->saas_model->array_from_post(array(
                    'saas_server', 'saas_mysql_host', 'saas_mysql_port', 'saas_mysql_username'
                ));
                $saas_mysql_password = $this->input->post('saas_mysql_password', true);
                if (!empty($saas_mysql_password)) {
                    $input_data['saas_mysql_password'] = encrypt($saas_mysql_password);
                }
            } else {
                $input_data = $this->saas_model->array_from_post(array('saas_server'));
            }
            $input_data['saas_server_wildcard'] = $this->input->post('saas_server_wildcard', true);
            $test_connection = $this->input->post('test_connection', true);
            if (!empty($test_connection)) {
                $this->saas_model->test_connection($input_data);
            }

            $this->update_config($input_data);
            // messages for user
            $type = "success";
            $message = _l('information_updated');
            set_alert($type, $message);
            redirect('saas/settings/index/server_settings');
        } else {
            redirect('saas/dashboard');
        }
    }

    public function update_email()
    {
        $input_data = $this->saas_model->array_from_post(array(
            'saas_company_email', 'saas_protocol', 'saas_smtp_host', 'saas_smtp_user', 'saas_smtp_port', 'saas_smtp_encryption'
        ));
        $saas_smtp_password = $this->input->post('saas_smtp_password', true);
        if (!empty($saas_smtp_password)) {
            $input_data['saas_smtp_password'] = encrypt($saas_smtp_password);
        }

        $this->update_config($input_data);

        log_activity('update email settings by ' . get_staff_full_name($this->session->userdata('user_id')));

        // messages for user
        $type = "success";
        $message = _l('information_updated');
        set_alert($type, $message);
        redirect('saas/settings/index/email_settings');
    }

    public function sent_test_email()
    {
        $test_email = $this->input->post('test_email');
        if (!empty($test_email)) {
            $params['subject'] = 'SMTP Setup Testing';
            $params['message'] = 'This is test SMTP email. <br />If you received this message that means that your SMTP settings is Corrects.';
            $params['recipient'] = $test_email;
            $params['resourceed_file'] = '';

            $result = $this->saas_model->send_saas_email($params, true);
            if ($result == true) {
                set_alert('success', 'Seems like your SMTP settings is Corrects. Check your email now. :)');
            } else {
                $s_data['email_error'] = '<h1>Your SMTP settings are not set correctly here is the debug log.</h1><br />' . show_error($this->email->print_debugger());
                $this->session->set_userdate($s_data);
            }
        }
        redirect('saas/settings/index/email_settings');
    }

    public function email_template()
    {
        if ($_POST) {
            $data = array(
                'subject' => $this->input->post('subject'),
                'template_body' => $this->input->post('email_template'),
            );

            $this->db->where(array('email_group' => $_POST['email_group']))->update('tbl_email_templates', $data);
            $return_url = $_POST['return_url'];
            redirect($return_url);
        } else {
            $data['active'] = 'email_template';
            $data['title'] = _l('saas_settings') . ' - ' . _l($data['active']);
            $data['all_tabs'] = $this->saas_model->get_all_tabs();
            $data['subview'] = $this->load->view('saas/settings/tab_view', $data, TRUE);
            $this->load->view('_layout_main', $data); //page load
        }
    }


    public function payments($payment = NULL)
    {

        $data['payment'] = $payment;
        $data['active'] = 'payments';
        $this->load->model('payment_modes_model');
        $data['payment_gateways'] = $this->payment_modes_model->get_payment_gateways(true);
        $data['title'] = _l('saas_settings') . ' - ' . _l('payments');
        $data['all_tabs'] = $this->saas_model->get_all_tabs();
        $data['subview'] = $this->load->view('saas/settings/tab_view', $data, TRUE);
        $this->load->view('_layout_main', $data); //page load
    }

    public function save_payments($payment)
    {
        $payment_info = get_row('tbl_online_payment', array('gateway_name' => $payment));

        $pdata['saas_' . slug_it(strtolower($payment)) . '_status'] = $this->input->post('saas_' . slug_it(strtolower($payment)) . '_status', true);
        for ($key = 1; $key <= 5; $key++) {
            $field = 'field_' . $key;
            $value = explode('|', $payment_info->$field);
            if (!empty($value[0])) {
                $value[0] = 'saas_' . $value[0];
                $pdata[$value[0]] = $this->input->post($value[0], true);
                if (!empty($value[1]) && $value[1] == 'password') {
                    $pdata[$value[0]] = encrypt($value[0]);
                } else if (!empty($value[1]) && $value[1] == 'checkbox') {
                    if (empty($this->input->post($value[0], true))) {
                        $pdata[$value[0]] = 'FALSE';
                    }
                }
            }
        }
        // update config
        $this->update_config($pdata);

        // messages for user
        $type = "success";
        $message = _l('payment_update_success');
        set_alert($type, $message);
        redirect('saas/settings/payments');
    }

    public function languages($lang = null)
    {
        $data['active'] = 'languages';
        $this->load->model('settings_model');
        if (!empty($lang)) {
            $data['language'] = $lang;
            $data['language_files'] = $this->language_files;
        } else {
            $data['active_language'] = $this->settings_model->get_active_languages();
            $data['availabe_language'] = $this->settings_model->available_translations();
        }

        $data['translation_stats'] = $this->settings_model->translation_stats($this->language_files);

        $data['title'] = _l('saas_settings') . ' - ' . _l('languages');
        $data['all_tabs'] = $this->saas_model->get_all_tabs();
        $data['subview'] = $this->load->view('saas/settings/tab_view', $data, TRUE);
        $this->load->view('_layout_main', $data); //page load
    }

    public function languages_status($language, $status)
    {
        $data['active'] = $status;
        $this->db->where('name', $language)->update('tbl_languages', $data);
        $type = 'success';
        if ($status == 1) {
            $message = _l('language_active_successfully');
        } else {
            $message = _l('language_deactive_successfully');
        }
        set_alert($type, $message);
        redirect('saas/settings/languages');
    }

    public function add_language()
    {
        $can_do = super_admin_access();
        if (!empty($can_do)) {
            $language = $this->input->post('language', TRUE);
            $this->settings_model->add_language($language, $this->language_files);
            $type = 'success';
            $message = _l('language_added_successfully');
            set_alert($type, $message);
        }
        redirect('saas/settings/languages');
    }

    public function edit_language($lang, $file)
    {
        $data['active'] = 'languages';
        $path = $this->language_files[$file . '_lang.php'];

        $data['language'] = $lang;
        //CI will record your lang file is loaded, unset it and then you will able to load another
        //unset the lang file to allow the loading of another file
        if (isset($this->lang->is_loaded)) {
            $loaded = sizeof($this->lang->is_loaded);
            if ($loaded < 3) {
                for ($i = 3; $i <= $loaded; $i++) {
                    unset($this->lang->is_loaded[$i]);
                }
            } else {
                for ($i = 0; $i <= $loaded; $i++) {
                    unset($this->lang->is_loaded[$i]);
                }
            }
        }
        $data['english'] = $this->lang->load($file, 'english', TRUE, TRUE, $path);
        if ($lang == 'english') {
            $data['translation'] = $data['english'];
        } else {
            $data['translation'] = $this->lang->load($file, $lang, TRUE, TRUE);
        }
        $data['active_language_files'] = $file;

        $data['load_setting'] = 'translations';
        $data['current_languages'] = $lang;

        $data['title'] = _l('saas_settings') . ' - ' . _l('languages');
        $data['all_tabs'] = $this->saas_model->get_all_tabs();
        $data['subview'] = $this->load->view('saas/settings/tab_view', $data, TRUE);
        $this->load->view('_layout_main', $data); //page load

    }

    public function set_translations()
    {
        $can_do = super_admin_access();
        if (!empty($can_do)) {
            $jpost = array();
            //            $jsondata = json_decode(html_entity_decode($_POST['hello']));
            $jpost = $_POST['hello'];
            $_language = $_POST['_language'];
            $_file = $_POST['_file'];
            $jpost['_language'] = $_language;
            $jpost['_file'] = $_file;
            $result = $this->settings_model->save_translation($jpost);
            if ($result == true) {
                // messages for user
                $type = "success";
                $message = '<strong style=color:#000>' . $jpost['_language'] . '</strong>' . " Information Successfully Update!";
            } else {
                $type = "error";
                $message = '<strong style=color:#000>' . $jpost['_language'] . '</strong>' . " Make sure the permission to 0777 to the folder";
            }
            set_alert($type, $message);
            redirect('saas/settings/languages/' . $_language);
        }
        redirect('saas/settings/languages');
    }

    private
    function update_config($input_data)
    {
        if (!empty($input_data)) {
            foreach ($input_data as $key => $value) {
                $where = array('config_key' => $key);
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where($where)->update('tbl_config', $data);
                $exists = $this->db->where($where)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
        }
        return true;
    }

    public function currencyList($code = null)
    {
        $data['active'] = 'currencyList';
        $data['title'] = _l('saas_settings') . ' - ' . _l($data['active']);
        $data['currency'] = get_row('tbl_currencies', array('code' => $code));
        $data['all_tabs'] = $this->saas_model->get_all_tabs();
        $data['subview'] = $this->load->view('saas/settings/tab_view', $data, TRUE);
        $this->load->view('_layout_main', $data); //page load
    }


    public function new_currency($action = null, $code = null)
    {
        if (!empty($action)) {
            $data = $this->settings_model->array_from_post(array('code', 'name', 'symbol'));
            $this->settings_model->_table_name = 'tbl_currencies';
            $this->settings_model->_primary_key = 'code';
            if (!empty($code)) {
                $this->settings_model->save($data, $code);
            } else {
                $this->settings_model->save($data);
                redirect('saas/settings/index/system_settings');
            }
        }
        $data['title'] = _l('new_currency');
        $data['modal_subview'] = $this->load->view('saas/settings/new_currency', $data, FALSE);
        $this->load->view('saas/_layout_modal', $data);
    }

    public function system_update()
    {
        $data['active'] = 'system_update';
        $data['title'] = _l('saas_settings') . ' - ' . _l($data['active']);
        if (!extension_loaded('curl')) {
            $data['update_errors'][] = 'CURL Extension not enabled';
            $data['latest_version'] = 0;
            $data['update_info'] = json_decode("");
        } else {
            $data['update_info'] = $this->admin_model->get_update_info();
            if (strpos($data['update_info'], 'Curl Error -') !== FALSE) {
                $data['update_errors'][] = $data['update_info'];
                $data['latest_version'] = 0;
                $data['update_info'] = json_decode("");
            } else {
                $data['update_info'] = json_decode($data['update_info']);
                $data['latest_version'] = $data['update_info']->latest_version;
                $data['update_errors'] = array();
            }
        }
        if (!extension_loaded('zip')) {
            $data['update_errors'][] = 'ZIP Extension not enabled';
        }

        $tmp_dir = get_temp_dir();
        if (!$tmp_dir || !is_writable($tmp_dir)) {
            $tmp_dir = app_temp_dir();
        }
        if (!is_writeable($tmp_dir)) {
            $data['update_errors'][] = "Temporary directory not writable - <b>$tmp_dir</b><br />Please contact your hosting provider make this directory writable. The directory needs to be writable for the update files.";
        }
        $data['current_version'] = $this->db->get('tbl_migrations')->row()->version;

        $data['all_tabs'] = $this->saas_model->get_all_tabs();
        $data['subview'] = $this->load->view('saas/settings/tab_view', $data, TRUE);
        $this->load->view('_layout_main', $data); //page load
    }

    public function moduleManagement()
    {
        $data['active_module'] = get_result('tbl_modules', array('for_company' => 'Yes'));
        $inactive_module = [array('module_name' => '<b>' . _l('inactive_module_here') . '</b>', 'module_id' => '', 'installed_version' => '1.0.1', 'active' => '1')];
        $data['inactive_module'] = array_merge($inactive_module, get_result('tbl_modules', array('for_company' => 'No', 'module_name !=' => 'saas'), 'array'));
        $data['active'] = 'moduleManagement';
        $data['title'] = _l('saas_settings') . ' - ' . _l($data['active']);
        $data['all_tabs'] = $this->saas_model->get_all_tabs();
        $data['subview'] = $this->load->view('saas/settings/tab_view', $data, TRUE);
        $this->load->view('_layout_main', $data); //page load
    }

    public function update_module()
    {
        $all_module = json_decode($this->input->post('all_active_module', true));
        if (!empty($all_module)) {
            foreach ($all_module as $module) {
                $data['for_company'] = 'Yes';
                $this->db->where('module_id', $module->id);
                $this->db->update('tbl_modules', $data);
            }
        }
        $all_module_inactive = json_decode($this->input->post('all_inactive_module', true));
        if (!empty($all_module_inactive)) {
            foreach ($all_module_inactive as $module) {
                if ($module->id != '') {
                    $data['for_company'] = 'No';
                    $this->db->where('module_id', $module->id);
                    $this->db->update('tbl_modules', $data);
                }
            }
        }
        $this->session->set_flashdata('message', lang('module_updated'));
        redirect('saas/settings/moduleManagement');
    }

    public function update_profile()
    {
        $data['title'] = _l('update_profile');
        $data['subview'] = $this->load->view('saas/settings/update_profile', $data, TRUE);
        $this->load->view('saas/_layout_main', $data);
    }

    public function profile_updated()
    {
        $user_id = get_staff_user_id();
        $profile_data = $this->settings_model->array_from_post(array('fullname', 'phone', 'language', 'locale'));

        if (!empty($_FILES['avatar']['name'])) {
            $val = $this->settings_model->uploadImage('avatar');
            $val == TRUE || redirect('saas/settings/update_profile');
            $profile_data['avatar'] = $val['path'];
        }

        $this->settings_model->_table_name = 'tbl_account_details';
        $this->settings_model->_primary_key = 'user_id';
        $this->settings_model->save($profile_data, $user_id);

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $user_id,
            'activity' => ('activity_update_profile'),
            'value1' => $profile_data['fullname'],
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        $client_id = $this->input->post('client_id', TRUE);
        if (!empty($client_id)) {
            $client_data = $this->settings_model->array_from_post(array('name', 'email', 'address'));
            $this->settings_model->_table_name = 'tbl_client';
            $this->settings_model->_primary_key = 'client_id';
            $this->settings_model->save($client_data, $client_id);
        }
        $type = "success";
        $message = _l('profile_updated');
        set_alert($type, $message);
        redirect('saas/settings/update_profile'); //redirect page
    }

    public function set_password()
    {
        $user_id = get_staff_user_id();
        $password = $this->hash($this->input->post('old_password', TRUE));
        $check_old_pass = $this->admin_model->check_by(array('password' => $password), 'tbl_users');
        $user_info = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
        if (!empty($check_old_pass)) {
            $new_password = $this->input->post('new_password', true);
            $confirm_password = $this->input->post('confirm_password', true);
            if ($new_password == $confirm_password) {
                $data['password'] = $this->hash($new_password);
                $this->settings_model->_table_name = 'tbl_users';
                $this->settings_model->_primary_key = 'user_id';
                $this->settings_model->save($data, $user_id);
                $type = "success";
                $message = _l('password_updated');
                $action = ('activity_password_update');
            } else {
                $type = "error";
                $message = _l('password_does_not_match');
                $action = ('activity_password_error');
            }
        } else {
            $type = "error";
            $message = _l('password_error');
            $action = ('activity_password_error');
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $user_id,
            'activity' => $action,
            'value1' => $user_info->username,
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        set_alert($type, $message);
        redirect('saas/settings/update_profile'); //redirect page
    }

    public function change_email()
    {
        $user_id = get_staff_user_id();
        $password = $this->hash($this->input->post('password', TRUE));
        $check_old_pass = $this->settings_model->check_by(array('password' => $password), 'tbl_users');
        $user_info = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
        if (!empty($check_old_pass)) {
            $new_email = $this->input->post('email', TRUE);
            if ($check_old_pass->email == $new_email) {
                $type = 'error';
                $message = _l('current_email');
                $action = _l('trying_update_email');
            } elseif ($this->is_email_available($new_email)) {
                $data = array(
                    'new_email' => $new_email,
                    'new_email_key' => md5(rand() . microtime()),
                );

                $this->settings_model->_table_name = 'tbl_users';
                $this->settings_model->_primary_key = 'user_id';
                $this->settings_model->save($data, $user_id);
                $data['user_id'] = $user_id;
                $this->send_email_change_email($new_email, $data);
                $type = "success";
                $message = _l('succesffuly_change_email');
                $action = _l('activity_updated_email');
            } else {
                $type = "error";
                $message = _l('duplicate_email');
                $action = ('trying_update_email');
            }
        } else {
            $type = "error";
            $message = _l('password_error');
            $action = ('trying_update_email');
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $user_id,
            'activity' => $action,
            'value1' => $user_info->email,
            'value2' => $new_email,
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        set_alert($type, $message);
        redirect('saas/settings/update_profile'); //redirect page
    }

    function send_email_change_email($email, $data)
    {
        $email_template = $this->settings_model->check_by(array('email_group' => 'change_email'), 'tbl_email_templates');
        $message = $email_template->template_body;
        $subject = $email_template->subject;

        $email_key = str_replace("{NEW_EMAIL_KEY_URL}", base_url() . 'login/reset_email/' . $data['user_id'] . '/' . $data['new_email_key'], $message);
        $new_email = str_replace("{NEW_EMAIL}", $data['new_email'], $email_key);
        $site_url = str_replace("{SITE_URL}", base_url(), $new_email);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $site_url);

        $params['recipient'] = $email;

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;

        $params['resourceed_file'] = '';
        $this->settings_model->send_email($params);
    }

    function is_email_available($email)
    {

        $this->db->select('1', FALSE);
        $this->db->where('LOWER(email)=', strtolower($email));
        $this->db->or_where('LOWER(new_email)=', strtolower($email));
        $query = $this->db->get('tbl_users');
        return $query->num_rows() == 0;
    }

    public function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

    public function change_username()
    {
        $user_id = get_staff_user_id();
        $password = $this->hash($this->input->post('password', TRUE));
        $check_old_pass = $this->admin_model->check_by(array('password' => $password), 'tbl_users');
        $user_info = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
        if (!empty($check_old_pass)) {
            $data['username'] = $this->input->post('username');
            $this->settings_model->_table_name = 'tbl_users';
            $this->settings_model->_primary_key = 'user_id';
            $this->settings_model->save($data, $user_id);
            $type = "success";
            $message = _l('username_updated');
            $action = ('activity_username_updated');
        } else {
            $type = "error";
            $message = _l('password_error');
            $action = ('username_changed_error');
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $user_id,
            'activity' => $action,
            'value1' => $user_info->username,
            'value2' => $this->input->post('username'),
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        set_alert($type, $message);
        redirect('saas/settings/update_profile'); //redirect page
    }

    public function create_sample_database()
    {
        $this->saas_model->create_sample_database();
        $this->session->set_flashdata('message', lang('sample_database_created'));
        redirect('saas/settings/index/system_settings');
    }

    public function login_as_sample_company()
    {
        $this->saas_model->login_as_sample_company();
        redirect('saas/dashboard');
    }


}