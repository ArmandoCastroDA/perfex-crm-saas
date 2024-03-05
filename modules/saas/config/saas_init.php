<?php
if (!function_exists('guess_base_url')) {
    function guess_base_url()
    {
        $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
        $base_url .= '://' . $_SERVER['HTTP_HOST'];
        $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        $base_url = preg_replace('/install.*/', '', $base_url);
        return $base_url;
    }
}


// check is_subdomain function is exist or not
if (!function_exists('is_subdomain')) {
    /**
     * @throws Exception
     */
    function is_subdomain()
    {
        $subdomain = check_subdomain();
        if (!empty($subdomain)) {
            $company_path = $subdomain['company_path'];

            $slug = $subdomain['slug'];
            $domain = $subdomain['subdomain'];
            $custom_domain = $subdomain['custom_domain'];
            $mode = $subdomain['mode'];
            $config = &get_config();
            $field = $mode == 'custom_domain' ? 'domain_url' : 'domain';

            if ($field == 'domain_url') {
                $domain = $custom_domain;
            }

            if ($mode == 'path') {
                $base_url = guess_base_url() . $slug . '/';
            } else {
                $base_url = guess_base_url();
            }
            if (empty($domain)) {
                saas_error('Domain not found', 'You have entered invalid domain', 404);
            }
            // check the domain is exist in database using mysqli
            $dbName = '';

            $db = mysqli_connect(config_item('database_hostname'), config_item('database_username'), config_item('database_password'), config_item('default_database'));
            $sql = "SELECT * FROM tbl_saas_companies WHERE " . $field . " = '" . $domain . "'";
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) == 0) {
                saas_error('Domain not found', 'You have entered invalid domain', 404);
            } else {
                $domainInfo = mysqli_fetch_assoc($result);
                if (!empty($domain)) {
                    $domain = $domainInfo['domain'];
                    $dbName = $domainInfo['db_name'];
                }
            }
            mysqli_close($db);

            if (!empty($company_path)) {
                $request_uri = $_SERVER['REQUEST_URI'];
                // Replace repeated company path
                if (stripos($request_uri, "/$company_path/$company_path") !== false) {
                    $_SERVER['REQUEST_URI'] = str_ireplace("/$company_path/$company_path", "/$company_path", $request_uri);
                    if (empty($_POST)) {
                        $url = company_full_url($_SERVER);
                        header("Location: $url");
                        exit;
                    }
                }
                // Serve static files
                if (stripos($request_uri, ".") && stripos($request_uri, ".php") === false) {
                    $url = str_ireplace("/$company_path", '', company_full_url($_SERVER));
                    header("Location: $url");
                    exit;
                }
            }

            $config['csrf_token_name'] = $domain . '_csrf_token_name';
            $config['csrf_cookie_name'] = $domain . '_csrf_cookie_name';
            $config['cookie_prefix'] = $domain;
            $config['sess_cookie_name'] = $domain . '_sp_session';
            $config['base_url'] = $base_url;
            $config['company_db_name'] = $dbName;

            return $domain;
        }
        return false;
    }
}

/**
 * @throws Exception
 */
function check_subdomain()
{
    $default_url = config_item('default_url');
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


    $subdomainInfo = false;
    $mode = 'custom_domain';
    //  check the base url is segment and or host (subdomain or cname/custom domain)
    $subdomainInfo = get_url_info($base_url);
    if (!empty($subdomainInfo['subdomain']))
        $mode = 'subdomain';

    if (!$subdomainInfo) {
        // get the subdomain from request url
        $subdomainInfo = get_subdomain();
        if (!empty($subdomainInfo['subdomain']))
            $mode = 'path';
    }
    if (!$subdomainInfo) {
        return false;
    }

    return array('subdomain' => $subdomainInfo['subdomain'] ?? '',
        'slug' => $subdomainInfo['slug'] ?? '',
        'custom_domain' => $subdomainInfo['custom_domain'] ?? '',
        'company_path' => $subdomainInfo['company_path'] ?? '',
        'mode' => $mode);
}

function saas_marker()
{
    $marker = '/';
    // add s,#,?,& to the marker if you want to use it in the url
    $marker_array = array('/s');
    // add the marker to the array
    return $marker_array;


}

