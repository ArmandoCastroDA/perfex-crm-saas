<?php defined('BASEPATH') or exit('No direct script access allowed');
/*
Module Name: Perfect SaaS - Powerful Multi-Tenancy Module for Perfex CRM
Description: this is a module for Perfex CRM that allows you to create a SaaS or multi-company enabled setup.
Version: 1.1.9
Requires at least: 2.3.*
*/

define('SaaS_MODULE', 'saas');


$CI = &get_instance();
/**
 * Load the module helper
 */
$CI->load->helper(SaaS_MODULE . '/saas');

// load libraries for saas
$CI->load->library(SaaS_MODULE . '/mails/saas_mail_template');
/**
 * Register activation module hook
 */
register_activation_hook(SaaS_MODULE, 'saas_activation_hook');

register_deactivation_hook(SaaS_MODULE, 'saas_deactivation_hook');
register_uninstall_hook(SaaS_MODULE, 'saas_uninstall_hook');

hooks()->add_filter('module_saas_action_links', 'module_saas_action_links');


/**
 * Cron management
 */
register_cron_task('saas_cron');

function saas_cron()
{
    $CI = &get_instance();

    $CI->load->model('saas/saas_cron_model');
    $CI->saas_cron_model->init();
}

/**
 * Add additional settings for this module in the module list area
 * @param array $actions current actions
 * @return array
 */
function module_saas_action_links($actions)
{
    $actions[] = '<a href="' . saas_url('settings/index/server_settings') . '">' . _l('settings') . '</a>';
    $actions[] = '<a href="https://docs.coderitems.com/perfectsaas/" target="_blank">' . _l('help') . '</a>';
    return $actions;
}

function saas_deactivation_hook()
{
    require_once(__DIR__ . '/deactivate.php');
}

function saas_uninstall_hook()
{
    require_once(__DIR__ . '/uninstall.php');
}

function saas_activation_hook()
{
    require_once(__DIR__ . '/install.php');
}

register_language_files(SaaS_MODULE, [SaaS_MODULE]);

hooks()->add_action('admin_init', 'saas_init_menu_items');
hooks()->add_action('clients_init', 'saas_init_client_items');
hooks()->add_action('app_init', 'saas_init');
hooks()->add_action('after_staff_login', 'check_login');
register_merge_fields('saas/merge_fields/saas_company_merge_fields');
register_merge_fields('saas/merge_fields/affiliate_merge_fields');
hooks()->add_filter('other_merge_fields_available_for', 'saas_register_other_merge_fields');
hooks()->add_filter('other_merge_fields_available_for', 'affiliate_register_other_merge_fields');
hooks()->add_action('after_email_templates', 'saas_email_templates');
hooks()->add_action('before_start_render_dashboard_content', 'saas_dashboard_content');
hooks()->add_action('before_payment_recorded', 'saas_payment_recorded');
hooks()->add_action('before_admin_login_form_close', 'saas_admin_login_form_close');
hooks()->add_action('before_login', 'saas_before_staff_login');
hooks()->add_action('sidebar_menu_items', 'saas_sidebar_menu_items');
hooks()->add_action('pre_activate_module', 'saas_pre_activate_module');
hooks()->add_action('pre_deactivate_module', 'saas_pre_deactivate_module');
hooks()->add_action('pre_uninstall_module', 'saas_pre_uninstall_module');
hooks()->add_filter('get_media_folder', 'saas_set_media_folder', PHP_INT_MAX);
//hooks()->add_filter('get_upload_path_by_type', 'saas_set_upload_path_by_type', PHP_INT_MAX);
hooks()->add_filter('after_render_aside_menu', 'saas_after_render_single_aside_menu');


function saas_after_render_single_aside_menu($item)
{
    if (!empty(subdomain())) {
        // remove badge from li#setup-menu-item a then span then span using css class
        $html = '';
        $html .= '<style>';
        $html .= 'li#setup-menu-item a span span.badge {';
        $html .= 'display: none;';
        $html .= '}';
        $html .= '</style>';
        echo $html;
    }


}


/**
 * @throws Exception
 */
