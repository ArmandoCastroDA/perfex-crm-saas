<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Affiliate_merge_fields extends App_merge_fields
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
            'affiliate-verification-email',
            'affiliate-withdrawal-request',
            'affiliate-withdrawal-accepted',
            'affiliate-withdrawal-declined',
        ];
        $available = ['affiliate'];
        return [
            [
                'name' => 'First Name',
                'key' => '{first_name}',
                'available' => $available,
                'templates' => $templates,
            ],
            [
                'name' => 'Last Name',
                'key' => '{last_name}',
                'available' => $available,
                'templates' => $templates,
            ],
            [
                'name' => 'Email',
                'key' => '{email}',
                'available' => $available,
                'templates' => $templates,
            ],
            [
                'name' => 'Verification URL',
                'key' => '{verification_url}', // Key for instance admin URL
                'available' => $available,
                'templates' => $templates,
            ],
            [
                'name' => 'Withdrawal Amount',
                'key' => '{withdrawal_amount}', // Key for instance admin URL
                'available' => $available,
                'templates' => $templates,
            ],
        ];
    }

    /**
     * Format merge fields for company instance
     * @param object $user
     * @return array
     */
    public function format($user)
    {
        return $this->instance($user);
    }

    /**
     * Company Instance merge fields
     * @param object $user
     * @return array
     */
    public function instance($user)
    {
        $fields = [];
        $fields['{first_name}'] = $user->firstname;
        $fields['{last_name}'] = $user->lastname;
        $fields['{email}'] = $user->email;
        $fields['{verification_url}'] = base_url('affiliate/verify/' . url_encode($user->user_id));
        $fields['{withdrawal_amount}'] = $user->withdrawal_amount;
        return $fields;
    }
}
