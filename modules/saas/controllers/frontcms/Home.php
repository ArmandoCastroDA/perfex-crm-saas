<?php

use Proxy\Http\Request;
use Proxy\Plugin\ProxifyPlugin;
use Proxy\Proxy;
use Proxy\Plugin\CorsPlugin;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Home extends App_Controller
{

    public $domain;
    public $themeName;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        $this->load->model('cms_menuitems_model');
        $this->load->model('saas_model');
//        setBaseURL();
    }


    public function theme($name = null, $page = null, $params = null)
    {
        $data['title'] = _l('themes');
        list($themePath, $themeUrl) = get_theme_path_url($this->domain);

        if (empty($name)) {
            $name = get_option('saas_default_theme');
        }
        if (!empty($params)) {
            $page = $page . '/' . $params;
        } else if (!empty($page)) {
            $page = $page;
        } else {
            $page = 'index.html';
        }
        $landingFile = $themePath . '/' . $name . '/' . $page;
        $themeName = dirname(str_ireplace($themePath, '', $landingFile));
        $themeUrl = $themeUrl . $themeName;

        $data = [];
        // check file exists or not
        if (!file_exists($landingFile)) {
            // file not found
            $error_file = APPPATH . 'views/errors/html/error_404.php';
            $message = 'File not found: ' . $landingFile;
            $heading = 'Page Not Found';
            $message = "$message 
        <script>
            let tag = document.querySelector('h1');
            if(tag){
                tag.innerHTML = '$heading';
            }
        </script>
    ";
            require_once($error_file);
            exit();
        }

        $html = file_get_contents($landingFile);
        $html = str_ireplace(
            ['"assets/', '\'assets/',],
            ['"' . $themeUrl . '/assets/', "'$themeUrl/assets/"],
            $html
        );
        $html = str_ireplace(['(assets/', '(&quot;assets/'], ["(" . $themeUrl . '/assets/', '(&quot;' . $themeUrl . '/assets/'], $html);
//        // /css and /js with base url
        $html = str_ireplace(
            ['"css/', '\'css/', '"js/', '\'js/', '"img/', '\'img/', '"images/', '\'images/'],
            ['"' . ($themeUrl . '/css/'), "'$themeUrl/css/", '"' . ($themeUrl . '/js/'), "'$themeUrl/js/", '"' . ($themeUrl . '/img/'), "'$themeUrl/img/", '"' . ($themeUrl . '/img/'), "'$themeUrl/images/", '"' . ($themeUrl . '/images/'), "'$themeUrl/images/"],
            $html
        );

        $html = str_ireplace(['[csrf_token_name]', '[csrf_token_hash]'], [$this->security->get_csrf_token_name(), $this->security->get_csrf_hash()], $html);
        $data['landing_page_content'] = $html;

        $this->load->view("themebuilder/index", $data);
    }

    public function index($page = null, $params = null)
    {

        $this->check_restriction();

        $themes = get_theme_list();
        $theme = get_option('saas_default_theme') ?? $themes[0];
        if (!empty($theme) && $theme != 'default') {
            $this->themeName = $theme;
            $this->theme($theme, $page, $params);
        } else {
            $data['active_menu'] = "home";
            $data['page_info'] = get_old_result('tbl_saas_front_pages', array('slug' => 'home'));
            $data['title'] = get_option('saas_companyname') ? get_option('saas_companyname') : 'Home';
            $data['subview'] = $this->load->view('frontcms/frontend/index', $data, true);
            $this->load->view('frontcms/_layout_front', $data);
        }
    }

    private function check_restriction()
    {
        $disable_frontend = get_option('disable_frontend');
        if (!empty($disable_frontend) && $disable_frontend == 1 || $disable_frontend == '1') {
            redirect('login');
        }

        $force_frontend = get_option('saas_force_redirect_to_dashboard');
        if ($force_frontend == "1" || $force_frontend == 1) {
            if (is_client_logged_in()) {
                return redirect('clients');
            }

            if (is_staff_logged_in()) {
                return redirect('admin');
            }
        }
        $url = get_option('saas_landing_page_url');
        $mode = get_option('saas_landing_page_url_mode');
        if (!empty($url) && $url != base_url() && $mode == 'redirection') {
            redirect($url);
        }
        if (!empty($mode) && $mode == 'proxy') {
            $this->proxy();
        }


    }

    /**
     * Method to serve the proxied landing page.
     * Its essensial the proxied adddress runs on same domain to prevent CORS or whitelabeled for this installation domain.
     *
     * @return void
     */
    public function proxy()
    {
        $url = get_option('saas_landing_page_url');
        require APP_MODULES_PATH . 'saas/vendor/autoload.php';

        $request = Request::createFromGlobals();

        $proxy = new Proxy();

        $proxy->getEventDispatcher()->addListener('request.before_send', function ($event) {

            $event['request']->headers->set('X-Forwarded-For', 'php-proxy');
        });

        $proxy->getEventDispatcher()->addListener('request.sent', function ($event) {
            if ($event['response']->getStatusCode() != 200) {
                show_error("Bad status code!", $event['response']->getStatusCode(), "Landing");
            }
        });

        $proxy->getEventDispatcher()->addListener('request.complete', function ($event) {
            $content = $event['response']->getContent();
            $content .= '<!-- via php-proxy -->';
            $event['response']->setContent($content);
        });

        $dispatcher = $proxy->getEventDispatcher();
        $proxify = new ProxifyPlugin();
        $proxify->subscribe($dispatcher);

        $cors = new CorsPlugin();
        $cors->subscribe($dispatcher);

        if (isset($_GET['q'])) {
            $url = url_decrypt($_GET['q']);
        }

        $response = $proxy->forward($request, $url);

        // send the response back to the client
        $response->send();
    }

    public function preview($dir = null, $page = null, $params = null)
    {
        // check page have .html or not
        if (strpos($page, '.html') !== false) {
            $page = $page;
        } else {
            $this->themeName = $page;
            $theme = $page;
            $page = null;
        }
        if (!empty($theme) && $theme != 'default') {
            $this->themeName = $theme;
            $this->theme($theme, $page, $params);
        }
    }

    public
    function client($page = null, $params = null)
    {

        $this->check_restriction();

        $sub = get_company_subscription();
        $themes = false;
        if (!empty($sub)) {
            $allowed_themes = (!empty($sub->allowed_themes) ? unserialize($sub->allowed_themes) : array());
            if (count($allowed_themes) > 0) {
                $themes = $allowed_themes;
            } else {
                redirect('login');
            }
            $this->domain = $sub->domain;
        }

        $theme = get_option('default_theme') ?? $themes[0];
        if (empty($theme)) {
            $theme = $themes[0];
        }
        $this->themeName = $theme;
        $this->theme($theme, $page, $params);
    }

    public
    function login($id = null)
    {
        // get referer from url and set in session
        $referer = $this->input->get('via');
        if (!empty($referer)) {
            $this->session->set_userdata('referer', $referer);
        }
        $data['title'] = get_option('saas_companyname') ? get_option('saas_companyname') : 'Login';
        $data['affiliate'] = true;
        $data['subview'] = $this->load->view('companies/panel/login', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public
    function forgot_password($id = null)
    {
        $data['title'] = get_option('saas_companyname') ? get_option('saas_companyname') : 'Register';
        $data['affiliate'] = true;
        $data['subview'] = $this->load->view('companies/panel/forgot_password', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public
    function register($id = null)
    {
        // get referer from url and set in session
        $referer = $this->input->get('via');
        if (!empty($referer)) {
            $this->session->set_userdata('referer', $referer);
        }
        $data['title'] = get_option('saas_companyname') ? get_option('saas_companyname') : 'Register';
        $data['active_menu'] = "pricing";
        if (!empty($id)) {
            $data['package'] = $this->saas_model->get_package_info($id);
            $data['package_id'] = $id;
        } else {
            $data['package'] = $this->saas_model->get_package_info();
            $data['package_id'] = $data['package']->id;
        }
        $data['register'] = true;
        $data['subview'] = $this->load->view('frontcms/frontend/register', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public
    function page($slug = null)
    {

        $data['page_info'] = get_old_result('tbl_saas_front_pages', array('slug' => $slug), false);
        $data['active_menu'] = $slug;
        if (empty($data['page_info'])) {
            $data['page_info'] = get_old_result('tbl_saas_front_pages', array('pages_id' => '4'), false);
        }
        $data['title'] = $data['page_info']->pages_id == 4 ? _l($slug) : $data['page_info']->title;
        $data['subview'] = $this->load->view('frontcms/frontend/index', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public
    function affiliate_program($slug = null)
    {
        $data['active_menu'] = 'affiliate';
        $data['title'] = _l('affiliate_program');
        $data['subview'] = $this->load->view('frontcms/frontend/affiliate', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public
    function find_my_company($slug = null)
    {
        $data['active_menu'] = 'home';
        $data['title'] = _l('find_my_company');
        $data['subview'] = $this->load->view('frontcms/frontend/find_my_company', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public
    function become_affiliator($slug = null)
    {
        $data['active_menu'] = 'affiliate';
        $data['title'] = _l('affiliate_program');
        $data['subview'] = $this->load->view('affiliates/user/register', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public
    function tos()
    {
        $data['active_menu'] = 'terms_and_conditions';
        $data['page_info'] = get_old_result('tbl_saas_front_pages', array('slug' => 'terms_and_conditions'), false);
        $data['subview'] = $this->load->view('frontcms/frontend/index', $data, true);
        $this->load->view('frontcms/_layout_front', $data);
    }

    public
    function save_faq()
    {
        $data = $this->saas_model->array_from_post(array('name', 'email', 'phone', 'subject'));
        $data['description'] = $this->input->post('comments');
        $data['phone'] = $data['phone'] ?? '';
        $this->saas_model->_table_name = 'tbl_saas_front_contact_us';
        $this->saas_model->_primary_key = 'id';
        $id = $this->saas_model->save($data);
        if (!empty($id)) {
            $comments = stripslashes($data['description']);
            $name = ($data['name']);
            $email = ($data['email']);
            $address = get_option('smtp_email');


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
                set_alert('success', 'Your message sent. Thanks for contacting. We will Contact you Soon.');
            }

        }
        redirect('/');
    }
}