function saas_admin_login_form_close()
{
    if (empty(subdomain())) {
        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<a class="btn btn-default btn-block" href="' . site_url('find-my-company') . '">' . _l('find_my_company') . '</a>';
        $html .= '</div>';

        echo $html;
    }
}

const insert_hook_data = [
    'before_invoice_added' => 'invoices',
    'before_estimate_added' => 'estimates',
    'before_create_credit_note' => 'credit_notes',
    'before_create_proposal' => 'proposals',
    'before_client_added' => 'customers',
    'before_create_contact' => 'contacts',
    'before_create_staff_member' => 'staff',
    'before_add_project' => 'projects',
    'before_add_task' => 'tasks',
    'before_ticket_created' => 'support',
    'before_lead_added' => 'leads',
    'before_expense_added' => 'expenses',
    'before_contract_added' => 'contracts',
    'before_item_created' => 'items',
];

foreach (insert_hook_data as $event => $table) {
    // Set priority to 0 as we want this to run before any other attached hooks to the filter.
    hooks()->add_filter($event, 'saas_insert_data', 0);
}
/**
 * @throws Exception
 */
function saas_insert_data($data)
{
    $is_subdomain = subdomain();
    $subscription = get_company_subscription(null, 'running');
    if (!empty($is_subdomain) && !empty($subscription)) {
        is_saas_expired($subscription);

        $filter = hooks()->current_filter();
        $slug = insert_hook_data[$filter];
        $usages = get_usages($subscription, $slug);

        if (!empty($usages)) {
            foreach ($usages as $usage) {
                if (!empty($usage['limit'])) {
                    $limit = $usage['limit'];
                    $count = $usage['total'];
                    // check if limit is numeric or string
                    // if string its means unlimited
                    // if numeric its means limited then check limit and count
                    if (is_numeric($usage['limit']) && $limit <= $count) {
                        set_alert('warning', _l('add_failed_you_have_reached_limit'));
                        redirect('checkoutPayment');
                    }
                } else {
                    set_alert('warning', _l('add_failed_you_have_reached_limit'));
                    redirect('checkoutPayment');
                }
            }
        }
    }

    return $data;
}

/**
 * @throws Exception
 */
function saas_sidebar_menu_items($items): array
{
    if (!empty(subdomain())) {
        $company_info = get_company_subscription(null, 'running');
        $usages = get_usages($company_info);

        // check $usages array slug  and items array slug if slug is same then add class active
        $allUses = [];
        if (!empty($usages)) {
            foreach ($usages as $usage) {
                if (empty($usage['limit'])) {
                    $allUses[] = $usage['slug'];
                }
            }
        }
        if (!empty($items)) {
            foreach ($items as $key => $item) {
                if (in_array($item['slug'], $allUses)) {
                    $items[$key]['slug'] = $item['slug'] . ' hidden';
                }
                // $item have children then check children slug and $usages array slug if slug is same then add class active
                if (!empty($item['children'])) {
                    foreach ($item['children'] as $k => $child) {
                        if (in_array($child['slug'], $allUses)) {
                            $items[$key]['children'][$k]['slug'] = $child['slug'] . ' hidden';
                        }
                    }
                }
            }
        }
    }
    return $items;
}

/**
 * @throws Exception
 */
function saas_dashboard_content()
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
            // make a alert for trial period
            $html .= '<div class="col-md-12 mtop20" role="alert">';
            $html .= '<div class="alert alert-danger " role="alert">';
            $html .= '<span class="text-sm text-danger">' . $b_text . '</span>';
            $html .= '<strong class=""><a href="' . BaseUrl('clients/updatePackage/' . $subs->companies_id) . '"> ' . _l('upgrade') . '</a></strong>';
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            $html .= '<span aria-hidden="true">&times;</span>';
            $html .= '</button>';
            $html .= '</div>';
            $html .= '</div>';
        }
    }
    echo $html;
}

hooks()->add_action('clients_authentication_constructor', 'saas_clients_authentication_constructor');
function saas_clients_authentication_constructor($data)
{
    if ($data->router->fetch_method() == 'login' && !empty($data->session->client_logged_in)) {
        return redirect('clients');
    }
}

