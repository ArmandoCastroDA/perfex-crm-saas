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
class Milestones extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    /**
     * @api {get} api/milestones/:id Request Milestones information
     * @apiName GetMilestones
     * @apiGroup Milestone
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {Number} id Milestones unique ID.
     *
     * @apiSuccess {Object} Milestones information.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *   {
     *       "id": "5",
     *       "name": "MIlestone A",
     *       "description": "",
     *       "description_visible_to_customer": "0",
     *       "due_date": "2019-09-30",
     *       "project_id": "2",
     *       "color": null,
     *       "milestone_order": "1",
     *       "datecreated": "2019-07-19",
     *       "total_tasks": "0",
     *       "total_finished_tasks": "0"
     *   }
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
        $data = $this->Api_model->get_table('milestones', $id);

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
     * @api {get} api/milestones/search/:keysearch Search Milestones Information.
     * @apiName GetMilestoneSearch
     * @apiGroup Milestone
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} keysearch Search Keywords.
     *
     * @apiSuccess {Object} Milestones information.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *         {
     *           "id": "5",
     *           "name": "MIlestone A",
     *           "description": "",
     *           "description_visible_to_customer": "0",
     *           "due_date": "2019-09-30",
     *           "project_id": "2",
     *           "color": null,
     *           "milestone_order": "1",
     *           "datecreated": "2019-07-19",
     *           "total_tasks": "0",
     *           "total_finished_tasks": "0"
     *       }
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
        $data = $this->Api_model->search('milestones', $key);

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
     * @api {post} api/milestones Add New Milestone
     * @apiName PostMilestone
     * @apiGroup Milestone
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} project_id            Mandatory project id.
     * @apiParam {String} name                  Mandatory Milestone Name.
     * @apiParam {Date}   due_date              Mandatory Milestone Due date.
     * @apiParam {String} [description]         Optional Milestone Description.
     * @apiParam {String} [description_visible_to_customer]     Show description to customer.
     * @apiParam {String} [milestone_order]                     Optional Milestone Order.
     *
     *
     * @apiParamExample {Multipart Form} Request-Example:
     *   array (size=6)
     *     'project_id' => string '2' (length=1)
     *     'name' => string 'Milestone A' (length=11)
     *     'due_date' => string '30/07/2019' (length=10)
     *     'description' => string 'Description' (length=11)
     *     'description_visible_to_customer' => string 'on' (length=2)
     *     'milestone_order' => string '1' (length=1)
     *
     *
     * @apiSuccess {String} status Request status.
     * @apiSuccess {String} message Milestone add successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Milestone add successful."
     *     }
     *
     * @apiError {String} status Request status.
     * @apiError {String} message Milestone add fail.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Milestone add fail."
     *     }
     * 
     */
    public function data_post()
    {
        // form validation
        $this->form_validation->set_rules('name', 'Milestone Name', 'trim|required|max_length[600]', array('is_unique' => 'This %s already exists please enter another Milestone Name'));
        $this->form_validation->set_rules('project_id', 'Project id', 'trim|required', array('is_unique' => 'This %s already exists please enter another Project id'));
        $this->form_validation->set_rules('due_date', 'Milestone Due Date', 'trim|required', array('is_unique' => 'This %s already exists please enter another Milestone Due Date'));
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
                'due_date' => $this->input->post('due_date', TRUE),
                'project_id' => $this->input->post('project_id', TRUE),
                
                'description' => $this->Api_model->value($this->input->post('description', TRUE)),
                'description_visible_to_customer' => $this->Api_model->value($this->input->post('description_visible_to_customer', TRUE)),
                'milestone_order' => $this->Api_model->value($this->input->post('milestone_order', TRUE))
            ];
            // insert data
            $this->load->model('projects_model');
            $output = $this->projects_model->add_milestone($insert_data);
            if($output > 0 && !empty($output)){
                // success
                $message = array(
                'status' => TRUE,
                'message' => 'Milestone add successful.'
                );
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                // error
                $message = array(
                'status' => FALSE,
                'message' => 'Milestone add fail.'
                );
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }


    /**
     * @api {delete} api/delete/milestones/:id Delete a Milestone
     * @apiName DeleteMilestone
     * @apiGroup Milestone
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {Number} id Milestone unique ID.
     *
     * @apiSuccess {String} status Request status.
     * @apiSuccess {String} message Milestone Delete Successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Milestone Delete Successful."
     *     }
     *
     * @apiError {String} status Request status.
     * @apiError {String} message Milestone Delete Fail.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Milestone Delete Fail."
     *     }
     */
    public function data_delete($id = '')
    {
        $id = $this->security->xss_clean($id);
        if(empty($id) && !is_numeric($id))
        {
            $message = array(
            'status' => FALSE,
            'message' => 'Invalid Milestone ID'
        );
        $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {
            // delete data
            $this->load->model('projects_model');
            $output = $this->projects_model->delete_milestone($id);
            if($output === TRUE){
                // success
                $message = array(
                'status' => TRUE,
                'message' => 'Milestone Delete Successful.'
                );
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                // error
                $message = array(
                'status' => FALSE,
                'message' => 'Milestone Delete Fail.'
                );
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }


    /**
     * @api {put} api/milestones/:id Update a Milestone
     * @apiName PutMilestone
     * @apiGroup Milestone 
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} project_id            Mandatory project id.
     * @apiParam {String} name                  Mandatory Milestone Name.
     * @apiParam {Date}   due_date              Mandatory Milestone Due date.
     * @apiParam {String} [description]         Optional Milestone Description.
     * @apiParam {String} [description_visible_to_customer]     Show description to customer.
     * @apiParam {String} [milestone_order]                     Optional Milestone Order.
     *
     *
     * @apiParamExample {json} Request-Example:
     *  {
     *      "project_id": "1", 
     *      "name": "Milestone A",
     *      "due_date": "30/07/2019",
     *      "description": "Description",
     *      "description_visible_to_customer": "on",
     *      "milestone_order": "1"
     *   }
     * @apiSuccess {String} status Request status.
     * @apiSuccess {String} message Milestone Update Successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Milestone Update Successful."
     *     }
     *
     * @apiError {String} status Request status.
     * @apiError {String} message Milestone Update Fail.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Milestone Update Fail."
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
            'message' => 'Invalid Milestone ID'
            );
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {

            $update_data = $this->input->post();
            // update data
            $this->load->model('projects_model');
            $output = $this->projects_model->update_milestone($update_data, $id);
            if($output > 0 && !empty($output)){
                // success
                $message = array(
                'status' => TRUE,
                'message' => 'Milestone Update Successful.'
                );
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                // error
                $message = array(
                'status' => FALSE,
                'message' => 'Milestone Update Fail.'
                );
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

}
