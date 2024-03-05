<?php defined('BASEPATH') or exit('No direct script access allowed');

function is_super_admin($staffid = '')
{
    /**
     * Checking for current user?
     */
    if (!is_numeric($staffid)) {
        if (isset($GLOBALS['current_user'])) {
            // check if current user admin === 1 and role === 4 (super admin)
            return $GLOBALS['current_user']->admin == 1 && $GLOBALS['current_user']->role == 4;
        }
        $staffid = get_staff_user_id();
    }

    $CI = &get_instance();
    if ($cache = $CI->app_object_cache->get('is-super-admin-' . $staffid)) {
        return $cache === 'yes';
    }

    $CI->db->select('1')
        ->where('admin', 1)
        ->where('role', 4)
        ->where('staffid', $staffid);

    $result = $CI->db->count_all_results(db_prefix() . 'staff') > 0 ? true : false;

    $CI->app_object_cache->add('is-super-admin-' . $staffid, $result ? 'yes' : 'no');

    return is_super_admin($staffid);
}

function all_super_admins()
{
    $CI = &get_instance();
    $CI->db->select('staffid')
        ->where('admin', 1)
        ->where('role', 4);

    $result = $CI->db->get(db_prefix() . 'staff')->result_array();
    $staffids = [];
    foreach ($result as $row) {
        $staffids[] = $row['staffid'];
    }
    return $staffids;
}

function is_super_admin_login($staffid)
{
    $CI = &get_instance();
    $CI->db->select('1')
        ->where('admin', 1)
        ->where('role', 4)
        ->where('staffid', $staffid);

    $result = $CI->db->count_all_results(db_prefix() . 'staff') > 0 ? true : false;
    return $result;
}

function saas_url($url = null)
{
    $saasURI = 'saas';

    if ($url == '' || $url == '/') {
        if ($url == '/') {
            $url = '';
        }

        return site_url($saasURI) . '/';
    }

    return site_url($saasURI . '/' . $url);
}

function init_saas_head($aside = true)
{
    $CI = &get_instance();
    $CI->load->view('saas/includes/head');
    $CI->load->view('saas/includes/header', ['startedTimers' => $CI->misc_model->get_staff_started_timers()]);

    if ($aside == true) {
        $CI->load->view('saas/includes/aside');
    }
}

function saas_access()
{
    if (!super_admin_access()) {
        $CI = &get_instance();
        $CI->session->set_flashdata('error', _l('access_denied'));
        redirect('admin/dashboard');
    }
}

/**
 * Is affiliate logged in
 * @return boolean
 */
function is_affiliate_logged_in()
{
    return get_instance()->session->has_userdata('affiliate_user_id');
}


function is_affiliate($staffid = '')
{
    saas_init();
    $CI = &get_instance();
    $CI->old_db = config_db(true, true);
    $user_id = $CI->session->userdata('affiliate_user_id');
    if ($cache = $CI->app_object_cache->get('is-affiliate-' . $user_id)) {
        return $cache === 'yes';
    }
    $CI->old_db->select('1')
        ->where('activated', 1)
        ->where('is_verified', 1)
        ->where('user_id', $user_id);
    $result = $CI->old_db->count_all_results('tbl_saas_affiliate_users') > 0 ? true : false;
    $CI->app_object_cache->add('is-affiliate-' . $staffid, $result ? 'yes' : 'no');
    return $result;
}

function affiliate_access()
{
    if (!is_affiliate()) {
        $CI = &get_instance();
        $CI->session->set_flashdata('error', _l('access_denied'));
        redirect('affiliate-program');
    }
}

function get_affiliate_user_id()
{
    return get_instance()->session->userdata('affiliate_user_id');
}

function saas_packege_field($type = 'text', $info = '', $colLeft = '', $colRight = '')
{
    $all_field = get_order_by('tbl_saas_package_field', array('status' => 'active', 'field_type' => $type), 'order', 'asc');
    $html = null;
    if (!empty(super_admin_access())) {
        if (!empty($all_field)) {
            foreach ($all_field as $v_fileds) {
                $name = ($v_fileds->field_name);
                $additional_price = null;
                // check if $info->$name have value or value is 0 then set value
                // where i put 0 then showing error message Message: Attempt to read property
                //

                if (!empty($info->$name) || isset($info->$name) && $info->$name == 0) {
                    $value = $info->$name;
                } else {
                    $value = '';
                }

                $help_text = null;
                if ($v_fileds->field_type == 'text') {
                    $additional_price_name = 'additional_' . $name;
                    if (!empty($info->$name)) {
                        $additional_price = $info->$additional_price_name;
                    }
                    $html .= '<div class="col-xs-6"><div class="form-group">
                <label class="' . $colLeft . ' control-label">' . _l($v_fileds->field_label) . '  ' . $help_text . '</label>
                <div class="' . $colRight . ' ms-2">
                <div class="input-group">
                <input type="text" name="' . $v_fileds->field_name . '" class="form-control"  value="' . $value . '">
                <div class="input-group-addon">
                <div class="checkbox">                  
                   <input
                    title="' . _l('additional_price_help', _l($v_fileds->field_label)) . '"
                    data-toggle="tooltip"
                    data-placement="top"
                    data-name="' . $additional_price_name . '"
                    class="additional_price"
                    type="checkbox"  value="Yes" name="' . $additional_price_name . '_checked" ' . (!empty($additional_price) ? 'checked' : '') . ' ' . '>
                   <label></label>                                   
                </div>
                </div>              
                <input type="number"               
                ' . (!empty($additional_price) ? '' : 'disabled') . '
                placeholder="' . _l('additional_price_placeholder', _l($v_fileds->field_label)) . '"              
                 name="' . $additional_price_name . '" style="border-left:0" class="form-control border-left-0 bs-0"  value="' . $additional_price . '">               
                </div>
                <small class="text-muted" style="font-size: 0.6rem">' . $v_fileds->help_text . '</small>
                </div>
                </div>
                </div>';
                } else if ($v_fileds->field_type == 'email') {
                    $html .= '<div class="form-group">
                <label class="' . $colLeft . ' control-label">' . _l($v_fileds->field_label) . '  ' . $help_text . '</label>
                <div class="' . $colRight . '">
                <input type="email" name="' . $name . '" class="form-control" value="' . $value . '">
                </div>
                </div>';
                } else if ($v_fileds->field_type == 'textarea') {

                    $html .= '<div class="form-group">
                <label class="' . $colLeft . ' control-label">' . _l($v_fileds->field_label) . '  ' . $help_text . '</label>
                <div class="' . $colRight . '">
                <textarea name="' . $name . '" class="form-control">' . $value . '</textarea>
                </div>
                </div>';
                } else if ($v_fileds->field_type == 'dropdown') {
                    $html .= '<div class="form-group">
                <label class="' . $colLeft . ' control-label">' . _l($v_fileds->field_label) . '  ' . $help_text . '</label>
                <div class="' . $colRight . '">
                <select name="' . $name . '" class="form-control select_box" style="width:100%">
                ' . dropdownField($v_fileds->default_value, $value) . '

                </select>
                </div>
                </div>';
                } else if ($v_fileds->field_type == 'date') {
                    $html .= '<div class="form-group">
                <label class="' . $colLeft . ' control-label">' . _l($v_fileds->field_label) . '  ' . $help_text . '</label>
                <div class="' . $colRight . '">
                <div class="input-group">
                <input type="text" name="' . $name . '" class="form-control datepicker" value="' . (!empty($value) ? $value : date('Y-m-d')) . '">
                <div class="input-group-addon">
                <a href="#"><i class="fa fa-calendar"></i></a>
                </div>
                </div>
                </div>
                </div>';
                } else if ($v_fileds->field_type == 'checkbox') {
                    $html .= '<div class="col-xs-6"><div class="form-group">
<label>' . _l($v_fileds->field_label) . '  ' . $help_text . '</label>               
                <div class="' . $colRight . '">';
                    $html .= '<div class="material-switch tw-mt-2">                  
                   <input type="checkbox" id="ext_url" value="Yes" name="' . $name . '" ' . (!empty($value) && $value == 'Yes' ? 'checked' : '') . ' ' . '>                 
                   <label for="ext_url" class="label-success"></label>
                </div></div>';
                    $html .= '</div></div>';
                } else if ($v_fileds->field_type == 'numeric') {

                    $html .= '<div class="form-group">
                <label class="' . $colLeft . ' control-label">' . _l($v_fileds->field_label) . '  ' . $help_text . '</label>
                <div class="' . $colRight . '">
                <input type="number" name="' . $name . '" class="form-control"  value="' . $value . '">
                </div>
                </div>';
                }
            }
        }
    }
    return $html;
}

