<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: API
Description: Rest API module for Perfex CRM
Version: 1.0.0
*/

define('API_MODULE_NAME', 'api');
hooks()->add_action('admin_init', 'api_init_menu_items');

/**
* Load the module helper
*/
$CI = & get_instance();
$CI->load->helper(API_MODULE_NAME . '/api');
/**
* Register activation module hook
*/
register_activation_hook(API_MODULE_NAME, 'api_activation_hook');


function api_activation_hook()
{
    require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(API_MODULE_NAME, [API_MODULE_NAME]);

/**
 * Init api module menu items in setup in admin_init hook
 * @return null
 */
function api_init_menu_items()
{
    /**
    * If the logged in user is administrator, add custom menu in Setup
    */
    if (is_admin()) {
        $CI = &get_instance();
        $CI->app_menu->add_sidebar_menu_item('api-options', [
            'collapse' => true,
            'name'     => _l('api'),
            'position' => 40,
            'icon'     => 'fa fa-cogs',
        ]);
        $CI->app_menu->add_sidebar_children_item('api-options', [
            'slug'     => 'api-register-options',
            'name'     => _l('api_management'),
            'href'     => admin_url('api/api_management'),
            'position' => 5,
        ]);
        
        $CI->app_menu->add_sidebar_children_item('api-options', [
            'slug'     => 'api-guide-options',
            'name'     => _l('api_guide'),
            'href'     => 'https://perfexcrm.themesic.com/apiguide/',
            'position' => 10,
        ]);
    }
}