// ensure the user is redirected to client portal after logging in and not landing page
hooks()->add_action('after_contact_login', function () {
    $CI = &get_instance();
    if (!$CI->session->has_userdata('red_url'))
        $CI->session->set_userdata([
            'red_url' => site_url('clients/'),
        ]);
    redirect($CI->session->userdata('red_url'));
});


hooks()->add_action('client_area_after_project_overview', 'saas_show_client_home');
function saas_show_client_home()
{
    if (empty(subdomain())) {
        $subs = get_company_subscription_by_id(null, 'running');
        if (!empty($subs) && !empty(is_client_logged_in())) {
            include_once(__DIR__ . '/views/companies/billing.php');
        }
    }
}

hooks()->add_action('app_customers_head', 'saas_customers_head');
/**
 * @throws Exception
 */
function saas_customers_head()
{
    $html = '';
    if (empty(subdomain())) {
        $subs = get_company_subscription_by_id(null, 'running');
        if (!empty($subs) && !empty(is_client_logged_in())) {
            $html .= '<style>';
            $html .= '.section-client-dashboard > dl:first-of-type,.projects-summary-heading,.submenu.customer-top-submenu {';
            $html .= 'display: none;';
            $html .= '}';
            $html .= '</style>';
            echo $html;
        }
    }
}

// Remove uneccessary menu item from client portal.
// @todo Make this configurable from admin
hooks()->add_filter('theme_menu_items', 'remove_menu_items');
function remove_menu_items($items)
{
    $subs = get_company_subscription_by_id(null, 'running');
    if (empty(subdomain())) {
        if (!empty($subs) && !empty(is_client_logged_in())) {
            unset($items['projects']);
            unset($items['contracts']);
            unset($items['estimates']);
            unset($items['proposals']);
        }
    }
    return $items;
}


/**
 * @throws Exception
 */
function saas_pre_uninstall_module()
{
    if (!empty(subdomain())) {
        access_denied();
    }
}

/**
 * @throws Exception
 */
function saas_pre_deactivate_module($module)
{
    $moduleName = $module['system_name'];

    if (!empty(subdomain())) {
        if ($moduleName == 'saas') {
            access_denied();
        }
        $subs = get_company_subscription(null, 'running');
        if (!empty($subs) && !empty($subs->modules)) {
            $modules = unserialize($subs->modules);
            if (!empty($modules) && in_array($moduleName, $modules)) {
                return true;
            } else {
                access_denied();
            }
        } else {
            access_denied();
        }
    }

}

function saas_pre_activate_module($module)
{
    $moduleName = $module['system_name'];
    if (function_exists('subdomain') && function_exists('is_subdomain') && !empty(subdomain())) {
        if ($moduleName == 'saas') {
            access_denied();
        }
        $subs = get_company_subscription(null, 'running');
        if (!empty($subs) && !empty($subs->modules)) {
            $modules = unserialize($subs->modules);
            if (!empty($modules) && in_array($moduleName, $modules)) {
                return true;
            } else {
                access_denied();
            }
        } else {
            access_denied();
        }
    }

}

function saas_register_other_merge_fields($for)
{
    $for[] = 'saas';
    return $for;
}

function affiliate_register_other_merge_fields($for)
{
    $for[] = 'affiliate';
    return $for;
}

/**
 * @throws Exception
 */
function saas_email_templates()
{
    if (!empty(is_super_admin()) && empty(subdomain())) {
        $CI = &get_instance();
        $CI->load->model('emails_model');
        $data['saas'] = $CI->emails_model->get([
            'type' => 'saas',
            'language' => 'english',
        ]);
        $data['affiliate'] = $CI->emails_model->get([
            'type' => 'affiliate',
            'language' => 'english',
        ]);
        $CI->load->view('saas/settings/email_templates', $data);
    }
}

function check_login()
{
    $is_super_admin = is_super_admin();
    if (!empty($is_super_admin)) {
        redirect(saas_url('dashboard'));
    }
}


