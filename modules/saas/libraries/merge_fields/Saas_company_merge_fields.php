<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Saas_company_merge_fields extends App_merge_fields
{
    /**
     * This function builds an array of custom email templates keys.
     * The provided keys will be available in perfex email template editor for the supported templates.
     * @return array
     */
    public function build()
    {
        // List of email templates used by the plugin
        $templates = [
            'saas-welcome-email',
            'saas-token-activate-account',
            'saas-faq-request-email',
            'saas-assign-new-package',
            'saas-company-expiration-email',
            'saas-inactive-company-email',
            'saas-company-url',
        ];
        $available = ['saas'];
        return [
            [
                'name' => 'Company name',
                'key' => '{name}', // Key for instance name
                'available' => $available,
                'templates' => $templates,
            ],
            [
                'name' => 'Company URL',
                'key' => '{company_url}', // Key for instance slug
                'available' => $available,
                'templates' => $templates,
            ],
            [
                'name' => 'Package name',
                'key' => '{package_name}', // Key for instance status
                'available' => $available,
                'templates' => $templates,
            ],
            [
                'name' => 'Expiration date',
                'key' => '{expiration_date}', // Key for instance URL
                'available' => $available,
                'templates' => $templates,
            ],
            [
                'name' => 'Activation link',
                'key' => '{activation_url}', // Key for instance admin URL
                'available' => $available,
                'templates' => $templates,
            ], [
                'name' => 'Activation token',
                'key' => '{activation_token}', // Key for instance admin URL
                'available' => $available,
                'templates' => $templates,
            ],

        ];
    }

    /**
     * Format merge fields for company instance
     * @param object $company
     * @return array
     */
    public function format($company)
    {
        return $this->instance($company);
    }

    /**
     * Company Instance merge fields
     * @param object $company
     * @return array
     */
    public function instance($company)
    {

        $activation_code = $company->activation_code;
        $wildcard = ConfigItems('saas_server_wildcard');
        $companyUrl = base_url();
        $domain = '&d=' . url_encode($company->domain);
        if (!empty($wildcard)) {
            $domain = '';
            $companyUrl = companyUrl($company->domain);
        }
        $sub_domain = $companyUrl . 'setup?c=' . url_encode($activation_code) . $domain;

        $fields = [];
        $fields['{name}'] = $company->name;
        $fields['{company_url}'] = companyUrl($company->domain);
        $fields['{package_name}'] = $company->package_name;
        $fields['{expiration_date}'] = $company->expired_date;
        $fields['{activation_url}'] = $sub_domain;
        $fields['{activation_token}'] = $company->activation_code;
        return $fields;
    }
}
