<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require __DIR__.'/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Staffs extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('Api_model');
    }

    /**
     * @api {get} api/staffs/:id Request Staff information
     * @apiName GetStaff
     * @apiGroup Staff
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {Number} id Staff unique ID.
     *
     * @apiSuccess {Object} Staff information.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "staffid": "8",
     *          "email": "data1.gsts@gmail.com",
     *          "firstname": "Đào Quang Dân",
     *          "lastname": "",
     *          "facebook": "",
     *          "linkedin": "",
     *          "phonenumber": "",
     *          "skype": "",
     *          "password": "$2a$08$ySLokLAM.AqmW9ZjY2YREO0CIrd5K4Td\/Bpfp8d9QJamWNUfreQuK",
     *          "datecreated": "2019-02-25 09:11:31",
     *          "profile_image": "8.png",
     *         ...
     *     }
     *
     * @apiError StaffNotFound The id of the Staff was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "No data were found"
     *     }
     */
    public function data_get($id = '')
    {
        // If the id parameter doesn't exist return all the
        $data = $this->Api_model->get_table('staffs', $id);

        // Check if the data store contains
        if ($data)
        {
            // Set the response and exit
            $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No data were found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    /**
     * @api {get} api/staffs/search/:keysearch Search Staff Information.
     * @apiName GetStaffSearch
     * @apiGroup Staff
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} keysearch Search keywords.
     *
     * @apiSuccess {Object} Staff information.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "staffid": "8",
     *          "email": "data1.gsts@gmail.com",
     *          "firstname": "Đào Quang Dân",
     *          "lastname": "",
     *          "facebook": "",
     *          "linkedin": "",
     *          "phonenumber": "",
     *          "skype": "",
     *          "password": "$2a$08$ySLokLAM.AqmW9ZjY2YREO0CIrd5K4Td\/Bpfp8d9QJamWNUfreQuK",
     *          "datecreated": "2019-02-25 09:11:31",
     *          "profile_image": "8.png",
     *         ...
     *     }
     *
     * @apiError StaffNotFound The id of the Staff was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "No data were found"
     *     }
     */
    public function data_search_get($key = '')
    {
        $data = $this->Api_model->search('staff', $key);
        // Check if the data store contains
        if ($data)
        {
            // Set the response and exit
            $this->response($data, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No data were found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    /**
     * @api {post} api/staffs Add New Staff
     * @apiName PostStaffs
     * @apiGroup Staff
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} firstname             Mandatory Staff Name.
     * @apiParam {String} email                 Mandatory Staff Related.
     * @apiParam {String} password              Mandatory Staff password.
     * @apiParam {Number} [hourly_rate]         Optional hourly rate.
     * @apiParam {String} [phonenumber]         Optional Staff phonenumber.
     * @apiParam {String} [facebook]            Optional  Staff facebook.
     * @apiParam {String} [linkedin]            Optional  Staff linkedin.
     * @apiParam {String} [skype]               Optional Staff skype.
     * @apiParam {String} [default_language]    Optional Staff default language.
     * @apiParam {String} [email_signature]     Optional Staff email signature.
     * @apiParam {String} [direction]           Optional Staff direction.
     * @apiParam {String} [send_welcome_email]  Optional Staff send welcome email.
     * @apiParam {Number[]} [departments]  Optional Staff departments.
     *
     * @apiParamExample {Multipart Form} Request-Example:
     *     array (size=15)
     *     'firstname' => string '4' (length=1)
     *     'email' => string 'a@gmail.com' (length=11)
     *     'hourly_rate' => string '0' (length=1)
     *     'phonenumber' => string '' (length=0)
     *     'facebook' => string '' (length=0)
     *     'linkedin' => string '' (length=0)
     *     'skype' => string '' (length=0)
     *     'default_language' => string '' (length=0)
     *     'email_signature' => string '' (length=0)
     *     'direction' => string '' (length=0)
     *    'departments' => 
     *       array (size=5)
     *         0 => string '1' (length=1)
     *         1 => string '2' (length=1)
     *         2 => string '3' (length=1)
     *         3 => string '4' (length=1)
     *         4 => string '5' (length=1)
     *     'send_welcome_email' => string 'on' (length=2)
     *     'fakeusernameremembered' => string '' (length=0)
     *     'fakepasswordremembered' => string '' (length=0)
     *     'password' => string '1' (length=1)
     *     'role' => string '18' (length=2)
     *
     *
     * @apiSuccess {String} status Request status.
     * @apiSuccess {String} message Staff add successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Staff add successful."
     *     }
     *
     * @apiError {String} status Request status.
     * @apiError {String} message Staff add fail.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Staff add fail."
     *     }
     * 
     */
    public function data_post()
    {
        // form validation
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|max_length[600]', array('is_unique' => 'This %s already exists please enter another Staff First Name'));
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', array('is_unique' => 'This %s already exists please enter another Staff Email'));
        $this->form_validation->set_rules('password', 'Password', 'trim|required', array('is_unique' => 'This %s already exists please enter another Staff password'));
        if ($this->form_validation->run() == FALSE)
        {
            // form validation error
            $message = array(
                'status' => FALSE,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors() 
            );
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {
            $departments = $this->Api_model->value($this->input->post('departments', TRUE));
            $insert_data = [
                'firstname' => $this->input->post('firstname', TRUE),
                'email' => $this->input->post('email', TRUE),
                'password' => $this->input->post('password', TRUE),
                'lastname' => '',
                'hourly_rate' => $this->Api_model->value($this->input->post('hourly_rate', TRUE)),
                'phonenumber' => $this->Api_model->value($this->input->post('phonenumber', TRUE)),
                'facebook' => $this->Api_model->value($this->input->post('facebook', TRUE)),
                'linkedin' => $this->Api_model->value($this->input->post('linkedin', TRUE)),
                'skype' => $this->Api_model->value($this->input->post('skype', TRUE)),
                'default_language' => $this->Api_model->value($this->input->post('default_language', TRUE)),
                'email_signature' => $this->Api_model->value($this->input->post('email_signature', TRUE)),
                'direction' => $this->Api_model->value($this->input->post('direction', TRUE)),
                'send_welcome_email' => $this->Api_model->value($this->input->post('send_welcome_email', TRUE)),
                'role' => '1',
                'permissions' => array( 
                    'bulk_pdf_exporter' => array('view'), 
                    'contracts' => array('create','edit','delete'),
                    'credit_notes' => array('create','edit','delete'),
                    'customers' => array('view','create','edit','delete'),
                    'email_templates' => array('view','edit'),
                    'estimates' => array('create','edit','delete'),
                    'expenses' => array('create','edit','delete'),
                    'invoices' => array('create','edit','delete'),
                    'items' => array('view','create','edit','delete'),
                    'knowledge_base' => array('view','create','edit','delete'),
                    'payments' => array('view','create','edit','delete'),
                    'projects' => array('view','create','edit','delete'),
                    'proposals' => array('create','edit','delete'),
                    'contracts' => array('view'),
                    'roles' => array('view','create','edit','delete'),
                    'settings' => array('view','edit'),
                    'staff' => array('view','create','edit','delete'),
                    'subscriptions' => array('create','edit','delete'),
                    'tasks' => array('view','create','edit','delete'),
                    'checklist_templates' => array('create','delete'),
                    'leads' => array('view','delete'),
                    'goals' => array('view','create','edit','delete'),
                    'surveys' => array('view','create','edit','delete'),
                )
            ];
            if($departments != ''){
                $insert_data['departments'] = $departments;
            }
            // insert data
            $this->load->model('staff_model');
            $output = $this->staff_model->add($insert_data);
            if($output > 0 && !empty($output)){
                // success
                $message = array(
                'status' => TRUE,
                'message' => 'Staff add successful.'
                );
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                // error
                $message = array(
                'status' => FALSE,
                'message' => 'Staff add fail.'
                );
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }

    }


    /**
     * @api {delete} api/delete/staffs/:id Delete a Staff
     * @apiName DeleteStaff
     * @apiGroup Staff
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {Number} id Staff unique ID.
     *
     * @apiSuccess {String} status Request status.
     * @apiSuccess {String} message Staff registration successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Staff Delete."
     *     }
     *
     * @apiError {String} status Request status.
     * @apiError {String} message Not register your accout.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Staff Not Delete."
     *     }
     */
    public function data_delete($id)
    {
        $id = $this->security->xss_clean($id);
        if(empty($id) && !is_numeric($id))
        {
            $message = array(
            'status' => FALSE,
            'message' => 'Invalid Staff ID'
        );
        $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {
            // delete data
            $this->load->model('staff_model');
            $output = $this->staff_model->delete($id, 0);
            if($output === TRUE){
                // success
                $message = array(
                'status' => TRUE,
                'message' => 'Staff Delete Successful.'
                );
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                // error
                $message = array(
                'status' => FALSE,
                'message' => 'Staff Delete Fail.'
                );
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }


    /**
     * @api {put} api/staffs/:id Update a Staff
     * @apiName PutStaff
     * @apiGroup Staff
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} firstname             Mandatory Staff Name.
     * @apiParam {String} email                 Mandatory Staff Related.
     * @apiParam {String} password              Mandatory Staff password.
     * @apiParam {Number} [hourly_rate]         Optional hourly rate.
     * @apiParam {String} [phonenumber]         Optional Staff phonenumber.
     * @apiParam {String} [facebook]            Optional  Staff facebook.
     * @apiParam {String} [linkedin]            Optional  Staff linkedin.
     * @apiParam {String} [skype]               Optional Staff skype.
     * @apiParam {String} [default_language]    Optional Staff default language.
     * @apiParam {String} [email_signature]     Optional Staff email signature.
     * @apiParam {String} [direction]           Optional Staff direction.
     * @apiParam {Number[]} [departments]  Optional Staff departments.
     *
     *
     * @apiParamExample {json} Request-Example:
     *  {
     *     "firstname": "firstname",
     *     "email": "aa454@gmail.com",
     *     "hourly_rate": "0.00",
     *     "phonenumber": "",
     *     "facebook": "",
     *     "linkedin": "",
     *     "skype": "",
     *     "default_language": "",
     *     "email_signature": "",
     *     "direction": "",
     *     "departments": {
     *          "0": "1",
     *          "1": "2"
     *      },
     *     "password": "123456"
     *  }
     *
     * @apiSuccess {String} status Request status.
     * @apiSuccess {String} message Staff Update Successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Staff Update Successful."
     *     }
     *
     * @apiError {String} status Request status.
     * @apiError {String} message Staff Update Fail.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Staff Update Fail."
     *     }
     */
    public function data_put($id)
    {
        $_POST = json_decode($this->security->xss_clean(file_get_contents("php://input")), true);
        $this->form_validation->set_data($_POST);
        
        if(empty($id) && !is_numeric($id))
        {
            $message = array(
            'status' => FALSE,
            'message' => 'Invalid Staff ID'
            );
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {

            $update_data = $this->input->post();
            $update_data['lastname'] = '';
            // update data
            $this->load->model('staff_model');
            $output = $this->staff_model->update($update_data, $id);

            if($output > 0 && !empty($output)){
                // success
                $message = array(
                'status' => TRUE,
                'message' => 'Staff Update Successful.'
                );
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                // error
                $message = array(
                'status' => FALSE,
                'message' => 'Staff Update Fail.'
                );
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
}
