<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Email template class for all mail sent through the saas.
 * The class is inherited by all saas email templates.
 */
trait AffiliateMailTemplate
{

    /**
     * Company Info
     *
     * @var string
     */
    protected $company_info;

    /**
     * Company email address
     *
     * @var string
     */
    protected $email;

    /**
     * The ID of the Company.
     *
     * @var mixed
     */
    protected $user_id;

    /**
     * Instance
     *
     * @var mixed
     */
    protected $instance_data;


    /**
     * The constructor.
     * This is called when perfex is creating instance of this template
     *
     * @param string $company_info
     * @param string $email
     * @param int $user_id
     * @param mixed $instance_data
     */
    public function __construct($email, $user_id, $instance_data)
    {
        parent::__construct();

        $this->email = $email;
        $this->user_id = $user_id;
        $this->instance_data = $instance_data;
    }

    /**
     * Build the email message.
     */
    public function build()
    {
        // Load required libraries
        $this->ci->load->library(SaaS_MODULE . '/merge_fields/affiliate_merge_fields');
        $this->ci->load->library('merge_fields/other_merge_fields');
        // Set email properties
        $this->to($this->email)
            ->set_rel_id($this->user_id)
            ->set_merge_fields('affiliate_merge_fields', $this->instance_data);
    }
}