function get_subdomain()
{
    $saasUrl = false;
    $company_url = false;
    $request_uri = $_SERVER['REQUEST_URI'];
    $default_url = config_item('default_url');
    $saas_marker = saas_marker();
    $saas_marker_url = '';
    // check the request url contain the marker or not
    foreach ($saas_marker as $marker) {
        // request url == /perfex/rootd?admin/authentication
        // marker == /s, #, ? , /saas
        // check using stripos
        // if exist then saasUrl = false
        // else saasUrl = true
        if (stripos($request_uri, $marker) !== false) {
            $saasUrl = false;
            $saas_marker_url = $marker;
            break;
        } else {
            $saasUrl = true;
        }
    }
    if (!$saasUrl) {
        // check the request url contain the marker or not and get the position of the marker
        $url_position = stripos($request_uri, $saas_marker_url . '/');
        if ($url_position === false && str_ends_with($request_uri, $saas_marker_url)) // if the marker is at the end of the url then get the position
            $url_position = stripos($request_uri, $saas_marker_url);

        if ($url_position !== false) {
            // Extract the substring before the marker
            $company_url = substr($request_uri, 1, $url_position - 1);
            // Find the position of the last slash
            $lastSlash = strrpos($company_url, '/');
            // Extract the substring after the last slash (i.e. the company slug)
            if ($lastSlash !== false)
                $company_url = substr($company_url, $lastSlash + 1);

            // Get the directory in case the perfex is installed in subfolder
            $base_url_path = parse_url($default_url);

            if (!isset($base_url_path['path'])) {
                throw new \Exception("Your base url in app/app-config.php should end with trailing slash !", 1);
            }

            $base_url_path = $base_url_path['path'];
            if (!empty($company_url) && str_starts_with($request_uri, $base_url_path . $company_url . $saas_marker_url)) {
                $company_id = trim($base_url_path . $company_url . $saas_marker_url, '/');
                $slug = trim($company_url . $saas_marker_url, '/');
                return array('subdomain' => $company_url,
                    'slug' => $slug, 'company_path' => $company_id);
            }
        }
    }
    return false;

}


if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool
    {
        $needle_len = strlen($needle);
        return ($needle_len === 0 || 0 === substr_compare($haystack, $needle, -$needle_len));
    }
}
if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return '' === $needle || 0 === strncmp($haystack, $needle, strlen($needle));
    }
}

/**
 * @throws Exception
 */
function get_url_info($base_url)
{
    $subdomain_url = '';
    $default_url = config_item('default_url');
    $main_url = config_item('main_url');

    // Validate input
    if (!filter_var($base_url, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) || stripos($base_url, '/') !== false) {
        throw new Exception('Invalid HTTP host provided: ' . $base_url);
    }
    $custom_domain = saas_http_host($_SERVER);
    // Check for default url
    $default_url = parse_url($default_url, PHP_URL_HOST);
    // remove www from default url
    if (strpos($default_url, 'www.') !== false) {
        $default_url = str_replace('www.', '', $default_url);
    }
    $main_url = parse_url($main_url, PHP_URL_HOST);
    // remove www from main url
    if (strpos($main_url, 'www.') !== false) {
        $main_url = str_replace('www.', '', $main_url);
    }
    $request_uri = $_SERVER['REQUEST_URI'];
    $is_saas_url = '/saas';
    if (strpos($request_uri, $is_saas_url) !== false) {
        return false;
    }
    if ($base_url === $default_url) {
        return false;
    }
    if ($base_url === $main_url) {
        return false;
    }
    // Check for subdomain or domain match
    if (str_ends_with($base_url, $default_url)) {
        $subdomain = str_replace('.' . $default_url, '', $base_url);
        if (empty($subdomain) || stripos($subdomain, '.') !== false) {
            throw new Exception('Invalid HTTP host provided: ' . $base_url);
        }
        $subdomain_url = $subdomain;
        $custom_domain = '';
    }
    if (str_ends_with($base_url, $main_url)) {
        $subdomain = str_replace('.' . $main_url, '', $base_url);
        if (empty($subdomain) || stripos($subdomain, '.') !== false) {
            throw new Exception('Invalid HTTP host provided: ' . $base_url);
        }
        $subdomain_url = $subdomain;
        $custom_domain = '';
    }
    return array('subdomain' => $subdomain_url, 'slug' => $subdomain_url, 'custom_domain' => $custom_domain);
}

function saas_error($heading, $message, $error_code = 403, $template = '404')
{
    $error_file = FCPATH . 'modules/saas/views/settings/domain_not_register.php';
    $message = "$message 
        <script>
            let tag = document.querySelector('h1');
            if(tag){
                tag.innerHTML = '$heading';
            }
        </script>
    ";
    if (file_exists($error_file)) {
        require_once($error_file);
        exit();
    }

    echo($heading . '<br/><br/>' . $message);
    exit();
}