if (!empty(subdomain())) {
    $disabled_features = disabled_default_modules(null, "menu");

    $GLOBALS['disabled_features'] = $disabled_features;
    hooks()->add_filter("sidebar_menu_items", "saas_remove_disabled_modules");
    hooks()->add_filter("setup_menu_items", "saas_remove_disabled_modules");
    hooks()->add_filter('theme_menu_items', 'saas_remove_disabled_modules');

    function saas_remove_disabled_modules($items)
    {
        $all_disabled_modules = [];
        $main_modules = [
            'expenses' => ['finance' => ['expenses-categories'], 'reports' => ['expenses-reports']],
            'estimates' => ['sales' => ['estimates']],
            'proposals' => ['sales' => ['proposals']],
            'invoices' => ['sales' => ['invoices'], 'finance' => ['taxes', 'currencies']],
            'items' => ['sales' => ['items']],
            'payments' => ['sales' => ['payments'], 'finance' => ['payment-modes', 'currencies']],
            'credit_notes' => ['sales' => ['credit_notes']],
            'leads' => ['reports' => ['leads-reports']],
            'knowledge_base' => ['reports' => ['knowledge-base-reports']],
        ];
        $disabled_modules = $GLOBALS['disabled_features'];
        foreach ($disabled_modules as $key => $feature) {
            if (isset($items[$feature]))
                unset($items[$feature]);

            if (isset($main_modules[$feature])) {
                foreach ($main_modules[$feature] as $parent_module => $child_menus) {
                    $all_disabled_modules[$parent_module] = array_merge($all_disabled_modules[$parent_module] ?? [], $child_menus);
                }
            }
        }
        if (!empty($all_disabled_modules)) {
            foreach ($all_disabled_modules as $key => $children) {
                if (isset($items[$key]['children'])) {
                    foreach ($items[$key]['children'] as $index => $child) {
                        if (in_array($child['slug'], $children)) {
                            unset($items[$key]['children'][$index]);
                        }
                    }
                }
            }
        }

        return $items;
    }

    // Remove disabled feature/default modules from top bar quick menu
    hooks()->add_filter("quick_actions_links", function ($items) use ($disabled_features) {
        foreach ($items as $key => $value) {
            $controller = explode('/', $value['url'])[0];
            if (
                in_array($controller, $disabled_features) ||
                (isset($value["permission"]) && in_array($value["permission"], $disabled_features)) ||
                (isset($value['name']) && in_array(strtolower($value['name'] . 's'), $disabled_features))
            ) unset($items[$key]);
        }
        return $items;
    });

    // Bind to staff permission interface for disabled default modules/features
    hooks()->add_filter("staff_can", function ($ret_val, $capability, $feature, $staff_id) use ($disabled_features) {
        if ($feature) {
            $disabled_features = disabled_default_modules();
            if (in_array($feature, $disabled_features)) $ret_val = false;
        }
        return $ret_val;
    }, 10, 4);

    // Exclude feature from permission list management
    hooks()->add_filter('staff_permissions', function ($corePermissions, $data) use ($disabled_features) {
        foreach ($corePermissions as $feature => $permission) {
            if (in_array($feature, $disabled_features)) unset($corePermissions[$feature]);
        }
        return $corePermissions;
    }, 10, 2);

    // Remove disabled features from the settings tabs
    hooks()->add_filter("settings_tabs", function ($tabs) use ($disabled_features) {
        foreach ($disabled_features as $feature) {
            if (isset($tabs[$feature]))
                unset($tabs[$feature]);
        }
        return $tabs;
    });
    // Filter disabled features for tenant clients contacts
    hooks()->add_filter('get_contact_permissions', function ($permissions) use ($disabled_features) {
        foreach ($permissions as $key => $value) {
            if (in_array($value['short_name'], $disabled_features)) unset($permissions[$key]);
        }
        return $permissions;
    });
    // Filter disabled feature in dashboard
    hooks()->add_filter('get_dashboard_widgets', function ($widgets) use ($disabled_features) {
        foreach ($widgets as $key => $widget) {
            $feature = explode('_', basename($widget['path']))[0];
            if (
                in_array($feature, $disabled_features) ||
                ($feature === 'finance' && in_array('invoices', $disabled_features) && in_array('estimates', $disabled_features) && in_array('proposals', $disabled_features))
            ) unset($widgets[$key]);
        }
        return $widgets;
    });


    hooks()->add_action('app_admin_footer', function () use ($disabled_features, $CI) {
        if (in_array($CI->router->fetch_class(), ['dashboard', 'staff', 'profile'])) {
            echo '<script>
                    const DISABLED_FEATURES = ' . json_encode($disabled_features) . ';
                    const DISABLED_FEATURE_ACTIVE_CONTROLLER ="' . $CI->router->fetch_class() . '";
                </script>';
            echo '<script src=' . module_dir_url('saas/assets/js/disabled_features.js') . '></script>';
        }
    });


}


