<?php defined('BASEPATH') or exit('No direct script access allowed');
include_once(__DIR__ . '/traits/AffiliateMailTemplate.php');

class Affiliate_mail_verification extends Saas_mail_template
{

    use AffiliateMailTemplate;

    /**
     * @inheritDoc
     */
    public $rel_type = 'company';

    /**
     * @inheritDoc
     */
    protected $for = 'customer';

    /**
     * @inheritDoc
     */
    public $slug = 'affiliate-verification-email';
}
