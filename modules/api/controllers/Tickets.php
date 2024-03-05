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
class Tickets extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    /**
     * @api {get} api/tickets/:id Request Ticket information
     * @apiName GetTicket
     * @apiGroup Ticket
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {Number} id Ticket unique ID.
     *
     * @apiSuccess {Object} Ticket information.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "ticketid": "7",
     *         "adminreplying": "0",
     *         "userid": "0",
     *         "contactid": "0",
     *         "email": null,
     *         "name": "Trung bình",
     *         "department": "1",
     *         "priority": "2",
     *         "status": "1",
     *         "service": "1",
     *         "ticketkey": "8ef33d61bb0f26cd158d56cc18b71c02",
     *         "subject": "Ticket ER",
     *         "message": "Ticket ER",
     *         "admin": "5",
     *         "date": "2019-04-10 03:08:21",
     *         "project_id": "5",
     *         "lastreply": null,
     *         "clientread": "0",
     *         "adminread": "1",
     *         "assigned": "5",
     *         "line_manager": "8",
     *         "milestone": "27",
     *         ...
     *     }
     * @apiError {Boolean} status Request status.
     * @apiError {String} message The id of the Ticket was not found.
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
        $data = $this->Api_model->get_table('tickets', $id);

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
     * @api {get} api/tickets/search/:keysearch Search Ticket Information.
     * @apiName GetTicketSearch
     * @apiGroup Ticket
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} keysearch Search keywords.
     *
     * @apiSuccess {Object} Ticket information.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "ticketid": "7",
     *         "adminreplying": "0",
     *         "userid": "0",
     *         "contactid": "0",
     *         "email": null,
     *         "name": "Trung bình",
     *         "department": "1",
     *         "priority": "2",
     *         "status": "1",
     *         "service": "1",
     *         "ticketkey": "8ef33d61bb0f26cd158d56cc18b71c02",
     *         "subject": "Ticket ER",
     *         "message": "Ticket ER",
     *         "admin": "5",
     *         "date": "2019-04-10 03:08:21",
     *         "project_id": "5",
     *         "lastreply": null,
     *         "clientread": "0",
     *         "adminread": "1",
     *         "assigned": "5",
     *         "line_manager": "8",
     *         "milestone": "27",
     *         ...
     *     }
     * @apiError {Boolean} status Request status.
     * @apiError {String} message The id of the Ticket was not found.
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
        $data = $this->Api_model->search('ticket', $key);

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
     * @api {post} api/tickets Add New Ticket
     * @apiName PostTicket
     * @apiGroup Ticket
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} subject                       Mandatory Ticket name .
     * @apiParam {String} department                    Mandatory Ticket Department.
     * @apiParam {String} contactid                     Mandatory Ticket Contact.
     * @apiParam {String} userid                        Mandatory Ticket user.
     * @apiParam {String} [project_id]                  Optional Ticket Project.
     * @apiParam {String} [message]                     Optional Ticket message.
     * @apiParam {String} [service]                     Optional Ticket Service.
     * @apiParam {String} [assigned]                    Optional Assign ticket.
     * @apiParam {String} [cc]                          Optional Ticket CC.
     * @apiParam {String} [priority]                    Optional Priority.
     * @apiParam {String} [tags]                        Optional ticket tags.
     *
     * @apiParamExample {Multipart Form} Request-Example:
     *    array (size=11)
     *     'subject' => string 'ticket name' (length=11)
     *     'contactid' => string '4' (length=1)
     *     'userid' => string '5' (length=1)
     *     'department' => string '2' (length=1)
     *     'cc' => string '' (length=0)
     *     'tags' => string '' (length=0)
     *     'assigned' => string '8' (length=1)
     *     'priority' => string '2' (length=1)
     *     'service' => string '2' (length=1)
     *     'project_id' => string '' (length=0)
     *     'message' => string '' (length=0)
     *
     *
     * @apiSuccess {Boolean} status Request status.
     * @apiSuccess {String} message Ticket add successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Ticket add successful."
     *     }
     *
     * @apiError {Boolean} status Request status.
     * @apiError {String} message Ticket add fail.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Ticket add fail."
     *     }
     * 
     */
    public function data_post()
    {
        // form validation
        $this->form_validation->set_rules('subject', 'Ticket Name', 'trim|required', array('is_unique' => 'This %s already exists please enter another Ticket Name'));
        $this->form_validation->set_rules('department', 'Department', 'trim|required', array('is_unique' => 'This %s already exists please enter another Ticket Department'));
        $this->form_validation->set_rules('contactid', 'Contact', 'trim|required', array('is_unique' => 'This %s already exists please enter another Ticket Contact'));
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
                'subject' => $this->input->post('subject', TRUE),
                'department' => $this->input->post('department', TRUE),
                'contactid' => $this->input->post('contactid', TRUE),
                'userid' => $this->input->post('userid', TRUE),

                'cc' => $this->Api_model->value($this->input->post('cc', TRUE)),
                'tags' => $this->Api_model->value($this->input->post('tags', TRUE)),
                'assigned' => $this->Api_model->value($this->input->post('assigned', TRUE)),
                'priority' => $this->Api_model->value($this->input->post('priority', TRUE)),
                'service' => $this->Api_model->value($this->input->post('service', TRUE)),
                'project_id' => $this->Api_model->value($this->input->post('project_id', TRUE)),
                'message' => $this->Api_model->value($this->input->post('message', TRUE))
             ];
               
            // insert data
            $this->load->model('tickets_model');
            $output = $this->tickets_model->add($insert_data);
            if($output > 0 && !empty($output)){
                // success
                $message = array(
                'status' => TRUE,
                'message' => 'Ticket add successful.'
                );
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                // error
                $message = array(
                'status' => FALSE,
                'message' => 'Ticket add fail.'
                );
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }


    /**
     * @api {delete} api/delete/tickets/:id Delete a Ticket
     * @apiName DeleteTicket
     * @apiGroup Ticket
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {Number} id Ticket unique ID.
     *
     * @apiSuccess {Boolean} status Request status.
     * @apiSuccess {String} message Ticket Delete Successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Ticket Delete Successful."
     *     }
     *
     * @apiError {Boolean} status Request status.
     * @apiError {String} message Ticket Delete Fail.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Ticket Delete Fail."
     *     }
     */
    public function data_delete($id = '')
    {
        $id = $this->security->xss_clean($id);
        if(empty($id) && !is_numeric($id))
        {
            $message = array(
            'status' => FALSE,
            'message' => 'Invalid Ticket ID'
        );
        $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {
            // delete data
            $this->load->model('tickets_model');
            $output = $this->tickets_model->delete($id);
            if($output === TRUE){
                // success
                $message = array(
                'status' => TRUE,
                'message' => 'Ticket Delete Successful.'
                );
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                // error
                $message = array(
                'status' => FALSE,
                'message' => 'Ticket Delete Fail.'
                );
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }


    /**
     * @api {put} api/tickets/:id Update a ticket
     * @apiName PutTicket
     * @apiGroup Ticket
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} subject                       Mandatory Ticket name .
     * @apiParam {String} department                    Mandatory Ticket Department.
     * @apiParam {String} contactid                     Mandatory Ticket Contact.
     * @apiParam {String} userid                        Mandatory Ticket user.
     * @apiParam {String} priority                      Mandatory Priority.
     * @apiParam {String} [project_id]                  Optional Ticket Project.
     * @apiParam {String} [message]                     Optional Ticket message.
     * @apiParam {String} [service]                     Optional Ticket Service.
     * @apiParam {String} [assigned]                    Optional Assign ticket.
     * @apiParam {String} [tags]                        Optional ticket tags.
     *
     *
     * @apiParamExample {json} Request-Example:
     *  {
     *       "subject": "Ticket ER",
     *       "department": "1",
     *       "contactid": "0",
     *       "ticketid": "7",
     *       "userid": "0",
     *       "project_id": "5",
     *       "message": "Ticket ER",
     *       "service": "1",
     *       "assigned": "5",
     *       "priority": "2",
     *       "tags": ""
     *   }
     *
     * @apiSuccess {Boolean} status Request status.
     * @apiSuccess {String} message Ticket Update Successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Ticket Update Successful."
     *     }
     *
     * @apiError {Boolean} status Request status.
     * @apiError {String} message Ticket Update Fail.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Ticket Update Fail."
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
            'message' => 'Invalid Ticket ID'
            );
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {

            $update_data = $this->input->post();
            // update data
            $this->load->model('tickets_model');
            $update_data['ticketid'] = $id;
            $output = $this->tickets_model->update_single_ticket_settings($update_data);
            if($output > 0 && !empty($output)){
                // success
                $message = array(
                'status' => TRUE,
                'message' => 'Ticket Update Successful.'
                );
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                // error
                $message = array(
                'status' => FALSE,
                'message' => 'Ticket Update Fail.'
                );
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
}