function saas_packege_list($info, $limit = null, $front = null): string
{
    $all_field = get_old_order_by('tbl_saas_package_field', array('status' => 'active'), 'order', 'asc', $limit);
    $allowed_payment_modes = (!empty($info->allowed_payment_modes) ? unserialize($info->allowed_payment_modes) : array());
    $all_module = (!empty($info->modules) ? unserialize($info->modules) : array());
    $all_themes = (!empty($info->allowed_themes) ? unserialize($info->allowed_themes) : array());


    $iconOk = '<i class="fa fa-check pricing_check"></i>';
    $iconNo = '<i class="fa fa-times pricing_check"></i><del>';
    $liClass = 'packaging-feature';
    if (!empty($front)) {
        $iconOk = '<span class="text-primary h5 me-2"><i class="uil uil-check-circle align-middle"></i></span>';
        $iconNo = '<span class="text-danger h5 me-2"><i class="uil uil-times-circle align-middle"></i></span><del>';
        $liClass = 'h6 text-muted mb-0';
    }

    $html = '';

    if (!empty($all_field)) {
        foreach ($all_field as $v_fileds) {
            $name = $v_fileds->field_name;
            $value = $info->$name;
            $html .= '<li class="' . $liClass . '">';
            if ($v_fileds->field_type == 'text') {
                if (!empty($value) && $value != 0) {
                    $html .= $iconOk . $value . ' ' . _l($v_fileds->field_label);
                } elseif (is_numeric($value) && $value == 0) {
                    $html .= $iconOk . _l('unlimited') . ' ' . _l($v_fileds->field_label);
                } else {
                    $html .= $iconNo . _l($v_fileds->field_label) . '</del>';
                }
            } else if ($v_fileds->field_type == 'textarea') {
            } else if ($v_fileds->field_type == 'dropdown') {
            } else if ($v_fileds->field_type == 'date') {
            } else if ($v_fileds->field_type == 'checkbox') {
                if (!empty($value) && $value == 'Yes') {
                    $html .= $iconOk . _l($v_fileds->field_label);
                } else {
                    $html .= $iconNo . _l($v_fileds->field_label) . '</del>';
                }
            } else if ($v_fileds->field_type == 'numeric') {
            }
            $html .= '</li>';
        }
    }
    if (!empty($all_module)) {
        $html .= '<li class="' . $liClass . '">';
        $html .= $iconOk . count($all_module) . ' ' . _l('modules') . '<span class="text-danger"> ' . _l('included') . '</span>';
        // show all modules with padding left
        if (empty($limit)) {
            $html .= '<ul class="list-unstyled">';
            foreach ($all_module as $sl => $module) {
                $module_title = moduleTitle($module);
                $html .= '<li class="' . $liClass . '" style="margin-left: 40px">';
                $html .= $sl + 1 . '. ' . $module_title;
                $html .= '</li>';
            }
            $html .= '</ul>';
        }

        $html .= '</li>';
    } else {
        $html .= '<li class="' . $liClass . '">';
        $html .= $iconNo . _l('modules') . '</del>';
        $html .= '</li>';
    }
    if (!empty($all_themes)) {
        $html .= '<li class="' . $liClass . '">';
        $html .= $iconOk . count($all_themes) . ' ' . _l('themes') . '<span class="text-danger"> ' . _l('included') . '</span>';
        if (empty($limit)) {
            $html .= '<ul class="list-unstyled">';
            foreach ($all_themes as $sl => $theme) {
                $html .= '<li class="' . $liClass . '" style="margin-left: 40px">';
                $html .= $sl + 1 . '. ' . ucfirst($theme);
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
    } else {
        $html .= '<li class="' . $liClass . '">';
        $html .= $iconNo . _l('themes') . '</del>';
        $html .= '</li>';
    }

    if (!empty($allowed_payment_modes)) {
        $html .= '<li class="' . $liClass . '">';
        $html .= $iconOk . count($allowed_payment_modes) . ' ' . _l('payment_modes');
        $html .= '</li>';
    } else {
        $html .= '<li class="' . $liClass . '">';
        $html .= $iconNo . _l('payment_modes') . '</del>';
        $html .= '</li>';
    }
    return $html;
}

function default_currency()
{
    return get_base_currency()->name;
}

function is_payment_mode_allowed_for_saas($id)
{
    // check currencies
    $currencies = explode(',', get_option('paymentmethod_' . $id . '_currencies'));
    foreach ($currencies as $currency) {
        $currency = trim($currency);
        if (mb_strtoupper($currency) == mb_strtoupper(get_base_currency()->name)) {
            return true;
        }
    }
    return false;
}

function display_money($amount, $currency = null)
{
    return app_format_money($amount, get_base_currency());
}

function package_price($package_info, $style = null)
{
    $html = '';
    $divStart = '<h3 class="packaging-title" >';
    $divEnd = '</h3>';
    if ($style == 'row') {
        $divStart = '';
        $divEnd = '<br/>';
    }
    $active_frequency = get_active_frequency(true);
    if (!empty($active_frequency['monthly_price'])) {
        if ($package_info->monthly_price == 0) {
            $html .= $divStart . _l('free_for_month') . $divEnd;
        } else {
            $html .= $divStart . display_money($package_info->monthly_price, default_currency()) . ' / ' . _l('month') . $divEnd;
        }
    }
    if (!empty($active_frequency['yearly_price'])) {
        $html .= $divStart . display_money($package_info->yearly_price, default_currency()) . ' / ' . _l('year') . $divEnd;
    }
    if (!empty($active_frequency['lifetime_price'])) {
        $html .= $divStart . display_money($package_info->lifetime_price, default_currency()) . ' / ' . _l('lifetime') . $divEnd;
    }
    return $html;
}


function super_admin_access($staffid = '')
{
    saas_init();
    if (!is_numeric($staffid)) {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->role === '4';
        }

        $staffid = get_staff_user_id();
    }

    $CI = &get_instance();

    if ($cache = $CI->app_object_cache->get('is-super-admin-' . $staffid)) {
        return $cache === 'yes';
    }

    $CI->db->select('1')
        ->where('role', 4)
        ->where('staffid', $staffid);
    $result = $CI->db->count_all_results(db_prefix() . 'staff') > 0 ? true : false;
    $CI->app_object_cache->add('is-super-admin-' . $staffid, $result ? 'yes' : 'no');

    return super_admin_access($staffid);
}

function get_old_order_by($tbl, $where = null, $order_by = null, $ASC = null, $limit = null)
{
    if (!empty($tbl)) {
        $CI = &get_instance();
        $CI->old_db = config_db(true, true);
        $CI->old_db->from($tbl);
        if (!empty($where) && $where != 0) {
            $CI->old_db->where($where);
        }
        if (!empty($ASC)) {
            $order = 'ASC';
        } else {
            $order = 'DESC';
        }
        $CI->old_db->order_by($order_by, $order);
        if (!empty($limit)) {
            $CI->old_db->limit($limit);
        }
        $query_result = $CI->old_db->get();
        $result = $query_result->result();
        return $result;
    }
}


if (!function_exists('get_old_data')) {
    function get_old_data($table, $where, $result = null)
    {
        $CI = &get_instance();
        $CI->old_db = config_db(true, true);
        $query = $CI->old_db->where($where)->get($table);
        if ($query->num_rows() > 0) {
            if (!empty($result)) {
                $row = $query->result();
            } else {
                $row = $query->row();
            }
            return $row;
        }
    }
}


function old_any_field($tbl, $where = array(), $field = false)
{
    $CI = &get_instance();
    $CI->db->where($where);
    $query = $CI->db->get($tbl);
    if ($query->num_rows() > 0) {
        if ($field) {
            return $query->row()->$field;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function ConfigItems($name)
{
    $result = get_old_result(db_prefix() . 'options', array('name' => $name), false);
    if (!empty($result)) {
        return $result->value;
    }
}

function trial_period($subs)
{
    if ($subs->trial_period != 0) {
        $time = date('Y-m-d H:i', strtotime($subs->expired_date));
        $to_date = strtotime($time); //Future date.
        $cur_date = strtotime(date('Y-m-d H:i'));
        $timeleft = $to_date - $cur_date;
        $daysleft = round((($timeleft / 24) / 60) / 60);
        $days = ($daysleft);
        return $days;
    } else {
        return false;
    }
}

function running_period($subs = null)
{
    if ($subs->trial_period == 0) {
        $time = date('Y-m-d H:i', strtotime($subs->expired_date));
        $to_date = strtotime($time); //Future date.
        $cur_date = strtotime(date('Y-m-d H:i'));
        $timeleft = $to_date - $cur_date;
        $daysleft = round((($timeleft / 24) / 60) / 60);
        return $daysleft;
    } else {
        return false;
    }
}

function get_old_result($tbl, $where = array(), $result = true)
{
    $CI = &get_instance();
    $CI->old_db = config_db(NULL, true);

    $CI->old_db->where($where);
    $query = $CI->old_db->get($tbl);
    if ($result) {
        return $query->result();
    } else {
        return $query->row();
    }
}

/**
 * @throws Exception
 */
function all_company_url($subdomain)
{
    // check wildcard  is created or not
    $url = [];
    $isWilcard = ConfigItems('saas_server_wildcard');
    if ($isWilcard === 'on' || $isWilcard === 'Yes') { // its means subdomain
        $url['subdomain'] = companyUrl($subdomain);
    }

    // check custom domain is created or not
    $companyInfo = get_company_subscription($subdomain);
    if (!empty($companyInfo->domain_url)) {
        $url['custom_domain'] = prep_url_custom_domain($companyInfo->domain_url);
    }
    // the current ip is local or not
    $ip = ($_SERVER['REMOTE_ADDR']);
    if ($ip == '::1' || $ip == '127:0:0:1') {
        $url['url'] = companyBaseUrl(true) . $subdomain . '/s/';
    } else {
        $url['url'] = companyBaseUrl(true) . $subdomain . '/s/';
    }
    return $url;

}

function prep_url_custom_domain($url)
{
    $url = prep_url($url);

    if (str_starts_with($url, 'http://') && is_https())
        $url = str_ireplace('http://', 'https://', $url);
    // add slash at the end of url if not exist
    if (substr($url, -1) != '/') {
        $url = $url . '/';
    }

    return $url;
}


function companyUrl($subdomain = null)
{
    $base_url = '';
    if (!empty($subdomain)) {
        $default_url = companyBaseUrl();
        // check http or https and www exist in default_url then remove it
        if (strpos($default_url, 'http://') !== false) {
            $base_url = str_replace('http://', '', $default_url);
        } elseif (strpos($default_url, 'https://') !== false) {
            $base_url = str_replace('https://', '', $default_url);
        } else {
            $base_url = $default_url;
        }

        if (strpos($base_url, 'www.') !== false) {
            $base_url = str_replace('www.', '', $base_url);
        }
        $scheme = parse_url($default_url, PHP_URL_SCHEME);
        if (empty($scheme)) {
            $scheme = 'http';
        }
        $base_url = $scheme . '://' . $subdomain . '.' . $base_url;
    } else {
        $base_url = guess_base_url();
    }
    return $base_url;
}

if (!function_exists('config_db')) {
    function config_db($db_name = null, $default_database = null)
    {
        $CI = &get_instance();
        // load config.php from module
        $config_db = $CI->config->config['config_db'];
        if (!empty($default_database)) {
            $database_name = APP_DB_NAME;
            $database_user = APP_DB_USERNAME;
            $database_password = APP_DB_PASSWORD;
            $database_host = APP_DB_HOSTNAME;
        } else {
            $database_name = $db_name;
            $database_user = $config_db['username'];
            $database_password = $config_db['password'];
            $database_host = $config_db['hostname'];

            if (get_option('saas_server') == 'mysql') {
                $db_mysql_host = get_option('saas_mysql_host');
                $db_mysql_username = get_option('saas_mysql_username');
                $db_mysql_password = decrypt(get_option('saas_mysql_password'));
                $db_mysql_port = get_option('saas_mysql_port');

                $database_host = $db_mysql_host . ':' . $db_mysql_port;
                $database_user = $db_mysql_username;
                $database_password = $db_mysql_password;
            }
        }
        $config_db['username'] = $database_user; // set database username
        $config_db['password'] = $database_password; // set database password
        $config_db['hostname'] = $database_host; // set database host
        $config_db['database'] = $database_name; // set database name
        $database_exist = $CI->db->query("SHOW DATABASES WHERE `database` = '" . $database_name . "'")->num_rows();

        if (!empty($database_exist)) {
            $CI->new_db = $CI->load->database($config_db, true);
            // set sql mode for database
            $CI->new_db->query("SET SESSION sql_mode = ''");
            $CI->new_db->query("SET sql_mode = ''");
            return $CI->new_db;
        }
        return false;
    }
}

if (!function_exists('company_db_name')) {
    function company_db_name($domain = null)
    {
        $companyInfo = get_old_data('tbl_saas_companies', ['status' => 'running', 'domain' => $domain]);
        if (!empty($companyInfo)) {
            return $companyInfo->db_name;
        }
        return false;
    }
}

/**
 * @throws Exception
 */
function is_company_active()
{
    $subdomain = subdomain();
    if (!empty($subdomain)) {
        $companyInfo = get_old_data('tbl_saas_companies', ['status' => 'running', 'domain' => $subdomain]);
        if (!empty($companyInfo)) {
            return true;
        }
    }
    return false;
}

if (!function_exists('is_complete_setup') && function_exists('is_subdomain') && function_exists('subdomain')) {
    /**
     * @throws Exception
     */
    function is_complete_setup()
    {
        $sub_domain = subdomain();
        if (!empty($sub_domain)) {
            if ($sub_domain == 'perfect_demo_sample') {
                return get_option('created_sample_database');
            }
            $domain_available = get_old_data('tbl_saas_companies', ['domain' => $sub_domain]);
            if (empty($domain_available)) {
                redirect('domain-not-available');
            }
            if ($domain_available->status == 'pending') {
                redirect(base_url('setup'));
            } else {
                return company_db_name($sub_domain);
            }
        }
    }
}
if (!function_exists('saas_init')) {
    /**
     * @throws Exception
     */
    function saas_init($db_name = null)
    {
        $CI = &get_instance();
        if (function_exists('is_complete_setup')) {
            $db_name = is_complete_setup();
        }
        // get session data
        $db_name_s = $CI->session->userdata('db_name');
        if (empty($db_name)) {
            $db_name = $db_name_s;
            // set session data
            $CI->session->set_userdata('db_name', $db_name);
        }
        if (!empty($db_name)) {
            $CI->db = config_db($db_name);
        }

    }
}
function saas_before_breadcrumb()
{
    $html = '';
    if (!empty(subdomain())) {
        $subs = get_company_subscription(null, 'running');
        $result = is_account_running($subs, true);
        if (!empty($result['trial'])) {
            $trial_period = $result['trial'];
            $type = 'trial';
            $b_text = _l('you_are_using_trial_version', $subs->package_name) . ' ' . $trial_period . ' ' . _l('days');
        } else {
            $trial_period = $result['running'];
            $type = 'running';
            $b_text = _l('your_pricing_plan_will_expired', $subs->package_name) . ' ' . $trial_period . ' ' . _l('days');
        }
        if ($trial_period <= 0) {
            redirect('upgrade');
        }
        if ($type == 'trial' || $trial_period < 3) {
            $html .= '<span class="text-sm text-danger">' . $b_text . '</span>';
            $html .= '<strong class=""><a href="' . base_url('checkoutPayment') . '"> ' . _l('upgrade') . '</a></strong>';
        }
    }
    echo $html;
}

/**
 * @throws Exception
 */
function saas_before_staff_login()
{
    $CI = &get_instance();
    $account = $CI->input->post('account', true);
    if (empty($account)) {
        $account = is_subdomain();
    }
    $account = trim($account);
    if (empty($account)) {
        return true;
    }
    // check domain is exits or not in database tbl_saas_companies
    $companyInfo = get_old_data('tbl_saas_companies', ['domain' => $account]);
    if (!empty($companyInfo)) {
        if ($companyInfo->status === 'running') {
            $db_name = $companyInfo->db_name;
            // update database into config
            $CI->db = config_db($db_name);
            // update session
            $CI->session->set_userdata('saas_company_id', $companyInfo->id);
            $CI->session->set_userdata('domain', $account);
            $CI->session->set_userdata('db_name', $db_name);
            return true;
        } else {
            set_alert('danger', _l('account_is_not_active'));
            redirect(admin_url('authentication'));
        }
    } else {
        set_alert('danger', _l('account_not_found'));
        redirect(admin_url('authentication'));
    }
}


/**
 * @throws Exception
 */
function get_company_subscription($domain = null, $status = null, $order_by = null, $limit = null, $group_by = null)
{
    if (empty($domain)) {
        $domain = subdomain();
    }

    $CI = &get_instance();
    $CI->old_db = config_db(NULL, true);

    $CI->old_db->select('tbl_saas_companies.*,tbl_saas_companies_history.id as company_history_id,tbl_saas_companies_history.*');
    $CI->old_db->from('tbl_saas_companies');
    $CI->old_db->join('tbl_saas_companies_history', 'tbl_saas_companies.id = tbl_saas_companies_history.companies_id', 'left');

    if (!empty($status)) {
        $CI->old_db->where('tbl_saas_companies.status', $status);
    }
    if (!empty($order_by)) {
        $CI->old_db->order_by('tbl_saas_companies.id', 'desc');
        if (!empty($limit)) {
            $CI->old_db->limit($limit);
        }
        // group by tbl_saas_companies.id
        if (!empty($group_by)) {
            $CI->old_db->group_by('tbl_saas_companies.id');
        }

        $type = 'result';
    } else {
        $CI->old_db->where('tbl_saas_companies.domain', $domain);
        $CI->old_db->where('tbl_saas_companies_history.active', 1);
        $type = 'row';
    }
    $query = $CI->old_db->get();
    $result = $query->$type();
    return $result;
}

function get_company_id($clientid = null)
{
    $CI = &get_instance();
    $CI->old_db = config_db(NULL, true);

    if (!is_numeric($clientid)) {
        $clientid = get_client_user_id();
    }
    $CI->old_db->select('saas_company_id');
    $CI->old_db->from(db_prefix() . 'clients');
    $CI->old_db->where('userid', $clientid);
    $client = $CI->old_db->get()->row();
    if (!empty($client)) {
        return $client->saas_company_id;
    }
    return false;
}

function get_saas_client_id($saas_company_id = null)
{
    $CI = &get_instance();
    $CI->old_db = config_db(NULL, true);
    if (!is_numeric($saas_company_id)) {
        $saas_company_id = get_company_id();
    }
    $CI->old_db->select('userid');
    $CI->old_db->from(db_prefix() . 'clients');
    $CI->old_db->where('saas_company_id', $saas_company_id);
    $client = $CI->old_db->get()->row();
    if (!empty($client)) {
        return $client->userid;
    }
    return false;
}

/**
 * @throws Exception
 */
function get_company_info()
{
    if (!empty(subdomain())) {
        $company_info = get_company_subscription();
    } else {
        $company_info = get_company_subscription_by_id();
    }
    return $company_info;
}

function get_company_subscription_by_id($company_id = null, $status = null, $order_by = null, $limit = null, $group_by = null)
{
    if (empty($company_id)) {
        $company_id = get_company_id();
    }
    $CI = &get_instance();
    $CI->old_db = config_db(NULL, true);

    $CI->old_db->select('tbl_saas_companies.*,tbl_saas_companies_history.id as company_history_id,tbl_saas_companies_history.*');
    $CI->old_db->from('tbl_saas_companies');
    $CI->old_db->join('tbl_saas_companies_history', 'tbl_saas_companies.id = tbl_saas_companies_history.companies_id', 'left');

    if (!empty($status)) {
        $CI->old_db->where('tbl_saas_companies.status', $status);
    }
    if (!empty($order_by)) {
        $CI->old_db->order_by('tbl_saas_companies.id', 'desc');
        if (!empty($limit)) {
            $CI->old_db->limit($limit);
        }
        // group by tbl_saas_companies.id
        if (!empty($group_by)) {
            $CI->old_db->group_by('tbl_saas_companies.id');
        }

        $type = 'result';
    } else {
        $CI->old_db->where('tbl_saas_companies.id', $company_id);
        $CI->old_db->where('tbl_saas_companies_history.active', 1);
        $type = 'row';
    }
    $query = $CI->old_db->get();
    $result = $query->$type();
    return $result;
}

function is_account_running($subs, $detail = null)
{
    $result = array();
    $total_days = 0;
    if ($subs->trial_period != 0) {
        $total_days = $result['trial'] = trial_period($subs);
    } elseif ($subs->trial_period == 0) {
        $total_days = $result['running'] = running_period($subs);
    }
    if (!empty($detail)) {
        return $result;
    } else {
        return $total_days;
    }
}

/**
 * @throws Exception
 */
function saas_more_exception_uri($except_menu)
{
    $subdomain = subdomain();
    if (!empty($subdomain)) {
        $subs = get_company_subscription($subdomain);
        if (!empty($subs)) {
            if ($subs->status == 'running' && $subs->maintenance_mode != 'Yes') {
                $total_days = is_account_running($subs);
                // check the $total_days is not negative or zero and must be greater than 0
                if ($total_days <= 0) {
                    redirect('upgrade');
                }
            } else {
                if ($subs->maintenance_mode == 'Yes') {
                    $maintenance_message = $subs->maintenance_mode_message;
                }
                $account_status = $subs->status;
                include_once module_dir_path('saas') . 'views/maintenance.php';
                die();
            }
            $yesNo = array(
                'attendance' => 'admin/attendance',
                'payroll' => 'admin/payroll',
                'leave_management' => 'admin/leave_management',
                'performance' => 'admin/performance',
                'training' => 'admin/training',
                'calendar' => 'admin/calendar',
                'mailbox' => 'admin/mailbox',
                'tickets' => array('admin/tickets', 'admin/report/tickets_report'),
                'filemanager' => 'admin/filemanager',
                'stock_manager' => array('admin/items', 'admin/supplier', 'admin/purchase', 'admin/return_stock', 'admin/warehouse', 'admin/all_payments'),
                'recruitment' => 'admin/job_circular',
                'reports' => 'admin/report',
                'live_chat' => 'admin/conversations',
            );
            foreach ($yesNo as $key => $value) {
                if ($subs->$key == 'No') {
                    // check if is array the assign to $except_menu and unset the key
                    if (is_array($value)) {
                        $except_menu = array_merge($except_menu, $value);
                        unset($yesNo[$key]);
                    } else {
                        $except_menu[] = $value;
                    }
                }
            }
            $numeric = array(
                'employee_no' => 'admin/users',
                'client_no' => 'admin/client',
                'project_no' => 'admin/projects',
                'tasks_no' => 'admin/tasks',
                'invoice_no' => 'admin/invoice',
                'leads_no' => 'admin/leads',
                'transactions' => 'admin/transactions',
                'bank_account_no' => 'admin/account',
            );
            foreach ($numeric as $nkey => $nvalue) {
                if (!is_numeric($subs->$nkey)) {
                    $except_menu[] = $nvalue;
                }
            }
        }
    } elseif (!empty(super_admin_access())) {
        $CI = &get_instance();
        $uri = $CI->uri->segment(1);
        if ($uri != 'saas') {
            redirect('saas/dashboard');
        }
        $except_menu = array();
    }
    return $except_menu;
}

/**
 * @throws Exception
 */
function saas_sidebar_menu($menu)
{
    $subdomain = subdomain();
    if (!empty($subdomain)) {
        $subs = get_company_subscription($subdomain);
        if (empty($subs)) {
            return $menu;
        }
        $yesNo = array(
            'attendance' => array('attendance', 'time_history', 'timechange_request', 'attendance_report', 'mark_attendance'),
            'payroll' => array('payroll', 'salary_template', 'hourly_rate', 'manage_salary_details', 'employee_salary_list', 'make_payment', 'generate_payslip', 'payroll_summary', 'advance_salary', 'provident_fund', 'overtime', 'award'),
            'leave_management' => array('leave_management', 'leave_category'),
            'performance' => array('performance', 'performance_indicator', 'give_performance_appraisal', 'performance_report'),
            'training' => 'training',
            'calendar' => 'calendar',
            'mailbox' => 'mailbox',
            'tickets' => array('tickets', 'tickets_report'),
            'filemanager' => 'filemanager',
            'stock_manager' => array('admin/items', 'admin/supplier', 'admin/purchase', 'admin/return_stock', 'admin/warehouse', 'admin/all_payments'),
            'recruitment' => array('job_circular', 'jobs_posted', 'jobs_applications'),
            'reports' => array('report', 'tasks_assignment', 'bugs_assignment', 'project_report', 'account_statement', 'income_report', 'expense_report', 'income_expense', 'ledger', 'date_wise_report', 'all_income', 'all_expense', 'all_transaction', 'report_by_month', 'sales_report', 'tasks_report', 'bugs_report', 'tickets_report', 'client_report'),
            'live_chat' => 'private_chat',
        );
        $except_menu = array();
        foreach ($yesNo as $key => $value) {
            if ($subs->$key == 'No') {
                // check if is array the assign to $except_menu and unset the key
                if (is_array($value)) {
                    $except_menu = array_merge($except_menu, $value);
                    unset($yesNo[$key]);
                } else {
                    $except_menu[] = $value;
                }
            }
        }
        $numeric = array(
            'employee_no' => 'users',
            'client_no' => array('client', 'client_report'),
            'project_no' => array('projects', 'project_report', 'bugs_assignment', 'tasks_assignment'),
            'tasks_no' => array('tasks', 'tasks_report'),
            'invoice_no' => array('invoice', 'recurring_invoice', 'payments_received', 'sales_report', 'pos_sales'),
            'leads_no' => 'leads',
            'transactions' => array('transactions', 'expense', 'deposit', 'transfer', 'transactions_report', 'balance_sheet', 'transfer_report'),
            'bank_account_no' => 'bank_cash',
        );
        foreach ($numeric as $nkey => $nvalue) {
            if (!is_numeric($subs->$nkey)) {
                // check if is array the assign to $except_menu and unset the key
                if (is_array($nvalue)) {
                    $except_menu = array_merge($except_menu, $nvalue);
                    unset($numeric[$nkey]);
                } else {
                    $except_menu[] = $nvalue;
                }
            }
        }
        // check $except_menu is there into $menu->label
        foreach ($menu as $key => $value) {
            if (in_array($value->label, $except_menu)) {
                unset($menu[$key]);
            }
        }
        // add new menu into $menu as object
        $menu[] = (object)[
            'menu_id' => '36582503',
            'label' => 'billing',
            'link' => 'billing',
            'icon' => 'fa fa-money',
            'parent' => '0',
            'sort' => '1',
            'status' => '1',
            'time' => '2017-09-01 00:00:00',
        ];
    }
    return $menu;
}

/**
 * @throws Exception
 */
function saas_before_create($table)
{
    if ($table == 'tbl_activities') {
        return true;
    }
    $subs = get_company_subscription(null, 'running');
    if (!empty(subdomain()) && !empty($subs)) {
        $all_table = array(
            'tbl_users' => 'employee_no',
            'tbl_client' => 'client_no',
            'tbl_project' => 'project_no',
            'tbl_task' => 'tasks_no',
            'tbl_invoices' => 'invoice_no',
            'tbl_leads' => 'leads_no',
            'tbl_transactions' => 'transactions',
            'tbl_accounts' => 'bank_account_no',
        );
        if (in_array($table, array_keys($all_table))) {
            $field = $all_table[$table];
            $value = $subs->$field;
            $total_rows = total_rows($table);
            if (!is_numeric($value)) {
                set_alert('error', _l('you_can_not_add_this_is_not_included'));
                redirect('checkoutPayment');
            } elseif (is_numeric($value) && $value != 0) {
                if ($value <= $total_rows) {
                    set_alert('error', _l('you_can_not_add_more_please_upgrade_the_package'));
                    redirect('checkoutPayment');
                }
            } elseif (is_numeric($value) && $value == 0) {
                return true;
            }
        }
    }
}

/**
 * @throws Exception
 */
function saas_is_saas()
{
    $subdomain = subdomain();
    if (!empty($subdomain)) {
        return true;
    }
    return false;
}

/**
 * @throws Exception
 */
function saas_set_media_folder($data)
{
    $subdomain = subdomain();
    if (!empty($subdomain)) {
        $data = $data . '/' . $subdomain;
        total_disk_space($subdomain);
    }
    return $data;
}

/**
 * @throws Exception
 */
function saas_set_upload_path_by_type($path)
{
    $subdomain = subdomain();
    if (!empty($subdomain)) {
        $dir = $subdomain;
        $new_path = FCPATH . 'uploads/tenants/' . $dir;
        if (!file_exists($new_path)) {
            mkdir($new_path, 0755);
            fopen(rtrim($new_path, '/') . '/' . 'index.html', 'w');
        }
        $path = str_replace('uploads/', 'uploads/tenants/' . $subdomain . '/', $path);

        $result = total_disk_space($subdomain, true);

        if (!empty($result['total_space']) && $result['used_space'] >= $result['total_space']) {
            set_alert('warning', 'your have exceeded the disk space');
            redirect('checkoutPayment');
        }

    }
    return $path;
}

/**
 * @throws Exception
 */

function total_disk_space($subdomain, $return = false)
{

    if (!empty($subdomain)) {
        if (!empty($subdomain->companies_id)) {
            $subs = $subdomain;
            $subdomain = $subs->domain;
        } else {
            $subs = get_company_subscription(null, 'running');
        }

        $disk_space = 0;
        $total = 0;
        $available_space = 0;
        if (!empty($subs->disk_space)) {
            $disk_space = $subs->disk_space; // 1GB
            // get gb, mb, kb from string and convert to bytes
            preg_match('/(\d+)(\w+)/', $disk_space, $matches);
            $number = $matches[1];
            $character = $matches[2];
            if ($character == 'GB') {
                $disk_space = $number * 1024 * 1024 * 1024;
            } else if ($character == 'TB') {
                $disk_space = $number * 1024 * 1024 * 1024 * 1024;
            } elseif ($character == 'MB') {
                $disk_space = $number * 1024 * 1024;
            } elseif ($character == 'KB') {
                $disk_space = $number * 1024;
            }
            // get total size of folder
            $total = 0;
            $how_calculated = ConfigItems('saas_calculate_disk_space'); // both, media, upload
            if (empty($how_calculated)) {
                $how_calculated = 'both';
            }
            if ($how_calculated == 'both') {
                $total += get_folder_size(FCPATH . 'uploads/' . $subdomain);
                $total += get_folder_size(FCPATH . 'media/' . $subdomain);
            } elseif ($how_calculated == 'media') {
                $total += get_folder_size(FCPATH . 'media/' . $subdomain);
            } elseif ($how_calculated == 'upload') {
                $total += get_folder_size(FCPATH . 'uploads/' . $subdomain);
            }
            $available_space = convertSize($disk_space - $total);


            if (!empty($total) && $total >= $disk_space) {
                hooks()->add_filter('before_init_media', 'saas_before_init_media');
            }

            if (!empty($return)) {
                return array(
                    'used_space' => $total,
                    'used_space_text' => convertSize($total),
                    'total_space' => $disk_space,
                    'total_space_text' => convertSize($disk_space),
                    'available_space' => $available_space,
                );
            }
        }
    }


    return true;
}

function get_folder_size($data): int
{
    $size = 0;
    foreach (glob(rtrim($data, '/') . '/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : get_folder_size($each);
    }
    // also count the size of the folder of media
    return $size;
}


function convertSize($bytes, $decimalPoint = 2)
{
    $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    $convertedSize = 0;
    if ($bytes > 0) {
        $index = floor(log($bytes, 1024));
        $convertedSize = round($bytes / pow(1024, $index), $decimalPoint) . ' ' . $unit[$index];
    }
    return $convertedSize;
}

function convertGBToBytes($disk_space)
{
    // convert gb to bytes // like 1GB to 1073741824 bytes
    // check it gb, mb, kb, tb and convert to bytes
    preg_match('/(\d+)(\w+)/', $disk_space, $matches);
    $number = $matches[1];
    $character = $matches[2];
    if ($character == 'GB') {
        $disk_space = $number * 1024 * 1024 * 1024;
    } else if ($character == 'TB') {
        $disk_space = $number * 1024 * 1024 * 1024 * 1024;
    } elseif ($character == 'MB') {
        $disk_space = $number * 1024 * 1024;
    } elseif ($character == 'KB') {
        $disk_space = $number * 1024;
    }
    return $disk_space;

}

function saas_before_init_media($options): array
{
    $root_options = $options['roots'];
    // check root options is array or not
    // if array then add disabled  with array
    if (is_array($root_options)) {
        foreach ($root_options as $key => $value) {
            $options['roots'][$key]['disabled'] = ['archive', 'chmod', 'duplicate', 'extract', 'file', 'get', 'mkdir', 'mkfile', 'paste', 'rename', 'resize', 'rm', 'upload', 'zipdl'];
        }
    } else {
        $options['roots']['disabled'] = ['archive', 'chmod', 'duplicate', 'extract', 'file', 'get', 'mkdir', 'mkfile', 'paste', 'rename', 'resize', 'rm', 'upload', 'zipdl'];
    }
    return $options;
}


function make_datatables($where = null, $where_in = null, $old = null)
{
    $CI = &get_instance();
    $CI->load->model('datatables');
    $CI->datatables->make_query();
    if (!empty($where)) {
        $CI->db->where($where);
    }
    if (!empty($where_in)) {
        $CI->db->where_in($where_in[0], $where_in[1]);
    }
    if ($_POST["length"] != -1) {
        $CI->db->limit($_POST['length'], $_POST['start']);
    }
    $query = $CI->db->get();
    return $query->result();
    // check the current query is ok or not in mysql


}

function render_table($data, $where = null, $where_in = null)
{

    $CI = &get_instance();
    $CI->load->model('datatables');
    $output = array(
        "draw" => intval($_POST["draw"]),
        "iTotalRecords" => $CI->datatables->get_all_data($where, $where_in),
        "iTotalDisplayRecords" => $CI->datatables->get_filtered_data($where, $where_in),
        "aaData" => $data
    );
    echo json_encode($output);
    exit();
}

function render_table_old($data, $where = null, $where_in = null)
{

    $CI = &get_instance();
    $CI->db = config_db(null, true);
    $CI->load->model('datatables');
    $output = array(
        "draw" => intval($_POST["draw"]),
        "iTotalRecords" => $CI->datatables->get_all_data($where, $where_in),
        "iTotalDisplayRecords" => $CI->datatables->get_filtered_data($where, $where_in),
        "aaData" => $data
    );
    echo json_encode($output);
    exit();
}

function get_any_field($table, $where, $table_field, $result = null)
{
    $CI = &get_instance();
    $query = $CI->db->select($table_field)->where($where)->get($table);
    if ($query->num_rows() > 0) {
        if (!empty($result)) {
            return $query->result_array();
        } else {
            $row = $query->row();
            return $row->$table_field;
        }
    }
}

function get_old_any_field($table, $where, $table_field, $result = null)
{
    $CI = &get_instance();
    $CI->old_db = config_db(true, true);
    $query = $CI->old_db->select($table_field)->where($where)->get($table);
    if ($query->num_rows() > 0) {
        if (!empty($result)) {
            return $query->result_array();
        } else {
            $row = $query->row();
            return $row->$table_field;
        }
    }
}

function get_row($table, $where, $fields = null)
{
    $CI = &get_instance();
    $query = $CI->db->where($where)->get($table);
    if ($query->num_rows() > 0) {
        $row = $query->row();
        if (!empty($fields)) {
            return $row->$fields;
        } else {
            return $row;
        }
    }
}

function btn_edit($uri)
{
    return anchor($uri, '<i class="fa fa-pencil-square"></i>', array('class' => "btn btn-primary btn-xs", 'title' => 'Edit', 'data-toggle' => 'tooltip', 'data-placement' => 'top'));
}

function btn_edit_modal($uri)
{
    return anchor($uri, '<span class="fa fa-pencil-square"></span>', array('class' => "btn btn-primary btn-xs", 'title' => 'Edit', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-toggle' => 'modal', 'data-target' => '#myModal'));
}

function btn_view($uri)
{
    return anchor($uri, '<span class="fa fa-list-alt"></span>', array('class' => "btn btn-info btn-xs", 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View'));
}

function btn_view_modal($uri)
{
    return anchor($uri, '<span class="fa fa-list-alt"></span>', array('class' => "btn btn-info btn-xs", 'title' => 'View', 'data-toggle' => 'modal', 'data-target' => '#myModal'));
}

function btn_delete($uri, $text = null, $icon = null)
{
    $icons = '<i class="fa fa-trash-o"></i>';
    $title = _l('delete');
    $btn = 'btn';
    if (!empty($text) && empty($icon)) {
        $icons = '';
        $title = $text;
        $btn = 'text';
    }
    if (!empty($icon) && empty($text)) {
        $title = '';
    }
    return anchor($uri, $icons . ' ' . $title, array(
        'class' => "btn $btn-danger btn-xs deleteBtn", 'title' => $text, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'onclick' => "return confirm('" . _l('delete_alert') . "');"
    ));
}

function get_result($tbl, $where = null, $type = null)
{
    $CI = &get_instance();
    $CI->db->select('*');
    $CI->db->from($tbl);
    if (!empty($where) && $where != 0) {
        $CI->db->where($where);
    }
    if (!empty($_POST["length"]) && $_POST["length"] != -1) {
        $CI->db->limit($_POST['length'], $_POST['start']);
    }
    $query_result = $CI->db->get();
    if (!empty($type) && $type == 'array') {
        $result = $query_result->result_array();
    } else if (!empty($type)) {
        $result = $query_result->row();
    } else {
        $result = $query_result->result();
    }
    return $result;
}

function get_order_by($tbl, $where = null, $order_by = null, $ASC = null, $limit = null)
{

    $CI = &get_instance();
    $CI->db->from($tbl);
    if (!empty($where) && $where != 0) {
        $CI->db->where($where);
    }
    if (!empty($ASC)) {
        $order = 'ASC';
    } else {
        $order = 'DESC';
    }
    $CI->db->order_by($order_by, $order);
    if (!empty($limit)) {
        $CI->db->limit($limit);
    }
    $query_result = $CI->db->get();
    $result = $query_result->result();
    return $result;
}

function encrypt($data)
{
    return get_instance()->encryption->encrypt($data);
}

function decrypt($data)
{
    return get_instance()->encryption->decrypt($data);
}

function url_encode($data)
{
    $url = base64_encode(serialize($data));
    // remove the = sign from the end of the string
    $url = str_replace('=', '', $url);
    return $url;
}

function url_decode($data)
{
    // add the = sign to the end of the string
    $data = str_pad($data, strlen($data) + (4 - strlen($data) % 4) % 4, '=', STR_PAD_RIGHT);
    return unserialize(base64_decode($data));
}

function tab_load_view($all_tab, $active)
{
    $tab = array_filter($all_tab, function ($key) use ($active) {
        return $key == $active;
    }, ARRAY_FILTER_USE_KEY);
    if (count(array($tab)) > 0) {
        return $tab[$active]['view'];
    } else {
        return false;
    }
}

/**
 * Check for company logo upload
 * @return boolean
 */
function handle_saas_company_logo_upload()
{
    $logoIndex = ['logo', 'logo_dark'];
    $success = false;

    foreach ($logoIndex as $logo) {
        $index = 'saas_company_' . $logo;

        if (isset($_FILES[$index]) && !empty($_FILES[$index]['name']) && _perfex_upload_error($_FILES[$index]['error'])) {
            set_alert('warning', _perfex_upload_error($_FILES[$index]['error']));

            return false;
        }
        if (isset($_FILES[$index]['name']) && $_FILES[$index]['name'] != '') {
            $path = get_upload_path_by_type('company');
            // Get the temp file path
            $tmpFilePath = $_FILES[$index]['tmp_name'];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                // Getting file extension
                $extension = strtolower(pathinfo($_FILES[$index]['name'], PATHINFO_EXTENSION));
                $allowed_extensions = [
                    'jpg',
                    'jpeg',
                    'png',
                    'gif',
                    'svg',
                ];

                if (!in_array($extension, $allowed_extensions)) {
                    set_alert('warning', 'Image extension not allowed.');
                    continue;
                }

                // Setup our new file path
                $filename = md5($logo . time()) . '.' . $extension;
                $newFilePath = $path . $filename;
                _maybe_create_upload_path($path);
                // Upload the file into the company uploads dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    update_option($index, $filename);
                    $success = true;
                }
            }
        }
    }


    return $success;
}


/**
 * Check for ticket attachment after inserting ticket to database
 * @param mixed $module_id
 * @return mixed           false if no attachment || array uploaded attachments
 */
function handle_module_attachments($module_id, $index_name = 'attachments')
{
    $path = get_upload_path() . $module_id . '/';

    $uploaded_files = [];
    if (isset($_FILES[$index_name])) {
        _file_attachments_index_fix($index_name);

        // check if is array
        if (!is_array($_FILES[$index_name]['name'])) {
            $_FILES[$index_name]['name'] = array($_FILES[$index_name]['name']);
            $_FILES[$index_name]['type'] = array($_FILES[$index_name]['type']);
            $_FILES[$index_name]['tmp_name'] = array($_FILES[$index_name]['tmp_name']);
            $_FILES[$index_name]['error'] = array($_FILES[$index_name]['error']);
            $_FILES[$index_name]['size'] = array($_FILES[$index_name]['size']);
        }

        for ($i = 0; $i < count($_FILES[$index_name]['name']); $i++) {
            if ($i <= 7) {
                // Get the temp file path
                $tmpFilePath = $_FILES[$index_name]['tmp_name'][$i];
                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    // Getting file extension
                    $extension = strtolower(pathinfo($_FILES[$index_name]['name'][$i], PATHINFO_EXTENSION));

                    // should be only image extension
                    $allowed_extensions = [
                        'jpg',
                        'jpeg',
                        'png',
                    ];
                    // Check for all cases if this extension is allowed
                    if (!in_array($extension, $allowed_extensions)) {
                        continue;
                    }

                    _maybe_create_upload_path($path);
                    $filename = unique_filename($path, $_FILES[$index_name]['name'][$i]);
                    $newFilePath = $path . $filename;
                    // Upload the file into the temp dir
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        array_push($uploaded_files, [
                            'file_name' => $filename,
                            'filetype' => $_FILES[$index_name]['type'][$i],
                        ]);
                    }
                }
            }
        }
    }
    if (count($uploaded_files) > 0) {
        return $uploaded_files;
    }

    return false;
}

function get_upload_path($module = 'modules'): string
{
    $dir = FCPATH . 'uploads/' . $module . '/';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    return $dir;
}

/**
 * Check for company logo upload
 * @return boolean
 */
function handle_saas_company_signature_upload()
{
    if (isset($_FILES['signature_image']) && _perfex_upload_error($_FILES['signature_image']['error'])) {
        set_alert('warning', _perfex_upload_error($_FILES['signature_image']['error']));

        return false;
    }
    if (isset($_FILES['signature_image']['name']) && $_FILES['signature_image']['name'] != '') {
        $path = get_upload_path_by_type('company');
        // Get the temp file path
        $tmpFilePath = $_FILES['signature_image']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $path_parts = pathinfo($_FILES['signature_image']['name']);
            $extension = $path_parts['extension'];
            $extension = strtolower($extension);
            $allowed_extensions = [
                'jpg',
                'jpeg',
                'png',
            ];
            if (!in_array($extension, $allowed_extensions)) {
                set_alert('warning', 'Image extension not allowed.');

                return false;
            }
            // Setup our new file path
            $filename = 'signature' . '.' . $extension;
            $newFilePath = $path . $filename;
            _maybe_create_upload_path($path);
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                update_option('saas_signature_image', $filename);

                return true;
            }
        }
    }

    return false;
}

/**
 * Handle company favicon upload
 * @return boolean
 */
function handle_saas_favicon_upload()
{
    if (isset($_FILES['favicon']['name']) && $_FILES['favicon']['name'] != '') {
        $path = get_upload_path_by_type('company');
        // Get the temp file path
        $tmpFilePath = $_FILES['favicon']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $path_parts = pathinfo($_FILES['favicon']['name']);
            $extension = $path_parts['extension'];
            $extension = strtolower($extension);
            // Setup our new file path
            $filename = 'favicon' . '.' . $extension;
            $newFilePath = $path . $filename;
            _maybe_create_upload_path($path);
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                update_option('saas_favicon', $filename);

                return true;
            }
        }
    }

    return false;
}

function get_saas_company_logo($uri = '', $href_class = '', $type = '')
{
    $company_logo = get_option('saas_company_logo' . ($type == 'dark' ? '_dark' : ''));
    $company_name = get_option('saas_companyname');

    if ($uri == '') {
        $logoURL = site_url();
    } else {
        $logoURL = site_url($uri);
    }

    if ($company_logo != '') {
        $logo = '<a href="' . $logoURL . '" class="logo img-responsive' . ($href_class != '' ? ' ' . $href_class : '') . '">
        <img src="' . base_url('uploads/company/' . $company_logo) . '" class="img-responsive" alt="' . html_escape($company_name) . '">
        </a>';
    } elseif ($company_name != '') {
        $logo = '<a href="' . $logoURL . '" class="' . $href_class . ' logo logo-text">' . $company_name . '</a>';
    } else {
        $logo = '';
    }
    echo $logo;
}


/**
 * @throws Exception
 */
function get_usages($company_info, $slug = ''): array
{
    $media_space = total_disk_space($company_info, true);

    $uses = array(
        [
            'name' => _l('staff'),
            'icon' => 'fa fa-users',
            'slug' => 'staff',
            'url' => admin_url('staff'),
            'total' => total_rows('staff'),
            'limit' => get_numeric($company_info->staff_no),
            'for' => 'staff_no',
        ], [
            'name' => _l('clients'),
            'icon' => 'fa-regular fa-user',
            'slug' => 'customers',
            'url' => admin_url('clients'),
            'total' => total_rows('clients'),
            'limit' => get_numeric($company_info->client_no),
            'for' => 'client_no',
        ], [
            'name' => _l('projects'),
            'icon' => 'fa-solid fa-chart-gantt',
            'slug' => 'projects',
            'url' => admin_url('projects'),
            'total' => total_rows('projects'),
            'limit' => get_numeric($company_info->project_no),
            'for' => 'project_no',
        ],
        [
            'name' => _l('items'),
            'icon' => 'fa fa-cubes',
            'slug' => 'items',
            'url' => admin_url('invoice_items'),
            'total' => total_rows('items'),
            'limit' => get_numeric($company_info->item_no),
            'for' => 'item_no',
        ], [
            'name' => _l('invoices'),
            'icon' => 'fa-solid fa-receipt',
            'slug' => 'invoices',
            'url' => admin_url('invoices'),
            'total' => total_rows('invoices'),
            'limit' => get_numeric($company_info->invoice_no),
            'for' => 'invoice_no',
        ], [
            'name' => _l('estimates'),
            'icon' => 'fa fa-file-text',
            'slug' => 'estimates',
            'url' => admin_url('estimates'),
            'total' => total_rows('estimates'),
            'limit' => get_numeric($company_info->estimate_no),
            'for' => 'estimate_no',
        ], [
            'name' => _l('credit_notes'),
            'icon' => 'fa fa-file-text',
            'slug' => 'credit_notes',
            'url' => admin_url('credit_notes'),
            'total' => total_rows('creditnotes'),
            'limit' => get_numeric($company_info->credit_note_no),
            'for' => 'credit_note_no',
        ], [
            'name' => _l('proposals'),
            'icon' => 'fa fa-file-text',
            'slug' => 'proposals',
            'url' => admin_url('proposals'),
            'total' => total_rows('proposals'),
            'limit' => get_numeric($company_info->proposal_no),
            'for' => 'proposal_no',
        ], [
            'name' => _l('leads'),
            'icon' => 'fa fa-tty',
            'slug' => 'leads',
            'url' => admin_url('leads'),
            'total' => total_rows('leads'),
            'limit' => get_numeric($company_info->leads_no),
            'for' => 'leads_no',
        ], [
            'name' => _l('expenses'),
            'icon' => 'fa-regular fa-file-lines',
            'slug' => 'expenses',
            'url' => admin_url('expenses'),
            'total' => total_rows('expenses'),
            'limit' => get_numeric($company_info->expense_no),
            'for' => 'expense_no',
        ], [
            'name' => _l('contracts'),
            'icon' => 'fa-solid fa-file-contract',
            'slug' => 'contracts',
            'url' => admin_url('contracts'),
            'total' => total_rows('contracts'),
            'limit' => get_numeric($company_info->contract_no),
            'for' => 'contract_no',
        ], [
            'name' => _l('tasks'),
            'icon' => 'fa fa-tasks',
            'slug' => 'tasks',
            'url' => admin_url('tasks'),
            'total' => total_rows('tasks'),
            'limit' => get_numeric($company_info->tasks_no),
            'for' => 'tasks_no',
        ], [
            'name' => _l('tickets'),
            'icon' => 'fa-regular fa-life-ring',
            'slug' => 'support',
            'url' => admin_url('tickets'),
            'total' => total_rows('tickets'),
            'limit' => get_numeric($company_info->tickets),
            'for' => 'tickets',
        ],
    );
    if (!empty($media_space['total_space'])) {
        $uses[] = [
            'name' => _l('disk_space'),
            'icon' => 'fa fa-hdd',
            'slug' => 'media',
            'url' => admin_url('utilities/media'),
            'total' => $media_space['used_space'],
            'limit' => $media_space['total_space'],
            'total_in_text' => $media_space['used_space_text'],
            'limit_in_text' => $media_space['total_space_text'],
            'for' => 'disk_space',
        ];
    }

    // add active according to $all_field array field name and $uses array for field name
    $all_field = get_old_order_by('tbl_saas_package_field', null, 'order', 'asc');
    if (is_array($all_field) && count($all_field) > 0) {
        foreach ($all_field as $field) {
            $field_name = $field->field_name;
            $additional_field = 'additional_' . $field_name;
            foreach ($uses as $key => $use) {
                if ($field->field_name == $use['for']) {
                    $uses[$key]['active'] = $field->status;
                    $uses[$key]['for'] = $field_name;
                    $uses[$key]['additional_price'] = $company_info->$additional_field;
                }
            }
        }
    }

    $modules = $company_info->modules ? unserialize($company_info->modules) : [];
    if (is_array($modules) && count($modules) > 0) {
        foreach ($modules as $module) {
            $uses[] = [
                'name' => _l($module),
                'icon' => 'fa fa-book',
                'slug' => $module,
                'url' => '#',
                'total' => '<i class="fa fa-infinity"></i>',
                'limit' => '<i class="fa fa-infinity"></i>',
                'for' => $module,
                'active' => 'active',
            ];
        }
    }
    if ($slug != '') {
        return array_filter($uses, function ($use) use ($slug) {
            return $use['slug'] == $slug;
        });
    }
    return $uses;
}

function get_numeric($key)
{
    // get the value from $company_info by key and return it
    // if the value 0 then return unlimited
    // if the value > 0 then return the value
    // if the value is empty then false
    if (isset($key)) {
        if ($key === 0 || $key === '0' || $key === 'Yes') {
            // unlimited icon
            return '<i class="fa fa-infinity"></i>';
        } elseif (is_numeric($key) && $key > 0) {
            return $key;
        } else {
            return false;
        }
    }
}

function get_coupon_by_package_type($package_type)
{
    $where = array(
        'end_date >=' => date('Y-m-d'),
        'status' => 'active',
        'show_on_pricing' => 'Yes'
    );
    $coupon = get_old_result('tbl_saas_coupon', $where + array('package_type' => $package_type), false);

    if (!empty($coupon)) {
        return $coupon;
    }
    return false;
}

function apply_coupon($package)
{

    $where = array(
        'end_date >=' => date('Y-m-d'),
        'status' => 'active',
        'show_on_pricing' => 'Yes'
    );
    $coupon = get_old_result('tbl_saas_coupon', $where + array('package_id' => $package->id), false);
    if (!empty($coupon)) {
        $package_type = $coupon->package_type . '_price';
        $package_offer = $coupon->package_type . '_offer';
        if ($coupon->type == 1) {
            $package->$package_offer = $package->$package_type - ($package->$package_type * $coupon->amount / 100);
        } else {
            $package->$package_offer = $package->$package_type - $coupon->amount;
        }
    }
    $couponForAll = get_old_result('tbl_saas_coupon', $where + array('package_id' => 0), false);
    if (!empty($couponForAll)) {
        $packageType = $couponForAll->package_type . '_price';
        $packageOffer = $couponForAll->package_type . '_offer';

        if ($couponForAll->type == 1) {
            $package->$packageOffer = $package->$packageType - ($package->$packageType * $couponForAll->amount / 100);
        } else {
            $package->$packageOffer = $package->$packageType - $couponForAll->amount;
        }
    }


    return $package;
}

function module_direcoty($module, $concat = '')
{
    return 'modules/' . $module . '/' . $concat;
}

/**
 * @throws Exception
 */
function setBaseURL()
{
    if (!empty(subdomain())) {
        // redirect to main domain with current url
        $default_url = companyUrl();
        // get current url from browser
        $current_url = current_url();
        $current_url = str_replace($default_url, APP_BASE_URL, $current_url);
        redirect($current_url);
    }
}

function BaseUrl($url = '')
{
    return APP_BASE_URL . $url;
}

function companyBaseUrl($domain = null)
{
    if (!empty($domain)) {
        return config_item('default_url');
    }

    $default_url = config_item('main_url');
    if (empty($default_url)) {
        $default_url = config_item('default_url');
    }
    return $default_url;
}


function saas_payment_recorded($payment)
{

    // get session company info
    $CI =& get_instance();
    $sessionData = $CI->session->userdata('saas_payment_data');
    $invoice_id = '';

    if (!empty($sessionData['invoice_id'])) {
        $invoice_id = $sessionData['invoice_id'];
    } else {
        $invoice_id = $payment['invoiceid'];
    }
    $invoice = $CI->invoices_model->get($invoice_id);
    $companies_id = $invoice->client->saas_company_id;
    if (!empty($invoice) && !empty($companies_id)) {
        $subs_info = get_company_subscription_by_id($companies_id);
        $checkTemp = get_old_result('tbl_saas_temp_payment', array('invoice_id' => $invoice_id, 'companies_id' => $companies_id), false);
    }

    if (!empty($checkTemp) && !empty($subs_info)) {
        if (round($payment['amount']) !== round($checkTemp->amount)) {
            set_alert('danger', _l('payment_amount_not_match'));
            redirect(admin_url('dashboard'));
        } else {
            $CI = &get_instance();
            // load saas model
            $CI->load->model('saas/saas_model');
            $data = array();
            $data['package_id'] = $checkTemp->package_id;
            $data['billing_cycle'] = $checkTemp->billing_cycle;
            $data['coupon_code'] = $checkTemp->coupon_code;
            $data['expired_date'] = $checkTemp->expired_date;
            $data['new_module'] = $checkTemp->new_module;
            $data['new_limit'] = $checkTemp->new_limit;
            $data['mark_paid'] = 1;
            if ($checkTemp->coupon_code) {
                $data['is_coupon'] = 1;
            } else {
                $data['is_coupon'] = null;
            }
            $data['payment_method'] = $payment['paymentmode'];
            $data['currency'] = get_base_currency()->name;
            $data['amount'] = $payment['amount'];

            $r = $CI->saas_model->update_package($companies_id, $data);
            if (!empty($r)) {
                // delete temp payment
                $CI->saas_model->_table_name = 'tbl_saas_temp_payment';
                $CI->saas_model->delete_old(array('temp_payment_id' => $checkTemp->temp_payment_id));
            }
        }
    }

    return $payment;
}

function saas_logo()
{
    $logo = get_option('saas_company_logo');
    // check if logo is not empty and file exists
    if (!empty($logo) && file_exists(FCPATH . 'uploads/company/' . $logo)) {
        $logo = base_url('uploads/company/' . $logo);
    } else {
        $logo = base_url('modules/saas/assets/images/logo.png');
    }
    return $logo;
}

function saas_base_url()
{
    $url = companyBaseUrl();
    // remove http:// or https:// from url
    $url = str_replace('http://', '', $url);
    $url = str_replace('https://', '', $url);
    // remove www. from url
    $url = str_replace('www.', '', $url);
    // remove last slash from url
    $url = rtrim($url, '/');
    return $url;
}

function check_reserved_tenant($value)
{

    $check = ConfigItems('saas_reserved_tenant');
    if (!empty($check)) {
        $reserved_tenant = explode(',', $check);
        if (in_array($value, $reserved_tenant)) {
            return true;
        }
    }
    return false;
}

function domainUrl($domain)
{
    // convert to lowercase and remove space,special character,dot and slash from $value variable and replace with underscore
    $domain = strtolower($domain);
    $domain = preg_replace('/[^a-z0-9_]/', '_', $domain);
    $domain = str_replace(' ', '_', $domain);
    $domain = str_replace('.', '_', $domain);
    return str_replace('/', '_', $domain);
}

/**
 * @throws Exception
 */
function subdomain()
{
    $is_subdomain = function_exists('is_subdomain') ? is_subdomain() : '';
    if (empty($is_subdomain)) {
        // get session company
        $CI = &get_instance();
        $is_subdomain = $CI->session->userdata('domain');
    }
    return $is_subdomain;
}

function get_theme_path_url($domain = ''): array
{
    $path = 'uploads/themebuilder/themes';
    if (!empty($domain)) {
        $path = 'uploads/themebuilder/' . $domain;
    }
    $themePath = FCPATH . $path;
    $themeUrl = base_url($path);
    return [$themePath, $themeUrl];
}

function get_theme_list()
{
    list($themePath, $themeUrl) = get_theme_path_url();
    $themes = array_filter(glob($themePath . '/*'), 'is_dir');
    return $themes;

}

function get_themes($domain = null): array
{
    $pages = [];
    $activeTheme = get_option('saas_default_theme') ?? '';
    if (!empty($domain)) {
        $activeTheme = get_option('default_theme') ?? '';
    }
    list($themePath, $themeUrl) = get_theme_path_url($domain);

    // get all parent directories in themes folder as themes name
    $themes = array_filter(glob($themePath . '/*'), 'is_dir');
    // check active theme is exists in themes folder then sort it to first
    if (in_array($themePath . '/' . $activeTheme, $themes)) {
        $activeThemePath = $themePath . '/' . $activeTheme;
        $activeThemeUrl = $themeUrl . '/' . $activeTheme;
        unset($themes[array_search($activeThemePath, $themes)]);
        array_unshift($themes, $activeThemeUrl);
    }

    foreach ($themes as $key => $theme) {
        $themeName = basename($theme);
        $htmlFiles = [];
        $patterns = [$themePath . '/' . $themeName . '/*.html', $themePath . '/' . $themeName . '/*/*.html'];
        // Get all files matching the patterns
        foreach ($patterns as $pattern) {
            $htmlFiles = array_merge($htmlFiles, glob($pattern));
        }

        foreach ($htmlFiles as $index => $file) {
            if (stripos($file, 'new-page-blank-template.html') !== false) continue; //skip template files
            $pathInfo = pathinfo($file);
            $extension = $pathInfo['extension'];
            if ($extension !== 'html') continue;

            $basePath = preg_replace('@^' . $themePath . '/@', '', $pathInfo['dirname']);

            $realFilename = $filename = $pathInfo['filename'];
            $folder = preg_replace('@/.+?$@', '', $basePath);
            $subfolder = preg_replace('@^.+?/@', '', $pathInfo['dirname']);

            if ($subfolder) {
                if ($filename == 'index')
                    $filename = basename($subfolder);
                else if ($folder !== basename($subfolder))
                    $filename = basename($subfolder) . '/' . $filename;
            }
            $url = str_ireplace($themePath, $themeUrl, $pathInfo['dirname'] . '/' . $pathInfo['basename']);

            $page = [
                "name" => basename($themeName) . ' - ' . ($filename),
                "title" => ucfirst($filename),
                "file" => str_ireplace($themePath, '', $file),
                "url" => $url,
                "folder" => empty($folder) ? 'themes' : $folder,
                "base_path_url" => str_ireplace(basename($realFilename) . '.' . $extension, '', $url)
            ];
            $pages[] = $page;
        }
    }
    return $pages;
}

function saas_remove_dir($target)
{
    try {
        if (is_dir($target)) {
            $dir = new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS);
            foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST) as $filename => $file) {
                if (is_file($filename)) {
                    unlink($filename);
                } else {
                    saas_remove_dir($filename);
                }
            }
            return rmdir($target); // Now remove target folder
        }
    } catch (\Exception $e) {
    }
    return false;
}

