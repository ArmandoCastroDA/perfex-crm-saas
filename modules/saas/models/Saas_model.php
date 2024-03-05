<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Saas_model extends App_Model
{
    public $_table_name;
    public $_order_by;
    public $_primary_key;
    protected $_primary_filter = 'intval';
    /**
     * @var bool|object
     */
    private $old_db;
    private $new_db;

    public const STATUS_UNPAID = 1;

    public const STATUS_PAID = 2;

    public const STATUS_PARTIALLY = 3;

    public const STATUS_OVERDUE = 4;

    public const STATUS_CANCELLED = 5;

    public const STATUS_DRAFT = 6;

    public const STATUS_DRAFT_NUMBER = 1000000000;
    /**
     * @var bool|object
     */
    private $sample_db;
    private $company_db;


    public function __construct()
    {
        parent::__construct();
    }

    public function build_sidebar_menu()
    {

        $menu = array();
        $menu['dashboard'] = array(
            'slug' => 'dashboard',
            'name' => _l('dashboard'),
            'href' => saas_url('dashboard'),
            'position' => 1,
            'icon' => 'fa fa-home',
            'badge' => array(),
            'children' => array(),
        );
        // company
        $menu['company'] = array(
            'slug' => 'company',
            'name' => _l('companies'),
            'position' => 2,
            'icon' => 'fa fa-building',
            'href' => saas_url('companies'),
            'children' => array(),
        );
        // custom_domain
        $menu['custom_domain'] = array(
            'slug' => 'custom_domain',
            'name' => _l('custom_domain'),
            'position' => 3,
            'badge' => array(),
            'icon' => 'fa fa-globe',
            'href' => saas_url('custom_domain'),
            'children' => array(
                // requests
                array(
                    'parent_slug' => 'custom_domain',
                    'slug' => 'requests',
                    'name' => _l('requests'),
                    'badge' => array(),
                    'href' => saas_url('domain/requests'),
                    'position' => 1,
                    'icon' => '',
                ),
                // settings
                array(
                    'parent_slug' => 'custom_domain',
                    'slug' => 'settings',
                    'name' => _l('settings'),
                    'href' => saas_url('domain/settings'),
                    'position' => 2,
                    'icon' => '',
                ),

            ),
        );
        $total_requests = total_rows('tbl_saas_domain_requests', array('status' => 'pending'));
        // if domain requested is pending show badge on menu
        if ($total_requests > 0) {
            $menu['custom_domain']['badge'] = array(
                'value' => $total_requests,
                'type' => 'warning'
            );
        }
        // assign package
        $menu['assign_package'] = array(
            'slug' => 'assignPackage',
            'name' => _l('assign_package'),
            'position' => 3,
            'icon' => 'fa fa-cubes',
            'href' => base_url('assignPackage'),
            'children' => array(),
        );
        // packages
        $menu['packages'] = array(
            'slug' => 'packages',
            'name' => _l('packages'),
            'position' => 3,
            'icon' => 'fa fa-cubes',
            'href' => saas_url('packages'),
            'children' => array(
                // packages
                array(
                    'parent_slug' => 'packages',
                    'slug' => 'packages',
                    'name' => _l('packages'),
                    'href' => saas_url('packages'),
                    'position' => 1,
                    'icon' => '',
                ),
                // packages
                array(
                    'parent_slug' => 'packages',
                    'slug' => 'features',
                    'name' => _l('customized_package'),
                    'href' => saas_url('packages/customize'),
                    'position' => 2,
                    'icon' => '',
                ),
                // packages
                array(
                    'parent_slug' => 'packages',
                    'slug' => 'fields',
                    'name' => _l('modules_price'),
                    'href' => saas_url('packages/modules'),
                    'position' => 3,
                    'icon' => '',
                ),
                // settings
                array(
                    'parent_slug' => 'packages',
                    'slug' => 'settings',
                    'name' => _l('settings'),
                    'href' => saas_url('packages/settings'),
                    'position' => 3,
                    'icon' => '',
                ),


//// packages
//                array(
//                    'parent_slug' => 'packages',
//                    'slug' => 'fields',
//                    'name' => _l('manage_fields'),
//                    'href' => saas_url('packages/fields'),
//                    'position' => 3,
//                    'icon' => '',
//                ),
            ),
        );
        // payments
        $menu['payments'] = array(
            'slug' => 'payments',
            'name' => _l('payments'),
            'position' => 4,
            'icon' => 'fa fa-solid fa-receipt',
            'href' => saas_url('payments'),
            'children' => array(),
        );
        // coupons
        $menu['coupons'] = array(
            'slug' => 'coupons',
            'name' => _l('coupons'),
            'position' => 5,
            'icon' => 'fa fa-gift',
            'href' => saas_url('coupons'),
            'children' => array(),
        );
        // affiliates
        $menu['affiliates'] = array(
            'slug' => 'affiliates',
            'name' => _l('affiliates'),
            'position' => 6,
            'icon' => 'fa fa-users',
            'href' => saas_url('affiliates'),
            'children' => array(
                // users
                array(
                    'parent_slug' => 'affiliates',
                    'slug' => 'users',
                    'name' => _l('users'),
                    'href' => saas_url('affiliates/users'),
                    'position' => 1,
                    'icon' => '',
                ),
                // payouts
                array(
                    'parent_slug' => 'affiliates',
                    'slug' => 'payouts',
                    'name' => _l('payouts'),
                    'href' => saas_url('affiliates/payouts'),
                    'position' => 2,
                    'icon' => '',
                ),
                // settings
                array(
                    'parent_slug' => 'affiliates',
                    'slug' => 'settings',
                    'name' => _l('settings'),
                    'href' => saas_url('affiliates/settings'),
                    'position' => 3,
                    'icon' => '',
                ),

            ),
        );
        // front CMS
        $menu['front_cms'] = array(
            'slug' => 'front_cms',
            'name' => _l('front_cms'),
            'position' => 7,
            'icon' => 'fa fa-globe',
            'href' => saas_url('front_cms'),
            'children' => array(
                // pages
                array(
                    'parent_slug' => 'builder',
                    'slug' => 'builder',
                    'name' => _l('theme_builder'),
                    'href' => saas_url('themebuilder'),
                    'position' => 1,
                    'icon' => '',
                ), // pages

                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'pages',
                    'name' => _l('pages'),
                    'href' => saas_url('frontcms/page'),
                    'position' => 1,
                    'icon' => '',
                ),
                // menus
                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'menus',
                    'name' => _l('menus'),
                    'href' => saas_url('frontcms/menus'),
                    'position' => 2,
                    'icon' => '',
                ),
                // media
                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'media',
                    'name' => _l('media'),
                    'href' => saas_url('frontcms/media'),
                    'position' => 3,
                    'icon' => '',
                ),
                // slider
                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'slider',
                    'name' => _l('slider'),
                    'href' => saas_url('frontcms/settings/slider'),
                    'position' => 4,
                    'icon' => '',
                ),
                // blogs
                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'blogs',
                    'name' => _l('blogs'),
                    'href' => saas_url('frontcms/blogs'),
                    'position' => 5,
                    'icon' => '',
                ),
                // creatives
                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'creatives',
                    'name' => _l('creatives'),
                    'href' => saas_url('frontcms/creatives'),
                    'position' => 6,
                    'icon' => '',
                ),
                // discovers
                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'discovers',
                    'name' => _l('discovers'),
                    'href' => saas_url('frontcms/discovers'),
                    'position' => 7,
                    'icon' => '',
                ),
                // features
                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'features',
                    'name' => _l('features'),
                    'href' => saas_url('frontcms/features'),
                    'position' => 8,
                    'icon' => '',
                ),
                // abouts
                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'abouts',
                    'name' => _l('abouts'),
                    'href' => saas_url('frontcms/abouts'),
                    'position' => 9,
                    'icon' => '',
                ),
                // brands
                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'brands',
                    'name' => _l('brands'),
                    'href' => saas_url('frontcms/brands'),
                    'position' => 10,
                    'icon' => '',
                ),
                // reviews
                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'reviews',
                    'name' => _l('reviews'),
                    'href' => saas_url('frontcms/reviews'),
                    'position' => 10,
                    'icon' => '',
                ),
                // gallery
                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'gallery',
                    'name' => _l('gallery'),
                    'href' => saas_url('frontcms/gallery'),
                    'position' => 11,
                    'icon' => '',
                ),
                // settings
                array(
                    'parent_slug' => 'front_cms',
                    'slug' => 'settings',
                    'name' => _l('settings'),
                    'href' => saas_url('frontcms/settings'),
                    'position' => 12,
                    'icon' => '',
                ),

            ),
        );
        // settings
        $menu['settings'] = array(
            'slug' => 'settings',
            'name' => _l('settings'),
            'position' => 8,
            'icon' => 'fa fa-cog',
            'href' => saas_url('settings'),
            'children' => array()
        );
        // faq
        $menu['faq'] = array(
            'slug' => 'faq',
            'name' => _l('faq'),
            'position' => 9,
            'icon' => 'fa fa-question-circle',
            'href' => saas_url('faq'),
            'children' => array()
        );

        // super admin
        $menu['super_admin'] = array(
            'slug' => 'super_admin',
            'name' => _l('super_admin'),
            'position' => 11,
            'icon' => 'fa fa-user-shield',
            'href' => saas_url('super_admin'),
            'children' => array()
        );

        return $menu;

    }

    public function company_info($id, $full = null)
    {
        $select = 'tbl_saas_companies.*,tbl_saas_companies_history.package_name,tbl_saas_companies_history.id as company_history_id';
        if (!empty($full)) {
            $select = 'tbl_saas_companies.*,tbl_saas_companies_history.id as company_history_id,tbl_saas_companies_history.*';
        }
        return $this->select_data('tbl_saas_companies', $select, NULL, array('tbl_saas_companies.id' => $id, 'tbl_saas_companies.for_seed' => NULL, 'tbl_saas_companies_history.active' => 1), ['tbl_saas_companies_history' => 'tbl_saas_companies.id = tbl_saas_companies_history.companies_id'], 'row');
    }

    public function delete_company($id)
    {
        $this->load->model('clients_model');
        $client_id = get_saas_client_id($id);
        $response = $this->clients_model->delete($client_id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('customer_delete_transactions_warning', _l('invoices') . ', ' . _l('estimates') . ', ' . _l('credit_notes')));
            redirect('admin/saas/companies');
        }

        $companyInfo = get_row('tbl_saas_companies', array('id' => $id, 'for_seed' => NULL));
        if (empty($companyInfo)) {
            set_alert('warning', _l('company_not_found'));
            redirect('admin/saas/companies');
        }

        if (!empty($companyInfo->db_name)) {
            $this->drop_database($companyInfo->db_name);
        }
        // delete data from tbl_saas_companies_history
        $this->_table_name = 'tbl_saas_companies_history';
        $this->delete(array('companies_id' => $id));

        // delete data from tbl_saas_companies_payment
        $this->_table_name = 'tbl_saas_companies_payment';
        $this->delete(array('companies_id' => $id));

        // delete data from tbl_saas_companies
        $this->_table_name = 'tbl_saas_companies';
        $this->_primary_key = 'id';
        $this->delete($id);
    }


    function drop_database($db_name): bool
    {
        if (ConfigItems('saas_server') === 'cpanel') {
            include_once(APP_MODULES_PATH . 'saas/libraries/Xmlapi.php');
            $cpanel_password = decrypt(ConfigItems('saas_cpanel_password'));
            $output = ConfigItems('saas_cpanel_output');
            $db_username = $this->db->username;
            $xmlapi = new xmlapi(ConfigItems('saas_cpanel_host'));
            $xmlapi->password_auth(ConfigItems('saas_cpanel_username'), $cpanel_password);
            $xmlapi->set_port(ConfigItems('saas_cpanel_port'));
            $xmlapi->set_debug(0);
            $xmlapi->set_output($output);
            $cpaneluser = ConfigItems('saas_cpanel_username');
            $databasename = $db_name;
            $xmlapi->api1_query($cpaneluser, 'Mysql', 'deldb', array($databasename));
        }
        // check if database exists or not in codeigniter
        $this->load->dbutil();
        if ($this->dbutil->database_exists($db_name)) {
            $this->myforge = $this->load->dbforge($db_name, TRUE);
            if ($this->myforge->drop_database($db_name)) {
                return true;
            } else {
                $this->new_db = config_db($db_name);
                if (!empty($this->new_db)) {
                    $tables = $this->new_db->list_tables();
                    foreach ($tables as $table) {
                        $this->myforge->drop_table($table);
                    }
                }
                return true;
            }
        }
        return true;
    }

    public function save_old($data, $id = NULL)
    {
        $this->old_db = config_db(true, true);
        // Insert
        if ($id === NULL) {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $this->old_db->set($data);
            $this->old_db->insert($this->_table_name);
            $id = $this->old_db->insert_id();

        } // Update
        else {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->old_db->set($data);
            $this->old_db->where($this->_primary_key, $id);
            $this->old_db->update($this->_table_name);
        }
        return $id;
    }

    public function select_old_data($table, $id, $value = null, $where = null, $join = null, $row = null)
    {
        $this->old_db = config_db(true, true);
        if ($id == '*') {
            $this->old_db->select('*', false);
        } else {
            $this->old_db->select("$id,$value", false);
        }
        $this->old_db->from($table);

        if (!empty($join)) {
            foreach ($join as $tbl => $wh) {
                $this->old_db->join($tbl, $wh, 'left');
            }
        }
        if (!empty($where)) {
            $this->old_db->where($where);
        }
        $query = $this->old_db->get();
        if (!empty($row) && $row === 'row') {
            $result = $query->row();
        } else if (!empty($row) && $row === 'object') {
            $result = $query->result();
        } else {
            $result = $query->result_array();
        }
        if (!empty($row)) {
            return $result;
        } else {
            if (strpos($id, ".") !== false) {
                $id = trim(strstr($id, '.'), '.');
            }
            if (strpos($value, ".") !== false) {
                $value = trim(strstr($value, '.'), '.');
            }
            // explode id  by _ and get the first index
            $select = explode('_', $id);
            $select = $select[0];
            $returnResult = [lang('select_data', lang($select))];
            if (!empty($result)) {
                $returnResult += array_column($result, $value, $id);
            }
        }
        return $returnResult;
    }


    public function get_all_tabs()
    {
        $url = 'saas/settings/';
        $tabs = array(
            'general' => [
                'position' => 1,
                'name' => 'settings_group_general',
                'url' => $url . 'index/general',
                'count' => '',
                'icon' => 'fa fa-cog',
                'view' => $url . 'general',
            ],
            'localization' => [
                'position' => 1,
                'name' => 'settings_group_localization',
                'url' => $url . 'index/localization',
                'count' => '',
                'icon' => 'fa-solid fa-globe ',
                'view' => $url . 'localization',
            ],
            'company_seed' => [
                'position' => 1,
                'name' => 'tenant_company_seed',
                'url' => $url . 'index/company_seed',
                'count' => '',
                'icon' => 'fa fa-building',
                'view' => $url . 'company_seed',
            ],
            'email_settings' => [
                'position' => 3,
                'name' => 'settings_group_email',
                'url' => $url . 'index/email_settings',
                'count' => '',
                'icon' => 'fa fa-envelope',
                'view' => $url . 'email',
            ],
            'server_settings' => [
                'position' => 2,
                'name' => 'server_settings',
                'url' => $url . 'index/server_settings',
                'count' => '',
                'icon' => 'fa fa-server',
                'view' => $url . 'server',
            ],
            'payments' => [
                'position' => 5,
                'name' => 'settings_group_online_payment_modes',
                'url' => $url . 'payments',
                'count' => '',
                'icon' => 'fa fa-credit-card',
                'view' => $url . 'payments',
            ],
        );
        return $tabs;
    }


    public function test_connection($data)
    {
        $data = $data['settings'];
        if ($data['saas_server'] == 'cpanel') {
            $data['saas_cpanel_password'] = decrypt(get_option('saas_cpanel_password'));
            $result = $this->createCPanelDatabase($data, true);
        } elseif ($data['saas_server'] == 'plesk') {
            $data['saas_plesk_password'] = decrypt(get_option('saas_plesk_password'));
            $result = $this->createPleskDatabase($data, true);
        } else if ($data['saas_server'] == 'mysql') {
            $result = $this->createMysqlDatabase($data, true);
        } else {
            $result = $this->createLocalDatabase($data, true);
        }

        if ($result['type'] == 'success') {
            update_option('done_server_settings', 1);
        }
        set_alert($result['type'], $result['message']);
        redirect('saas/settings/index/server_settings');
    }

    public function resetore_database($id)
    {
        $company_info = $this->company_info($id, true);
        if (!empty($company_info)) {
            $this->create_tables($company_info);
            set_alert('success', _l('database_restored_successfully'));
        } else {
            set_alert('warning', _l('company_not_found'));
        }
        redirect('saas/companies');

    }

    public function create_sample_database()
    {
        $company_info = seed_db();
        $package_info = $this->db->get('tbl_saas_packages')->row();
        $data = array(
            'name' => get_option('saas_companyname') ?? 'Perfect Saas' . ' Seed',
            'email' => 'sample@perfectsaas.com',
            'domain' => 'company_seed',
            'password' => '123456',
            'country' => 'US',
            'package_id' => $package_info->id,
            'address' => get_option('invoice_company_address') ?? 'New York',
            'mobile' => get_option('invoice_company_phone') ?? '123456789',
            'timezone' => get_option('saas_default_timezone') ?? 'America/Halifax',
            'status' => 'running',
            'frequency' => 'lifetime',
            'expired_date' => date('Y-m-d', strtotime('+100 years')),
            'currency' => get_base_currency()->symbol ?? '$',
            'amount' => 0,
            'is_trial' => 'No',
            'trial_period' => 0,
            'for_seed' => 'yes',
        );
        $this->_table_name = 'tbl_saas_companies';
        $this->_primary_key = 'id';
        $company_id = (!empty($company_info->id) ? $company_info->id : null);
        $id = $this->save($data, $company_id);

        $result = $this->create_database('sample', true);

        if ($result['result'] == 'success') {
            $update = array(
                'db_name' => $result['db_name'],
                'status' => 'running',
            );
            $this->save($update, $id);

            $sub_h_data = array(
                'i_have_read_agree' => 'Yes',
                'active' => 1,
                'package_name' => ($data['package_name'] ?? $package_info->name),
                'calendar' => ($data['calendar'] ?? $package_info->calendar),
                'reports' => ($data['reports'] ?? $package_info->reports),
                'allowed_payment_modes' => ($data['allowed_payment_modes'] ?? $package_info->allowed_payment_modes),
                'modules' => ($data['modules'] ?? $package_info->modules),
                'allowed_themes' => ($data['allowed_themes'] ?? $package_info->allowed_themes),
                'disabled_modules' => ($data['disabled_modules'] ?? $package_info->disabled_modules),
            );

            $all_field = get_old_order_by('tbl_saas_package_field', array('status' => 'active'), 'order', 'asc');
            if (!empty($all_field)) {
                foreach ($all_field as $key => $field) {
                    $field_name = $field->field_name;
                    if ($field->field_type == 'text') {
                        $additional_field = 'additional_' . $field_name;
                        $sub_h_data[$additional_field] = 0;
                    }
                    $sub_h_data[$field_name] = 0;
                }
            }
            $this->_table_name = 'tbl_saas_companies_history';
            $this->_primary_key = 'id';
            if (empty($company_id)) {
                $this->save_old($sub_h_data);
            }
            update_option('created_sample_database', $result['db_name']);
            $msg = 'Sample database created successfully';
        } else {
            $msg = $result['error'];
        }
        set_alert($result['result'], $msg);
        redirect('saas/settings');
    }

    public
    function create_database($id, $fresh_db = null)
    {
        if ($id == 'sample') {
            $company_seed = seed_db();
            if (empty($company_seed)) {
                $company_info = new stdClass();
                $company_info->domain = 'db_company';
                $company_info->id = 'seed';
                $company_info->email = 'sample@perfectsaas.com';
                $company_info->name = get_option('saas_companyname') ?? 'Perfect Saas';
                $company_info->password = '123456';
                $company_info->country = 'US';
                $company_info->address = get_option('invoice_company_address') ?? 'New York';
                $company_info->mobile = get_option('invoice_company_phone') ?? '123456789';
                $company_info->timezone = get_option('saas_default_timezone') ?? 'America/Halifax';
            } else {
                $company_info = $company_seed;
            }
        } else {
            $company_info = $this->company_info($id, true);
        }

        if (!empty($company_info)) {
            if (ConfigItems('saas_server') === 'cpanel') {
                $server_result = $this->createCPanelDatabase($company_info);
            } elseif (ConfigItems('saas_server') === 'plesk') {
                $server_result = $this->createPleskDatabase($company_info);
            } elseif (ConfigItems('saas_server') === 'mysql') {
                $server_result = $this->createMysqlDatabase($company_info);
            } else {
                $server_result = $this->createLocalDatabase($company_info);
            }

            if (!empty($server_result['db_name'])) {
                if (empty($_POST['username'])) {
                    $server_result['status'] = 'running';
                }
                $this->_table_name = 'tbl_saas_companies';
                $this->_primary_key = 'id';
                if (is_numeric($id)) {
                    $this->save($server_result, $id);
                } else {
                    $this->save($server_result, $company_info->id);
                }
                $company_info->db_name = $server_result['db_name'];
                $this->create_tables($company_info, $fresh_db);
                // Create database tables
                $result['result'] = 'success';
                $result['db_name'] = $server_result['db_name'];
            } else {
                $result['error'] = $server_result['error'];
            }
            return $result;
        }
    }

    private function install_basic_data($companyInfo, $fresh_db)
    {
        // check if already exist the email address on tbl_users
        $db_name = $companyInfo->db_name;
        $old_db_name = $this->db->database;
        $uTable = db_prefix() . 'staff';
        if (empty($fresh_db)) {
            $this->db->query("INSERT INTO `" . $db_name . "`.`" . $uTable . "` SELECT * FROM `" . $old_db_name . "`.`" . $uTable . "` WHERE `" . $uTable . "`.`role` != 4");
        }
        $already_exist = $this->db->where('email', $companyInfo->email)->get($db_name . '.' . $uTable)->row();
        if (!empty($_POST['firstname'])) {
            $firstname = $_POST['firstname'];
        } else {
            $firstname = explode(' ', $companyInfo->name)[0];
        }
        if (!empty($_POST['lastname'])) {
            $lastname = $_POST['lastname'];
        } else {
            // check if lastname exist on company name field
            $isLastname = explode(' ', $companyInfo->name);
            if (count($isLastname) > 1) {
                $lastname = $isLastname[1];
            } else {
                $lastname = '';
            }
        }

        if (!empty($_POST['company_country'])) {
            $company_country = $_POST['company_country'];
        } else {
            $company_country = $companyInfo->country;
        }
        if (!empty($_POST['company_address'])) {
            $company_address = $_POST['company_address'];
        } else {
            $company_address = $companyInfo->address;
        }
        if (!empty($_POST['company_name'])) {
            $company_name = $_POST['company_name'];
        } else {
            $company_name = $companyInfo->name;
        }
        if (!empty($_POST['company_city'])) {
            $company_city = $_POST['company_city'];
        } else {
            $company_city = '';
        }
        if (!empty($_POST['company_phone'])) {
            $company_phone = $_POST['company_phone'];
        } else {
            $company_phone = $companyInfo->mobile;
        }
        if (!empty($_POST['timezone'])) {
            $timezone = $_POST['timezone'];
        } else {
            $timezone = $companyInfo->timezone;
        }
        if (empty($already_exist)) {
            if (!empty($_POST['password'])) {
                $password = $this->hash($_POST['password']);
            } else {
                if (empty($companyInfo->password)) {
                    $password = $this->hash('123456');
                } else {
                    $password = $this->hash($companyInfo->password);
                }
            }

            // insert username,password,email,role_id,activated,banned value into  $db_name.tbl_users
            $this->db->insert($db_name . '.' . $uTable, array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'password' => $password,
                'email' => $companyInfo->email,
                'datecreated' => date('Y-m-d H:i:s'),
                'admin' => 1,
                'active' => 1,
            ));
            $this->db->insert_id();
        }

        if (!empty($companyInfo->modules)) {
            $this->active_modules($companyInfo->modules, $companyInfo->company_history_id);
        }

        // update site_name and default_timezone in db_prefix() . 'options'
        $this->db->query("UPDATE `" . $db_name . "`.`" . db_prefix() . "options` SET `value` = '" . $timezone . "' WHERE `" . db_prefix() . "options`.`name` = 'default_timezone'");
        $di = time();
        $this->db->query("UPDATE `" . $db_name . "`.`" . db_prefix() . "options` SET `value` = '" . $di . "' WHERE `" . db_prefix() . "options`.`name` = 'di'");
        $this->db->query("UPDATE `" . $db_name . "`.`" . db_prefix() . "options` SET `value` = '" . $company_name . "' WHERE `" . db_prefix() . "options`.`name` = 'companyname'");
        $this->db->query("UPDATE `" . $db_name . "`.`" . db_prefix() . "options` SET `value` = '" . $company_name . "' WHERE `" . db_prefix() . "options`.`name` = 'invoice_company_name'");
        $this->db->query("UPDATE `" . $db_name . "`.`" . db_prefix() . "options` SET `value` = '" . $company_address . "' WHERE `" . db_prefix() . "options`.`name` = 'invoice_company_address'");
        $this->db->query("UPDATE `" . $db_name . "`.`" . db_prefix() . "options` SET `value` = '" . $company_city . "' WHERE `" . db_prefix() . "options`.`name` = 'invoice_company_city'");
        $this->db->query("UPDATE `" . $db_name . "`.`" . db_prefix() . "options` SET `value` = '" . $company_phone . "' WHERE `" . db_prefix() . "options`.`name` = 'invoice_company_phonenumber'");
        $this->db->query("UPDATE `" . $db_name . "`.`" . db_prefix() . "options` SET `value` = '" . $company_country . "' WHERE `" . db_prefix() . "options`.`name` = 'invoice_company_country_code'");
        return true;
    }

    private function fresh_seeds($companyInfo)
    {
        // get all tables except the tbl_saas_companies
        $db_name = $companyInfo->db_name;
        $this->db->database = $db_name;

        $tables = $this->db->list_tables();
        // drop all tables
        if (!empty($tables)) {
            foreach ($tables as $table) {
                // drop all tables with foreign key constraints and cascade delete
                $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
                $this->db->query("DROP TABLE IF EXISTS `" . $db_name . "`.`" . $table . "`");
            }
        }

        $seedPath = APP_MODULES_PATH . 'saas/assets/seeds/';
        include_once($seedPath . 'sqlparser.php');
        $parser = new SqlScriptParser();
        $sqlStatements = $parser->parse($seedPath . 'fresh.sql');
        $h = trim($this->db->hostname);
        $u = trim($this->db->username);
        $p = trim($this->db->password);
        $d = trim($this->db->database);
        $link = new mysqli($h, $u, $p, $d);

        foreach ($sqlStatements as $statement) {
            $distilled = $parser->removeComments($statement);
            if (!empty($distilled)) {
                $link->query($distilled);
            }
        }
        $link->close();

        return true;
    }

    private
    function create_tables($companyInfo, $fresh_db = null)
    {
        $sample_database = seed_db();
        $old_db_name = $this->db->database;
        if (!empty($sample_database)) {
            $db_name = $companyInfo->db_name;
            // get all tables from different database name is $sample_database
            $this->sample_db = config_db($sample_database->db_name);
            $seeds_tables = $this->sample_db->list_tables();
            if (empty($seeds_tables)) {
                $this->fresh_seeds($companyInfo);
                $this->sample_db = config_db($sample_database->db_name);
                $seeds_tables = $this->sample_db->list_tables();
            }
            $this->sample_db->close();
            $this->company_db = config_db($db_name);
            $tables = $this->company_db->list_tables();
            $this->company_db->close();

            $this->db->query("SET SESSION sql_mode = ''");
            // drop all tables
            foreach ($tables as $table) {
                // drop all tables with foreign key constraints and cascade delete
                $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
                $this->db->query("DROP TABLE IF EXISTS `" . $db_name . "`.`" . $table . "`");
            }

            $this->db->db_debug = false;
            $except_tables = array(
                db_prefix() . 'countries',
                db_prefix() . 'currencies',
                db_prefix() . 'emailtemplates',
                db_prefix() . 'estimate_request_status',
                db_prefix() . 'leads_email_integration',
                db_prefix() . 'leads_sources',
                db_prefix() . 'leads_status',
                db_prefix() . 'migrations',
                db_prefix() . 'options',
                db_prefix() . 'payment_modes',
                db_prefix() . 'roles',
                db_prefix() . 'tickets_priorities',
                db_prefix() . 'tickets_status',
            );
            foreach ($seeds_tables as $table) {
                $this->db->query("CREATE TABLE IF NOT EXISTS `" . $db_name . "`.`" . $table . "` LIKE `" . $old_db_name . "`.`" . $table . "`");
                // check if the tbl_users table then skip the tbl_users table
                if ($table == db_prefix() . 'modules' || $table == db_prefix() . 'staff') {
                    continue;
                }
                if (!empty($fresh_db)) {
                    if (in_array($table, $except_tables)) {
                        $this->db->query("INSERT INTO `" . $db_name . "`.`" . $table . "` SELECT * FROM `" . $old_db_name . "`.`" . $table . "`");
                    }
                } else {
                    $this->db->query("INSERT INTO `" . $db_name . "`.`" . $table . "` SELECT * FROM `" . $old_db_name . "`.`" . $table . "`");
                }
            }
        } else {
            $this->fresh_seeds($companyInfo);
            $old_db_name = $this->db->database;
        }

        $this->db->database = $old_db_name;
        $this->install_basic_data($companyInfo, $fresh_db);

        $this->db->db_debug = true;
        return true;
    }

    /**
     * Create cPanel database
     *
     * @param Instance $instance
     * @return void
     */
    private
    function createCPanelDatabase($company_info, $test = null)
    {
        // load Xmlapi library
        include_once(APP_MODULES_PATH . 'saas/libraries/Xmlapi.php');
        $cpanel_password = decrypt(ConfigItems('saas_cpanel_password'));
        $output = ConfigItems('saas_cpanel_output');
        $db_username = $this->db->username;
        $xmlapi = new xmlapi(ConfigItems('saas_cpanel_host'));
        $xmlapi->password_auth(ConfigItems('saas_cpanel_username'), $cpanel_password);
        $xmlapi->set_port(ConfigItems('saas_cpanel_port'));
        $xmlapi->set_debug(1);
        $xmlapi->set_output($output);
        if (!empty($test)) {
            try {
                $status = $xmlapi->api1_query(ConfigItems('saas_cpanel_username'), "cpanel", "api1_get_cpanel_revision");
                if (!empty($status)) {
                    $status = json_decode($status);
                }
                if (!empty($status->data->reason)) {
                    $shortMessage = str_replace("'", "", $status->data->reason);
                    $shortMessage = str_replace('"', '', $shortMessage);
                    $result['message'] = $shortMessage . ' please input correct Details';
                    $result['type'] = 'warning';
                } else {
                    $result['message'] = _l('cpanel_connection_success');
                    $result['type'] = 'success';
                }
                return $result;
            } catch (\Exception $e) {
                // get short message and remove special characters like ' and " to avoid json error
                $shortMessage = substr($e->getMessage(), 0, 100);
                $shortMessage = str_replace("'", "", $shortMessage);
                $shortMessage = str_replace('"', '', $shortMessage);
                $result['message'] = $shortMessage;
                $result['type'] = 'warning';
                return $result;
            }
        }
        $cpaneluser = ConfigItems('saas_cpanel_username');
        if ($output == 'json') {
            $cpaneluser_short = ConfigItems('saas_cpanel_username');
        } else {
            $cpaneluser_short = substr(ConfigItems('saas_cpanel_username'), 0, 8);
        }
        $databasename = $company_info->domain . '_' . (!empty($company_info->companies_id) ? $company_info->companies_id : $company_info->id);
        $create_db = $xmlapi->api1_query($cpaneluser, "Mysql", "adddb", array($databasename));
        if ($output == 'json' && !empty($create_db)) {
            $create_db = json_decode($create_db);
        }
        $assign_permission = $xmlapi->api1_query($cpaneluser, 'Mysql', 'adduserdb', array('' . $databasename . '', '' . $db_username . '', 'all'));
        // $assign_permission = $xmlapi->api1_query($cpaneluser, "Mysql", "adduserdb", array($cpaneluser_short . "_" . $databasename, $db_username, 'all'));
        $databasename = $cpaneluser_short . "_" . $databasename;
        if ($output == 'json' && !empty($assign_permission)) {
            $assign_permission = json_decode($assign_permission);
        }

        if (!empty($assign_permission->error)) {
            return [
                'warning' => $create_db->error
            ];
        }
        return [
            'db_name' => $databasename
        ];
    }

    /**
     * Create Plesk database
     *
     * @param Instance $instance
     * @return array
     */
    private
    function createPleskDatabase($company_info, $test = null)
    {
        require APP_MODULES_PATH . 'saas/vendor/autoload.php';
        $plesk_password = decrypt(get_option('saas_plesk_password'));
        $host = get_option('saas_plesk_host');
        $login = get_option('saas_plesk_username');
        $cpaneluser_short = substr(get_option('saas_plesk_username'), 0, 8);
        $password = $plesk_password;
        $client = new \PleskX\Api\Client($host);
        $client->setCredentials($login, $password);
        if (!empty($test)) {
            try {
                $client->request('<server><get_protos/></server>');
                $result['message'] = _l('plesk_connection_success');
                $result['type'] = 'success';
            } catch (\Exception $e) {
                $shortMessage = substr($e->getMessage(), 0, 100);
                $shortMessage = str_replace("'", "", $shortMessage);
                $shortMessage = str_replace('"', '', $shortMessage);
                $result['message'] = $shortMessage;
                $result['type'] = 'warning';
            }
            return $result;
        } else {
            // Prepare the database name and webspace ID
            $databasename = $company_info->domain . '_' . (!empty($company_info->companies_id) ? $company_info->companies_id : $company_info->id) . '_db';
            $webspace_id = get_option('saas_plesk_webspace_id');
            $webspace_id = $webspace_id ?: 1;
            $db_name = $this->db->database;

            try {
                // Step 1: Check if the database exists from getAll(string $field, $value): array method
                $result = $client->database()->getAll('webspace-id', $webspace_id);
                $UserId = array_filter($result, function ($database) use ($db_name) {
                    return $database->name == $db_name;
                });

                $defaultUserInfo = reset($UserId);
                $databaseInfo = array_filter($result, function ($database) use ($databasename) {
                    return $database->name == $databasename;
                });

                // Step 2: Create the database if it doesn't exist
                if (empty($databaseInfo)) {
                    $databaseInfo = $client->database()->create([
                        'webspace-id' => $webspace_id,
                        'name' => $databasename,
                        'type' => 'mysql',
                        'db-server-id' => $defaultUserInfo->dbServerId,
                    ]);
                } else {
                    $databaseInfo = reset($databaseInfo);
                }
                return [
                    'db_name' => $databasename
                ];

            } catch (\Exception $e) {
                $shortMessage = substr($e->getMessage(), 0, 100);
                $shortMessage = str_replace("'", "", $shortMessage);
                $shortMessage = str_replace('"', '', $shortMessage);
                return [
                    'warning' => $shortMessage
                ];
            }
        }
    }


    private function createMysqlDatabase($company_info, $test = null)
    {

        $db_mysql_host = get_option('saas_mysql_host');
        $db_mysql_username = get_option('saas_mysql_username');
        $db_mysql_password = decrypt(get_option('saas_mysql_password'));
        $db_mysql_port = get_option('saas_mysql_port');
        if (!empty($test)) {
            $db_name = 'db_dummy';
            // create dummy database using mysql connection
            try {
                $conn = new PDO("mysql:host=$db_mysql_host;port=$db_mysql_port", $db_mysql_username, $db_mysql_password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->query('CREATE DATABASE IF NOT EXISTS ' . $db_name /*!40100 CHARACTER SET utf8 COLLATE 'utf8_general_ci' */);
                $conn->query('DROP DATABASE ' . $db_name);
                return [
                    'message' => _l('local_connection_success'),
                    'type' => 'success'
                ];
            } catch (\Exception $e) {
                $shortMessage = substr($e->getMessage(), 0, 100);
                $shortMessage = str_replace("'", "", $shortMessage);
                $shortMessage = str_replace('"', '', $shortMessage);
                return [
                    'message' => $shortMessage,
                    'type' => 'warning'
                ];
            }
        } else {
            $databasename = $company_info->domain . '_' . (!empty($company_info->companies_id) ? $company_info->companies_id : $company_info->id);
            try {
                $conn = new PDO("mysql:host=$db_mysql_host;port=$db_mysql_port", $db_mysql_username, $db_mysql_password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->query('CREATE DATABASE IF NOT EXISTS ' . $databasename /*!40100 CHARACTER SET utf8 COLLATE 'utf8_general_ci' */);
                return [
                    'db_name' => $databasename
                ];
            } catch (\Exception $e) {
                $shortMessage = substr($e->getMessage(), 0, 100);
                $shortMessage = str_replace("'", "", $shortMessage);
                $shortMessage = str_replace('"', '', $shortMessage);
                return [
                    'message' => $shortMessage,
                    'type' => 'warning'
                ];
            }
        }

    }


    /**
     * Create local database
     *
     * @param Instance $instance
     * @return void
     */
    private
    function createLocalDatabase($company_info, $test = null)
    {
        if (!empty($test)) {
            // check database can be created or not created on local server a dummy database
            $db_name = 'db_dummy';
            try {
                $this->db->query('CREATE DATABASE IF NOT EXISTS ' . $db_name /*!40100 CHARACTER SET utf8 COLLATE 'utf8_general_ci' */);
                $this->db->query('DROP DATABASE ' . $db_name);
                return [
                    'message' => _l('local_connection_success'),
                    'type' => 'success'
                ];
            } catch (\Exception $e) {
                $shortMessage = substr($e->getMessage(), 0, 100);
                $shortMessage = str_replace("'", "", $shortMessage);
                $shortMessage = str_replace('"', '', $shortMessage);
                return [
                    'message' => $shortMessage,
                    'type' => 'warning'
                ];
            }
        } else {
            $db_name = $company_info->domain . '_' . (!empty($company_info->companies_id) ? $company_info->companies_id : $company_info->id);
            $this->db->query('CREATE DATABASE IF NOT EXISTS ' . $db_name /*!40100 CHARACTER SET utf8 COLLATE 'utf8_general_ci' */);
            return [
                'db_name' => $db_name
            ];
        }
    }

    public
    function send_activation_token_email($id)
    {
        $company_info = get_row('tbl_saas_companies', array('id' => $id));
        try {
            $this->load->model('staff_model');
            $allSuperadmin = $this->staff_model->get('', ['role' => 4, 'active' => 1]);
            $users = [];
            foreach ($allSuperadmin as $key => $value) {
                add_notification([
                    'description' => 'callbacks_new_company_notification',
                    'touserid' => $value['staffid'],
                    'fromcompany' => true,
                    'link' => 'saas/companies/details/' . $id,
                ]);
                $users[] = $value['staffid'];
            }
            pusher_trigger_notification(array_unique($users));

            $send = send_mail_template('saas_token_activate_account', SaaS_MODULE, $company_info->email, $company_info->id, $company_info);
            if (!$send) {
                $type = "warning";
                $message = _l('email_not_sent_please_configure_email_settings');
                set_alert($type, $message);
                redirect('saas/settings/index/email_settings');
            }
            return $send;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    public
    function send_welcome_email($id)
    {
        $company_info = $this->company_info($id);
        $send = send_mail_template('saas_welcome_mail', SaaS_MODULE, $company_info->email, $company_info->id, $company_info);
        if (!$send) {
            $type = "warning";
            $message = _l('email_not_sent_please_configure_email_settings');
            set_alert($type, $message);
            redirect('saas/settings/index/email_settings');
        }
        return $send;
    }

    public
    function send_subscription_expired_email($id)
    {
        $company_info = $this->company_info($id);
        $send = send_mail_template('saas_company_expiration_email', SaaS_MODULE, $company_info->email, $company_info->id, $company_info);
        if (!$send) {
            $type = "warning";
            $message = _l('email_not_sent_please_configure_email_settings');
            set_alert($type, $message);
            redirect('saas/settings/index/email_settings');
        }
        return $send;

    }

    public
    function send_inactive_email($id)
    {
        $company_info = $this->company_info($id);
        $send = send_mail_template('saas_inactive_company_email', SaaS_MODULE, $company_info->email, $company_info->id, $company_info);
        if (!$send) {
            $type = "warning";
            $message = _l('email_not_sent_please_configure_email_settings');
            set_alert($type, $message);
            redirect('saas/settings/index/email_settings');
        }
        return $send;
    }

    public
    function is_company_active()
    {
        // get activation code from get request
        $activation_code = $this->input->get('c', TRUE);
        $subdomain = $this->is_subdomain();
        if (!empty($activation_code)) {
            $activation_code = url_decode($activation_code);
            $where = array('status' => 'running', 'activation_code' => $activation_code);
        } elseif (!empty($subdomain)) {
            $where = array('status' => 'running', 'domain' => $subdomain);
        }
        if (!empty($where)) {
            $companyInfo = get_row('tbl_saas_companies', $where);
            if (!empty($companyInfo)) {
                return $companyInfo;
            }
        }
        return false;
    }


    private
    function is_subdomain($domain = null)
    {
        $isIP = @($_SERVER['SERVER_ADDR'] === trim($_SERVER['HTTP_HOST'], '[]'));
        if (!empty($isIP)) {
            return false;
        }
        $default_url = get_option('default_url');
        $base_url = guess_base_url();
        $scheme = parse_url($default_url, PHP_URL_SCHEME);
        if (empty($scheme)) {
            $default_url = 'http://' . $default_url;
        }
        $default_url = parse_url($default_url, PHP_URL_HOST);
        $base_url = parse_url($base_url, PHP_URL_HOST);
        // check www exist in base_url then remove it
        if (strpos($base_url, 'www.') !== false) {
            $base_url = str_replace('www.', '', $base_url);
        }
        // check www exist in default_url then remove it
        if (strpos($default_url, 'www.') !== false) {
            $default_url = str_replace('www.', '', $default_url);
        }
        // check default_url and base_url is not same then return the subdomain
        if ($default_url != $base_url) {
            // return first subdomain
            $subdomain = explode('.', $base_url);
            return $subdomain[0];
        }
        return false;
    }


    public function update_company_history($data, $history_id = null)
    {

        if (!empty($history_id)) {
            if (!empty($data['delete_modules']) && count($data['delete_modules']) > 0) {
                $this->deactive_modules($data['delete_modules'], $history_id);
            }

            if (!empty($data['modules'])) {
                $this->active_modules($data['modules'], $history_id);
            }
        }


        $package_id = $data['package_id'];
        $package_info = get_old_result('tbl_saas_packages', array('id' => $package_id), false);

        $sub_h_data = array(
            'i_have_read_agree' => 'Yes',
            'active' => 1,
            'package_name' => ($data['package_name'] ?? $package_info->name),
            'calendar' => ($data['calendar'] ?? $package_info->calendar),
            'reports' => ($data['reports'] ?? $package_info->reports),
            'allowed_payment_modes' => ($data['allowed_payment_modes'] ?? $package_info->allowed_payment_modes),
            'modules' => ($data['modules'] ?? $package_info->modules),
            'allowed_themes' => ($data['allowed_themes'] ?? $package_info->allowed_themes),
            'disabled_modules' => ($data['disabled_modules'] ?? $package_info->disabled_modules),
        );

        $all_field = get_old_order_by('tbl_saas_package_field', array('status' => 'active'), 'order', 'asc');
        if (!empty($all_field)) {
            foreach ($all_field as $key => $field) {
                $field_name = $field->field_name;
                if ($field->field_type == 'text') {
                    $additional_field = 'additional_' . $field_name;
                    $sub_h_data[$additional_field] = $data[$additional_field] ?? $package_info->$additional_field;
                }
                $sub_h_data[$field_name] = $data[$field_name] ?? $package_info->$field_name;
            }
        }

        if (empty($history_id)) {
            $sub_h_data['created_at'] = date("Y-m-d H:i:s");
        }
        if (!empty($data['companies_id'])) {
            $sub_h_data['companies_id'] = $data['companies_id'];
        }
        if (!empty($data['currency'])) {
            $sub_h_data['currency'] = $data['currency'];
        }
        if (!empty($data['frequency'])) {
            $sub_h_data['frequency'] = $data['frequency'];
        }
        if (!empty($data['expired_date'])) {
            $sub_h_data['validity'] = $data['expired_date'];
        }
        if (!empty($data['amount'])) {
            $sub_h_data['amount'] = $data['amount'];
        }
        if (!empty($data['ip'])) {
            $sub_h_data['ip'] = $data['ip'];
        }

        $this->_table_name = 'tbl_saas_companies_history';
        $this->_primary_key = 'id';
        $companies_history_id = $this->save_old($sub_h_data, $history_id);

        if (empty($history_id)) {

            if (!empty($data['delete_modules']) && count($data['delete_modules']) > 0) {
                $this->deactive_modules($data['delete_modules'], $companies_history_id);
            }

            if (!empty($data['modules'])) {
                $this->active_modules($data['modules'], $companies_history_id);
            }
        }
        return $companies_history_id;
    }

    public function deactive_modules($modules, $companies_history_id)
    {
        $companyInfo = $this->select_data('tbl_saas_companies', 'tbl_saas_companies.*,tbl_saas_companies_history.package_name,tbl_saas_companies_history.id as company_history_id', NULL, array('tbl_saas_companies_history.id' => $companies_history_id), ['tbl_saas_companies_history' => 'tbl_saas_companies.id = tbl_saas_companies_history.companies_id'], 'row');
        if (!empty($companyInfo->db_name)) {
            $db_name = $companyInfo->db_name;
            $this->new_db = config_db($db_name);

            if (!empty($modules)) {
                foreach ($modules as $key => $module) {
                    // set session for new database
                    $this->session->set_userdata('new_db_name', $db_name);
                    hooks()->add_action('pre_uninstall_module', 'saas_db_activate_module');
                    $this->app_modules->deactivate($module);
                }
                // unset session for new database
                $this->session->unset_userdata('new_db_name');
                $this->db = config_db(null, true);
            }

        }
    }

    public function active_modules($modules, $companies_history_id): bool
    {
        $all_modules = $this->app_modules->get();
        $companyInfo = $this->select_data('tbl_saas_companies', 'tbl_saas_companies.*,tbl_saas_companies_history.package_name,tbl_saas_companies_history.id as company_history_id', NULL, array('tbl_saas_companies_history.id' => $companies_history_id), ['tbl_saas_companies_history' => 'tbl_saas_companies.id = tbl_saas_companies_history.companies_id'], 'row');
        if (!empty($companyInfo->db_name)) {
            $db_name = $companyInfo->db_name;
            $this->new_db = config_db($db_name);

            // get all modules from database
            $all_module = $this->new_db->select('module_name')->get(db_prefix() . 'modules')->result_array();
            $new_modules = unserialize($modules) ?? [];
            $all_module = array_column($all_module, 'module_name');

            $diff = array_diff($new_modules, $all_module);

            $activated = [];
            if (!empty($diff)) {
                foreach ($diff as $module) {
                    // set session for new database
                    $this->session->set_userdata('new_db_name', $db_name);
                    hooks()->add_action('pre_activate_module', 'saas_db_activate_module');
                    $result = $this->app_modules->activate($module);
                    if (!empty($result)) {
                        $activated[] = $module;
                    }
                }

                // unset session for new database
                $this->session->unset_userdata('new_db_name');
                $this->db = config_db(null, true);
            }
            // check different from $diff and activated
            $not_activated = array_diff($activated, $diff);
            // check if not activated module exist then remove it from $new_modules
            if (!empty($not_activated)) {
                $new_modules = array_diff($new_modules, $not_activated);
            }

            if (!empty($new_modules) && count($new_modules) > 0) {
                // truncate all modules
                $this->new_db->truncate(db_prefix() . 'modules');
                $new_modules[] = 'saas';
                foreach ($new_modules as $new_module) {

                    $installed_version = array_column(array_filter($all_modules, function ($all_module) use ($new_module) {
                        return $all_module['system_name'] == $new_module;
                    }), 'headers')[0]['version'];

                    $this->new_db->where('module_name', $new_module);
                    $this->new_db->insert(db_prefix() . 'modules', ['module_name' => $new_module, 'installed_version' => $installed_version, 'active' => 1]);
                }
            }

        }
        return true;

    }

    public
    function update_package($company_id, $post_data)
    {

        $package_id = $post_data['package_id'];
        $package_info = get_old_result('tbl_saas_packages', array('id' => $package_id), false);

        $data['updated_date'] = date('Y-m-d H:i:s');
        $data['updated_by'] = get_staff_user_id();
        $billing_cycle = $this->input->post('billing_cycle', true);
        if (empty($billing_cycle)) {
            $billing_cycle = $post_data['billing_cycle'];
        }
        $expired_date = $this->input->post('expired_date', true);
        if (empty($expired_date)) {
            $expired_date = $post_data['expired_date'];
        }
        $mark_paid = $this->input->post('mark_paid', true);
        if (empty($mark_paid)) {
            $mark_paid = $post_data['mark_paid'];
        }

        $data['frequency'] = str_replace('_price', '', $billing_cycle);;
        $data['trial_period'] = $package_info->trial_period;
        $data['is_trial'] = 'Yes';
        $data['expired_date'] = $expired_date;;
        $data['package_id'] = $package_id;
        $data['currency'] = get_base_currency()->name;
        $data['amount'] = $package_info->$billing_cycle;
        if (!empty($mark_paid)) {
            $data['status'] = 'running';
            $data['is_trial'] = 'No';
            $data['trial_period'] = 0;
        }

        $this->_table_name = 'tbl_saas_companies';
        $this->_primary_key = 'id';
        $this->save_old($data, $company_id);

        $new_module = $post_data['new_module'];
        $new_limit = $post_data['new_limit'];
        $company_history_id = null;
        if (!empty($new_limit) || !empty($new_module)) {
            $companyInfo = get_company_subscription_by_id($company_id);

            $company_history_id = $companyInfo->company_history_id;
            if (!empty($new_limit)) {
                $new_limit = unserialize($new_limit);
                foreach ($new_limit as $key => $limit) {
                    if (!empty($limit)) {
                        if ($key === 'disk_space') {
                            $old_disk_space = $companyInfo->$key; // 1GB
                            // convert GB to byte and add new limit
                            $old_disk_space = convertGBToBytes($old_disk_space) + $limit * 1024 * 1024;
                            $data[$key] = convertSize($old_disk_space, 2);
                        } else {
                            $data[$key] = $companyInfo->$key + $limit;
                        }

                    }
                }
            }
            if (!empty($new_module)) {
                $new_module = unserialize($new_module);
                $old_module = $companyInfo->modules ? unserialize($companyInfo->modules) : [];
                // switch $new_module key and value and reset array key
                $new_module = array_flip($new_module);
                $new_module = array_values($new_module);
                // add the new module with old module if not exist
                $data['allowed_modules'] = serialize(array_unique(array_merge($old_module, $new_module)));
            }
        } else {
            $this->_table_name = 'tbl_saas_companies_history';
            $this->_primary_key = 'companies_id';
            $this->save_old(array('active' => 0), $company_id);
        }

        $data['companies_id'] = $company_id;
        $data['ip'] = $this->input->ip_address();

        $companies_history_id = $this->update_company_history($data, $company_history_id);

        $data = $post_data;
        $data['companies_id'] = $company_id;
        $data['companies_history_id'] = $companies_history_id;
        if (!empty($mark_paid)) {
            $this->packagePayment($data);
        }

        log_activity('Company Package Updated [Company ID: ' . $company_id . ', Package ID: ' . $package_id . ']');

        $this->send_email_to_company($company_id);

        return true;
    }

    public function get_package_info($package_id = null)
    {
        $where = array();
        if (!empty($package_id)) {
            $where['id'] = $package_id;
            $package_info = get_old_result('tbl_saas_packages', $where, false);
        } else {
            // get package info where recommended is Yes if not then get first package
            $package_info = get_old_result('tbl_saas_packages', array('recommended' => 'Yes'), false);
            if (empty($package_info)) {
                $package_info = get_old_result('tbl_saas_packages', array(), false);
            }
        }
        return apply_coupon($package_info);
    }

    public
    function send_email_to_company($company_id)
    {
        // send email to company for assign_new_package
        $company_info = $this->select_old_data('tbl_saas_companies', 'tbl_saas_companies.*,tbl_saas_companies_history.package_name,tbl_saas_companies_history.id as company_history_id', NULL, array('tbl_saas_companies.id' => $company_id, 'tbl_saas_companies_history.active' => 1), ['tbl_saas_companies_history' => 'tbl_saas_companies.id = tbl_saas_companies_history.companies_id'], 'row');
        $send = send_mail_template('saas_assign_new_package', SaaS_MODULE, $company_info->email, $company_info->id, $company_info);
        if (!$send) {
            $type = "warning";
            $message = _l('email_not_sent_please_configure_email_settings');
            set_alert($type, $message);
            redirect('saas/settings/index/email_settings');
        }
        return $send;
    }

    public
    function packagePayment($data)
    {
        $coupon_code = '';
        $package_id = $data['package_id'];
        $billing_cycle = $data['billing_cycle'];
        $is_coupon_applied = $data['is_coupon'];
        $coupon_code = $data['coupon_code'];
        $reference_no = (!empty($data['reference_no'])) ? $data['reference_no'] : 'SAAS-' . date('Ymd') . '-' . rand(100000, 999999);

        $discount_percentage = 0;
        $discount_amount = 0;

        if (!empty($is_coupon_applied)) {
            $where = array('code' => $coupon_code, 'status' => 'active');
            $coupon_info = get_old_result('tbl_saas_coupon', $where, false);

            if (!empty($coupon_info)) {
                $user_id = get_staff_user_id();
                if (!empty($user_id)) {
                    $where = array('user_id' => $user_id, 'coupon' => $coupon_code);
                } else {
                    $where = array('email' => $data['email'], 'coupon' => $coupon_code);
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
                    $applied_coupon_id = $this->saas_model->save_old($coupon_data);
                }
            }
        }


        // save payment info
        $payment_date = $this->input->post('payment_date', true);
        $pdata = array(
            'reference_no' => $reference_no,
            'companies_history_id' => $data['companies_history_id'],
            'companies_id' => $data['companies_id'],
            'transaction_id' => 'TRN' . date('Ymd') . date('His') . '_' . substr(number_format(time() * rand(), 0, '', ''), 0, 6),
            'payment_method' => (!empty($data['payment_method'])) ? $data['payment_method'] : 'manual',
            'currency' => $data['currency'] ?? get_base_currency()->name,
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
        return $this->saas_model->save_old($pdata);
    }

    public
    function select_data($table, $id, $value = null, $where = null, $join = null, $row = null)
    {
        if ($id == '*') {
            $this->db->select('*', false);
        } else {
            $this->db->select("$id,$value", false);
        }
        $this->db->from($table);
        if (!empty($join)) {
            foreach ($join as $tbl => $wh) {
                $this->db->join($tbl, $wh, 'left');
            }
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        if (!empty($row) && $row === 'row') {
            $result = $query->row();
        } else if (!empty($row) && $row === 'object') {
            $result = $query->result();
        } else {
            $result = $query->result_array();
        }
        if (!empty($row)) {
            return $result;
        } else {
            if (strpos($id, ".") !== false) {
                $id = trim(strstr($id, '.'), '.');
            }
            if (strpos($value, ".") !== false) {
                $value = trim(strstr($value, '.'), '.');
            }
            // explode id  by _ and get the first index
            $select = explode('_', $id);
            $select = $select[0];
            $returnResult = [lang('select_data', lang($select))];
            if (!empty($result)) {
                $returnResult += array_column($result, $value, $id);
            }
        }
        return $returnResult;
    }

    public
    function array_from_post($fields)
    {
        $data = array();
        foreach ($fields as $field) {
            $data[$field] = $this->input->post($field, true);
        }
        return $data;
    }

    public
    function save($data, $id = NULL)
    {
        // Insert
        if ($id === NULL) {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        } // Update
        else {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }
        return $id;
    }

    public function save_batch($data, $id = NULL)
    {
        if ($id === NULL) {
            $this->db->insert_batch($this->_table_name, $data);
        } // Update
        else {
            $this->db->update_batch($this->_table_name, $data, $id);
        }
        return $id;
    }

    public
    function delete($where)
    {
        if (!is_array($where)) {
            $id = $where;
            $where = array($this->_primary_key => $id);
        }
        $this->db->where($where);
        $this->db->delete($this->_table_name);
    }

    public
    function delete_old($where)
    {
        if (!is_array($where)) {
            $id = $where;
            $where = array($this->_primary_key => $id);
        }
        $this->old_db = config_db(true, true);
        $this->old_db->where($where);
        $this->old_db->delete($this->_table_name);
    }

    public
    function hash($password)
    {
        $password = app_hash_password($password);
        return $password;
    }

    public
    function check_update($table, $where, $id = Null)
    {
        $this->db->select('*', FALSE);
        $this->db->from($table);
        if ($id != null) {
            $this->db->where($id);
        }
        $this->db->where($where);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function getAffiliateUser($id)
    {
        $this->old_db = config_db(true, true);

        $select = 'tbl_saas_affiliate_users.*,
        CONCAT(tbl_saas_affiliate_users.first_name, " ", tbl_saas_affiliate_users.last_name) as fullname,
        (SELECT COUNT(tbl_saas_companies.id) FROM tbl_saas_companies WHERE tbl_saas_affiliate_users.user_id = tbl_saas_companies.referral_by) AS total_referral,
        COALESCE((SELECT SUM(tbl_saas_affiliate_payouts.amount) FROM tbl_saas_affiliate_payouts WHERE tbl_saas_affiliate_payouts.user_id = tbl_saas_affiliate_users.user_id AND tbl_saas_affiliate_payouts.status = "approved"), 0) AS withdrawal_amount,';

        $this->old_db->select($select, FALSE);
        $this->old_db->from('tbl_saas_affiliate_users');


        $this->old_db->join('tbl_saas_affiliate_payouts', 'tbl_saas_affiliate_payouts.user_id=tbl_saas_affiliate_users.user_id', 'left');
        $this->old_db->join('tbl_saas_companies', 'tbl_saas_affiliate_users.user_id=tbl_saas_companies.referral_by', 'left');
        $this->old_db->where('tbl_saas_affiliate_users.user_id', $id);
        $this->old_db->group_by('tbl_saas_affiliate_users.user_id');
        $query = $this->old_db->get();
        $result = $query->row();
        $total = $this->old_db->select_sum('get_amount')->where('referral_by', $id)->get('tbl_saas_affiliates')->row()->get_amount;
        if ($total == null) {
            $total = 0;
        }
        if (!empty($result)) {
            $result->total_balance = $total;
        } else {
            $result = new stdClass();
            $result->total_balance = 0;
            $result->withdrawal_amount = 0;

        }
        return $result;

    }


    public function add_affiliate($companyId, $data, $first_resistration = null)
    {
        $companyInfo = get_old_result('tbl_saas_companies', ['id' => $companyId], false);
        if ($companyInfo->referral_by != null) {
            $user_info = get_old_result('tbl_saas_affiliate_users', ['user_id' => $companyInfo->referral_by], false);
            $paymentRules = get_option('payment_rules_for_affiliates');

            $this->_table_name = 'tbl_saas_affiliates';
            $this->_primary_key = 'affiliate_id';
            $affiliateRule = get_option('affiliate_rule');
            $commissionType = get_option('affiliate_commission_type');
            $commissionValue = get_option('affiliate_commission_amount');
            $a_data = array(
                'referral_to' => $companyId,
                'referral_by' => $user_info->user_id,
                'amount_was' => $data['amount'],
                'affiliate_rule' => $affiliateRule,
                'affiliate_payment_rules' => $paymentRules,
                'commission_type' => $commissionType,
                'commission_value' => $commissionValue,
                'get_amount' => $commissionType == 'percentage' ? ($data['amount'] * $commissionValue) / 100 : $commissionValue,
                'payment_method' => 'direct',
                'date' => date('Y-m-d'),
            );

            if (!empty($first_resistration)) {
                if ($paymentRules == 'no_payment_required') {
                    if ($affiliateRule === "only_first_subscription") {
                        $check = get_row('tbl_saas_affiliates', array('referral_by' => $user_info->user_id));
                        if (empty($check)) {
                            $this->save($a_data);
                        }
                    } else {
                        $this->save($a_data);
                    }
                }
            } else {
                $this->save($a_data);
            }
        }
    }

    public function get_packages()
    {
        return get_old_result('tbl_saas_packages', array('status' => 'published'));
    }

    public function uploadImage($field, $path = 'uploads/')
    {
        $config['upload_path'] = (!empty($path) ? $path : 'uploads/');
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|csv|txt|zip|svg';
        $config['max_size'] = '1000000';
        $config['overwrite'] = TRUE;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "warning";
            $message = $error;
            set_alert($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $fdata = $this->upload->data();
            $file_data['fileName'] = $fdata['file_name'];
            $file_data['path'] = $config['upload_path'] . $fdata['file_name'];
            $file_data['fullPath'] = $fdata['full_path'];
            $file_data['ext'] = $fdata['file_ext'];
            $file_data['size'] = $fdata['file_size'];
            $file_data['is_image'] = $fdata['is_image'];
            $file_data['image_width'] = $fdata['image_width'];
            $file_data['image_height'] = $fdata['image_height'];

            return $file_data;
        }
    }

    public function get_payment_modes()
    {
        $this->load->model('payment_modes_model');
        return $this->payment_modes_model->get();
    }

    public
    function update_option($input_data): bool
    {
        $table = db_prefix() . 'options';
        if (!empty($input_data)) {
            foreach ($input_data as $key => $value) {
                $where = array('name' => $key);
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where($where)->update($table, $data);
                $exists = $this->db->where($where)->get($table);
                if ($exists->num_rows() == 0) {
                    $this->db->insert($table, array("name" => $key, "value" => $value));
                }
            }
        }
        return true;
    }

    public function check_for_merge_invoice($client_id, $current_invoice = '')
    {

        if ($current_invoice != '') {
            $this->db->select('status');
            $this->db->where('id', $current_invoice);
            $row = $this->db->get(db_prefix() . 'invoices')->row();
            // Cant merge on paid invoice and partialy paid and cancelled
            if ($row->status == self::STATUS_PAID || $row->status == self::STATUS_PARTIALLY || $row->status == self::STATUS_CANCELLED) {
                return [];
            }
        }

        $statuses = [
            self::STATUS_UNPAID,
            self::STATUS_OVERDUE,
            self::STATUS_DRAFT,
        ];

        $this->db->select('id');
        $this->db->where('clientid', $client_id);
        $this->db->where('STATUS IN (' . implode(', ', $statuses) . ')');


        if ($current_invoice != '') {
            $this->db->where('id !=', $current_invoice);
        }

        $invoices = $this->db->get(db_prefix() . 'invoices')->result_array();
        $invoices = hooks()->apply_filters('invoices_ids_available_for_merging', $invoices);

        $_invoices = [];

        foreach ($invoices as $invoice) {
            $inv = $this->invoices_model->get($invoice['id']);
            if ($inv) {
                $_invoices[] = $inv;
            }
        }

        return $_invoices;
    }

    public function save_client($company_id, $password = null, $bycript = null)
    {
        $companyInfo = get_old_result('tbl_saas_companies', array('id' => $company_id), false);
        $country = 0;
        if (!empty($companyInfo->country)) {
            if (!is_numeric($companyInfo->country)) {
                $country = get_old_result(db_prefix() . 'countries', array('short_name' => $companyInfo->country), false);
                if (!empty($country)) {
                    $country = $country->country_id ?? 0;
                }
            } else {
                $country = $companyInfo->country;
            }
        }
        $address = $companyInfo->address ?? null;

        $c_data = array();
        $c_data['company'] = $companyInfo->name;
        $c_data['vat'] = null;
        $c_data['phonenumber'] = $companyInfo->mobile ?? null;
        $c_data['website'] = null;
        $c_data['default_currency'] = 0;
        $c_data['default_language'] = null;
        $c_data['address'] = $address;
        $c_data['city'] = null;
        $c_data['state'] = null;
        $c_data['zip'] = null;
        $c_data['country'] = $country;
        $c_data['billing_country'] = $country;
        $c_data['billing_street'] = null;
        $c_data['billing_city'] = null;
        $c_data['billing_zip'] = null;
        $c_data['billing_state'] = null;
        $c_data['shipping_country'] = $country;
        $c_data['shipping_street'] = null;
        $c_data['shipping_city'] = null;
        $c_data['shipping_state'] = null;
        $c_data['shipping_zip'] = null;
        $c_data['show_primary_contact'] = 0;
        $c_data['registration_confirmed'] = 1;
        $c_data['saas_company_id'] = $company_id;

        $c_data['datecreated'] = date('Y-m-d H:i:s');
        $c_data['addedfrom'] = is_staff_logged_in() ? get_staff_user_id() : 0;

        // check if saas_company_id already exist in tbl_clients then update
        $client = get_old_result(db_prefix() . 'clients', array('saas_company_id' => $company_id), false);
        $this->_table_name = db_prefix() . 'clients';
        $this->_primary_key = 'userid';
        if (!empty($client)) {
            $client_id = $this->save($c_data, $client->userid);
        } else {
            $client_id = $this->save($c_data);
        }

        if ($client_id) {
            $this->create_contact($companyInfo, $client_id, $password, $bycript);
        }


        return $client_id;
    }

    public function create_contact($companyInfo, $client_id, $password = null, $bycript = null)
    {
        if (!empty($password)) {
            // create client contact
            $data = array();
            // get first name and last name from company name
            $name = explode(' ', $companyInfo->name);
            // check if name is not empty and have two index
            if (!empty($name) && count($name) > 1) {
                $data['firstname'] = $name[0];
                $data['lastname'] = $name[1];
            } else {
                $data['firstname'] = $companyInfo->name;
                $data['lastname'] = $companyInfo->name;
            }

            if (is_automatic_calling_codes_enabled()) {
                $clientCountryId = $this->db->select('country')
                    ->where('userid', $client_id)
                    ->get('clients')->row()->country ?? null;

                $clientCountry = get_country($clientCountryId);
                $callingCode = $clientCountry ? '+' . ltrim($clientCountry->calling_code, '+') : null;
            } else {
                $callingCode = null;
            }
            if ($callingCode && !empty($c_data['phonenumber']) && $c_data['phonenumber'] == $callingCode) {
                $data['phonenumber'] = '';
            }
            $data['email_verified_at'] = date('Y-m-d H:i:s');
            $data['is_primary'] = 1;
            $data['userid'] = $client_id;
            if (!empty($bycript)) {
                $data['password'] = $password;
                $password_before_hash = 'your previous password';
            } else {
                $data['password'] = $this->hash($password);
                $password_before_hash = $password;
            }

            $data['datecreated'] = date('Y-m-d H:i:s');
            $data['invoice_emails'] = 1;
            $data['email'] = trim($companyInfo->email);

            // check if email already exist in tbl_contacts then update
            $contact = get_old_result(db_prefix() . 'contacts', array('email' => $data['email']), false);
            $this->_table_name = db_prefix() . 'contacts';
            $this->_primary_key = 'id';

            if (!empty($contact)) {
                $contact_id = $this->save($data, $contact->id);
            } else {
                $contact_id = $this->save($data);
            }

            if ($contact_id) {
                $this->db->insert(db_prefix() . 'contact_permissions', [
                    'userid' => $contact_id,
                    'permission_id' => 1,
                ]);

                $this->db->where('id', $contact_id);
                $this->db->update(db_prefix() . 'contacts', ['invoice_emails' => 1, 'credit_note_emails' => 1]);

                send_mail_template(
                    'customer_created_welcome_mail',
                    $data['email'],
                    $data['userid'],
                    $contact_id,
                    $password_before_hash
                );
            }

        }
        return true;
    }

    public function proceedPayment($subs_info)
    {

        $data = $_POST;
        $data['amount'] = $data['total'] ?? 0;
        $data['package_id'] = $subs_info->package_id;
        $data['billing_cycle'] = $subs_info->frequency . '_price';
        $data['expired_date'] = $subs_info->expired_date;

        $data['payment_method'] = $data['paymentmode'];
        $data['i_have_read_agree'] = 'on';
        $new_limit = $this->input->post('new_limit', true);
        $new_module = $this->input->post('new_module', true);
        $new_limit_price = $this->input->post('new_limit_price', true);
        $new_module_name = $this->input->post('new_module_name', true);

        if (!empty($new_module)) {
            $data['new_module'] = serialize($new_module);
        }
        if (!empty($new_limit)) {
            $data['new_limit'] = serialize($new_limit);
        }

        $payment_modes = $this->saas_model->get_payment_modes();

        $modes = array();
        foreach ($payment_modes as $mode) {
            $modes[$mode['id']] = $mode['name'];
        }
        $customer_id = get_saas_client_id($subs_info->companies_id);


        $i_data['clientid'] = $customer_id;
        $i_data['number'] = ConfigItems('next_invoice_number');
        $i_data['project_id'] = 0;
        $i_data['include_shipping'] = 0;
        $i_data['discount_type'] = '';
        $i_data['date'] = date('Y-m-d');
        $i_data['duedate'] = date('Y-m-d');
        $i_data['allowed_payment_modes'] = serialize(array_keys($modes));
        $i_data['currency'] = get_base_currency()->id;
        $i_data['sale_agent'] = $subs_info->created_by ?? $subs_info->updated_by ?? 0;
        $i_data['subtotal'] = $data['amount'];
        $i_data['total'] = $data['amount'];
        $i_data['prefix'] = 'SaaS ' . ConfigItems('invoice_prefix');
        $i_data['number_format'] = ConfigItems('invoice_number_format');
        $i_data['datecreated'] = date('Y-m-d H:i:s');
        $i_data['addedfrom'] = $subs_info->created_by ?? $subs_info->updated_by ?? 0;
        $i_data['hash'] = app_generate_hash();


        $this->saas_model->_table_name = db_prefix() . 'invoices';
        $this->saas_model->_primary_key = 'id';
        $invoice_id = $this->saas_model->save_old($i_data);
        $invoice_id = 7;

        $item_data = array();
        if (!empty($new_limit)) {
            foreach ($new_limit as $key => $limit) {
                if (!empty($limit)) {
                    $rate = $new_limit_price[$key];
                    // remove _no from key if exist
                    $key = str_replace('_no', '', $key);

                    $item_data['description'] = 'Upgrade ' . _l($key) . ' Limit';
                    $item_data['long_description'] = 'Upgrade ' . _l($key) . ' Limit';
                    $item_data['qty'] = $limit;
                    $item_data['rate'] = number_format($rate, get_decimal_places(), '.', '');
                    $item_data['rel_id'] = $invoice_id;
                    $item_data['rel_type'] = 'invoice';

                    $this->saas_model->_table_name = db_prefix() . 'itemable';
                    $this->saas_model->_primary_key = 'id';
                    $this->saas_model->save_old($item_data);

                }
            }
        }

        if (!empty($new_module)) {
            foreach ($new_module as $key => $module) {
                if (!empty($module)) {
                    $name = $new_module_name[$key];

                    $item_data['description'] = 'Upgrade ' . $name . ' module';
                    $item_data['long_description'] = 'Upgrade ' . $name . ' module';
                    $item_data['qty'] = 1;
                    $item_data['rate'] = number_format($module, get_decimal_places(), '.', '');
                    $item_data['rel_id'] = $invoice_id;
                    $item_data['rel_type'] = 'invoice';


                    $this->saas_model->_table_name = db_prefix() . 'itemable';
                    $this->saas_model->_primary_key = 'id';
                    $this->saas_model->save_old($item_data);

                }
            }
        }


        if (empty($new_module) && empty($new_limit)) {
            $item_data['description'] = 'Subscription for package ' . $subs_info->package_name;
            $item_data['long_description'] = 'Subscription for package ' . $subs_info->package_name;
            $item_data['qty'] = 1;
            $item_data['rate'] = number_format($data['amount'], get_decimal_places(), '.', '');
            $item_data['rel_id'] = $invoice_id;
            $item_data['rel_type'] = 'invoice';

            $this->saas_model->_table_name = db_prefix() . 'itemable';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->save_old($item_data);
        }

        $temp_data = array();
        $temp_data['companies_id'] = $data['companies_id'];
        $temp_data['invoice_id'] = $invoice_id;
        $temp_data['package_id'] = $data['package_id'];
        $temp_data['billing_cycle'] = $data['billing_cycle'];
        $temp_data['expired_date'] = $data['expired_date'];
        $temp_data['coupon_code'] = $data['coupon_code'] ?? '';
        $temp_data['amount'] = $data['amount'];
        $temp_data['clientid'] = $customer_id;
        $temp_data['hash'] = $i_data['hash'];
        $temp_data['new_module'] = $data['new_module'] ?? '';
        $temp_data['new_limit'] = $data['new_limit'] ?? '';


        $this->saas_model->_table_name = 'tbl_saas_temp_payment';
        $this->saas_model->_primary_key = 'temp_payment_id';
        $this->saas_model->save_old($temp_data);

        $pData['hash'] = $i_data['hash'];
        $pData['paymentmode'] = $data['paymentmode'];
        $pData['amount'] = $data['amount'];
        $pData['make_payment'] = 'Pay Now';
        $pData['invoice_id'] = $invoice_id;
        $pData['companies_id'] = $data['companies_id'];

        // set session for payment
        $this->session->set_userdata('saas_payment_data', $data);

        $this->load->model('payments_model');
        $this->payments_model->process_payment($pData, $invoice_id);
    }

    public function get_affiliate_user_id()
    {
        // check affiliate_user_id in session
        if ($this->session->has_userdata('affiliate_user_id')) {
            return $this->session->userdata('affiliate_user_id');
        }
        $company_info = get_company_info();
        // check $company_info->email in tbl_saas_affiliate_users
        $is_affiliate = get_old_result('tbl_saas_affiliate_users', array('email' => $company_info->email), false);
        if (empty($is_affiliate)) {
            // create new affiliate user
            $contact = get_old_result(db_prefix() . 'contacts', array('email' => $company_info->email), false);
            if (empty($contact)) {
                $contact = get_old_result(db_prefix() . 'contacts', array('is_primary' => 1, 'userid' => get_saas_client_id()), false);
            }

            if (!empty($contact)) {
                $referral_link = slug_it($contact->firstname . '-' . $contact->lastname . '-' . uniqid());
                $password = $contact->password;
                $firstname = $contact->firstname;
                $lastname = $contact->lastname;
            } else {
                $password = $company_info->password ?? '123456';
                $firstname = $company_info->name ?? '';
                $lastname = '';
                $referral_link = slug_it($firstname . '-' . uniqid());
            }

            $this->saas_model->_table_name = 'tbl_saas_affiliate_users';
            $this->saas_model->_primary_key = 'user_id';

            $data = array(
                'email' => $company_info->email,
                'password' => $password,
                'activated' => 1,
                'is_verified' => 1,
                'first_name' => $firstname,
                'last_name' => $lastname,
                'isAffiliate' => 1,
                'banned' => 0,
                'referral_link' => $referral_link,
                'created_at' => date('Y-m-d H:i:s'),
            );
            $user_id = $this->saas_model->save_old($data);
            $this->session->set_userdata('affiliate_user_id', $user_id);
            return $user_id;

        } else {
            $this->session->set_userdata('affiliate_user_id', $is_affiliate->user_id);
            return $is_affiliate->user_id;
        }

    }


    public function get_affiliate_states($affiliate_info)
    {
        $states = [
            [
                'name' => 'total_referred',
                'icon' => 'uil uil-users-alt fs-24',
                'color' => 'primary',
                'count' => $affiliate_info->total_referral ?? 0
            ],
            [
                'name' => 'total_earning',
                'icon' => 'uil uil-dollar-sign fs-24',
                'color' => 'success',
                'count' => display_money($affiliate_info->total_balance)
            ],
            [
                'name' => 'total_withdrawn',
                'icon' => 'uil uil-wallet fs-24',
                'color' => 'danger',
                'count' => display_money($affiliate_info->withdrawal_amount)
            ],
            [
                'name' => 'remaining_balance',
                'icon' => 'uil uil-wallet fs-24',
                'color' => 'warning',
                'count' => display_money($affiliate_info->total_balance - $affiliate_info->withdrawal_amount)
            ],
        ];
        return $states;
    }

    public function login_as_sample_company()
    {
        $this->session->set_userdata('sample_company', true);
        $sample_database = get_option('created_sample_database');
        $this->db = config_db($sample_database);

        $this->session->set_userdata('db_name', $sample_database);
        $this->session->set_userdata('domain', 'perfect_demo_sample');
        return true;
    }


}