/**
 * @throws Exception
 */
function saas_init_menu_items()
{


    /**
     * If the logged in user is administrator, add custom menu in Setup
     */
    if (function_exists('is_complete_setup')) {
        is_complete_setup();
    }
    $CI = &get_instance();
    if (!empty(is_super_admin()) && empty(subdomain())) {
        $CI->app_menu->add_sidebar_menu_item('saas', [
            'name' => '<span class="text-danger">' . _l('saas_management') . '</span>',
            'position' => 0,
            'icon' => 'fa-solid fa-receipt menu-icon text-danger',
            'href' => saas_url('dashboard'),
        ]);
    }
    $db_name = $CI->session->userdata('db_name');
    if (!empty(is_admin()) && !empty(subdomain()) || !empty(is_admin()) && !empty($db_name)) {
        $subs = get_company_subscription(null, 'running');
        $all_themes = (!empty($subs->allowed_themes) ? unserialize($subs->allowed_themes) : array());
        if ($subs->maintenance_mode == 'Yes') {
            $maintenance_message = $subs->maintenance_mode_message;
            $account_status = $subs->status;
            include_once module_dir_path('saas') . 'views/maintenance.php';
            die();
        }

        // Reserved routes
        $restricted_menus = ['modules'];
        foreach ($restricted_menus as $menu) {
            $CI->app_menu->add_setup_menu_item($menu, ['name' => '', 'href' => '', 'disabled' => true]);
        }

        $restricted_classes = ['mods'];
        $controller = $CI->router->fetch_class();
        $method = $CI->router->fetch_method();

        if (in_array($controller, $restricted_classes)) {
            access_denied();
        }
        // Check if the default module is allowed for the tenant
        $disabled_default_modules = disabled_default_modules();
        if ($controller === 'reports' && $method === 'knowledge_base_articles') {
            $method = 'knowledge_base';
        }

        if (in_array($controller, $disabled_default_modules) || ($controller === 'clients' && in_array($method, $disabled_default_modules)) || ($controller === 'reports' && in_array($method, $disabled_default_modules))) {
            access_denied();
        }

        if (ConfigItems('saas_billings_menu') != 'admin_panel') {
            $CI->app_menu->add_sidebar_menu_item('perfect_saas', [
                'name' => '<span class="text-danger">' . _l('saas_billings') . '</span>',
                'position' => 80,
                'icon' => 'fa-solid fa-receipt menu-icon text-danger',
                'badge' => [],
            ]);
            $CI->app_menu->add_sidebar_children_item('perfect_saas', [
                'slug' => 'dashboard',
                'name' => _l('dashboard'),
                'href' => admin_url('billings'),
                'position' => 1,
                'badge' => [],
            ]);

            $CI->app_menu->add_sidebar_children_item('perfect_saas', [
                'slug' => 'customizePackages',
                'name' => _l('customize_packages'),
                'href' => BaseUrl('clients/customizePackages/' . $subs->companies_id),
                'position' => 2,
                'badge' => [],
            ]);
            $CI->app_menu->add_sidebar_children_item('perfect_saas', [
                'slug' => 'buy_modules',
                'name' => _l('buy_modules'),
                'href' => BaseUrl('clients/get_modules/' . $subs->companies_id),
                'position' => 2,
                'badge' => [],
            ]);

            if ($subs->custom_domain == 'Yes') {
                $CI->app_menu->add_sidebar_children_item('perfect_saas', [
                    'slug' => 'custom_domain',
                    'name' => _l('custom_domain'),
                    'href' => admin_url('custom_domain'),
                    'position' => 2,
                    'badge' => [],
                ]);
            }
            if (count($all_themes) > 0) {
                $CI->app_menu->add_sidebar_children_item('perfect_saas', [
                    'slug' => 'theme_builder',
                    'name' => _l('theme_builder'),
                    'href' => admin_url('themebuilder'),
                    'position' => 3,
                    'badge' => [],
                ]);
            }
            if (get_option('enable_affiliate') == 'TRUE') {
                $CI->app_menu->add_sidebar_children_item('perfect_saas', [
                    'slug' => 'referrals',
                    'name' => _l('referrals'),
                    'href' => admin_url('referrals'),
                    'position' => 3,
                    'badge' => [],
                ]);
            }
        }

    }
}