try {
    is_subdomain();
} catch (Exception $e) {
}

function company_full_url($server, $use_forwarded_host = true): string
{
    $url_origin = saas_url_origin($server, $use_forwarded_host);
    $request_uri = $server['REQUEST_URI'];
    return $url_origin . $request_uri;
}

/**
 * @throws Exception
 */
function saas_url_origin($server, $use_forwarded_host = true): string
{
    $ssl = (!empty($server['HTTPS']) && $server['HTTPS'] == 'on');
    $sp = strtolower($server['SERVER_PROTOCOL']);
    $protocol = !empty($server['HTTP_X_FORWARDED_PROTO']) ? $server['HTTP_X_FORWARDED_PROTO'] : substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $server['SERVER_PORT'];
    $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
    $host = saas_http_host($server, $use_forwarded_host);
    $host = isset($host) ? $host : $server['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function saas_http_host($server, $use_forwarded_host = true)
{
    $host = ($use_forwarded_host && isset($server['HTTP_X_FORWARDED_HOST'])) ? $server['HTTP_X_FORWARDED_HOST'] : (isset($server['HTTP_HOST']) ? $server['HTTP_HOST'] : null);
    if (empty($host) || !filter_var($host, FILTER_VALIDATE_DOMAIN))
        throw new \Exception("Error detecting valid http host", 1);
    return $host;
}

/**
 * Handle 404 static file request for tenants.
 * The method assist in handling files upload by tenants before proceeding with 404.
 * This method is need until Perfex author stop using hard coded upload/ string in file uploads url.
 *
 * @param object $tenant
 * @param string $upload_folder The base upload folder.
 * @param string $tenants_upload_folder The tenant-specific upload folder.
 * @return void
 */
function saas_handle_tenant_404_static_file_request($tenant_slug, $upload_folder, $tenants_upload_folder)
{

    // Get the requested URI.
    $requested_uri = $_SERVER['REQUEST_URI'] ?? '';

    // If no file extension is found in the URI, return.
//    if (stripos($requested_uri, '.') === false) {
//        return;
//    }

    // Check if the query string is set.
    if (isset($_SERVER['QUERY_STRING'])) {
        $query_string = $_SERVER['QUERY_STRING'];

        // Remove the query string from the requested URI.
        $requested_file = str_replace('?' . $query_string, '', $requested_uri);
    } else {
        $requested_file = $requested_uri;
    }

    // Check if the requested file is not a PHP file.
    $requested_file = ltrim($requested_file, '/');

    $url = company_full_url($_SERVER);


    // Remove tenant path id if present from url and requested file
//    $path_signature = perfex_saas_tenant_url_signature($tenant_slug);
//
//    if (stripos($requested_file, $path_signature) !== false) {
//
//        $requested_file = ltrim(str_ireplace($path_signature, "", $requested_file), '/');
//        $url = str_ireplace('/' . $path_signature, '', $url);
//
//        // Redirect if file exist
//        if (file_exists(urldecode($requested_file))) {
//            $is_tenant_upload_path = stripos($requested_file, $tenants_upload_folder) === 0;
//            $can_serve_file = $is_tenant_upload_path;
//            if (!$is_tenant_upload_path) {
//                // Check if file in share list and has match value, then sever from master
//                $shared_fields = (array)$tenant->package_invoice->metadata->shared_settings->shared ?? [];
//                if (!empty($shared_fields)) {
//                    $values = (array)perfex_saas_get_options($shared_fields, false);
//                    $values = array_column($values, 'value');
//                    $can_serve_file = in_array(basename($requested_file), $values) || in_array($requested_file, $values);
//                }
//            }
//            if ($can_serve_file) {
//                header("Location: $url");
//                exit;
//            }
//        }
//    }


//    if (
//        stripos($requested_file, $tenants_upload_folder) === false &&
//        stripos($requested_file, $upload_folder) === 0 &&
//        pathinfo($requested_file, PATHINFO_EXTENSION) !== 'php'
//    ) {
//
//        $url = str_replace_first($upload_folder, $tenants_upload_folder . $tenant_slug . '/', $url);
//
//        // Redirect to the tenant-specific file URL.
//        header("Location: $url");
//        exit;
//    }
}

if (!function_exists('str_replace_first')) {
    /**
     * Replace first match of a string
     *
     * @param string $search
     * @param string $replace
     * @param string $subject
     * @return string
     */
    function str_replace_first($search, $replace, $subject)
    {
        $pos = strpos($subject, $search);

        if ($pos !== false) {
            return substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }
}