function saas_db_activate_module($module)
{
    $CI = &get_instance();
    $db_name = $CI->session->userdata('new_db_name');
    $CI->db = config_db($db_name);
    $CI->db->query("SET SESSION sql_mode = ''");
    $CI->db->db_debug = false;
}

function saas_db_module_activated($module)
{
    echo "<pre>";
    print_r($module);
    exit();

    $CI = &get_instance();
    $db_name = $CI->session->userdata('new_db_name');
    $CI->db = config_db($db_name);
    $CI->db->query("SET SESSION sql_mode = ''");
    $CI->db->db_debug = false;
}

function saas_db_deactivate_module($module)
{
    $CI = &get_instance();
    $db_name = $CI->session->userdata('new_db_name');
    $CI->db = config_db($db_name);
    $CI->db->query("SET SESSION sql_mode = ''");
    $CI->db->db_debug = false;
}

function get_frequency(): array
{
    $frequency = [
        [
            'name' => 'monthly',
            'value' => 'monthly_price',
            'label' => _l('monthly'),
            'class' => 'btn-primary',
        ],
        [
            'name' => 'yearly',
            'value' => 'yearly_price',
            'label' => _l('yearly'),
            'class' => 'btn-default',
        ],
        [
            'name' => 'lifetime',
            'value' => 'lifetime_price',
            'label' => _l('lifetime'),
            'class' => 'btn-default',
        ]
    ];
    return $frequency;
}

