<?php defined('BASEPATH') or exit('No direct script access allowed');
include_once(__DIR__ . '/traits/SaasMailTemplate.php');
class Saas_welcome_mail extends Saas_mail_template
{

    use SaasMailTemplate;

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
    public $slug = 'saas-welcome-mail';

    /**
     * @inheritDoc
     */
    public $name = 'Saas Welcome Mail';


}
