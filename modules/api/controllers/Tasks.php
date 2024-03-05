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
class Tasks extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    /**
     * @api {get} api/tasks/:id Request Task information
     * @apiName GetTask
     * @apiGroup Task
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {Number} id Task unique ID.
     *
     * @apiSuccess {Object} Tasks information.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "id": "10",
     *         "name": "Khảo sát chi tiết hiện trạng",
     *         "description": "",
     *         "priority": "2",
     *         "dateadded": "2019-02-25 12:26:37",
     *         "startdate": "2019-01-02 00:00:00",
     *         "duedate": "2019-01-04 00:00:00",
     *         "datefinished": null,
     *         "addedfrom": "9",
     *         "is_added_from_contact": "0",
     *         "status": "4",
     *         "recurring_type": null,
     *         "repeat_every": "0",
     *         "recurring": "0",
     *         "is_recurring_from": null,
     *         ...
     *     }
     *
     * @apiError {Boolean} status Request status.
     * @apiError {String} message No data were found.
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
        $data = $this->Api_model->get_table('tasks', $id);

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
     * @api {get} api/tasks/search/:keysearch Search Tasks Information.
     * @apiName GetTaskSearch
     * @apiGroup Task
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} keysearch Search Keywords.
     *
     * @apiSuccess {Object} Tasks information.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "id": "10",
     *         "name": "Khảo sát chi tiết hiện trạng",
     *         "description": "",
     *         "priority": "2",
     *         "dateadded": "2019-02-25 12:26:37",
     *         "startdate": "2019-01-02 00:00:00",
     *         "duedate": "2019-01-04 00:00:00",
     *         "datefinished": null,
     *         "addedfrom": "9",
     *         "is_added_from_contact": "0",
     *         "status": "4",
     *         "recurring_type": null,
     *         "repeat_every": "0",
     *         "recurring": "0",
     *         "is_recurring_from": null,
     *         ...
     *     }
     *
     * @apiError {Boolean} status Request status.
     * @apiError {String} message No data were found.
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
        // If the id parameter doesn't exist return all the
        $data = $this->Api_model->search('tasks', $key);

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
     * @api {post} api/tasks Add New Task
     * @apiName PostTask
     * @apiGroup Task
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} name              Mandatory Task Name.
     * @apiParam {Date} startdate           Mandatory Task Start Date.
     * @apiParam {String} [is_public]       Optional Task public.
     * @apiParam {String} [billable]        Optional Task billable.
     * @apiParam {String} [hourly_rate]     Optional Task hourly rate.
     * @apiParam {String} [milestone]       Optional Task milestone.
     * @apiParam {Date} [duedate]           Optional Task deadline.
     * @apiParam {String} [priority]        Optional Task priority.
     * @apiParam {String} [repeat_every]    Optional Task repeat every.
     * @apiParam {Number} [repeat_every_custom]     Optional Task repeat every custom.
     * @apiParam {String} [repeat_type_custom]      Optional Task repeat type custom.
     * @apiParam {Number} [cycles]                  Optional cycles.
     * @apiParam {string="lead","customer","invoice", "project", "quotation", "contract", "annex", "ticket", "expense", "proposal"} rel_type Mandatory Task Related.
     * @apiParam {Number} rel_id            Optional Related ID.
     * @apiParam {String} [tags]            Optional Task tags.
     * @apiParam {String} [description]     Optional Task description.
     *
     *
     * @apiParamExample {Multipart Form} Request-Example:
     *     array (size=15)
     *     'is_public' => string 'on' (length=2)
     *     'billable' => string 'on' (length=2)
     *     'name' => string 'Task 12' (length=7)
     *     'hourly_rate' => string '0' (length=1)
     *     'milestone' => string '' (length=0)
     *     'startdate' => string '17/07/2019' (length=10)
     *     'duedate' => string '31/07/2019 11:07' (length=16)
     *     'priority' => string '2' (length=1)
     *     'repeat_every' => string '' (length=0)
     *     'repeat_every_custom' => string '1' (length=1)
     *     'repeat_type_custom' => string 'day' (length=3)
     *     'rel_type' => string 'customer' (length=8)
     *     'rel_id' => string '9' (length=1)
     *     'tags' => string '' (length=0)
     *     'description' => string '<span>Task Description</span>' (length=29)
     *
     *
     * @apiSuccess {String} status Request status.
     * @apiSuccess {String} message Task add successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Task add successful."
     *     }
     *
     * @apiError {String} status Request status.
     * @apiError {String} message Task add fail.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Task add fail."
     *     }
     * 
     */
    public function data_post()
    {
        // form validation
        $this->form_validation->set_rules('name', 'Task Name', 'trim|required|max_length[600]', array('is_unique' => 'This %s already exists please enter another Task Name'));
        $this->form_validation->set_rules('startdate', 'Task Start Date', 'trim|required', array('is_unique' => 'This %s already exists please enter another Task Start Date'));
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
            $insert_data = [
                'name' => $this->input->post('name', TRUE),
                'startdate' => $this->input->post('startdate', TRUE),
                
                'is_public' => $this->Api_model->value($this->input->post('is_public', TRUE)),
                'billable' => $this->Api_model->value($this->input->post('billable', TRUE)),
                'hourly_rate' => $this->Api_model->value($this->input->post('hourly_rate', TRUE)),
                'milestone' => $this->Api_model->value($this->input->post('milestone', TRUE)),
                'duedate' => $this->Api_model->value($this->input->post('duedate', TRUE)),
                'priority' => $this->Api_model->value($this->input->post('priority', TRUE)),
                'repeat_every' => $this->Api_model->value($this->input->post('repeat_every', TRUE)),
                'repeat_every_custom' => $this->Api_model->value($this->input->post('repeat_every_custom', TRUE)),
                'repeat_type_custom' => $this->Api_model->value($this->input->post('repeat_type_custom', TRUE)),
                'cycles' => $this->Api_model->value($this->input->post('cycles', TRUE)),
                'rel_type' => $this->Api_model->value($this->input->post('rel_type', TRUE)),
                'rel_id' => $this->Api_model->value($this->input->post('rel_id', TRUE)),
                'tags' => $this->Api_model->value($this->input->post('tags', TRUE)),
                'description' => $this->Api_model->value($this->input->post('description', TRUE))];
               
            // insert data
            $this->load->model('tasks_model');
            $output = $this->tasks_model->add($insert_data);
            if($output > 0 && !empty($output)){
                // success
                $message = array(
                'status' => TRUE,
                'message' => 'Task add successful.'
                );
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                // error
                $message = array(
                'status' => FALSE,
                'message' => 'Task add failed.'
                );
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }


    /**
     * @api {delete} api/delete/tasks/:id Delete a Task
     * @apiName DeleteTask
     * @apiGroup Task
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {Number} id Task unique ID.
     *
     * @apiSuccess {String} status Request status.
     * @apiSuccess {String} message Task Delete Successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Task Delete Successful."
     *     }
     *
     * @apiError {String} status Request status.
     * @apiError {String} message Task Delete Fail.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Task Delete Fail."
     *     }
     */
    public function data_delete($id = '')
    {
        $id = $this->security->xss_clean($id);
        if(empty($id) && !is_numeric($id))
        {
            $message = array(
            'status' => FALSE,
            'message' => 'Invalid Task ID'
        );
        $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {
            // delete data
            $this->load->model('tasks_model');
            $output = $this->tasks_model->delete_task($id);
            if($output === TRUE){
                // success
                $message = array(
                'status' => TRUE,
                'message' => 'Task Delete Successful.'
                );
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                // error
                $message = array(
                'status' => FALSE,
                'message' => 'Task Delete Fail.'
                );
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }


    /**
     * @api {put} api/tasks/:id Update a task
     * @apiName PutTask
     * @apiGroup Task
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} name              Mandatory Task Name.
     * @apiParam {Date} startdate           Mandatory Task Start Date.
     * @apiParam {String} [is_public]       Optional Task public.
     * @apiParam {String} [billable]        Optional Task billable.
     * @apiParam {String} [hourly_rate]     Optional Task hourly rate.
     * @apiParam {String} [milestone]       Optional Task milestone.
     * @apiParam {Date} [duedate]           Optional Task deadline.
     * @apiParam {String} [priority]        Optional Task priority.
     * @apiParam {String} [repeat_every]    Optional Task repeat every.
     * @apiParam {Number} [repeat_every_custom]     Optional Task repeat every custom.
     * @apiParam {String} [repeat_type_custom]      Optional Task repeat type custom.
     * @apiParam {Number} [cycles]                  Optional cycles.
     * @apiParam {string="lead","customer","invoice", "project", "quotation", "contract", "annex", "ticket", "expense", "proposal"} rel_type Mandatory Task Related.
     * @apiParam {Number} rel_id            Optional Related ID.
     * @apiParam {String} [tags]            Optional Task tags.
     * @apiParam {String} [description]     Optional Task description.
     *
     *
     * @apiParamExample {json} Request-Example:
     *  {
     *      "billable": "1", 
     *      "is_public": "1",
     *      "name": "Task 1",
     *      "hourly_rate": "0.00",
     *      "milestone": "0",
     *      "startdate": "27/08/2019",
     *      "duedate": null,
     *      "priority": "0",
     *      "repeat_every": "",
     *      "repeat_every_custom": "1",
     *      "repeat_type_custom": "day",
     *      "cycles": "0",
     *      "rel_type": "lead",
     *      "rel_id": "11",
     *      "tags": "",
     *      "description": ""
     *   }
     *
     * @apiSuccess {String} status Request status.
     * @apiSuccess {String} message Task Update Successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Task Update Successful."
     *     }
     *
     * @apiError {String} status Request status.
     * @apiError {String} message Task Update Fail.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Task Update Fail."
     *     }
     */
    public function data_put($id = '')
    {
        $_POST = json_decode($this->security->xss_clean(file_get_contents("php://input")), true);
        $this->form_validation->set_data($_POST);
        
        if(empty($id) && !is_numeric($id))
        {
            $message = array(
            'status' => FALSE,
            'message' => 'Invalid Task ID'
            );
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {

            $update_data = $this->input->post();
            // update data
            $this->load->model('tasks_model');
            $output = $this->tasks_model->update($update_data, $id);
            if($output > 0 && !empty($output)){
                // success
                $message = array(
                'status' => TRUE,
                'message' => 'Task Update Successful.'
                );
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                // error
                $message = array(
                'status' => FALSE,
                'message' => 'Task Update Fail.'
                );
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

}
