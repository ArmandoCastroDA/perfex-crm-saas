<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Setup extends CI_Controller
{
    protected $error = '';

    public $current_step = 1;

    public static $last_step = 4;

    public function __construct()
    {
        parent::__construct();


        $GLOBALS['EXT']->call_hook('pre_controller_constructor');

        $this->load->model('saas_model');
        $this->load->helper('saas');

        if (!class_exists('ForceUTF8\Encoding') && file_exists(APPPATH . 'vendor/autoload.php')) {
            require_once(APPPATH . 'vendor/autoload.php');
        }

        $this->db->reconnect();

        if (is_mobile()) {
            $this->session->set_userdata(['is_mobile' => true]);
        } else {
            $this->session->unset_userdata('is_mobile');
        }
        $timezone = get_option('saas_default_timezone');

        if ($timezone != '') {
            date_default_timezone_set($timezone);
        }
        load_admin_language();
        $vars = [];
        $vars['locale'] = $GLOBALS['locale'];
        $vars['language'] = $GLOBALS['language'];
        $this->load->vars($vars);
        $is_active = $this->saas_model->is_company_active();
        if (!empty($is_active)) {
            redirect('login');
        }
    }

    public function index()
    {

        error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
        ini_set('max_execution_time', 30000);
        $data['title'] = _l('welcome_to') . ' ' . get_option('saas_companyname');
        $data['step'] = 1;
        // get code from url by get method
        $code = $this->input->get('c', true);
        $domain = $this->input->get('d', true);
        if (!empty($code)) {
            $activation_token = url_decode($code);
        } else {
            $activation_token = $this->input->post('activation_token', true);
        }

        if (!empty($activation_token)) {
            $data['activation_token'] = $activation_token;
            $company_info = get_row('tbl_saas_companies', array('activation_code' => $activation_token));
            if (empty($company_info)) {
                $data['step'] = 1;
                $this->current_step = 1;
                $data['activation_token_error'] = _l('invalid_activation_code');
                $data['error'] = _l('invalid_activation_code');
            } else {
                $data['step'] = (!empty($_POST['step'])) ? $_POST['step'] : 1;

                $data['company_info'] = $company_info;
                $data['email'] = $company_info->email;
                if (empty($data['error']) && isset($_POST['step']) && $_POST['step'] == 1) {
                    // update company info and set active
                    $this->complete_install($_POST);
                    $data['step'] = 2;
                    $this->current_step = $data['step'];
                } elseif (isset($data['step']) && $data['step'] == 5) {
                    redirect('admin');
                }
            }
        }
        $form = new stdClass();
        $form->language = get_option('active_language');
        $form->recaptcha = 1;
        $form->success_submit_msg = _l('success_submit_msg');
        $data['form'] = $form;
        $data['current_step'] = $this->current_step;
        $data['steps'] = $this->steps();
        $this->load->view('saas/settings/setup', $data);
    }

    public function steps()
    {
        $step = $this->current_step;
        return [
            [
                'id' => 1,
                'name' => 'Account',
                'status' => $step > 1 ? 'complete' : 'current',
            ],
            [
                'id' => 2,
                'name' => 'Ready to go',
                'status' => $step === 2 ? 'complete' : 'upcoming',
            ],
        ];
    }

    private function complete_install($data)
    {

        $company_info = get_row('tbl_saas_companies', array('activation_code' => $data['activation_token']));
        if (!empty($company_info)) {
            $id = $company_info->id;

            $fresh_db = (!empty($data['fresh_database']) ? $data['fresh_database'] : '');
            $fresh_db = (!empty($fresh_db) ? $fresh_db : '');
            $this->saas_model->create_database($id, $fresh_db);

            $c_data['status'] = 'running';
            $this->saas_model->_table_name = 'tbl_saas_companies';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->save($c_data, $id);

            $this->saas_model->save_client($id, $data['password']);


            $this->saas_model->send_welcome_email($id);
            return true;
        } else {
            return false;
        }
    }

    public function check_existing_activation_token_new($activation_token = null, $front = null)
    {

        if (!empty($this->input->post('name', true))) {
            $activation_token = $this->input->post('name', true);
        }
        if (!empty($activation_token)) {
            $check_token = get_row('tbl_saas_companies', array('activation_code' => $activation_token));
            if (!empty($check_token)) {
                $result['success'] = 1;
                $result['name'] = $check_token->name;
                $result['email'] = $check_token->email;
                // get first name and last name from name
                $name = explode(' ', $check_token->name);
                // if the name have three part then first name and other two part will be last name
                if (count($name) == 3) {
                    $result['first_name'] = $name[0];
                    $result['last_name'] = $name[1] . ' ' . $name[2];
                } else {
                    $result['first_name'] = $name[0];
                    if (isset($name[1])) {
                        $result['last_name'] = $name[1];
                    } else {
                        $result['last_name'] = '';
                    }
                }
            } else {
                $result['error'] = _l('we_did_not_found_your_token');
            }
            if (empty($front)) {
                echo json_encode($result);
                exit();
            } else {
                return $result;
            }
        }
    }

    /**
     * @throws Exception
     */
    public function domain_not_available()
    {
        $sub_domain = subdomain();
        if (!empty($sub_domain)) {
            $domain_available = get_old_result('tbl_saas_companies', array('domain' => $sub_domain));
            $reserved = check_reserved_tenant($sub_domain);
            if (!empty($reserved)) {
                redirect(BaseUrl());
            }
            if (!empty($domain_available)) {
                redirect(config_item('default_controller'));
            } else {
                $data['title'] = _l('welcome_to') . ' ' . config_item('company_name');
                $this->load->view('saas/settings/domain_not_registered', $data);
            }
        } else {
            redirect(config_item('default_controller'));
        }
    }
}