function get_active_frequency($for_select = null): array
{
    $frequency = get_frequency();
    $disabled_frequency = get_option('saas_disable_frequency');
    if (!empty($disabled_frequency)) {
        $disabled_frequency = ($disabled_frequency)
            ? json_decode($disabled_frequency)
            : [];
        // check if $disabled_frequency is object then convert it to array
        if (is_object($disabled_frequency)) {
            $disabled_frequency = (array)$disabled_frequency;
        }
        // remove disabled frequency from frequency array and return it
        foreach ($frequency as $key => $value) {
            if ($value['value'] == 'monthly_price') {
                continue;
            }
            if (in_array($value['value'], $disabled_frequency)) {
                unset($frequency[$key]);
            }
        }
    }
    if (!empty($for_select)) {
        $frequency = array_column($frequency, 'label', 'value');
    }
    return $frequency;

}

function get_join_data($table, $select = '*', $where = null, $join = null, $row = null, $order = null)
{
    $CI = &get_instance();
    if ($select == '*') {
        $CI->db->select('*', false);
    } else {
        $CI->db->select("$select", false);
    }
    $CI->db->from($table);
    if (!empty($join)) {
        foreach ($join as $tbl => $wh) {
            $CI->db->join($tbl, $wh, 'left');
        }
    }
    if (!empty($where)) {
        $CI->db->where($where);
    }
    if (!empty($order)) {
        // is array
        if (is_array($order)) {
            foreach ($order as $key => $value) {
                $CI->db->order_by($key, $value);
            }
        } else {
            $CI->db->order_by($order);
        }
    }

    $query = $CI->db->get();
    if (!empty($row) && $row === 'array') {
        $result = $query->result_array();
    } else if (!empty($row) && $row === 'object') {
        $result = $query->result();
    } else {
        $result = $query->row();
    }
    return $result;
}