/**
 * @throws Exception
 */
function saas_init_client_items()
{

    is_complete_setup();
    if (empty(subdomain())) {
        $subs = get_company_subscription_by_id(null, 'running');

        $all_themes = (!empty($subs->allowed_themes) ? unserialize($subs->allowed_themes) : array());

        if (!empty($subs) && $subs->maintenance_mode == 'Yes') {
            $maintenance_message = $subs->maintenance_mode_message;
            $account_status = $subs->status;
            include_once module_dir_path('saas') . 'views/maintenance.php';
            die();
        }
        $disable_frontend = get_option('disable_frontend');
        $frontend = true;
        if (!empty($disable_frontend) && $disable_frontend == 1) {
            $frontend = false;
        }
        if (empty($subs) && empty(is_client_logged_in())) {
            // check if $disable_frontend is empty or 0 then hide add_theme_menu_item
            if ($frontend) {
                add_theme_menu_item('packages', [
                    'name' => _l('packages'),
                    'href' => site_url('pricing'),
                    'position' => 2,
                ]);
                // affiliate
                add_theme_menu_item('affiliate', [
                    'name' => _l('affiliate'),
                    'href' => site_url('affiliate'),
                    'position' => 3,
                ]);
                add_theme_menu_item('find_my_company', [
                    'name' => _l('find_my_company'),
                    'href' => site_url('find-my-company'),
                    'position' => 3,
                ]);
                //
            }

        } else if (!empty($subs) && !empty(is_client_logged_in())) {
            {
                add_theme_menu_item('dashboard', [
                    'name' => '<span class="tw-font-bold">' . _l('dashboard') . '</span>',
                    'href' => site_url('clients'),
                    'position' => -2,
                ]);
                add_theme_menu_item('packages', [
                    'name' => make_dropdown(),
                    'href' => '#cooldown',
                    'position' => -1,
                    'href_attributes' => [
                        'class' => 'make_dropdown',
                        'style' => 'display:none;',
                    ]
                ]);

                if ($subs->custom_domain == 'Yes') {
                    add_theme_menu_item('custom_domain', [
                        'name' => _l('custom_domain'),
                        'href' => site_url('clients/custom_domain'),
                        'position' => 2,
                        'badge' => [],
                    ]);
                }
                if (count($all_themes) > 0) {
                    add_theme_menu_item('theme_builder', [
                        'name' => _l('theme_builder'),
                        'href' => site_url('clients/themebuilder'),
                        'position' => 3,
                        'badge' => [],
                    ]);
                }
                if (get_option('enable_affiliate') == 'TRUE') {
                    add_theme_menu_item('referrals', [
                        'name' => _l('referrals'),
                        'href' => site_url('clients/referrals'),
                        'position' => 3,
                        'badge' => [],
                    ]);
                }
            }
        }
    }
}

/**
 * @throws Exception
 */
function make_dropdown()
{
    // make a dropdown for client portal
    $html = '';
    $html .= '<a href="#" class=" dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">';
    $html .= _l('packages');
    $html .= ' <span class="caret"></span>';
    $html .= '</a>';
    $html .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
    $html .= '<li><a href="' . site_url('clients/customizePackages') . '">' . _l('customize') . '</a></li>';
    $html .= '<li><a href="' . site_url('clients/updatePackage') . '">' . _l('upgrade') . '</a></li>';
    $html .= '<li><a href="' . site_url('clients/get_modules') . '">' . _l('buy_modules') . '</a></li>';
    $html .= '</ul>';
    return $html;

}

