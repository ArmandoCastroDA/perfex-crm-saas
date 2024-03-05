<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['api/delete/(:any)/(:num)'] = '$1/data/$2'; 
$route['api/(:any)/search/(:any)'] = '$1/data_search/$2';
$route['api/(:any)/search'] = '$1/data_search';
$route['api/login/auth'] = 'login/login_api';
$route['api/login/view'] = 'login/view';
$route['api/login/key'] = 'login/api_key';
$route['api/(:any)/(:num)'] = '$1/data/$2'; 
$route['api/(:any)'] = '$1/data';