function get_old_join_data($table, $select = '*', $where = null, $join = null, $row = null, $order = null)
{
    $CI = &get_instance();
    $CI->old_db = config_db(NULL, true);
    if ($select == '*') {
        $CI->old_db->select('*', false);
    } else {
        $CI->old_db->select("$select", false);
    }
    $CI->old_db->from($table);
    if (!empty($join)) {
        foreach ($join as $tbl => $wh) {
            $CI->old_db->join($tbl, $wh, 'left');
        }
    }
    if (!empty($where)) {
        $CI->old_db->where($where);
    }
    if (!empty($order)) {
        // is array
        if (is_array($order)) {
            foreach ($order as $key => $value) {
                $CI->old_db->order_by($key, $value);
            }
        } else {
            $CI->old_db->order_by($order);
        }
    }

    $query = $CI->old_db->get();
    if (!empty($row) && $row === 'array') {
        $result = $query->result_array();
    } else if (!empty($row) && $row === 'object') {
        $result = $query->result();
    } else {
        $result = $query->row();
    }
    return $result;
}

function is_saas_expired($company)
{
    $result = is_account_running($company, true);
    if (!empty($result['trial'])) {
        $trial_period = $result['trial'];
    } else {
        $trial_period = $result['running'];
    }
    if ($trial_period <= 0) {
        redirect('upgrade');
    }
}

