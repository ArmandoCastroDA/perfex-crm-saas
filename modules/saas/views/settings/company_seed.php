<?php
$company_info = seed_db();
$done_server_settings = get_option('done_server_settings');
if (empty($done_server_settings)) {
    ?>
    <div class="alert alert-danger">
        <h4><strong>Need to Configuring your Server! </strong></h4>
        <p>
            I have seen you did not configure your server yet.
            click <a href="<?= base_url('saas/settings/index/server_settings') ?>">here to configure your server.</a>
            if you any help Please follow the instructions below to configure your server
            properly in <a href="https://docs.coderitems.com/perfectsaas/#configure_server"
                           target="_blank"> how to configure server</a>
        </p>
        <hr/>
        <a href="<?php echo saas_url('companies/dismiss_server_settings_notice'); ?>" class="alert-link">I have done it!
            Don't
            show this message again</a>
    </div>
<?php } ?>
<div>
    <div class="alert alert-danger">
        <?php echo _l('saas_seed_warning'); ?>
    </div>

    <div class="tw-flex tw-justify-between tw-items-center">
        <a href="<?php echo saas_url('settings/create_sample_database'); ?>"
            <?= (empty($company_info) ? '' : 'onclick="return confirm(\'Are you sure you want to reset sample database? . the all data will be reset\')"') ?>
           class="btn btn-primary">
            <?php
            if (empty($company_info)) {
                echo _l('create_sample_database');
            } else {
                echo _l('reset_sample_database');
            }
            ?>
        </a>
        <?php
        if (!empty($company_info)) {
            ?>
            <button type="button"
                    class="btn btn-outline-info btn-sm">
                <?php echo _l('sample_database_name'); ?>
                <span class="label badge-pill label-info"><?php echo $company_info->db_name; ?></span>
            </button>
        <?php }
        ?>
    </div>
    <?php
    if (!empty($company_info)) {
        ?>
        <hr/>
        <div class="alert alert-danger">
            <?php echo _l('login_as_sample_company_help'); ?>
        </div>
        <button type="button"
                data-company-id="<?php echo $company_info->id; ?>"
                class="btn btn-primary btn-sm view-company">
            <i class="fa fa-sign-in"></i> <?php echo _l('login_as_sample_company'); ?>
        </button>
        <?php $this->load->view('saas/companies/login_as_company', ['company_info' => $company_info]) ?>
    <?php }
    ?>
</div>