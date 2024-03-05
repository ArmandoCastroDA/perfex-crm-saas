<?php
$uri = $this->uri->segment(4);
if (empty($uri)) {
    $uri = null;
}
$company_seed = seed_db();
if (empty($company_seed)) { ?>
    <div class="alert alert-danger">
        <strong>Warning!</strong>
        Before creating a company,
        Please create company seed from <a href="<?php echo saas_url('settings/index/company_seed'); ?>">here</a>.
        without company seed you the company will not install with sample data.
    </div>
<?php }
?>

<?php if (!empty(super_admin_access())) { ?>
    <div class="_buttons tw-mb-2 sm:tw-mb-4">
        <a href="<?php echo saas_url('companies/create'); ?>"
           class="btn btn-primary pull-left display-block">
            <i class="fa-regular fa-plus tw-mr-1"></i>
            <?php echo _l('new_company'); ?>
        </a>
        <div class="clearfix"></div>
    </div>
<?php } ?>


<div class="panel_s">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped DataTables " id="DataTables" width="100%">
                <thead>
                <tr>
                    <th><?= _l('name') ?></th>
                    <th><?= _l('email') ?></th>
                    <th><?= _l('account') ?></th>
                    <th><?= _l('package') ?></th>
                    <th><?= _l('trial_period') ?></th>
                    <th><?= _l('status') ?></th>

                    <th><?= _l('action') ?></th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <script type="text/javascript">
                list = base_url + "saas/companies/companiesList/" + '<?= $uri ?>';
            </script>
        </div>
    </div>
</div>
<?php echo form_close(); ?>


<!-- Companies viewer modal -->
<div class="modal view-company-modal animated fadeIn" id="view-company-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog tw-w-full tw-h-screen tw-mt-0" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="fa fa-close"></i></span>
                </button>
                <div class="tw-flex tw-justify-end">
                    <div class="tw-flex col-md-2 col-xs-6">
                        <?php
                        $company_options = get_result('tbl_saas_companies', ['status' => 'running'], 'array');
                        echo render_select('view-company', $company_options, ['id', ['name']], '', '0', [], [], 'tw-w-full', '', true); ?>
                    </div>
                    <h4 class="modal-title"></h4>
                </div>
            </div>
            <div class="modal-body tw-m-0">
                <div class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center first-loader">
                    <i class="fa fa-spin fa-spinner fa-4x"></i>
                </div>
                <iframe class="tw-w-full tw-h-full" id="company-viewer">
                </iframe>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        'use strict';
        initDataTable('#DataTables', list, undefined, undefined, 'undefined');
    });
    // document onload
</script>
<?php $this->load->view('saas/companies/login_as_company') ?>