function login_to_client($company_id)
{
    if (is_client_logged_in()) {
        return false;
    }

    $client_id = get_saas_client_id($company_id);
    $company_info = get_old_result('tbl_saas_companies', array('id' => $company_id), false);

    $contact = get_old_result(db_prefix() . 'contacts', array('email' => $company_info->email), false);
    if (empty($contact)) {
        $contact = get_old_result(db_prefix() . 'contacts', array('is_primary' => 1, 'userid' => get_saas_client_id()), false);
    }
    if (empty($contact)) {
        // create new contact
        $CI = &get_instance();
        $CI->load->model('saas_model');
        $password = $company_info->password;
        // check if password is empty then create new password
        if (empty($password)) {
            $password = '123456';
        }
        $bcrypt = null;
        // check password is encrypted or not
        if (strlen($password) < 20) {
            $bcrypt = true;
        }
        $CI->saas_model->create_contact($company_info, $client_id, $password, $bcrypt);
    }
    login_as_client($client_id);
    // redirect to to current url
    redirect(current_url());
}

function moduleTitle($module)
{
    $moduleName = !empty($module['system_name']) ? $module['system_name'] : $module;
    $CI = &get_instance();
    if ($title = $CI->app_object_cache->get('module-title-' . $moduleName)) {
        return $title;
    }
    $result = get_old_any_field('tbl_saas_package_module', array('module_name' => $moduleName), 'module_title');
    if (!empty($result->module_title)) {
        $title = $result->module_title;
    } else {
        $title = !empty($module['system_name']) ? $module['headers']['module_name'] : $module;
    }
    $CI->app_object_cache->add('module-title-' . $moduleName, $title);
    return $title;
}

