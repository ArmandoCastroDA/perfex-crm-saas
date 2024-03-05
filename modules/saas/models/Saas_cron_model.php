<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Saas_cron_model extends App_model
{
    /**
     * Timeout limit in seconds
     *
     * @var integer
     */
    private $available_execution_time = 25;

    /**
     * Monitor of used seconds
     *
     * @var integer
     */
    private $start_time;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('saas/saas_model');

        $max_time = (int)ini_get("max_execution_time");
        if ($max_time <= 0)
            $max_time = 60 * 60; //1 hour;

        $this->available_execution_time = $max_time - 5; //minus 5 seconds for cleanup
        $this->start_time = time();
    }

    /**
     * Init cron activites for both tenant and master routine
     *
     * @return void
     * @throws Exception
     */
    public function init()
    {
        $cron_cache = $this->cron_cache();
        // Run saas related cron task for tenants
        if (is_subdomain()) {
            try {
                // Check and run db upgrade if neccessary
                $this->run_database_upgrade();
            } catch (\Throwable $th) {
                log_message('error', $th->getMessage());
            }
            return;
        }


        // Run master cron instance. From here, deployer is called and tenants cron triggered is called.
        try {


            // Run cron for pending company activation
            $this->run_company_pending_cron();

            // run check expired subscription cron
            $this->run_expired_subscription_cron();


            // Trigger cron for running company/tenant
            $start_from_id = (!empty($cron_cache->last_proccessed_company_id)) ? $cron_cache->last_proccessed_company_id : 0;

            $this->db->where('id >', $start_from_id)->where('status', 'running');
            $companies = $this->db->get('tbl_saas_companies')->result();


            // Run cron for each instance and return the last processed instance id
            $last_proccessed_company_id = $this->run_companies_cron($companies);
            $cron_cache->last_proccessed_company_id = $last_proccessed_company_id;

            // Update cron cache
            $cron_cache->cron_last_success_runtime = time();

            if ($last_proccessed_company_id === 0) {
                // Reset module activation pointer since all company must have been processed
                $cron_cache->new_module_activation = 0;
            }

            $this->save_settings($cron_cache);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
        }
    }

    /**
     * Run cron for all company/Tenants.
     *
     * It uses Timeouter to detect timeout and return last processed id
     *
     * @param integer $start_from_id The company id to start from.
     * @return integer The last processed company id
     */

    public function run_companies_cron($companies)
    {
        if (is_subdomain()) return;
        if (empty($companies)) return 0;

        $this->load->library(SaaS_MODULE . '/Timeouter');
        foreach ($companies as $company) {
            $time_elapsed = (time() - $this->start_time);
            try {
                // Start timeout
                Timeouter::limit($this->available_execution_time - $time_elapsed, 'Time out.');

                declare(ticks=1) {

                    try {

                        $url = all_company_url($company->domain)['url'] . 'cron/index';
                        // Simulate cron command: wget -q -O- http://saasdomain.com/companydomain/s/cron/index
                        $cron_result = $this->saas_curl_request($url, ['timeout' => 20]);

                        if (!$cron_result || (!empty($cron_result['error']) && !empty($cron_result['result']))) {

                            log_message("Error", "Cron: Error running cron on $url :" . $cron_result['error']);
                        }
                    } catch (\Exception $e) {
                        log_message('error', "Cron job failure for $company->slug :" . $e->getMessage());
                    }
                }

                Timeouter::end();
            } catch (\Exception $e) {

                Timeouter::end();
                return $company->id;
            }
        }

        return 0;

    }


    /**
     * Perform an HTTP request using cURL.
     *
     * @param string $url The URL to send the request to.
     * @param array $options An array of options for the request.
     *
     * @return array An array containing the 'error' and 'response' from the request.
     */
    function saas_curl_request($url, $options)
    {
        // Initialize cURL
        $curl = curl_init($url);
        // Set SSL verification and timeout options
        $verify_ssl = (int)($options['sslverify'] ?? 0);
        $timeout = (int)($options['timeout'] ?? 30);

        if ($options) {
            // Get request method
            $method = strtoupper($options["method"] ?? "GET");

            // Get request data and headers
            $data = @$options["data"];
            $headers = (array)@$options["headers"];

            // Set JSON data and headers for POST requests
            if ($method === "POST") {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }

            // Set custom headers if provided
            if ($headers) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            }
        }

        // Set common cURL options
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => $verify_ssl,
            CURLOPT_TIMEOUT => (int)$timeout,
        ]);

        // Make the request
        $result = curl_exec($curl);

        // Check for errors
        $error = '';
        if (!$curl || !$result) {
            $error = 'Curl Error - "' . curl_error($curl) . '" - Code: ' . curl_errno($curl);
        }

        // Close the cURL session
        curl_close($curl);

        return ['error' => $error, 'response' => $result];
    }

    public function run_tenants_cron($companies)
    {
        $this->load->library(SaaS_MODULE . '/Timeouter');

        // Get all instance and run cron
        foreach ($companies as $company) {

            $time_elapsed = (time() - $this->start_time);

            try {

                // Start timeout
                Timeouter::limit($this->available_execution_time - $time_elapsed, 'Time out.');

                declare(ticks=1) {

                    try {

                        $url = saas_company_base_url($company, 'cron/index');

                        // Simulate cron command: wget -q -O- http://saasdomain.com/demoinstance/ps/cron/index
                        $cron_result = perfex_saas_http_request($url, ['timeout' => 20]);

                        if (!$cron_result || (!empty($cron_result['error']) && !empty($cron_result['result']))) {

                            log_message("Error", "Cron: Error running cron on $url :" . $cron_result['error']);
                        }
                    } catch (\Exception $e) {
                        log_message('error', "Cron job failure for $company->slug :" . $e->getMessage());
                    }
                }

                Timeouter::end();
            } catch (\Exception $e) {

                Timeouter::end();
                return $company->id;
            }
        }

        return 0;
    }

    /**
     * Run cron for expired subscription
     *
     * @return void
     */
    public function run_expired_subscription_cron()
    {

        if (is_subdomain()) return;

        // check if any company expired_date is less than today and status is running
        // and send email from 5 days to 1 day before expiration
        $expire_date_from = date('Y-m-d', strtotime('-5 days'));
        $expiring_companies = get_result('tbl_saas_companies', ['status' => 'running', 'expired_date >=' => $expire_date_from, 'expired_date <=' => date('Y-m-d')]);
        if (!empty($expiring_companies)) {
            foreach ($expiring_companies as $company) {
                $this->saas_model->send_subscription_expired_email($company->id);
            }
        }
        // check if any company already expired and status is running and change status to expired
        // and send email to admin
        $expired_companies = get_result('tbl_saas_companies', ['status' => 'running', 'trial_period' => 0, 'expired_date <' => date('Y-m-d')]);

        if (!$expired_companies) return;
        foreach ($expired_companies as $company) {

            // change status to expired
            $this->saas_model->_table_name = 'tbl_saas_companies';
            $this->saas_model->_primary_key = 'id';
            $this->saas_model->save(['status' => 'expired'], $company->id);
            // send email to admin

            $this->saas_model->send_inactive_email($company->id);
        }
        return true;


    }

    /**
     * @throws Exception
     */
    public function run_company_pending_cron()
    {
        if (is_subdomain()) return;

        $pending_companies = get_result('tbl_saas_companies', ['status' => 'pending']);
        if (!$pending_companies) return;
        // check if any company has been pending for more than 24 hours and delete it else sent email with for activation
        foreach ($pending_companies as $company) {
            $time_elapsed = (time() - strtotime($company->created_date));
            if ($time_elapsed > 24 * 60 * 60) {
                $this->saas_model->delete_company($company->id);
            } else if ($time_elapsed > 6 * 60 * 60) {
                $this->saas_model->send_welcome_email($company->id);
            }
        }


    }

    /**
     * Get cron cache
     *
     * @return object
     */
    public function cron_cache($field = '')
    {
        $cron_cache = (object)json_decode(ConfigItems('saas_cron_cache') ?? '');
        if ($field) return $cron_cache->{$field} ?? '';
        return $cron_cache;
    }

    /**
     * Update cron cache
     *
     * @param array|object $settings
     * @return void
     */
    public function save_settings($settings)
    {
        $settings = array_merge((array)$this->cron_cache(), (array)$settings);
        update_option('saas_cron_cache', json_encode($settings));
        return (object)$settings;
    }



    /**
     * Run perfex database upgrade.
     * This should be used for the tenant or master. Its advisable to run for only tenants
     * and master admin should run db upgrade from the screen UI
     *
     * @return void
     * @throws Exception
     */
    public function run_database_upgrade()
    {
        if ($this->app->is_db_upgrade_required($this->app->get_current_db_version())) {

            hooks()->do_action('pre_upgrade_database');

            if (is_subdomain()) {
                // Reset the database update info from tenant view
                hooks()->add_action('database_updated', function () {
                    update_option('update_info_message', '');
                }, PHP_INT_MAX);
            }

            // This call will redirect and code should not be placed after following line.
            $this->app->upgrade_database();
        }
    }
}