function seed_db()
{
    $company_seed = get_row('tbl_saas_companies', array('for_seed' => 'yes', 'domain' => 'company_seed'));
    if (empty($company_seed)) {
        return false;
    }
    return $company_seed;
}

function get_default_modules(): array
{
    $modules = ['leads', 'projects', 'tasks', 'expenses', 'proposals', 'estimates', 'estimate_request', 'tickets', 'reports', 'contracts', 'knowledge_base', 'custom_fields', 'credit_notes', 'subscriptions', 'invoices', 'items', 'payments'];
    asort($modules);
    return $modules;
}

/**
 * @throws Exception
 */
function disabled_default_modules($domain = null, $mode = "controller")
{
    $subs = get_company_subscription($domain, 'running');
    $modules = [];
    if (!empty($subs->disabled_modules)) {
        $modules = unserialize($subs->disabled_modules);
    }
    if ($mode !== "controller") {
        // Adapt for menu and tabs check
        foreach ($modules as $key => $value) {
            if (empty($value)) unset($modules[$key]);
            if (stripos($value, '_') !== false)
                $modules[] = str_replace('_', '-', $value);

            if ($value === 'tickets') {
                $modules[] = 'support';
            }
        }
    }
    if (in_array('invoices', $modules)) {
        $modules[] = 'taxes';
        $modules[] = 'currencies';
    }
    if (in_array('payments', $modules)) {
        $modules[] = 'paymentmodes';
        $modules[] = 'currencies';
    }
    if (in_array('credit_notes', $modules)) {
        $modules[] = 'creditnotes';
    }
    return $modules;
}

function isClientLogin($company_id = null)
{
    if (!is_client_logged_in() && !empty($company_id)) {
        login_to_client($company_id);
    } elseif (!is_client_logged_in() && empty($company_id)) {
        redirect('authentication/login');
    }
}